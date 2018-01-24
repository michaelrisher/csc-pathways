<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:15
	 */
	class classes extends Main {
		private $moduleName = 'classes';

		/**
		 * get a simple listing of classes only id and title are returned
		 * only allowed if the user is admin
		 * @param int $page not used yet
		 * @return array
		 */
		public function listing( $page = 1 ) {
			$this->loadModules( 'roles discipline' );
//			$userRoles = $this->roles->getRolesByModule( Core::getSessionId(), $this->moduleName );
			$fullRoles = $this->roles->getAllForUser( Core::getSessionId() );
			$userDisciplines = $this->discipline->getIdsForUser( Core::getSessionId() );
			//if user has class view role then do it
			if ( true ) {
				$limit = 25;
				$page--;//to make good looking page numbers for users
				$offset = $page * $limit;
				$search = '';
				if( isset( $_POST ) || isset( $_GET ) ){
					$_POST = Core::sanitize( $_POST );
					if( isset( $_POST['search'] ) ){
						$search = $_POST['search'];
					}
					if( isset( $_GET['q'] ) ){
						$search = Core::sanitize( $_GET['q'] );
					}
				}
//				$search = isset( $_POST['search'] ) ? $_POST['search'] : '' ;
				if( isset( $_POST['all'] ) ){
					$query = "SELECT * FROM classes ORDER BY sort";
				}
				else if( empty( $search ) ){
					$query = "SELECT * FROM classes ORDER BY sort LIMIT $offset,$limit";//remove limit for a time LIMIT $page,50
				} else {
					$query = "SELECT * FROM classes WHERE title LIKE '%$search%'ORDER BY sort LIMIT $offset,$limit";
				}

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
					return null;
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					if ( $this->roles->haveAccess( 'ClassView', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines ) ) {
						$canEdit = $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
						$canDelete = $this->roles->haveAccess( 'ClassDelete', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
						$a = array(
							'id' => $row['id'],
							'title' => $row['title'],
							'edit' => $canEdit,
							'delete' => $canDelete
						);
						array_push( $return, $a );
					}
				}

				//get count of data
				if( empty( $search ) ) {
					$query = "SELECT COUNT( id ) AS items FROM classes ";
				} else {
					$query = "SELECT COUNT( id ) AS items FROM classes WHERE title LIKE '%$search%'";
				}
				$result->close();
				if( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
					return null;
				}

				if( $result->num_rows == 1 ){
					$row = $result->fetch_assoc();
					$count = $row['items'];
				}
				$result->close();
				$return = array(
					'listing' => $return,
					'count' => intval( $count ),
					'limit' => $limit,
					'currentPage' => (int)++$page );
				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				}
				return $return;
			}
		}

		/**
		 * get the data from the database for a class
		 * only allowed if the user is admin
		 * echos json if ajaxed
		 * returns an array if forced to return
		 * @param $id string id of the class
		 * @param integer|string $language id or code of the language
		 * @param bool|false $forceReturn force a return if the get is not called through ajax
		 * @return array|void array if forceReturn is true echos echos json otherwise
		 */
		public function get( $id, $language = 'en', $forceReturn = false ) {
			$this->loadModule( 'users' );
			//get the language code for
			if ( isset( $language ) && !isset( $_POST['language'] ) ) {
				$this->loadModule( "language" );
				if( gettype( $language ) == 'integer' ){
					$langCode = $language; //if its an int i assume its the id
				} else {
					$langCode = $this->language->getId( $language, true );
				}

			} elseif ( isset( $_POST['language'] ) ) {
				$langCode = $_POST['language'];
			} else {
				$langCode = 0;
			}
			$query = <<<EOD
SELECT
    classes.id,
    classData.language,
    classes.sort,
    classes.title,
    classes.units,
    classes.discipline,
    classes.transfer,
    classData.prereq,
    classData.coreq,
    classData.advisory,
    classData.description
FROM
    classes
INNER JOIN classData on classes.id = classData.class
WHERE classes.id = $id
ORDER BY classData.language ASC
EOD;

			if ( !$result = $this->db->query( $query ) ) {
//					echo( 'There was an error running the query [' . $this->db->error . ']' );
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}

			if( $result->num_rows > 1 ){ //find the right language
				$results = $result->fetch_all( MYSQLI_ASSOC );
				for( $i = 0; $i < count( $results ); $i++ ){
					if( $results[$i]['language'] == $langCode ){
						$row = $results[$i];
						break;
					}
				}
				if( !isset( $row ) ){ //if it is not there by some chance grab first one
					$row = $results[0];
				}
			} else {
				$row = $result->fetch_assoc();
			}
			$return = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'units' => $row['units'],
				'transfer' => $row['transfer'],
				'advisory' => $row['advisory'],
				'prereq' => $row['prereq'],
				'coreq' => $row['coreq'],
				'description' => $row['description'],
				'language' => $langCode,
				'discipline' => $row['discipline']
			);

			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}

		/**
		 * save a class from the admin page
		 * only allowed if the user is admin
		 */
		public function save( $id, $create = false ) {
			//what if a user from outside discipline wants to create a class for there cert (ie math for a compsci cert)
			$this->loadModules( 'users audit roles' );
			if( !$create ){
				//double check we aren't creating since the create var is deprecated
				$create = isset( $_POST['create'] ) ? $_POST['create'] : false;
			}
//			$lang = new Lang( Lang::getCode() );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if ( $this->users->isLoggedIn() ) {
				if( $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), -1 ) ) {
					//get the sort from the title
					$code = explode( ' - ', $_POST['title'] )[0];
					$sort = preg_replace( '/\D/', '', $code );

					//if creating make sure the title is different
					$findClass = $this->find( array( 'search' => 'title LIKE "' . $code . '%"' ) );
					if( $create ){
						if ( count( $findClass['listing'] ) > 0 ) {
							$isDuplicate = false;
							foreach( $findClass['listing'] as $found ){
								//get found class code
								$foundCode = explode( ' - ', $found['title'] );
								$foundNum = preg_replace( '/\D/', '', $foundCode);
								if( $sort == $foundNum ){ $isDuplicate = true; }
							}
							if( $isDuplicate ) {
								$obj['error'] = "A class with the same code exists already";
								echo Core::ajaxResponse( $obj, false );
								return;
							}
						}
					}

					$setClass = $this->upsertRecord( 'classes', "id=${id}", array(
						'id' => $id,
						'title' => trim( $_POST['title'] ),
						'units' => floatval( $_POST['units'] ),
						'transfer' => $_POST['transfer'],
						'discipline' => $_POST['discipline'],
						'sort' => intval( $sort )
					) );

					$setClassData = $this->upsertRecord( 'classData', "class=${id} AND language=${_POST['language']}", array(
						'class' => $id,
						'language' => $_POST['language'],
						'prereq' => trim( $_POST['prereq'] ),
						'advisory' => trim( $_POST['advisory'] ),
						'coreq' => trim( $_POST['coreq'] ),
						'description' => trim( $_POST['description'] ),
					) );


					if ( $setClass && $setClassData ) {
						$obj['msg'] = 'Saved successfully.';
						$obj['editable'] = $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), $_POST['discipline'] );
						$obj['deletable'] = $this->roles->haveAccess( 'ClassDelete', Core::getSessionId(), $_POST['discipline'] );
						if( !$create ) {
							$this->audit->newEvent( "Updated class: " . $_POST['title'] );
						} else {
							$this->audit->newEvent( "Create class: " . $_POST['title'] );
						}
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = "An error occurred please contact administrator";
						echo Core::ajaxResponse( $obj, false );
					}
				} else {
					$obj['error'] = "Insufficient permissions to edit classes";
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired. Please log in again.";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * delete a class from the admin page
		 * only allowed if the user is admin
		 */
		public function delete() {
			$this->loadModules( 'roles audit users' );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if( $this->users->isLoggedIn() ) {
				$class = $this->get( $_POST['id'], 'en', true );
				$disciplineId = $class['discipline'];
				if ( $this->roles->haveAccess( 'ClassDelete', Core::getSessionId(), $disciplineId ) ) {
					$event = isset( $class['title'] ) ? $class['title'] : $_POST['id'];
					$statement = $this->db->prepare( "DELETE FROM classes WHERE id=?" );
					$statementData = $this->db->prepare( "DELETE FROM classData WHERE class=?" );
					$statementData->bind_param( "i", $_POST['id'] );
					$statement->bind_param( "i", $_POST['id'] );
					if ( $statement->execute() && $statementData->execute() ) {
						$obj['msg'] = $lang->o( 'ajaxDelete' );
						$this->audit->newEvent( "Deleted class: " . $event );
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = $statement->error;
						echo Core::ajaxResponse( $obj, false );
					}
				} else{
					$obj['error'] = "You do not have access to delete classes";
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired. Please log in again.";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * Create a class from the admin page
		 * only allowed if the user is admin
		 */
		public function create() {
			//todo make this standard where the module makes the modal
			$query = "SHOW TABLE STATUS LIKE 'classes'";
			if ( !$result = $this->db->query( $query ) ) {
				die( 'There was an error running the query [' . $this->db->error . ']' );
			}
			$row = null;
			$id = -1;
			if ( $result->num_rows ) {
				$row = $result->fetch_assoc();
				$id = $row['Auto_increment'];
			}
			$this->save( $id, true );
		}

		/**
		 * ajax to recieve the html modal with all data included
		 * @param int $id
		 */
		public function edit( $id = -1 ){
			$this->loadModules( "roles users discipline" );
			//get first
			if( $id != -1 ){
				$langCode = 0;
				if ( isset( $_POST ) && isset( $_POST['language'] ) ) {
					Core::sanitize( $_POST['language'] );
					$langCode = $_POST['language'];
				}
				$class = $this->get( $id, $langCode, true );
				$class['create'] = 0;
			} else{ //we are creating a class
				$query = "SHOW TABLE STATUS LIKE 'classes'";
				if ( !$result = $this->db->query( $query ) ) {
					die( 'There was an error running the query [' . $this->db->error . ']' );
				}
				$row = null;
				$id = -1;
				if ( $result->num_rows ) {
					$row = $result->fetch_assoc();
					$id = $row['Auto_increment'];
				}
				$class = array(
					'id' => $id,
					'title' => '',
					'units' => 0,
					'transfer' => '',
					'advisory' => '',
					'prereq' => '',
					'coreq' => '',
					'description' => '',
					'language' => 0,
					'create' => 1,
					'discipline' => -1
				);
			}
			//check if user can edit
			if( $this->users->isLoggedIn() ) {
				if( $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), $class['discipline'] ) && IS_AJAX ){
					//get disciplines
					$disciplines = $this->discipline->listing( true );
					?>
					<p>* fields are required</p>
					<form>
						<?php if( $class['create'] ){?>
						<input type="hidden" name="create" value="<?=$class['create']?>">
						<?php } ?>
						<input type="hidden" name="id" value="<?=$class['id']?>">
						<input type="hidden" name="language" value="<?=$class['language']?>">
						<ul>
							<li>
								<label for="title">Title*</label>
								<input name="title" type="text" value="<?=$class['title']?>" class="tooltip" title="Class title should look like: CIS-1 - Title">
								<span>Enter the class title</span>
							</li>
							<li>
								<label for="discipline">Discipline*</label>
								<select name="discipline">
									<option disabled selected> -- Select A Discipline -- </option>
									<?php
									foreach( $disciplines as $discipline ){
										echo "<option " . ( ( $discipline['id'] == $class['discipline'] ) ? ( 'selected' ) : ( '' ) ) . " value='${discipline['id']}'>${discipline['name']} ${discipline['description']}</option>";
									}
									?>
								</select>
								<span>Enter the class discipline</span>
							</li>
							<li>
								<label for="units">Units*</label>
								<input name="units" type="number" step="0.5" value="<?=$class['units']?>">
								<span>Enter the class units</span>
							</li>
							<li>
								<label for="transfer">Transfer</label>
								<input name="transfer" type="text" value="<?=$class['transfer']?>">
								<span>Enter the class transfer</span>
							</li>
							<li>
								<label for="advisory">Advisory</label>
								<input name="advisory" type="text" value="<?=htmlentities( $class['advisory'] )?>">
								<span>Enter the class advisory<a class="addClass floatright">+ Add Class</a></span>
							</li>
							<li>
								<label for="prereq">Prerequisite</label>
								<input name="prereq" type="text" value="<?=htmlentities( $class['prereq'] )?>">
								<span>Enter the class prerequisite<a class="addClass floatright">+ Add Class</a></span>
							</li>
							<li>
								<label for="coreq">Corequisite</label>
								<input name="coreq" type="text" value="<?=htmlentities( $class['coreq'] )?>">
								<span>Enter the class corequisite<a class="addClass floatright">+ Add Class</a></span>
							</li>
							<li>
								<label for="description">Description*</label>
								<textarea onkeyup="adjustTextarea(this)" name="description" type="textarea"><?=$class['description']?></textarea>
								<span>Enter the class Description</span>
							</li>
						</ul>
					</form><?php
				} else {
					echo "<p>You do not have access to edit classes</p>";
				}
			} else {
				echo "<p>Session expired. Please log in again.</p>";
			}
		}

		public function find( $options ){
			//check if array is an array of arrays
			$data = null;
			$multi = false;

			if( isset( $options[0] ) ){
				$multi = true;
			}
			if ( $multi ){
				for( $i = 0; $i < count( $options ); $i++ ){
					$input[$i] = array(
						'order' => isset( $options[$i]['order'] ) ? $options[$i]['order'] : 'id',
						'page' => isset( $options[$i]['page'] ) ? $options[$i]['page'] : 1,
						'search' => isset( $options[$i]['search'] ) ? $options[$i]['search'] : ''
					);
				}
				$data = $options;
			} else {
				$options = array(
					'order' => isset( $options['order'] ) ? $options['order'] : 'id',
					'page' => isset( $options['page'] ) ? $options['page'] : 1,
					'search' => isset( $options['search'] ) ? $options['search'] : ''
				);
				$data = array( $options );
			}

			$this->loadModules( 'roles discipline' );
			$fullRoles = $this->roles->getAllForUser( Core::getSessionId() );
			$userDisciplines = $this->discipline->getIdsForUser( Core::getSessionId() );

			$return =array();
			foreach ( $data as $item ) {
				$query = "SELECT * FROM classes";

				if( !empty( $item['search'] ) ){
					$query  .= " WHERE " . $item['search'];
				}

				if( !empty( $item['order'] ) ){
					$query .= " ORDER BY " .$item['order'];
				}

				if ( !$result = $this->db->query( $query ) ) {
					error_log( 'classes.php->find() ' . $this->db->error ) ;
				}

				while ( $row = $result->fetch_assoc() ) {
					if( $this->roles->haveAccess( 'ClassView', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines ) ) {
						$canEdit = $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
						$canDelete = $this->roles->haveAccess( 'ClassEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
						$a = array(
							'id' => $row['id'],
							'sort' => $row['sort'],
							'title' => $row['title'],
							'units' => $row['units'],
							'transfer' => $row['transfer'],
							'units' => $row['units'],
							'discipline' => $row['discipline'],
							'edit' => $canEdit,
							'delete' => $canDelete
						);
						array_push( $return, $a );
					}
				}
			}
			$listing = array( 'listing' => $return );
			$return = $listing;
			return $return;
		}
		public function getGroup( $id, $forceReturn = false ){
			$id = (int)Core::sanitize( $id );
			$return = null;
			$query = "SELECT * FROM classGroups WHERE id = ?";

			$statement = $this->db->prepare( $query );
			$statement->bind_param( 'i', $id );

			if( $statement->execute() ){
				$result = $statement->get_result();
				if( $result->num_rows > 0 ){
					$row = $result->fetch_assoc();
					$return = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'text' => $row['text']
					);
				}
			}

			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}

		/**
		 * adding a sort column keeping for future reference
		 * @deprecated
		 */
		private function update() {
			if ( !$this->users->isLoggedIn() ) return;
			$classes = $this->listing();
			foreach ( $classes as $class ) {
				$data = $this->get( $class['id'] );
				$code = explode( ' - ', $data['title'] )[0];
				$code = preg_replace( '/\D/', '', $code );

				$statement = $this->db->prepare( "UPDATE classes SET sort=? WHERE id=?" );
				$statement->bind_param( "is", $code, $data['id'] );
				if ( $statement->execute() ) {
					echo $data['title'] . ' sort updated ' . $code . '<br>';
				}
			}
		}

		/**
		 * replacing old class link with new standard keeping for future reference
		 * @deprecated
		 */
		private function updateClassLink() {
			$this->loadModule( 'users' );
			if ( !$this->users->isLoggedIn() ) return;
			$classes = $this->listing();
			foreach ( $classes as $class ) {
				$data = $this->get( $class['id'] );
				$data['prereq'] = preg_replace( '/<a.+?data-code="(.+?)">(.+?)<\/a>/', '[class id="$1" text="$2" /]', $data['prereq'] );
				$data['coreq'] = preg_replace( '/<a.+?data-code="(.+?)">(.+?)<\/a>/', '[class id="$1" text="$2" /]', $data['coreq'] );
				$data['advisory'] = preg_replace( '/<a.+?data-code="(.+?)">(.+?)<\/a>/', '[class id="$1" text="$2" /]', $data['advisory'] );
				$statement = $this->db->prepare( "UPDATE classes SET prereq=?, coreq=?, advisory=? WHERE id=?" );
				$statement->bind_param( "ssss", $data['prereq'], $data['coreq'], $data['advisory'], $data['id'] );
				$i = 0;
				if ( $statement->execute() ) {
					echo ++$i . '       ' . $data['title'] . ' fixed' . print_r( $data ) . '<br><br>';
				}
			}
		}

		/**
		 * moved data from the old table into the new table
		 * @deprecated
		 */
		private function moveDataToTable() {
			$this->loadModule( 'users' );
			if ( !$this->users->isLoggedIn() ) return;
			$classes = $this->listing();
			foreach ( $classes as $class ) {
				$query = "INSERT INTO classData(class, language, prereq, coreq, advisory, description) VALUES(?,?,?,?,?,?)";
				$data = $this->get( $class['id'] );
//				Core::debug( $data );
				$statement = $this->db->prepare( $query );
				$lang = 0;
				$statement->bind_param( "sissss", $data['id'], $lang, $data['prereq'], $data['coreq'], $data['advisory'], $data['description'] );
				//*
				$i = 0;
				if ( $statement->execute() ) {
					echo ++$i . '       ' . $data['title'] . ' fixed' . print_r( $data ) . '<br><br>';
				}
				//*/
				$statement->close();
			}
		}

		/**
		 * shows a classes when as html
		 * @param $id
		 */
		public function show( $id ) {
			$data['params'] = $id;
			include CORE_PATH . 'pages/class.php';
		}

		public function showClassGroup( $id ){
			$data['params'] = $id;
			include CORE_PATH . 'pages/classGroup.php';
		}
	}