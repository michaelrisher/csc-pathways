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
	$params = $data['params'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::queueStyle( 'assets/css/select2.css' );
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="admin">
			<div class="classes aligncenter margin15Bottom">
				<p>Classes</p>
				<div class="searchBar padding5">
					<input type="search" class="search" placeholder="Search Classes..." value="<?= isset( $_GET['q'] ) ? $_GET['q'] : '';?>" />
				</div>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'classes' );
						if( is_numeric( $params ) ){
							$data = $GLOBALS['main']->classes->listing( $params );
						} else {
							$data = $GLOBALS['main']->classes->listing();
						}
//						Core::debug( $data );
						if( isset( $data['listing'] ) ) {
							foreach ( $data['listing'] as $class ) {
								echo "<li data-id='${class['id']}'>${class['title']}";
								if( $class['delete'] )
									echo "<img class='delete tooltip' title='Delete class' src='" . CORE_URL . "assets/img/delete.png'/>";
								if( $class['edit'] ) {
									echo "<img class='languageEdit tooltip' title='Edit in Different Language' src='" . CORE_URL . "assets/img/region.png'/>";
									echo "<img class='edit tooltip' title='Edit class' src='" . CORE_URL . "assets/img/edit.svg'/>";
								}
								echo "</li>";
							}
						} else{
							echo "<li>There are no classes or you do not have the rights to see classes</li>";
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
								echo "<a href='" . CORE_URL . "editClass/1" . $search . "'>|&lt;</a>";
							}
							//left side of current math
							if ( $currentPage <= $amount ) {
								$left = ( ( $currentPage - $amount ) + $amount ) - 1;
							} else {
								$left = $amount;
							}
							for ( $i = $left; $i >= 1; $i-- ) {
								echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage - $i ) . $search . "'>" . ( $currentPage - $i ) . "</a>";
							}
							echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage ) . $search . "' class='current'>" . ( $currentPage ) . "</a>";
							//right side of current math
							for ( $i = 1; $i <= $amount; $i++ ) {
								if ( ( $currentPage + $i ) > $pages ) {
									break;
								}
								echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage + $i ) . $search . "'>" . ( $currentPage + $i ) . "</a>";
							}
							if ( $currentPage < $pages ) {
								echo "<a href='" . CORE_URL . "editClass/" . $pages . $search . "'>&gt;|</a>";
							}
						}
					?>
					</div>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create Class" name="createClass"/>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::queueScript( 'assets/js/select2.js' );
	Core::queueScript( 'assets/js/classes.js' );
	Core::includeScripts();
?>

</body>
</html>
