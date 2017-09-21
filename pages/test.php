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
//	bind_textdomain_codeset('default', 'ISO-8859-1');
//	$language = $_GET['lang'];
//	echo $language . '<br>';
//	putenv( "LANG=$language" );
//	setlocale( LC_ALL, $language );
//
//	$domain = 'messages';
//	echo CORE_PATH . "locale";
//	echo '<br>';
//	bindtextdomain( $domain, CORE_PATH . "locale" );
//	textdomain( $domain );
//
//	echo gettext( "A string to be translated would go here" );
//	echo '<br>';
//	echo _( 'test' );
//	echo '<br>';
//
//	phpinfo();

//	$db = new DB();
//	$con = $db->createConnection();
//
//	$query = "SELECT * FROM audit WHERE user=?";
//	$state = $con->prepare( $query );
//	$id = 2;
//	$str = "\$state->bind_param( 'i', \$id );";
//	eval( $str );
//	$result = $state->execute();
//	Core::debug( $result );

	$data = array(
		'id' => 0,
		'title' => 'tile',
		'hasAs' => 0.0,
		'sort' => true,
	);

	$query = "UPDATE table SET ";
	$evalStr = "\$state->bind_param(";
	$paramsStr = '';
	$bindType = '';
	foreach( $data as $key => $val ){
		$query .= $key . '=?,';
		$paramsStr .= "\$data['$key'], ";
		$type = gettype( $val );
		if ( $type == 'string' ) {
			$bindType .= 's';
		} elseif( $type == 'double' ) {
			$bindType .= 'd';
		} elseif( $type == 'integer' || $type == 'boolean' ){
			$bindType .= 'i';
		} else {
			$bindType .= 'b';
		}
	}

	$query = rtrim( $query, "," );
	$paramsStr = rtrim( $paramsStr, ", " );
	$query .= " WHERE \$where";
	$evalStr .= "\"$bindType\"," .$paramsStr . ");";


	Core::debug( array(
		'query' => $query,
		'eval' => $evalStr,
		'param' => $paramsStr,
		'bind' => $bindType
	) );