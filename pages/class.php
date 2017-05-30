<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 12:29
	 */
	$db = new DB();
	$mysqli = $db->createConnection();
	$data = Core::sanitize( $data );
	$id = $data['params'];
	$sql = "SELECT * FROM classes WHERE id = '" . $id . "'";
	if(!$result = $mysqli->query($sql)){
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	$row = null;
	if( $result->num_rows == 1 ) {
		$row = $result->fetch_assoc();
		echo '<pre>';
		print_r( $row );
		echo '</pre>';
		?>
		<div>
			<div class="">
				<div class="aligncenter clearfix margin15">
					<p class="info">Class Information <img class="floatright margin10Right back" data-to="cert" width=20
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
								echo '<p class="italic">Prerequisite: ' . $row['prereq'] . '</p>';
							}
							if ( $row['advisory'] != null || !empty( $row['advisory'] ) ) {
								echo '<p class="italic">Advisory: ' . $row['advisory'] . '</p>';
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
