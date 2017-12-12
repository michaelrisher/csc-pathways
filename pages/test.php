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
	$GLOBALS['main']->loadModule( 'certs' );
	$data = $GLOBALS['main']->certs->listingByCodes( array( 650, 728, 803 ) );
	Core::debug( $data );


?>
</body>
</html>