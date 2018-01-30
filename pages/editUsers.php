<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/21/2017
	 * Time: 11:47
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
		Core::queueStyle( 'assets/css/select2.css' );
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="admin">
			<div class="users aligncenter margin15Bottom">
				<p>Users</p>
				<div class="listing alignleft">
					<ul>
						<?php
							$GLOBALS['main']->loadModule( 'users' );
							$data = $GLOBALS['main']->users->listing();
							if( isset( $data ) ) {
								foreach ( $data as $user ) {
									echo "<li data-id='${user['id']}'>${user['username']}";
									if ( $user['delete'] )
										echo "<img class='delete tooltip' src='" . CORE_URL . "assets/img/delete.png' title='Delete User'/>";
									if ( $user['edit'] )
										echo "<img class='edit tooltip' src='" . CORE_URL . "assets/img/edit.svg' title='Edit User'/>";
									else
										echo "<img class='view tooltip' src='" . CORE_URL . "assets/img/view.png' title='View User'/>";
									echo "</li>";
								}
							} else{
								echo "<li>There are no users or you do not have the rights to see users</li>";
							}
						?>
					</ul>
				</div>
				<div class="margin25Top">
					<?php
						$GLOBALS['main']->loadModule( 'roles' );
						if( $GLOBALS['main']->roles->haveAccess( 'UserEdit', Core::getSessionId(), -1 ) ){
					?>
					<input type="button" value="Create User" name="createUser"/>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::queueScript( 'assets/js/users.js' );
	Core::queueScript( 'assets/js/select2.js' );
	Core::includeScripts();
?>

</body>
</html>