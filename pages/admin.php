<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		Core::errorPage( 404 );
	}
	$params = $data['params'];
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
			<div class="audit aligncenter">
				<p>Audit Log</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'audit' );

						if( is_numeric( $params ) ){
							$data = $GLOBALS['main']->audit->listing( $data['params'] );
						} else {
							$data = $GLOBALS['main']->audit->listing();
						}
						foreach ( $data as $event ) {
							echo "<li>User ${event['username']}: ${event['event']}  <span class='floatright'>";
							echo date( 'm/d/Y h:i:s A', strtotime( $event['date'] ) );
							echo "</span>";
							echo "</li>";
						}
					?>
					</ul>
					<div class="pages aligncenter" >
						<p>Pages</p>
					<?php
						$pages = $GLOBALS['main']->audit->getPages();
						$currentPage = is_numeric( $params ) ? $params : 1;
						if(  $currentPage > 1 ) echo "<a href='" . CORE_URL . "admin/" . ( $currentPage - 1 ). "'/>&lt;</a>";
						for( $i = 1; $i <= $pages; $i++ ){
							echo "<a href='" . CORE_URL . 'admin/' . $i . "' class='";
							echo is_numeric( $params ) ? ( $params == $i ? 'current' : '' ) : ( $i == 1 ? 'current' : '' );
							echo "'>" . ( $i ) . "</a>";
						}
						if(  $currentPage < $pages ) echo "<a href='" . CORE_URL . "admin/" . ( $currentPage + 1 ). "'/>&gt;</a>";
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>

</body>
</html>

<?php
//	Core::debug( $_SESSION );
//	Core::debug( $_SERVER );
?>
