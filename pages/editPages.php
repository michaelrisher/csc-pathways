<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:13
	 */
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		Core::phpRedirect( 'login' );
//		Core::errorPage( 404 );
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
			<div class="pages aligncenter margin15Bottom">
				<p>Pages</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'pages' );
						$data = $GLOBALS['main']->pages->listing();

						if( isset( $data ) ) {
							foreach ( $data['listing'] as $item ) {
								echo "<li data-id='${item['id']}'>${item['name']}";
								echo "<img class='delete tooltip' title='Delete Page' src='" . CORE_URL . "assets/img/delete.png'/>";
								echo "<a href='pages/edit/${item['id']}'><img class='edit tooltip' title='Edit Page' src='" . CORE_URL . "assets/img/edit.svg'/></a>";
								echo "</li>";
							}
						} else{
							echo "<li>There are no pages or you do not have the rights to see pages</li>";
						}
					?>
					</ul>
				</div>
				<div class="pages aligncenter" >
					<p>Pages</p>
					<div>
						<?php
							if ( isset( $data['count'] ) && $data['limit'] ) {
								$pages = ceil( $data['count'] / $data['limit'] );
								$currentPage = $data['currentPage'];
								$amount = 3;
//							echo '#' . $pages;
								$search = isset( $_GET['q'] ) ? ( '?q=' . $_GET['q'] ) : '';
								if ( $currentPage > 1 ) {
									echo "<a href='" . CORE_URL . "editPages/1" . $search . "'>|&lt;</a>";
								}
								//left side of current math
								if ( $currentPage <= $amount ) {
									$left = ( ( $currentPage - $amount ) + $amount ) - 1;
								} else {
									$left = $amount;
								}
								for ( $i = $left; $i >= 1; $i-- ) {
									echo "<a href='" . CORE_URL . 'editPages/' . ( $currentPage - $i ) . $search . "'>" . ( $currentPage - $i ) . "</a>";
								}
								echo "<a href='" . CORE_URL . 'editPages/' . ( $currentPage ) . $search . "' class='current'>" . ( $currentPage ) . "</a>";
								//right side of current math
								for ( $i = 1; $i <= $amount; $i++ ) {
									if ( ( $currentPage + $i ) > $pages ) {
										break;
									}
									echo "<a href='" . CORE_URL . 'editPages/' . ( $currentPage + $i ) . $search . "'>" . ( $currentPage + $i ) . "</a>";
								}
								if ( $currentPage < $pages ) {
									echo "<a href='" . CORE_URL . "editPages/" . $pages . $search . "'>&gt;|</a>";
								}
							}
						?>
					</div>
				</div>
				<div class="margin25Top">
					<a href="<?=CORE_URL?>pages/create?create=">
						<input type="button" value="Create Page" name="createPage"/>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::queueScript( 'assets/js/pages.js' );
	Core::includeScripts();
?>

</body>
</html>
