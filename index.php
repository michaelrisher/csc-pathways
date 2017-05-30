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

	$uri = array('page'=>'', 'params' => '' );

	if( $url == null ){
		$uri['page'] = 'home';
	} else{
		$tempUri = preg_split( '/\//', $url, 2 );
		$uri['page'] = ( !empty( $tempUri[0] ) ? $tempUri[0] : '' );
	}



	if ( isset( $tempUri[1] ) ) {
		$tempVar = preg_split( '/\//', $tempUri[1] );
		$uri['params'] = ( count( $tempVar ) == 1 ? $tempUri[1] : $tempVar );
	}

	//some globals for page loading
	$scriptQueue = array();
	$styleQueue = array();
	
	if ( file_exists( CORE_PATH . 'pages/' . $uri['page'] .'.php' ) ) {
		$data['params'] = $uri['params'];
		Core::queueStyle( 'assets/css/reset.css' );
		Core::queueStyle( 'assets/css/ui.css' );

		include( CORE_PATH . 'pages/' . $uri['page'] . '.php' );
	}
