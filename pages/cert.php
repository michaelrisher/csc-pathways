<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/19/2017
	 * Time: 12:38
	 */

	$id = $data['params'];
	$GLOBALS['main']->loadModule( 'certs' );
	$data = $GLOBALS['main']->certs->get( $id, true );
	$lang = new Lang( Lang::getCode() );
?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<div class="clearfix">
				<img alt="<?=$lang->o('certInfoToTop')?>" title="<?=$lang->o('certInfoToTop')?>" class="floatright margin15Right back tooltip" data-to="top" width=20 src="<?= CORE_URL . 'assets/img/back.png' ?>"/>
				<img alt="<?=$lang->o('copyLink')?>" title="<?=$lang->o('copyLink')?>" class="floatright margin15Right link tooltip" data-to="top" width=20 src="<?= CORE_URL . 'assets/img/link.png' ?>"/>
			</div>
			<p class="info"><?= $lang->o('certInfoHeader')?></p>


			<p class="title"><?= $data['title'] ?> (<?php
					if ( $data['hasAs'] ) {
						echo 'AS/';
					}
					if ( $data['hasCe'] ) {
						echo 'CE';
					}
					echo " ${data['code']})";
				?></p>

			<div class="alignjustify">
				<p><?= Core::replaceClassLink( $data['description'] ) ?></p>

				<p class="bold margin15Top"><?= $lang->o('certInfoElo')?></p>

				<p><?=$lang->o('certInfoEloMsg')?>:</p>
				<?= Core::replaceClassLink( $data['elo'] ) ?>
			</div>
		</div>
	</div>
	<?= Core::replaceClassLink($data['schedule']); ?>
</div>
