<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */
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
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="resetPassword">
			<div class="loginWrapper aligncenter">
				<p>Reset Password</p>
				<div class="alignleft margin15Top">Password Tips:</div>
				<ol class="alignleft">
					<li>Use at least 1 lower case letter</li>
					<li>Use at least 1 upper case letter</li>
					<li>Use at least 1 number</li>
					<li>Use at least 1 symbol</li>
					<li>Do not use commonly known information about yourself like a birthday</li>
					<li>Do not use the same password you use for RCC logins</li>
					<li><a href="https://securingtomorrow.mcafee.com/consumer/family-safety/15-tips-to-better-password-security/">More information on safer passwords</a></li>
				</ol>
				<form action="users/verifyToken" method="post">
					<input type="hidden" value="<?= isset( $_GET['token'] ) ? $_GET['token'] : '' ?>" name="token" />
					<input type="hidden" value="<?= isset( $_GET['token'] ) ? 0 : 1 ?>" name="stage">
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
						<li class="noneImportant">
							<label for="password2">Confirm Password</label>
							<input type="password" name="password2" maxlength="100">
							<span>Confirm the above password</span>
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