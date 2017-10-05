<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:15
	 */
	class classes extends Main {

		/**
		 * get a simple listing of classes only id and title are returned
		 * only allowed if the user is admin
		 * @param int $page not used yet
		 * @return array
		 */
		public function listing( $page = 1 ) {
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
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
				} else if( empty( $search ) ){
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
					$a = array(
						'id' => $row['id'],
						'title' => $row['title']
					);
					array_push( $return, $a );
				}

				//get count of data
				if( empty( $search ) ) {
					$query = "SELECT COUNT( id ) AS items FROM classes";
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
					'currentPage' => ++$page );
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
    classes.transfer,
    classData.prereq,
    classData.coreq,
    classData.advisory,
    classData.description
FROM
    classes
INNER JOIN classData on classes.id = classData.class
WHERE classes.id = '$id'
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
				'language' => $langCode
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
		public function save( $create = true ) {
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if ( $this->users->isLoggedIn() ) {
				//get the sort from the title
				$sort = explode( ' - ', $_POST['title'] )[0];
				$sort = preg_replace( '/\D/', '', $sort );

				$setClass = $this->upsertRecord( 'classes', "id='${_POST['id']}'", array(
					'id' => $_POST['id'],
					'title' => $_POST['title'],
					'units' => intval( $_POST['units'] ),
					'transfer' => $_POST['transfer'],
					'sort' => intval( $sort )
				));

				$setClassData = $this->upsertRecord( 'classData', "class='${_POST['id']}' AND language=${_POST['language']}", array(
					'class' => $_POST['id'],
					'language' => $_POST['language'],
					'prereq' => $_POST['prereq'],
					'advisory' => $_POST['advisory'],
					'coreq' => $_POST['coreq'],
					'description' => $_POST['description'],
				) );

				if ( $setClass && $setClassData ) {
					$obj['msg'] = $lang->o( 'ajaxSaved' );
					if( !$create ) {
						$this->audit->newEvent( "Updated class: " . $_POST['title'] );
					} else {
						$this->audit->newEvent( "Created class: " . $_POST['title'] );
					}
					echo Core::ajaxResponse( $obj );
				} else {
					$obj['error'] = $lang->o( 'ajaxErrorOccurred' );
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = $lang->o( 'ajaxSessionExpire' );
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * delete a class from the admin page
		 * only allowed if the user is admin
		 */
		public function delete() {
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if ( $this->users->isLoggedIn() ) {
				$query = "SELECT title FROM classes WHERE id = '${_POST['id']}'";
				$event = '';
				if ( !$result = $this->db->query( $query ) ) {
					$event = $_POST['id'];
				}
				$row = $result->fetch_assoc();
				$event = $row['title'];

				$statement = $this->db->prepare( "DELETE FROM classes WHERE id=?" );
				$statement->bind_param( "s", $_POST['id'] );
				if ( $statement->execute() ) {
					$obj['msg'] = $lang->o( 'ajaxDelete' );
					$this->audit->newEvent( "Deleted class: " . $event );
					echo Core::ajaxResponse( $obj );
				} else {
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = $lang->o( 'ajaxSessionExpire' );
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * Create a class from the admin page
		 * only allowed if the user is admin
		 */
		public function create() {
			$this->save( true );
			/*
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if ( $this->users->isLoggedIn() ) {
				$statement = $this->db->prepare( "INSERT INTO classes(id, title, units, transfer, prereq, advisory, coreq, description) VALUES (?,?,?,?,?,?,?,?)" );
				$statement->bind_param( "ssdsssss", $_POST['id'], $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['coreq'], $_POST['description'] );
				if ( $statement->execute() ) {
					$obj['msg'] = $lang->o( 'ajaxCreate' );
					echo Core::ajaxResponse( $obj );
					$this->audit->newEvent( "Created class: " . $_POST['title'] );
				} else {
					$error = $statement->error;
					if ( preg_match( "/Duplicate entry '(.+?)' for key 'id'/", $error ) ) {
						$value = preg_replace( "/Duplicate entry '(.+?)' for key 'id'/", '$1', $error );
						$obj['error'] = "That id of \"$value\" already exists";
						$obj['errorCode'] = 1;
					} else {
						$obj['error'] = $statement->error;
					}
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = $lang->o( 'ajaxSessionExpire' );
				echo Core::ajaxResponse( $obj, false );
			}
			*/
		}

		/**
		 * get the number of pages with the limit
		 * @deprecated
		 * @param int $limit the number od results to limit
		 * @return float
		 */
		public function getPages( $limit = 25){
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$query = "SELECT COUNT(id) as pages FROM classes";

				if ( $result = $this->db->query( $query ) ) {
					$row = $result->fetch_assoc();
					return ceil( $row['pages'] / $limit );
				}
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

		public function show( $id ) {
			$data['params'] = $id;
			include CORE_PATH . 'pages/class.php';
		}
	}