<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:13
	 */
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		Core::errorPage( 404 );
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="admin">
			<div class="certs aligncenter margin15Bottom">
				<p>Certificates</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'certs' );
						$data = $GLOBALS['main']->certs->listing();
						foreach ( $data as $cert ) {
							echo "<li data-id='${cert['id']}'>${cert['code']} - ${cert['description']}";
							echo "<img class='delete tooltip' title='Delete certificate' src='". CORE_URL ."assets/img/delete.png'/>";
							echo "<img class='languageEdit tooltip' title='Edit in Different Language' src='". CORE_URL ."assets/img/region.png'/>";
							echo "<a href='certs/edit/${cert['id']}'><img class='edit tooltip' title='Edit certificate' src='". CORE_URL ."assets/img/edit.svg'/></a>";
							echo "</li>";
						}
					?>
					</ul>
				</div>
				<div class="margin25Top">
					<a href="<?=CORE_URL?>certs/create?create=">
						<input type="button" value="Create Certificate" name="createCert"/>
					</a>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
