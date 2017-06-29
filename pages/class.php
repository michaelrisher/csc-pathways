<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 12:29
	 */

	//TODO update this to new code
	$data = Core::sanitize( $data );
	$id = $data['params'];
	$GLOBALS['main']->loadModule( 'classes' );
	$row = $GLOBALS['main']->classes->get( $id, true );
	if( isset( $row ) && $row['id'] != null ) {

		?>
		<div>
			<div class="">
				<div class="aligncenter clearfix margin15">
					<p class="info">Class Information <img alt="To Certificate Info" title="To Certificate Info" class="floatright margin10Right back tooltip" data-to="cert" width=20
														   src="<?= CORE_URL . 'assets/img/back.png' ?>"/></p>
				</div>
				<div class="margin15 padding10">
					<div class="clearfix">
						<p class="floatleft"><?= $row['title'] ?></p>

						<p class="floatright"><?= $row['units'] ?>&nbsp;units</p>
					</div>
					<div class="padding30Bottom">
						<?php
							if ( $row['transfer'] != null || !empty( $row['transfer'] ) ) {
								echo '<p>' . $row['transfer'] . '</p>';
							}
							if ( $row['prereq'] != null || !empty( $row['prereq'] ) ) {
								echo '<p class="italic">Prerequisite: ' . Core::replaceClassLink( $row['prereq'] ) . '</p>';
							}
							if ( $row['advisory'] != null || !empty( $row['advisory'] ) ) {
								echo '<p class="italic">Advisory: ' . Core::replaceClassLink( $row['advisory'] ) . '</p>';
							}
							if ( $row['coreq'] != null || !empty( $row['coreq'] ) ) {
								echo '<p class="italic">Corequisite: ' . Core::replaceClassLink( $row['coreq'] ) . '</p>';
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
