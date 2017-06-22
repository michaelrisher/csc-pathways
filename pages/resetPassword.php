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
	<div id="headerWrapper">
		<div id="header">
			<div class="clearfix">
				<div class="floatleft title">Computer Science <br> Computer Information Systems</div>
				<div class="floatleft subtitle">Join the Bitcoin Revolution.</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="resetPassword">
			<div class="loginWrapper aligncenter">
				<p>Reset Password</p>
				<form action="users/verifyToken" method="post">
					<input type="hidden" value="<?= isset( $_GET['token'] ) ? $_GET['token'] : ''?>" name="token" />
					<ul>
						<li>
							<label for="user">Username</label>
							<input type="text" name="user" maxlength="100">
							<span>Enter your Username</span>
						</li>
						<li class="noneImportant">
							<label for="password">Password</label>
							<input type="password" name="password" maxlength="100">
							<span>Enter a valid password</span>
						</li>
						<li>
							<input type="submit" value="Next" >
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
</body>
</html>