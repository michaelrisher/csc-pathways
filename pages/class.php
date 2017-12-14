<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 12:29
	 */

	if( is_array( $data['params'] ) ){
		$id = $data['params'][1];
	} else {
		$id = $data['params'];
	}
	$GLOBALS['main']->loadModule( 'classes' );
	$row = $GLOBALS['main']->classes->get( $id, Lang::getCode(), true );
	$lang = new Lang( Lang::getCode() );
	if( isset( $row ) && $row['id'] != null ) {

		?>
		<div>
			<div class="">
				<div class="aligncenter clearfix margin15">
					<div class="clearfix">
						<img alt="To Certificate Info" title="To Certificate Info" class="floatright margin10Right back tooltip" data-to="cert" width=20 src="<?= CORE_URL . 'assets/img/back.png' ?>"/>
						<img alt="<?= $lang->o('copyLink')?>" title="<?= $lang->o('copyLink')?>" class="floatright margin15Right link tooltip" data-to="top" width=20 src="<?= CORE_URL . 'assets/img/link.png' ?>"/>
					</div>
					<p class="info"><?= $lang->o('classInfoHeader')?></p>
				</div>
				<div class="margin15 padding10">
					<div class="clearfix">
						<p class="floatleft"><?= $row['title'] ?></p>

						<p class="floatright"><?= $row['units'] ?>&nbsp;<?= $lang->o('classIntoUnit')?></p>
					</div>
					<div class="padding30Bottom">
						<?php
							if ( $row['transfer'] != null || !empty( $row['transfer'] ) ) {
								echo '<p>' . $row['transfer'] . '</p>';
							}
							if ( $row['prereq'] != null || !empty( $row['prereq'] ) ) {
								echo '<p class="italic">' . $lang->o('classInfoPrereq') . ': ' . Core::replaceClassLink( $row['prereq'] ) . '</p>';
							}
							if ( $row['advisory'] != null || !empty( $row['advisory'] ) ) {
								echo '<p class="italic">' . $lang->o('classInfoAdvisory') . ': ' . Core::replaceClassLink( $row['advisory'] ) . '</p>';
							}
							if ( $row['coreq'] != null || !empty( $row['coreq'] ) ) {
								echo '<p class="italic">' . $lang->o('classInfoCoreq') . ': ' . Core::replaceClassLink( $row['coreq'] ) . '</p>';
							}
						?>
						<br>
						<p class="alignjustify"><?= $row['description'] ?></p>
					</div>
				</div>

			</div>
		</div>
		<?php
	} else {
		echo "<div class='aligncenter'><p>Failed to load page.<br>Try reloading the page.</p></div>";
	}
	?>
