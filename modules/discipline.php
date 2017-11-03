<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 11/1/2017
	 * Time: 18:06
	 */
	class discipline extends Main{
		private $moduleName = 'discipline';

		/**
		 * get the listing of disciplines. didnt see fit to make this its own module
		 * @param bool|false $forceReturn
		 * @return array
		 */
		public function listing( $forceReturn = false){
			//to kinda standardize it for later needs
			$query = "SELECT * FROM disciplines";

			if ( !$result = $this->db->query( $query ) ) {
				echo( 'There was an error running the query [' . $this->db->error . ']' );
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				$a = array(
					'id' => $row['id'],
					'name' => $row['name'],
					'description' => $row['description']
				);
				array_push( $return, $a );
			}


			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			}
			return $return;
		}

		/**
		 * Gets a single discipline
		 * @param int $did
		 * @param bool|false $forceReturn
		 * @return array|void
		 */
		public function get( $did, $forceReturn = false ){
			$query = "SELECT * FROM disciplines WHERE id = '$did'";

			if ( !$result = $this->db->query( $query ) ) {
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}
			$row = $result->fetch_assoc();
			$return = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'description' => $row['description']
			);

			if ( IS_AJAX  && !$forceReturn) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}

		/**
		 * gets all the discipline for a user
		 * @param int $uid user id
		 * @return array|void
		 */
		public function getForUser( $uid ){
			$query = <<<EOD
SELECT
    users.id as userId,
    disciplines.id as disciplineId,
    disciplines.name,
    disciplines.description
FROM
    userXdiscipline,
    users,
    disciplines
WHERE
    userXdiscipline.userId = users.id AND disciplines.id = userXdiscipline.disciplineId AND users.id = $uid
EOD;

			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, array(
					'id' => $row['disciplineId'],
					'name' => $row['name'],
					'description' => $row['description']
				) );
			}
			return $return;
		}

		public function getIdsForUser( $uid ){
			$query = <<<EOD
SELECT
    users.id as userId,
    disciplines.id as disciplineId
FROM
    userXdiscipline,
    users,
    disciplines
WHERE
    userXdiscipline.userId = users.id AND disciplines.id = userXdiscipline.disciplineId AND users.id = $uid
EOD;

			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, $row['disciplineId'] );
			}
			return $return;
		}
	}