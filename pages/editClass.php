<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:13
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
					<li><a href="admin">Admin Home</a></li>
					<li><a href="editClass">Classes</a></li>
					<li>Certificates</li>
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
				<p>Classes</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'classes' );
						$data = $GLOBALS['main']->classes->listing();
						foreach ( $data as $class ) {
							echo "<li data-id='${class['id']}'>${class['title']}";
							echo "<img class='delete' src='". CORE_URL ."assets/img/delete.png'/>";
							echo "<img class='edit' src='". CORE_URL ."assets/img/edit.svg'/>";
							echo "</li>";
						}
					?>
					</ul>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create Class" name="createClass"/>
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
