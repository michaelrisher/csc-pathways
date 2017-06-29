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

	$tempUri = preg_split( '/\//', $url, 3 );
	$uri['module'] = ( !empty( $tempUri[0] ) ? $tempUri[0] : '' );
	$uri['function'] = ( isset( $tempUri[1] ) ? $tempUri[1] : '' );
	if ( isset( $tempUri[2] ) ) {
		$tempVar = preg_split( '/\//', $tempUri[2] );
		$uri['params'] = ( count( $tempVar ) == 1 ? $tempUri[2] : $tempVar );
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

	//TODO fix it so all functions that don't exist go to 404
	if ( /*IS_AJAX &&*/ isset( $_GET['rested'] ) ) { //calling a module
		$GLOBALS['main']->loadModule( $uri['module'] );
		if ( isset( $uri['params'] ) ) {
			$response = $GLOBALS['main']->$uri['module']->{$uri['function']}($uri['params']);
		} else{
			$response = $GLOBALS['main']->$uri['module']->{$uri['function']}();
		}
	} elseif( $uri['module'] == 'pages' ) {
		//simple page call
		if( gettype( $uri['params'] ) == 'array' ){ //todo deal with multiple params at a later date
			$uri['moreParams'] = $uri['params'][1];
			$uri['params'] = $uri['params'][0];
		}
		//if the page exists load it with the params if there was any
		if ( file_exists( CORE_PATH . 'pages/' . $uri['params'] .'.php' ) ) {
			$data['params'] = isset( $uri['moreParams'] ) ? $uri['moreParams'] : $uri['params'];
			Core::queueStyle( 'assets/css/reset.css' );
			Core::queueStyle( 'assets/css/ui.css' );
			include( CORE_PATH . 'pages/' . $uri['params'] . '.php' );
		} else {
			//the page doesn't exist so 404 the user
			Core::errorPage( 404 );
		} //TODO error page
	}
