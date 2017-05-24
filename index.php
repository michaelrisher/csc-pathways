<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 09:41
	 */

	require_once 'config.php';

	//get the url
	$url = isset( $_GET['url'] ) ? $_GET['url'] : null;

	//start sessions
	session_start();

	if( $url == null ){
		$url['url'] = 'home';
	}

	//some globals for page loading
	$scriptQueue = array();
	$styleQueue = array();
	
	if ( file_exists( CORE_PATH . 'pages/' . $url['url'] .'.php' ) ) {
		Core::queueStyle( 'assets/css/reset.css' );
		Core::queueStyle( 'assets/css/ui.css' );

		include( CORE_PATH . 'pages/' . $url['url'] . '.php' );
	}
