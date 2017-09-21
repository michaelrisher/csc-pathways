<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */
	if( $GLOBALS['main']->users->isLoggedIn() ){
		Core::phpRedirect( 'admin' );
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
	<?php include CORE_PATH . 'assets/inc/logo.php' ?>
	<div id="main">
		<div class="login">
			<div class="loginWrapper aligncenter">
				<p>Administrator Login</p>
				<form action="users/login" method="post">
					<ul>
						<li>
							<label for="user">Username</label>
							<input type="text" name="user" maxlength="100">
							<span>Enter your Username</span>
						</li>
						<li>
							<label for="password">Password</label>
							<input type="password" name="password" maxlength="100">
							<span>Enter a valid password</span>
						</li>
						<li>
							<input type="submit" value="Login" >
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::includeScripts();
?>

</body>
</html>