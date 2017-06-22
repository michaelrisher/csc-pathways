<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */
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
<div id="wrapper" class="admin">
	<div id="headerWrapper">
		<div id="header">
			<div class="clearfix">
				<div class="floatleft title"><a href="<?= CORE_URL ?>home">Computer Science <br> Computer Information Systems</a></div>
				<div class="floatleft subtitle">Join the Bitcoin Revolution.</div>
			</div>
			<div class="nav clearfix">
				<ul>
					<li><a href="<?= CORE_URL ?>admin">Admin Home</a></li>
					<li><a href="<?= CORE_URL ?>editClass">Classes</a></li>
					<li><a href="<?= CORE_URL ?>editCerts">Certificates</a></li>
					<?php if( $GLOBALS['main']->users->isAdmin() ) { ?>
						<li><a href="<?= CORE_URL ?>editUsers">Users</a></li>
					<?php } ?>
				</ul>
				<div class="floatright">
					<a href="logout">Logout</a>
				</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="admin">
			<div class="classes aligncenter">
				<p>Audit Log</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'audit' );
						$data = $GLOBALS['main']->audit->listing();
						foreach ( $data as $event ) {
							echo "<li>User ${event['username']}: ${event['event']}  <span class='floatright'>${event['date']}</span>";
							echo "</li>";
						}
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>

</body>
</html>

<?php
//	Core::debug( $_SESSION );
//	Core::debug( $_SERVER );
?>
