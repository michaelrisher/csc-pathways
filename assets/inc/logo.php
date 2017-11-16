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
		<?php if( $GLOBALS['main']->users->isLoggedIn() ) {
			include CORE_PATH . 'assets/inc/nav.php';
		}
		?>
	</div>
</div>
