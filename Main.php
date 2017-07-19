<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/31/2017
	 * Time: 13:17
	 */
	class Main {
		public $db;
		public $url;

		public function Main(){
			$connection = new mysqli( DB_IP, DB_USER, DB_PASS, DB_DB );

			if($connection->connect_errno > 0){
				//TODO change this
				die('Unable to connect to database [' . $connection->connect_error . ']');
			}
			$this->db = $connection;
		}

		public function __destruct(){
			$this->db->close();
		}

		public function uri( $url ){
			$this->url = $url;
		}

		//load a module
		public function loadModule( $module ){
			if( isset( $this->$module ) ) { //if already defined
				return;
			}

			$class = $module;
			if( file_exists( CORE_PATH . 'modules/' . $module . '.php' ) ) {
				require_once( CORE_PATH  . 'modules/' . $module . '.php' );
			}

			$this->$module = new $class;
		}

		/**
		 * @param $table
		 * @param $id
		 * @param array $data
		 * @return bool true if successful
		 * {
		 * id : data,  //required
		 * column : data
		 * }
		 */
		protected function upsertRecord( $table, $where, $data = array() ){
			$exists = false;
			if( isset( $where ) ) {
				$query = "SELECT * FROM $table WHERE $where";
				if( !$result = $this->db->query($query) ){
					return false;
				}
				if( $result->num_rows > 0 ){
					$exists = true;
				}
				$result->close();
				if ( $exists ) { //exists so update
					$query = "UPDATE $table SET ";
					foreach( $data as $key => $val ){
						$query .= $key . '=';
						if ( gettype( $val ) == 'string' ) {
							$query .= "\"" . $val . "\", ";
						} else{
							$query .= $val . ', ';
						}
					}

					//remove last comma and space
					$query = rtrim( $query, ", " );
					$query .= " WHERE " . $where;

					if( $this->db->query( $query ) ) {
						return true;
					} else {
						return false;
					}
				} else { //the record does not exist
					$query = "INSERT INTO $table (";
					$values = array();
					foreach( $data as $key => $val ){
						$query .= $key . ',';
						array_push( $values, $val );
					}
					$query = rtrim( $query, ',' ); //remove the right comma
					$query .= ') VALUES (';
					for( $i = 0; $i < count( $values ); $i++ ) {
						if ( gettype( $values[$i] ) == 'string' ) {
							$query .= "\"" . $values[$i] . "\",";
						} else{
							$query .= $values[$i] . ',';
						}
					}
					$query = rtrim( $query, ',' ); //remove the right comma
					$query .= ')';
					if( $this->db->query( $query ) ) {
						return true;
					} else {
						return false;
					}
				}
			}
		}

		/**
		 * @param $table
		 * @param $where
		 * @return bool|mysqli_result
		 */
		protected function deleteRecord( $table, $where ){
			if( isset( $where ) ) {
				$query = "DELETE FROM $table WHERE $where";

				return $this->db->query( $query );
			}
		}
	}