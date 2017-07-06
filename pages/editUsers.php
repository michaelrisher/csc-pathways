<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/21/2017
	 * Time: 11:47
	 */
	if( !$GLOBALS['main']->users->isAdmin() ){
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
				<?php include_once CORE_PATH . 'assets/inc/adminNav.php'; ?>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="admin">
			<div class="users aligncenter margin15Bottom">
				<p>Users</p>
				<div class="listing alignleft">
					<ul>
						<?php
							$GLOBALS['main']->loadModule( 'users' );
							$data = $GLOBALS['main']->users->listing();
							foreach ( $data as $user ) {
								echo "<li data-id='${user['id']}'>${user['username']}";
								echo "<img class='delete tooltip' src='". CORE_URL ."assets/img/delete.png' title='Delete User'/>";
								echo "<img class='edit tooltip' src='". CORE_URL ."assets/img/edit.svg' title='Edit User'/>";
								echo "</li>";
							}
						?>
					</ul>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create User" name="createUser"/>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
?>

</body>
</html>