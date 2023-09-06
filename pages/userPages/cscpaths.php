<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 09:47
	 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::includeStyles();
		$GLOBALS['main']->loadModule('certs');
		$category = array();
		$category[0] = $GLOBALS['main']->certs->listingByCodes( array( 650, 728, 741, 742, 816, 806 ) )['listing'];
		$category[1] = $GLOBALS['main']->certs->listingByCodes( array( 920, 740 ) )['listing'];
		$category[2] = $GLOBALS['main']->certs->listingByCodes( array( 934, 933, 726 ) )['listing'];
		$nonCreds = $GLOBALS['main']->certs->listingByCodes( array( 8010, 8003, 8002 ) )['listing'];
	?>
</head>
<body>
<div id="wrapper">
	<?php include CORE_PATH . 'assets/inc/logo.php' ?>
	<div id="main">
		<div id="tree" class="datablock">
			<div class="aligncenter treemap">
				<img src="<?= CORE_URL . 'assets/img/tree.png'?>" alt="Picture of a path to follow, top class cis 1a, bottom left class csc or cis 5, bottom right class cis 17a/b"/>
			</div>
			<div class="certList clearfix">
				<div class="floatleft">
					<div class="treeCertSubject">
						<p><?= $lang->o('certGroup1')?></p>
					</div>
					<table>
						<tr class="treeCertsDesc">
							<td><?= $lang->o( 'certListDesc' )?></td>
							<td><?= $lang->o( 'certListCert' )?></td>
							<td><?= $lang->o( 'certListUnit' )?></td>
						</tr>
						<?php
							foreach( $category[0] as $item ){
								if( $item['active'] ) {
									echo "<tr class='treeCert'>";
									echo "<td>" . Core::fakeLink( 'cert', $item['id'], $item['description'] ) . "</td>";
									echo "<td>";
									if ( $item['hasAs'] ) {
										echo 'AS/';
									}
									if ( $item['hasCe'] ) {
										echo 'CE';
									}
									echo $item['code'] . "</td>";
									echo "<td>${item['units']}</td>";
									echo "</tr>";
								}
							}
						?>
					</table>
				</div>
				<div class="middleTree floatleft">
					<div class="treeCertSubject">
						<p><?= $lang->o('certGroup2')?></p>
					</div>
					<table>
						<tr class="treeCertsDesc">
							<td><?= $lang->o( 'certListDesc' )?></td>
							<td><?= $lang->o( 'certListCert' )?></td>
							<td><?= $lang->o( 'certListUnit' )?></td>
						</tr>
						<?php
							foreach( $category[1] as $item ){
								echo "<tr class='treeCert'>";
								echo "<td>" . Core::fakeLink( 'cert', $item['id'], $item['description']) . "</td>";
								echo "<td>";
								if( $item['hasAs'] ){
									echo 'AS/';
								}
								if( $item['hasCe'] ){
									echo 'CE';
								}
								echo $item['code'] . "</td>";
								echo "<td>${item['units']}</td>";
								echo "</tr>";
							}
						?>
						<tr>
							<td class="aligncenter" colspan="3"><p class="padding15Top"><?= $lang->o('csusbCyber')?><br><a href="https://www.csusb.edu/cyber-security"><?= $lang->o('csusbCyberLink0'). '<br>' . $lang->o('csusbCyberLink1')?></a></p></td>
						</tr>
					</table>
				</div>
				<div class="floatright">
					<div class="treeCertSubject">
						<p><?= $lang->o('certGroup3')?></p>
					</div>
					<table>
						<tr class="treeCertsDesc">
							<td><?= $lang->o( 'certListDesc' )?></td>
							<td><?= $lang->o( 'certListCert' )?></td>
							<td><?= $lang->o( 'certListUnit' )?></td>
						</tr>
						<?php
							foreach( $category[2] as $item ){
								echo "<tr class='treeCert'>";
								echo "<td>" . Core::fakeLink( 'cert', $item['id'], $item['description']) . "</td>";
								echo "<td>";
								if( $item['hasAs'] ){
									echo 'AS/';
								}
								if( $item['hasCe'] ){
									echo 'CE';
								}
								echo $item['code'] . "</td>";
								echo "<td>${item['units']}</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
			<div class="clearfix">
				<div class="floatleft">
					<div class="treeCertSubject">
						<p><?= $lang->o('certNonCredit')?></p>
					</div>
					<table width="328px">
						<tr class="treeCertsDesc">
							<td><?= $lang->o( 'certListDesc' )?></td>
							<td><?= $lang->o( 'certListCert' )?></td>
							<td><?= $lang->o( 'certListHour' )?></td>
						</tr>
						<?php

							foreach( $nonCreds as $item ){
								echo "<tr class='treeCert'>";
								echo "<td>" . Core::fakeLink( 'cert', $item['id'], $item['description']) . "</td>";
								//echo "<td>In Dev</td>";
								//*
								if( $item['hasAs'] ){
									echo 'AS/';
								}
								if( $item['hasCe'] ){
									echo 'CE';
								}
								echo $item['code'] . "</td>";
								echo "<td>${item['units']}</td>";
								//*/
								echo "<td>54</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
		<div class="datablock<?= ( isset( $_GET ) && isset( $_GET['cert'] )) ? '' : ' none';?>" id="cert">
			<?php
				if( isset( $_GET ) && isset( $_GET['cert'] ) ){
					$GLOBALS['main']->loadModule( 'certs' );
					$_GET = Core::sanitize( $_GET );
					$GLOBALS['main']->certs->show( $_GET['cert'] );
				}
			?>
		</div>
		<div class="datablock<?= ( isset( $_GET ) && isset( $_GET['classGroup'] )) ? '' : ' none';?>" id="classGroup">
			<?php
				if ( isset( $_GET ) && isset( $_GET['classGroup'] ) ) {
					$GLOBALS['main']->loadModule( 'classes' );
					$_GET = Core::sanitize( $_GET );
					$GLOBALS['main']->classes->showClassGroup( $_GET['classGroup'] );
				}
			?>
		</div>
		<div class="datablock<?= ( isset( $_GET ) && isset( $_GET['class'] )) ? '' : ' none';?>" id="class">
			<?php
				if( isset( $_GET ) && isset( $_GET['class'] ) ){
					$GLOBALS['main']->loadModule( 'classes' );
					$_GET = Core::sanitize( $_GET );
					$GLOBALS['main']->classes->show( $_GET['class'] );
				}
			?>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::includeScripts();
?>
</body>
</html>

<!--
<tr class="treeCert">
	<td></td>
	<td></td>
	<td></td>
</tr>

-->
