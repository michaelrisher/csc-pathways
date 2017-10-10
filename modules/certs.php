<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/6/2017
	 * Time: 12:22
	 */
	class certs extends Main {

		private $moduleName = 'certs';
		/**
		 * get a simple-ish listing of the certificate
		 * @param string $order any custom sort you want to run with the query
		 * @return array
		 */
		public function listing( $order = 'id' ) {
			//to kinda standardize it for later needs
			$this->loadModule( 'roles' );
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			if( Core::inArray( 'gCertView', $ROLES ) ) {
				$page = 1;
				$limit = 50;
				$page--;//to make good looking page numbers for users
				$offset = $page * $limit;
				$query = "SELECT * FROM certificateList ORDER BY $order";//remove limit for a time LIMIT $page,50
				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				$canEdit = Core::inArray( 'gCertEdit', $ROLES );
				$canDelete = Core::inArray( 'gCertDelete', $ROLES );
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'code' => $row['code'],
						'description' => $row['description'],
						'category' => $row['category'],
						'hasAs' => $row['hasAs'],
						'hasCe' => $row['hasCe'],
						'units' => $row['units'],
						'sort' => $row['sort'],
						'active' => $row['active'],
						'edit' => $canEdit,
						'delete' => $canDelete
					);
					array_push( $return, $a );
				}

				//get count of data
				$query = "SELECT COUNT(*) AS items FROM audit";
				$result->close();
				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
					return null;
				}

				if ( $result->num_rows == 1 ) {
					$row = $result->fetch_assoc();
					$count = $row['items'];
				}
				$result->close();
				$return = array(
					'listing' => $return,
					'count' => intval( $count ),
					'limit' => $limit,
					'currentPage' => ++$page
				);
				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				}
				return $return;
			}
		}

		/**
		 * edit cert action from the admin page
		 * only allowed if the user is admin
		 * @param $params
		 */
		public function edit( $params ) {
			$this->loadModule( 'roles' );
			$this->loadModule( 'users' );
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			if ( $this->users->isLoggedIn() && Core::inArray( 'gCertEdit', $ROLES ) ) {
				Core::queueStyle( 'assets/css/reset.css' );
				Core::queueStyle( 'assets/css/ui.css' );
				//put the data onscreen
				if( gettype( $params ) == 'array' ){
					$id = $params[0];
					$lang = (int)$params[1];
				} else{
					$id = $params;
					$lang = 0;
				}
				$data = $this->get( $id, $lang );
				$data['categories'] = $this->listCategories();
				$data['language'] = $lang; //bug fix when there is not a lang cert yet
				include( CORE_PATH . 'pages/certEdit.php' );
			} else {
				Core::errorPage( 404 );
			}
		}


		/**
		 * get a specific cert from an id
		 * echos JSON to the user
		 * returns an array if forceReturn is true
		 * @param $id int id which is not the three digit code
		 * @param integer|string $language id or code of the language
		 * @param bool|false $forceReturn forces a array return
		 * @return array|null
		 */
		public function get( $id, $language = 'en', $forceReturn = false ) {
			$this->loadModule( 'users' );
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
certificateList.id,
certificateList.code,
certificateData.language,
certificateList.hasAs,
certificateList.hasCe,
certificateList.units,
certificateList.category,
certificateList.description AS title,
certificateList.sort,
certificateList.active,
certificateData.description,
certificateData.elo,
certificateData.schedule
FROM
certificateList
INNER JOIN enumCategories ON certificateList.category = enumCategories.id
INNER JOIN certificateData ON certificateList.id = certificateData.cert
WHERE certificateList.id = $id
ORDER by certificateData.language ASC
EOD;
			if ( !$result = $this->db->query( $query ) ) {
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}

			if( $result->num_rows > 1 ){
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
			$return = $row;

			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}

		/**
		 * get a listing of all the categories
		 * only allowed if the user is admin
		 * @return array
		 */
		public function listCategories(){
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM enumCategories";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'category' => $row['category'],
					);
					array_push( $return, $a );
				}
				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				}
				return $return;
			}
		}

		/**
		 * save from the admin page
		 * only allowed if the user is admin
		 * @param $id
		 */
		public function save( $id ) {
			$this->loadModule( 'users' );
			$this->loadModule( 'roles' );
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			if( $this->users->isLoggedIn() ){
				if( Core::inArray( 'gCertEdit', $ROLES ) ) {
					$this->loadModule( 'audit' );
					$id = core::sanitize( $id );
					$_POST['title'] = core::sanitize( $_POST['title'] ); //certlist description
					$_POST['code'] = core::sanitize( $_POST['code'] );
					$_POST['units'] = core::sanitize( $_POST['units'] );
					$_POST['category'] = core::sanitize( $_POST['category'] );
					$_POST['description'] = core::sanitize( $_POST['description'], true );
					$_POST['elo'] = core::sanitize( $_POST['elo'], true );
					$_POST['schedule'] = core::sanitize( $_POST['schedule'], true );
					$_POST['sort'] = core::sanitize( $_POST['sort'] );
					$language = intval( Core::sanitize( $_POST['language'] ) );
					$hasCe = isset( $_POST['hasCe'] ) ? 1 : 0; //js returns something like hasCe=on if its on else its not set
					$hasAs = isset( $_POST['hasAs'] ) ? 1 : 0; //js returns something like hasCe=on if its on else its not set
					$active = isset( $_POST['active'] ) ? 1 : 0; //js returns something like hasCe=on if its on else its not set


					//separate the two tables data
					$certListUpdated = $this->upsertRecord( 'certificateList', "id=$id", array(
						'code' => (string)$_POST['code'],
						'hasAs' => $hasAs,
						'hasCe' => $hasCe,
						'category' => (int)$_POST['category'],
						'units' => (int)$_POST['units'],
						'description' => $_POST['title'],
						'sort' => (int)$_POST['sort'],
						'active' => $active
					) );

					$error = '';
					if ( !$certListUpdated ) {
						$error = 'List failed to upsert';
					}

					$certDataUpdated = $this->upsertRecord( 'certificateData', "cert=$id AND language=$language", array(
						'cert' => $id,
						'language' => $language,
						'description' => $_POST['description'],
						'elo' => $_POST['elo'],
						'schedule' => $_POST['schedule']
					) );

					if ( !$certDataUpdated ) {
						$error .= '<br>Data failed to upsert';
					}

					if ( $certListUpdated && $certDataUpdated ) {
						$obj['msg'] = $lang->o( 'ajaxSaved' ); //"Saved successfully.";
						$this->audit->newEvent( "Updated cert: " . $_POST['title'] );
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = $error;
						echo Core::ajaxResponse( $obj, false );
					}
				} else{
					$obj['error'] = 'Insufficient permissions to edit certificate';
					echo Core::ajaxResponse( $obj, false );
				}
			} else{
				$obj['error'] = 'Session expired. Please log in again.';// 'Session expired.<br>Please log in again';
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * create from the admin page
		 * only allowed if the user is admin
		 */
		public function create(){
			$this->loadModule('users');
			$this->loadModule('roles');
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			if( $this->users->isLoggedIn() &&  Core::inArray( 'gCertEdit', $ROLES ) ) {
				Core::queueStyle( 'assets/css/reset.css' );
				Core::queueStyle( 'assets/css/ui.css' );
				//get the next id

				$query = "SHOW TABLE STATUS LIKE 'certificateList'";
				if ( !$result = $this->db->query( $query ) ) {
					die( 'There was an error running the query [' . $this->db->error . ']' );
				}
				$row = null;
				if ( $result->num_rows ) {
					$row = $result->fetch_assoc();
					$data = array(
						'id' => $row['Auto_increment'],
						'title' => '',
						'code' => '',
						'hasAs' => 0,
						'hasCe' => 0,
						'category' => 0,
						'description' => '',
						'elo' => '',
						'schedule' => '',
						'sort' => 0,
						'categories' => $this->listCategories(),
						'language' => 0,
						'active' => 0
					);
				}
				$result->close();
				include( CORE_PATH . 'pages/certEdit.php' );
			} else{
				Core::errorPage( 404 );
			}
		}

		/**
		 * delete a cert from the admin page
		 * only allowed if the user is admin
		 * @param $id
		 */
		public function delete( $id ){
			$this->loadModule( 'users' );
			$this->loadModule( 'roles' );
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
//			$_POST = Core::sanitize( $_POST, true );
			if( $this->users->isLoggedIn() ) {
				if( Core::inArray( 'gCertDelete', $ROLES ) ) {
					$id = Core::sanitize( $id );
					$this->loadModule( 'audit' );
					$row = $this->get( $id, 0, true );
					$event = $row['description'];

					$statement = $this->db->prepare( "DELETE FROM certificateList WHERE id=?" );
					$statement->bind_param( "s", $id );
					$statementData = $this->db->prepare( "DELETE FROM certificateData WHERE cert=?" );
					$statementData->bind_param( "s", $id );
					if ( $statement->execute() && $statementData->execute() ) {
						$obj['msg'] = 'Deleted successfully';
						$this->audit->newEvent( "Deleted certificate: " . $event );
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = $statement->error;
						echo Core::ajaxResponse( $obj, false );
					}

					$statement->close();
					$statementData->close();
				} else {
					$obj['error'] = 'Insufficient permissions to delete certificates';
					echo Core::ajaxResponse( $obj, false );
				}
			} else{
				$obj['error'] = 'Session expired. Please log in again.';
				echo Core::ajaxResponse( $obj, false );
			}
		}

		function show( $id ){
			$data['params'] = $id;
			include( CORE_PATH . 'pages/cert.php' );
		}
	}
