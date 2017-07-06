<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/6/2017
	 * Time: 11:42
	 */
?>
<ul>
	<li><a href="<?= CORE_URL ?>admin">Admin Home</a></li>
	<li><a href="<?= CORE_URL ?>editClass">Classes</a></li>
	<li><a href="<?= CORE_URL ?>editCerts">Certificates</a></li>
	<?php if( $GLOBALS['main']->users->isAdmin() ) { ?>
		<li><a href="<?= CORE_URL ?>editUsers">Users</a></li>
	<?php } ?>
</ul>
<div class="floatright">
	<a href="<?= CORE_URL ?>help">Help</a>
	<a href="<?= CORE_URL ?>logout">Logout</a>
</div>
