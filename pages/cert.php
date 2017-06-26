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
?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<p class="info">Certification Information <img class="floatright margin15Right back" data-to="top" width=20 src="<?= CORE_URL . 'assets/img/back.png' ?>"/></p>

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

				<p class="bold margin15Top">Program Learning Outcomes</p>

				<p>Upon successful completion of this program, students should be able to:</p>
				<?= Core::replaceClassLink( $data['elo'] ) ?>
			</div>
		</div>
	</div>
	<?= Core::replaceClassLink($data['schedule']); ?>
</div>
