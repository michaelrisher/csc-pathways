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
						foreach ( $data['listing'] as $event ) {
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
						$pages = ceil( $data['count'] / $data['limit'] );
						$currentPage = $data['currentPage'];
						$amount = 3;
						if(  $currentPage > 1 ){
							echo "<a href='" . CORE_URL . "admin/1'/>|&lt;</a>";
						}
						//left side of current math
						if( $currentPage <= $amount ){
							$left = ( ( $currentPage - $amount ) + $amount ) - 1;
						} else{
							$left = $amount;
						}
						for( $i = $left; $i >= 1; $i-- ){
							echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage - $i ) . "'>"  . ( $currentPage - $i ) . "</a>";
						}
						echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage ) . "' class='current'>"  . ( $currentPage ) . "</a>";
						//right side of current math
						for( $i = 1; $i <= $amount; $i++ ){
							if( ( $currentPage + $i ) > $pages ){ break; }
							echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage + $i ) . "'>"  . ( $currentPage + $i ) . "</a>";
						}
						if(  $currentPage < $pages ){
							echo "<a href='" . CORE_URL . "admin/" . $pages. "'>&gt;|</a>";
						}
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
