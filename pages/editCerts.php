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
						if( isset( $data['listing'] ) && !empty( $data['listing'] ) ) {
							foreach ( $data['listing'] as $cert ) {
								echo "<li data-id='${cert['id']}'>";
								//echo "<img class='key tooltip' title='Id: ${cert['id']}' src='". CORE_URL ."assets/img/key.png'/>";
								echo "${cert['code']} - ${cert['description']}";
								if ( $cert['delete'] )
									echo "<img class='delete tooltip' title='Delete certificate' src='" . CORE_URL . "assets/img/delete.png'/>";
								if ( $cert['edit'] ) {
									echo "<img class='languageEdit tooltip' title='Edit in Different Language' src='" . CORE_URL . "assets/img/region.png'/>";
									echo "<a href='certs/edit/${cert['id']}'><img class='edit tooltip' title='Edit certificate' src='" . CORE_URL . "assets/img/edit.svg'/></a>";
								} else {
									echo "<a href='certs/view/${cert['id']}'><img class='view tooltip' title='View class' src='" . CORE_URL . "assets/img/view.png'/></a>";
								}
								echo "</li>";
							}
						} else {
							echo "<li>There are no certificates or you do not have the rights to see classes</li>";
						}
					?>
					</ul>
				</div>
				<div class="margin25Top">
					<?php
						$GLOBALS['main']->loadModule( 'roles' );
						if( $GLOBALS['main']->roles->haveAccess( 'CertEdit', Core::getSessionId(), -1 ) ){
					?>
					<a href="<?=CORE_URL?>certs/create?create=">
						<input type="button" value="Create Certificate" name="createCert"/>
					</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::queueScript( 'assets/js/certs.js' );
	Core::includeScripts();
?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
