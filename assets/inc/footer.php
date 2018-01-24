<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 10:09
	 */
	$GLOBALS['main']->loadModule( 'language' );
	$languages = $GLOBALS['main']->language->listing();
	?>

<footer>
	<div>
		<span><?=$lang->o("language")?>:</span>
		<div class="langPicker">
			<ul>
				<li>
					<span id="currentLang"><img src="<?=CORE_URL?>assets/img/flags.png" class="flag <?= $language ?>"/><?= Lang::codeToText( $language ); ?></span>
					<span><img src="<?=CORE_URL?>assets/img/upArrow.png" class="uparrow"/></span>
					<ul class="langList">
						<?php
							foreach ( $languages as $lang ) {
								echo "<li data-value='".$lang['code']."'><img src='".CORE_URL."assets/img/flags.png' class='flag ".$lang['code']."' />";
								echo htmlentities( $lang['fullName'], 0, 'UTF-8' );
								echo "</li>";
							}
						?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</footer>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( 'assets/js/ui.js');
	Core::queueScript( 'assets/js/jquery-ui.js' );