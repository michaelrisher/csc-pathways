<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:13
	 */
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		//Core::errorPage( 404 );
		Core::phpRedirect( 'login' );
	}
	$params = $data['params'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include_once CORE_PATH . 'assets/inc/header.php';
		Core::queueStyle( 'assets/css/select2.css' );
		Core::queueStyle( "assets/css/font-awesome.css" );
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
					<div class="search clearfix">
						<input type="search" class="search" placeholder="Search Classes..." value="<?= isset( $_GET['q'] ) ? $_GET['q'] : '';?>" />
						<span class="floatright"><i class="fa fa-chevron-down down" aria-hidden="true"></i></span>
					</div>
					<div class="none searchFilter">
						<p class="margin15Bottom bold">Search Filter</p>
						<label><p>Sort By</p>
						<select name="sortBy" autoload nosearch>
							<?php
								$temp = array( 'Ascending', 'Descending' );
								$sorts = array( 'ASC', 'DESC' );
								if( isset( $_GET['sort'] ) ){
									$selectedSort = Core::sanitize( $_GET['sort'] );
								}
								$i = 0;
								foreach ( $sorts as $sort ) {
									$selected = ( $selectedSort == $sort ? 'selected' : '' );
									echo "<option value='$sort' $selected>${temp[$i++]}</option>";
								}

							?>
						</select>
						</label>

						<label for="filterDiscipline"><p>Filter Disciplines</p>
						<select id="filterDiscipline" multiple autoload name="disciplines">
							<?php
								$GLOBALS['main']->loadModule( 'discipline' );
								$disciplines = $GLOBALS['main']->discipline->listing( -1, true );
								if( isset( $_GET['discs'] ) ) {
									$selectedDiscs = explode( ',', Core::sanitize( $_GET['discs'] ) );
								} else $selectedDiscs = array();
								foreach ( $disciplines['listing'] as $d ) {
									if( Core::inArray( $d['id'], $selectedDiscs ) ){
										echo "<option selected value='${d['id']}'>${d['name']} - ${d['description']}</option>";
									} else
										echo "<option value='${d['id']}'>${d['name']} - ${d['description']}</option>";
								}
							?>
						</select>
						</label>
						<label for="limit"><p>Amount per page</p>
							<select autoload name="limit">
								<?php
									if( isset( $_GET['limit'] ) ){
										$selected = intval( $_GET['limit'] );
									} else {
										$selected = 25;
									}
									for( $i = 25; $i <= 100; $i+=25 ){
										echo "<option value='$i' " . ( $selected == $i ? 'selected' : '' ) . ">$i</option>";
									}
								?>
							</select>
						</label>
					</div>
				</div>
				<div class="listing alignleft">
					<ul>
						<?php
							$GLOBALS['main']->loadModule( 'classes' );
							if( isset( $params[1] ) && is_numeric( $params[1] ) ){
								$data = $GLOBALS['main']->classes->listing( array( 'page' => $params[1] ) );
							} else {
								$data = $GLOBALS['main']->classes->listing();
							}
							if( isset( $data['listing'] ) && !empty( $data['listing'] ) ) {
								foreach ( $data['listing'] as $class ) {
									echo "<li data-id='${class['id']}'>${class['title']}";
									if( $class['delete'] )
										echo "<img class='delete tooltip' title='Delete class' src='" . CORE_URL . "assets/img/delete.png'/>";
									if( $class['edit'] ) {
										echo "<img class='languageEdit tooltip' title='Edit in Different Language' src='" . CORE_URL . "assets/img/region.png'/>";
										echo "<img class='edit tooltip' title='Edit class' src='" . CORE_URL . "assets/img/edit.svg'/>";
									} else {
										echo "<img class='view tooltip' title='View class' src='" . CORE_URL . "assets/img/view.png'/>";
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
					<?php Core::pages( $data, 'editClass' ); ?>

				</div>
				<div class="margin25Top">
					<?php
						$GLOBALS['main']->loadModule( 'roles' );
						if( $GLOBALS['main']->roles->haveAccess( 'ClassEdit', Core::getSessionId(), -1 ) ){
					?>
					<input type="button" value="Create Class" name="createClass"/>
					<?php } ?>
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
