<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/19/2017
	 * Time: 12:33
	 */
	class language extends Main {
		/**
		 * get a simple listing of classes only id and title are returned
		 * only allowed if the user is admin
		 * @param int $page not used yet
		 * @return array
		 */
		public function listing( $page = 1 ) {
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM enumLanguages";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'code' => $row['code'],
						'fullName' => utf8_encode( $row['fullName'] )
					);
					array_push( $return, $a );
				}
				if ( IS_AJAX ) {
//					echo '{"success" : true, "data" : { "s" : 1 } }';
					$s = Core::ajaxResponse( $return );
					echo $s;
				} else {
					return $return;
				}
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
			$query = "SELECT * FROM enumLanguages WHERE id = $id";

			if ( !$result = $this->db->query( $query ) ) {
				//echo( 'There was an error running the query [' . $this->db->error . ']' );
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}
			$row = $result->fetch_assoc();
			$return = array(
				'id' => $row['id'],
				'code' => $row['code'],
				'fullName' => $row['fullName']
			);

			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}


		/**
		 * @param $code
		 * @param bool|false $forceReturn
		 * @return array|null
		 */
		public function getId( $code, $forceReturn = false ){
			$query = "SELECT id FROM enumLanguages WHERE code = '$code'";
			if ( !$result = $this->db->query( $query ) ) {
				//echo( 'There was an error running the query [' . $this->db->error . ']' );
				//echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}
			$row = $result->fetch_assoc();
			$return = $row['id'];

			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}
	}