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
<div class="modal none" data-id="1" style="z-index: 5; left: 497px; top: 0px; display: block;">
	<div class="modalWrapper">
		<div class="modalHeader clearfix">
			<span class="title">Edit User</span><span class="close">&times;</span>
		</div>
		<div class="modalContent">
			<div class='tabWrapper users'>
				<div class="tab active" data-tab="edit" >Edit</div>
				<div class="tab" data-tab="roles" >Roles</div>
				<div class="tab" data-tab="dept" >Discipline</div>
			</div>
			<form>
				<input name="id" type="hidden" value="10">
				<ul>
					<li><label for="username">User name</label><input name="username" type="text" value="joe"><span>Enter the username</span></li>
					<li><label for="isAdmin">Admin</label><input name="isAdmin" type="checkbox" value="1">Check if the user should be able to edit users<span>Check if the user should be able to edit users</span></li>
					<li><label for="active">Active User</label><input checked="checked" name="active" type="checkbox" value="1">Check if the user should be allowed to login<span>Check if the user should be allowed to login</span></li>
				</ul>
			</form>
		</div>
		<div class="modalFooter">
			<input class="" name="save" type="button" value="Save"><input class="low" name="" type="button" value="Reset Password"><input class="low" name="" type="button" value="Cancel">
		</div>
	</div>
</div>
</body>
</html>