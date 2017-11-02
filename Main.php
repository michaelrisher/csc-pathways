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
		private $lastError;

		public function Main(){
			$connection = new mysqli( DB_IP, DB_USER, DB_PASS, DB_DB );

			if($connection->connect_errno > 0){
				//TODO change this
				die('Unable to connect to database [' . $connection->connect_error . ']');
			}
			$this->db = $connection;
		}

		/**
		 * destructor
		 */
		public function __destruct(){
			$this->db->close();
		}

		/**
		 * returns the uri (ie the module, function, params )
		 * @param string $url
		 */
		public function uri( $url ){
			$this->url = $url;
		}

		/**
		 * load modules in a space delimited list
		 * @param string $modules names of the modules to load in a space delimited list
		 */
		public function loadModules( $modules ){
			$array = explode( ' ', $modules );
			for( $i = 0; $i < count( $array ); $i++ ){
				$this->loadModule( $array[$i] );
			}
		}

		/**
		 * load a single module
		 * @param string $module name of the module to load
		 */
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
		 * @param $where
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
				if( !$result = @$this->db->query($query) ){
					return false;
				}
				if( @$result->num_rows > 0 ){
					$exists = true;
				}
				@$result->close();
				$evalStr = "\$state->bind_param(";
				$bindType = '';
				$paramsStr = '';
				if ( $exists ) { //exists so update
					$query = "UPDATE $table SET ";
					foreach( $data as $key => $val ){
						$query .= $key . '=?,';
						$paramsStr .= "\$data['$key'], ";
						$bindType .= $this->getTypeMysql( $val );
					}

					//remove last comma and space
					$query = rtrim( $query, "," );
					$paramsStr = rtrim( $paramsStr, ", " );
					$query .= " WHERE $where;";
					$evalStr .= "\"$bindType\"," .$paramsStr . ");";

					//setup statements
					$state = @$this->db->prepare( $query );
					//bind param call using eval
					@eval( $evalStr );
					//after bind then excute
					if( @$state->execute() ) {
						return true;
					} else {
						$this->lastError = $state->error;
						return false;
					}
				} else { //the record does not exist
					$query = "INSERT INTO $table (";
					$queryPart2 = '';
					foreach( $data as $key => $val ){
						$query .= $key . ',';
						$queryPart2 .= '?,';
						$bindType .= $this->getTypeMysql( $val );
						$paramsStr .= "\$data['$key'], ";
					}
					$query = rtrim( $query, ',' ); //remove the right comma
					$queryPart2 = rtrim( $queryPart2, ',' ); //remove the right comma
					$query .= ') VALUES (' . $queryPart2 . ')';
					//query should be like this at this point
					//INSERT INTO table (col1,col2,col3) VALUES (?,?,?)
					//remove last comma
					$paramsStr = rtrim( $paramsStr, ", " );
					//set up the eval string
					$evalStr .= "\"$bindType\"," .$paramsStr . ");";

					//setup statements
					$state = @$this->db->prepare( $query );
					//bind param call using eval
					@eval( $evalStr );
					//after bind then excute
					if( @$state->execute() ) {
						return true;
					} else {
						$this->lastError = $state->error;
						return false;
					}
				}
			}
		}

		/**
		 * translates php variables into bind_parm data-type strings
		 * @param $val
		 * @return string
		 */
		private function getTypeMysql( $val ){
			$type = gettype( $val );
			if ( $type == 'string' ) {
				return's';
			} elseif( $type == 'double' ) {
				return 'd';
			} elseif( $type == 'integer' || $type == 'boolean' ){
				return 'i';
			} else {
				return 'b';
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

		protected function getLastError(){
			return $this->lastError;
		}
	}