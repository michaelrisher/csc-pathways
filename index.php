<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 09:41
	 */
	require_once 'config.php';

	//clean the get
	$_GET = Core::sanitize($_GET);
	//get the url
	$url = isset( $_GET['url'] ) ? $_GET['url'] : null;

	//start sessions
	session_start();

	$uri = array('module'=>'', 'function' => '' );
	$url = trim( $url, '/' );
	$tempUri = preg_split( '/\//', $url, 3 );
	$uri['module'] = ( !empty( $tempUri[0] ) ? $tempUri[0] : '' );
	$uri['function'] = ( isset( $tempUri[1] ) ? $tempUri[1] : '' );
	if ( isset( $tempUri[2] ) ) {
		$tempVar = preg_split( '/\//', $tempUri[2] );
		if( !empty( $tempVar ) ){
			$uri['params'] = ( count( $tempVar ) == 1 ? $tempUri[2] : $tempVar );
		}
	}

	//some globals for page loading
	$scriptQueue = array();
	$styleQueue = array();

	//load the webgear class
	require_once( 'Main.php');
	$main = new Main();
	$main->uri( $uri );
	$GLOBALS['main'] =& $main;
	$main->loadModule( 'users' );
	$main->users->checkExpiredSession(); //check for expired session

	//set cookie with the core_url
	setcookie ("url", CORE_URL, time()+3600*24*(2) );

	$GLOBALS['main']->loadModule( $uri['module'] );
	if ( isset( $uri['params'] ) ) {
		if( method_exists( $GLOBALS['main']->$uri['module'], $uri['function'] ) ){
			$response = $GLOBALS['main']->$uri['module']->{$uri['function']}($uri['params']);
		} else {
			Core::errorPage( 404 );
		}
	} else{
		if( method_exists( $GLOBALS['main']->$uri['module'], $uri['function'] ) ) {
			$response = $GLOBALS['main']->$uri['module']->{$uri['function']}();
		} else {
			Core::errorPage( 404 );
		}
	}
