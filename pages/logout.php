<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */

	if( $GLOBALS['main']->users->isLoggedIn() ){
		$GLOBALS['main']->users->logout();
	} else{
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
<div id="wrapper">
	<div id="headerWrapper">
		<div id="header">
			<div class="clearfix">
				<div class="floatleft title">Computer Science <br> Computer Information Systems</div>
				<div class="floatleft subtitle">Join the Bitcoin Revolution.</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="logout">
			<div class="aligncenter">
				<p>Logged Out Successfully</p>
				<p><?=Core::createTimer( 5 )?></p>
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
<script>
	startTimer($('.timer' ),function(){ location.href = 'home'; });
</script>
</body>
</html>
