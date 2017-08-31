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
					<input type="search" class="search" placeholder="Search Classes" />
				</div>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'classes' );
						if( is_numeric( $params ) ){
							$data = $GLOBALS['main']->classes->listing( $data['params'] );
						} else {
							$data = $GLOBALS['main']->classes->listing();
						}
						foreach ( $data as $class ) {
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
					<?php
						$pages = $GLOBALS['main']->classes->getPages();
						$currentPage = is_numeric( $params ) ? $params : 1;
						if(  $currentPage > 1 ) echo "<a href='" . CORE_URL . "editClass/" . ( $currentPage - 1 ). "'/>&lt;</a>";
						for( $i = 1; $i <= $pages; $i++ ){
							echo "<a href='" . CORE_URL . 'editClass/' . $i . "' class='";
							echo is_numeric( $params ) ? ( $params == $i ? 'current' : '' ) : ( $i == 1 ? 'current' : '' );
							echo "'>" . ( $i ) . "</a>";
						}
						if(  $currentPage < $pages ) echo "<a href='" . CORE_URL . "editClass/" . ( $currentPage + 1 ). "'/>&gt;</a>";
					?>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create Class" name="createClass"/>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
