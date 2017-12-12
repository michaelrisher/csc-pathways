<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 11:45
	 */
	$GLOBALS['main']->loadModule( 'roles' );
?>
<div class="nav clearfix">
	<ul>
		<li><a href="<?= CORE_URL ?>admin">Admin Home</a></li>
		<li><a href="<?= CORE_URL ?>editClass">Classes</a></li>
		<li><a href="<?= CORE_URL ?>editCerts">Certificates</a></li>
		<li><a href="<?= CORE_URL ?>editUsers">Users</a></li>
		<?php if( $GLOBALS['main']->roles->haveAccess( 'dataManage', Core::getSessionId(), -1 ) ) { ?>
			<li><a href="<?= CORE_URL ?>editDisciplines">Disciplines</a></li>
			<li><a href="<?= CORE_URL ?>editPages">Pages</a></li>
		<?php } ?>
	</ul>
	<div class="floatright">
		<a href="<?= CORE_URL ?>help">Help</a>
		<a href="<?= CORE_URL ?>logout">Logout</a>
	</div>
</div>