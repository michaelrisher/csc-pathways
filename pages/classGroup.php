<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 12/14/2017
	 * Time: 11:24
	 */
	if( is_array( $data['params'] ) ){
		$id = $data['params'][1];
	} else {
		$id = $data['params'];
	}
	$GLOBALS['main']->loadModule( 'classes' );
	$row = $GLOBALS['main']->classes->getGroup( $id, true );
	$lang = new Lang( Lang::getCode() );
	if( isset( $row ) && $row['id'] != null ) {
?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<div class="clearfix">
				<img alt="<?=$lang->o('classInfoToTop')?>" title="<?=$lang->o('classInfoToTop')?>" class="floatright margin15Right back tooltip" data-to="cert" width=20 src="<?= CORE_URL . 'assets/img/back.png' ?>"/>
			</div>
			<p class="info"><?= $lang->o('classGroupHeader')?></p>


			<p class="title"><?= $row['title'] ?></p>

			<div class="alignjustify">
				<p class="margin10Bottom">Here are the classes that you can take in the humanities class group.</p>
				<div>
					<?= Core::replaceClassLink( $row['text'] ) ?>
				</div>

			</div>
		</div>
	</div>
</div>
<?php }  else {
	echo "<div class='aligncenter'><p>Failed to load page.<br>Try reloading the page.</p></div>";
	} ?>
