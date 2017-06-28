<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 14:12
	 */
	class audit extends Main{
		/**
		 * get a full listing of the db
		 * @param int $page not used yet
		 * @return array
		 */
		public function listing( $page = 1 ){
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$limit = 25;
				$page--;//to make good looking page numbers for users
				$offset = $page * $limit;
				$query = "SELECT date,users.username,event FROM audit INNER JOIN users ON audit.user = users.id ORDER BY date DESC LIMIT $offset,$limit";

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'date' => $row['date'],
						'username' => $row['username'],
						'event' => $row['event']
					);
					array_push( $return, $a );
				}
				return $return;
			}
		}

		public function getPages(){
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$limit = 25;
				$query = "SELECT COUNT(id) as pages FROM audit";

				if ( $result = $this->db->query( $query ) ) {
					$row = $result->fetch_assoc();
					return ceil( $row['pages'] / $limit );
				}
			}
		}

		/**
		 * create a new event in the audit log
		 * @param $event
		 */
		public function newEvent( $event ){
			$this->loadModule('users');
			if( $this->users->isLoggedIn() ){
				$statement = $this->db->prepare("INSERT INTO audit( user, event) VALUES (?,?)");
				$statement->bind_param( "is", $_SESSION['session']['id'], $event );
				$statement->execute();
			}
		}
	}
