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
	}