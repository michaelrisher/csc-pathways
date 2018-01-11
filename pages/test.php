<?php
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		Core::errorPage( 404 );
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::includeStyles();
	?>
</head>
<body>
<?php
	$GLOBALS['main']->loadModules( 'certs classes' );
	$data = $GLOBALS['main']->classes->find( array( "search" => 'title="EXA-1 - Example class a simple testj"' ) );
	Core::debug( $data );
	$data = $GLOBALS['main']->classes->find( array( "search" => 'title="EXA-1 - Example class a simple test"' ) );
	Core::debug( $data );
	$data = $GLOBALS['main']->classes->find( array( "search" => 'title LIKE "EXA-1%"' ) );
	Core::debug( $data );


?>
</body>
</html>