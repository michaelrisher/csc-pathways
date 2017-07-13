<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 10:09
	 */
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
						<li data-value="en"><img src="<?=CORE_URL?>assets/img/flags.png" class="flag en" />English</li>
						<li data-value="es"><img src="<?=CORE_URL?>assets/img/flags.png" class="flag es"/>Español</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</footer>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( 'assets/js/ui.js');
	Core::includeScripts();