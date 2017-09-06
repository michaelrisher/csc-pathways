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
					<input type="search" class="search" placeholder="Search Classes" value="<?= isset( $_GET['q'] ) ? $_GET['q'] : '';?>" />
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
						foreach ( $data['listing'] as $class ) {
							echo "<li data-id='${class['id']}'>${class['title']}";
							echo "<img class='delete tooltip' title='Delete class' src='". CORE_URL ."assets/img/delete.png'/>";
							echo "<img class='languageEdit tooltip' title='Edit in Different Language' src='". CORE_URL ."assets/img/region.png'/>";
							echo "<img class='edit tooltip' title='Edit class' src='". CORE_URL ."assets/img/edit.svg'/>";
							echo "</li>";
						}
					?>
					</ul>
				</div>
				<div class="pages aligncenter" >
					<p>Pages</p>
					<div>
					<?php
						$pages = ceil( $data['count'] / $data['limit'] );
						$currentPage = $data['currentPage'];
						$amount = 3;
//						echo '#' . $pages;
						if(  $currentPage > 1 ){
							echo "<a href='" . CORE_URL . "editClass/1'/>|&lt;</a>";
//							echo "<a href='" . CORE_URL . "editClass/" . ( $currentPage - 1 ). "'/>&lt;</a>";
						}
						//left side of current math
						if( $currentPage <= $amount ){
							$left = ( ( $currentPage - $amount ) + $amount ) - 1;
						} else{
							$left = $amount;
						}
						for( $i = $left; $i >= 1; $i-- ){
							echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage - $i ) . "'>"  . ( $currentPage - $i ) . "</a>";
						}
						echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage ) . "' class='current'>"  . ( $currentPage ) . "</a>";
						//right side of current math
						for( $i = 1; $i <= $amount; $i++ ){
							if( ( $currentPage + $i ) > $pages ){ break; }
							echo "<a href='" . CORE_URL . 'editClass/' . ( $currentPage + $i ) . "'>"  . ( $currentPage + $i ) . "</a>";
						}
						if(  $currentPage < $pages ){
//							echo "<a href='" . CORE_URL . "editClass/" . ( $currentPage + 1 ). "'/>&gt;</a>";
							echo "<a href='" . CORE_URL . "editClass/" . $pages. "'>&gt;|</a>";
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
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
