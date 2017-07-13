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
				$query = "SELECT * FROM classes ORDER BY sort";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'title' => $row['title']
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
		 * get the data from the database for a class
		 * only allowed if the user is admin
		 * echos json if ajaxed
		 * returns an array if forced to return
		 * @param $id string id of the class
		 * @param bool|false $forceReturn force a return if the get is not called through ajax
		 * @return array|void array if forceReturn is true echos echos json otherwise
		 */
		public function get( $id, $forceReturn = false ) {
			$this->loadModule( 'users' );
			//get the language code for
			$language = Lang::getCode();
			$langQuery = "SELECT id FROM enumLanguages WHERE code = '$language'";
			$langCode = null;
			if( !$result = $this->db->query( $langQuery ) ){
				$langCode = 0; //if couldn't find for whatever reason default to en
			}
			$query = <<<EOD
SELECT
    classes.id,
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
WHERE classData.language = $langCode AND classes.id = '$id'
EOD;


			//$query = "SELECT * FROM classes WHERE id = '$id'";

			if ( !$result = $this->db->query( $query ) ) {
//					echo( 'There was an error running the query [' . $this->db->error . ']' );
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}
			$row = $result->fetch_assoc();
			$return = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'units' => $row['units'],
				'transfer' => $row['transfer'],
				'advisory' => $row['advisory'],
				'prereq' => $row['prereq'],
				'coreq' => $row['coreq'],
				'description' => $row['description']
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
		public function save() {
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if ( $this->users->isLoggedIn() ) {
				$statement = $this->db->prepare( "UPDATE classes SET title=?, units=?, transfer=?, prereq=?, advisory=?, coreq=?, description=? WHERE id=?" );
				$statement->bind_param( "sdssssss", $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['coreq'], $_POST['description'], $_POST['id'] );
				if ( $statement->execute() ) {
					$obj['msg'] = "Saved successfully.";
					$this->audit->newEvent( "Updated class: " . $_POST['title'] );
					echo Core::ajaxResponse( $obj );
				} else {
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired.<br>Please log in again";
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
					$obj['msg'] = "Deleted successfully.";
					$this->audit->newEvent( "Deleted class: " . $event );
					echo Core::ajaxResponse( $obj );
				} else {
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * Create a class from the admin page
		 * only allowed if the user is admin
		 */
		public function create() {
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if ( $this->users->isLoggedIn() ) {
				$statement = $this->db->prepare( "INSERT INTO classes(id, title, units, transfer, prereq, advisory, coreq, description) VALUES (?,?,?,?,?,?,?,?)" );
				$statement->bind_param( "ssdsssss", $_POST['id'], $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['coreq'], $_POST['description'] );
				if ( $statement->execute() ) {
					$obj['msg'] = "Created successfully.";
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
				$obj['error'] = "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
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