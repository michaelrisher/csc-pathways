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
		public function listing( $order = 'id', $page = 1 ) {
			//to kinda standardize it for later needs
			$this->loadModules( 'roles discipline' );
			$fullRoles = $this->roles->getAllForUser( Core::getSessionId() );
			$userDisciplines = $this->discipline->getIdsForUser( Core::getSessionId() );
			$page = 1;
			$limit = 50;
			$page--;//to make good looking page numbers for users
			$offset = $page * $limit;
			$query = "SELECT * FROM certificateList ORDER BY $order";//remove limit for a time LIMIT $page,50
			if ( !$result = $this->db->query( $query ) ) {
				echo( 'There was an error running the query [' . $this->db->error . ']' );
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				if( $this->roles->haveAccess( 'CertView', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines ) ) {
					$canEdit = $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
					$canDelete = $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
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
						'discipline' => $row['discipline'],
						'edit' => $canEdit,
						'delete' => $canDelete
					);
					array_push( $return, $a );
				}
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

		/**
		 * edit cert action from the admin page
		 * only allowed if the user is admin
		 * @param $params
		 */
		public function edit( $params ) {
			$this->loadModules( 'roles users discipline' );
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			if ( $this->users->isLoggedIn()  ) {
				if( gettype( $params ) == 'array' ){
					$id = $params[0];
					$lang = (int)$params[1];
				} else{
					$id = $params;
					$lang = 0;
				}
				$data = $this->get( $id, $lang );

				if( $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), $data['discipline']) ) {
					Core::queueStyle( 'assets/css/reset.css' );
					Core::queueStyle( 'assets/css/ui.css' );
					//put the data onscreen

					$data['categories'] = $this->listCategories();
					$data['disciplines'] = $this->discipline->listing( -1, true );
					$data['language'] = $lang; //bug fix when there is not a lang cert yet
					include( CORE_PATH . 'pages/certEdit.php' );
				} else{
					Core::errorPage( 403 );
				}
			} else {
				Core::errorPage( 404 );
			}
		}

		/**
		 * @param array $input an array of options or an array of array of options for multiple searchs
		 * @return array
		 */
		public function find( $input ){
			//check if array is an array of arrays
			$data = null;
			$multi = false;

			if( isset( $input[0] ) ){
				$multi = true;
			}
			if ( $multi ){
				for( $i = 0; $i < count( $input ); $i++ ){
					$input[$i] = array(
						'order' => isset( $input[$i]['order'] ) ? $input[$i]['order'] : 'id',
						'page' => isset( $input[$i]['page'] ) ? $input[$i]['page'] : 1,
						'search' => isset( $input[$i]['search'] ) ? $input[$i]['search'] : ''
					);
				}
				$data = $input;
			} else {
				$input = array(
					'order' => isset( $input['order'] ) ? $input['order'] : 'id',
					'page' => isset( $input['page'] ) ? $input['page'] : 1,
					'search' => isset( $input['search'] ) ? $input['search'] : ''
				);
				$data = array( $input );
			}

			$this->loadModules( 'roles discipline' );
			$fullRoles = $this->roles->getAllForUser( Core::getSessionId() );
			$userDisciplines = $this->discipline->getIdsForUser( Core::getSessionId() );

			$return =array();
			foreach ( $data as $item ) {
				$query = "SELECT * FROM certificateList";

				if( !empty( $item['search'] ) ){
					$query  .= " WHERE " . $item['search'];
				}

				if( !empty( $item['order'] ) ){
					$query .= " ORDER BY " .$item['order'];
				}

				if ( !$result = $this->db->query( $query ) ) {
					error_log( 'certs.php->find() ' . $this->db->error ) ;
				}

				while ( $row = $result->fetch_assoc() ) {
					if( $this->roles->haveAccess( 'CertView', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines ) ) {
						$canEdit = $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
						$canDelete = $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), $row['discipline'], $fullRoles, $userDisciplines );
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
certificateList.discipline,
certificateData.language,
certificateList.hasAs,
certificateList.hasCe,
certificateList.units,
certificateList.description AS title,
certificateList.sort,
certificateList.active,
certificateData.description,
certificateData.elo,
certificateData.schedule
FROM
certificateList
INNER JOIN certificateData ON certificateList.id = certificateData.cert
WHERE certificateList.id = $id
ORDER by certificateData.language ASC
EOD;
			if ( !$result = $this->db->query( $query ) ) {
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				error_log( 'certs:162 ' . $this->db->error );
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
			$this->loadModules( 'users roles' );
			$lang = new Lang( Lang::getCode() );
			$obj = array();
			if( $this->users->isLoggedIn() ){
				if( $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), Core::sanitize( $_POST['discipline'] ) ) ){
					$this->loadModule( 'audit' );
					$id = core::sanitize( $id );
					$_POST['title'] = core::sanitize( $_POST['title'] ); //certlist description
					$_POST['code'] = core::sanitize( $_POST['code'] );
					$_POST['units'] = core::sanitize( $_POST['units'] );
					$_POST['discipline'] = core::sanitize( $_POST['discipline'] );
//					$_POST['category'] = core::sanitize( $_POST['category'] );
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
//						'category' => (int)$_POST['category'],
						'units' => (int)$_POST['units'],
						'description' => $_POST['title'],
						'sort' => (int)$_POST['sort'],
						'active' => $active,
						'discipline' => $_POST['discipline']
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
			$this->loadModules('users roles discipline');
			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], $this->moduleName );
			if( $this->users->isLoggedIn() ) {
				if( $this->roles->haveAccess( 'CertEdit', Core::getSessionId(), -1 ) ) {
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
							'disciplines' => $this->discipline->listing( true ),
							'language' => 0,
							'active' => 0
						);
					}
					$result->close();
					include( CORE_PATH . 'pages/certEdit.php' );
				}
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
			$lang = new Lang( Lang::getCode() );
			$obj = array();
//			$_POST = Core::sanitize( $_POST, true );
			if( $this->users->isLoggedIn() ) {
				$id = Core::sanitize( $id );
				$row = $this->get( $id, 0, true );
				if( $this->roles->haveAccess( 'CertDelete', Core::getSessionId(), $row['discipline'] ) ){
					$this->loadModule( 'audit' );
					$event = $row['title'];

					$statement = $this->db->prepare( "DELETE FROM certificateList WHERE id=?" );
					$statement->bind_param( "i", $id );
					$statementData = $this->db->prepare( "DELETE FROM certificateData WHERE cert=?" );
					$statementData->bind_param( "i", $id );
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

		public function listingByCodes( $codes ){
			$output = array();
			for( $i = 0; $i < count( $codes ); $i++ ){
				array_push( $output, array( 'search' => "code='" . $codes[$i] . "'") );
			}
			return $this->find( $output );
		}
	}
