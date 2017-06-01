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
				<div class="floatleft title">Computer Science <br> Computer Information Systems</div>
				<div class="floatleft subtitle">Join the Bitcoin Revolution.</div>
			</div>
			<div class="nav clearfix">
				<ul>
					<li>link1</li>
					<li>link1</li>
					<li>link1</li>
				</ul>
				<div class="floatright">
					<a href="logout">Logout</a>
				</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="admin">
			stuff here
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
<!--<div class="modal-shadow"></div>-->
<div class="modal">

</div>
</body>
</html>

<?php
//	Core::debug( $_SESSION );
//	Core::debug( $_SERVER );
?>

<!--
<tr class="treeCert">
	<td></td>
	<td></td>
	<td></td>
</tr>

-->