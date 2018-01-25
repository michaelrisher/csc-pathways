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
//		Core::queueStyle( 'assets/css/select2.css' );
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="admin">
			<div class="disciplines aligncenter margin15Bottom">
				<p>Disciplines</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'discipline' );
						if( isset( $params[1] ) && is_numeric( $params[1] ) ){
							$data = $GLOBALS['main']->discipline->listing( $params[1] );
						} else {
							$data = $GLOBALS['main']->discipline->listing();
						}

//						Core::debug( $data );
						if( isset( $data ) ) {
							foreach ( $data['listing'] as $item ) {
								echo "<li data-id='${item['id']}'>${item['name']} ${item['description']}";
								echo "<img class='delete tooltip' title='Delete discipline' src='" . CORE_URL . "assets/img/delete.png'/>";
								echo "<img class='edit tooltip' title='Edit discipline' src='" . CORE_URL . "assets/img/edit.svg'/>";
								echo "</li>";
							}
						} else{
							echo "<li>There are no disciplines or you do not have the rights to see disciplines</li>";
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
									echo "<a href='" . CORE_URL . "editDisciplines/1" . $search . "'>|&lt;</a>";
								}
								//left side of current math
								if ( $currentPage <= $amount ) {
									$left = ( ( $currentPage - $amount ) + $amount ) - 1;
								} else {
									$left = $amount;
								}
								for ( $i = $left; $i >= 1; $i-- ) {
									echo "<a href='" . CORE_URL . 'editDisciplines/' . ( $currentPage - $i ) . $search . "'>" . ( $currentPage - $i ) . "</a>";
								}
								echo "<a href='" . CORE_URL . 'editDisciplines/' . ( $currentPage ) . $search . "' class='current'>" . ( $currentPage ) . "</a>";
								//right side of current math
								for ( $i = 1; $i <= $amount; $i++ ) {
									if ( ( $currentPage + $i ) > $pages ) {
										break;
									}
									echo "<a href='" . CORE_URL . 'editDisciplines/' . ( $currentPage + $i ) . $search . "'>" . ( $currentPage + $i ) . "</a>";
								}
								if ( $currentPage < $pages ) {
									echo "<a href='" . CORE_URL . "editDisciplines/" . $pages . $search . "'>&gt;|</a>";
								}
							}
						?>
					</div>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create Discipline" name="createDiscipline"/>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::queueScript( 'assets/js/discipline.js' );
	Core::includeScripts();
?>

</body>
</html>
