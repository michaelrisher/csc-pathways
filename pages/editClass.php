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
			<div class="classes aligncenter margin15Bottom">
				<p>Classes</p>
				<input type="search" placeholder="Search Classes" />
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'classes' );
						$data = $GLOBALS['main']->classes->listing();
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
