<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 09:47
	 */
	define( 'MODE', 'local' );
	

	if( MODE == 'local' ) {
		define( 'CORE_DIR', 'lab/' );
		define( 'CORE_PATH', $_SERVER['DOCUMENT_ROOT'] . substr( $_SERVER['SCRIPT_NAME'], 0, -9 ) );
		define( 'CORE_ROOT', $_SERVER['DOCUMENT_ROOT'] . substr( $_SERVER['SCRIPT_NAME'], 0, ( -9 - strlen( CORE_DIR ) ) ) );
		define( 'CORE_REQUEST_TYPE', $_SERVER['REQUEST_METHOD'] );
		define( 'CORE_URL', ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://' . $_SERVER[ 'HTTP_HOST' ] . '/' . CORE_DIR );
		define( 'DB_IP', 'localhost' );
		define( 'DB_USER', 'root' );
		define( 'DB_PASS', '' );
		define( 'DB_DB', 'pathways' );
	} else if( MODE == 'live' | MODE == 'staging' ) {
		define( 'CORE_DIR', 'pathways/' );
		define( 'CORE_PATH', '/home/michael_risher/public_html/' . CORE_DIR );//$_SERVER['DOCUMENT_ROOT'] . substr( $_SERVER['SCRIPT_NAME'], 0, -9 ) );
		define( 'CORE_ROOT', '/home/michael_risher/public_html/' );//$_SERVER['DOCUMENT_ROOT'] . substr( $_SERVER['SCRIPT_NAME'], 0, ( -9 - strlen( CORE_DIR ) ) ) );
		define( 'CORE_REQUEST_TYPE', $_SERVER['REQUEST_METHOD'] );
		define( 'CORE_URL', ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://' . $_SERVER[ 'HTTP_HOST' ] . '/~michael_risher/' . CORE_DIR . '/' );
		define( 'DB_IP', 'localhost' );
		define( 'DB_USER', 'pathways' );
		define( 'DB_PASS', 'S0larF0r3st!' );
		define( 'DB_DB', 'pathways' );
	}
	
	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		define( 'IS_AJAX', true );
	} else{
		define( 'IS_AJAX', false );
	}

	function __autoload( $className ) {
		$lib = CORE_PATH . 'classes/class.' . $className . '.php';
		if( IS_AJAX ){ //to make the Core work on the classes and cert pages
			if( MODE == 'local' ) {
				$lib = $_SERVER['DOCUMENT_ROOT'] . '/' . CORE_DIR . 'classes/class.' . $className . '.php';
			} elseif( MODE == 'live' || MODE == 'staging' ){
				$lib = CORE_ROOT . CORE_DIR . 'classes/class.' . $className . '.php';
			}
			//die( $lib );
		}
		if( file_exists( $lib ) ){
			require_once( $lib );
		}
	}