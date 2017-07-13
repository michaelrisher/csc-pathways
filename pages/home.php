<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 09:47
	 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::includeStyles();
		$GLOBALS['main']->loadModule('certs');
		$data = $GLOBALS['main']->certs->listing('category, sort');
		$category = array();
		foreach( $data as $item ){
			if( !@is_array( $category[$item['category']] ) ){ //@ means suppress warning
				$category[$item['category']] = array();
			}
			array_push( $category[$item['category']], $item );
		}
	?>
</head>
<body>
	<div id="wrapper">
		<?php include CORE_PATH . 'assets/inc/logo.php' ?>
		<div id="main">
			<div id="tree">
				<div class="aligncenter treemap">
					<img src="<?= CORE_URL . 'assets/img/tree.png'?>" alt="Picture of a path to follow, top class cis 1a, bottom left class csc or cis 5, bottom right class cis 17a/b"/>
				</div>
				<div class="certList clearfix">
					<div class="floatleft">
						<div class="treeCertSubject">
							<p>Programming</p>
						</div>
						<table>
							<tr class="treeCertsDesc">
								<td>Description</td>
								<td>Cert #</td>
								<td>Units</td>
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
						</table>
					</div>
					<div class="middleTree floatleft">
						<div class="treeCertSubject">
							<p>Networking & Information Security</p>
						</div>
						<table>
							<tr class="treeCertsDesc">
								<td>Description</td>
								<td>Cert #</td>
								<td>Units</td>
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
							<tr>
								<td class="aligncenter" colspan="3"><p class="padding15Top">Checkout <br> <a href="https://www.csusb.edu/cyber-security">CSUSB CyberSecurity<br>Center</a></p></td>
							</tr>
						</table>
					</div>
					<div class="floatright">
						<div class="treeCertSubject">
							<p>Web Master & Applications</p>
						</div>
						<table>
							<tr class="treeCertsDesc">
								<td>Description</td>
								<td>Cert #</td>
								<td>Units</td>
							</tr>
							<?php
								foreach( $category[3] as $item ){
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
	<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
</body>
</html>

<!--
<tr class="treeCert">
	<td></td>
	<td></td>
	<td></td>
</tr>

-->