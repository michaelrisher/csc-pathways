<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/12/2017
	 * Time: 12:01
	 */
?>
<div id="headerWrapper">
	<div id="header">
		<div class="clearfix">
			<div class="floatleft title"><a href="<?= CORE_URL ?>home"><?= $lang->o('title1') . '<br>' . $lang->o('title0') ?></a></div>
			<div class="floatleft subtitle"><?= $lang->o('subtitle') ?></div>
		</div>
		<?php if( $GLOBALS['main']->users->isLoggedIn() ){ ?>
		<div class="nav clearfix">
			<ul>
				<li><a href="<?= CORE_URL ?>admin">Admin Home</a></li>
				<li><a href="<?= CORE_URL ?>editClass">Classes</a></li>
				<li><a href="<?= CORE_URL ?>editCerts">Certificates</a></li>
<!--				--><?php //if( $GLOBALS['main']->users->isAdmin() ) { ?>
					<li><a href="<?= CORE_URL ?>editUsers">Users</a></li>
<!--				--><?php //} ?>
			</ul>
			<div class="floatright">
				<a href="<?= CORE_URL ?>help">Help</a>
				<a href="<?= CORE_URL ?>logout">Logout</a>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
