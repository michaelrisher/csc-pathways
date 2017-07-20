<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/10/2017
	 * Time: 09:47
	 */
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		die( "not logged in" );
	}
	bind_textdomain_codeset('default', 'ISO-8859-1');
	$language = $_GET['lang'];
	echo $language . '<br>';
	putenv( "LANG=$language" );
	setlocale( LC_ALL, $language );

	$domain = 'messages';
	echo CORE_PATH . "locale";
	echo '<br>';
	bindtextdomain( $domain, CORE_PATH . "locale" );
	textdomain( $domain );

	echo gettext( "A string to be translated would go here" );
	echo '<br>';
	echo _( 'test' );
	echo '<br>';

	phpinfo();