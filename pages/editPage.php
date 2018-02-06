<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 11/15/2017
	 * Time: 16:20
	 */
	$GLOBALS['main']->loadModule( 'users' );
	if( !$GLOBALS['main']->users->isLoggedIn() ){
		Core::errorPage( 404 );
	}
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
			<div class="pages aligncenter margin15Bottom">
				<p>
					<?php
						if( isset( $_GET['create'] ) ){
							echo "Create";
						} else{
							echo "Editing ${data['name']}";
						}
					?>
				</p>
				<form action="<?=CORE_URL?>pages/save/<?=$data['id']?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?=$data['id']?>" />
					<p class="alignleft margin15Bottom">* Required fields</p>
					<ul>
						<li>
							<label for="name">Name*</label>
							<input type="text" name="name" value="<?=isset( $data['name'] ) ? $data['name'] : ''?>"/>
							<span>Enter the page name</span>
						</li>
						<li>
							<label for="path">Path*</label>
							<input type="text" name="path" value="<?=isset( $data['path'] ) ? $data['path'] : ''?>"/>
							<span>Enter the path for this page, this value must be distinct</span>
						</li>
						<li class="alignleft">
							<label for="discipline">Discipline*</label>
							<select name="discipline">
								<option disabled selected> -- Select A Discipline -- </option>
								<?php
									foreach( $data['disciplines']['listing'] as $discipline ){
										echo "<option " . ( ( $discipline['id'] == $data['discipline'] ) ? ( 'selected' ) : ( '' ) ) . " value='${discipline['id']}'>${discipline['name']} ${discipline['description']}</option>";
									}
								?>
							</select>
							<span>Enter the discipline for the page</span>
						</li>
						<li>
							<label for="headerTemplate">Header Template</label>
							<div class="tripleInput">
								<input type="text" name="headerTemplate" value="<?=isset( $data['headerTemplate'] ) ? $data['headerTemplate'] : ''?>"/>
								<button type="button" class="upload" accept=".html,.php,.htm,.xhtml,.dhtml"><i class="fa fa-upload"></i> Upload</button>
								<input type="button" value="Open Text Editor" disabled class="low" />
							</div>
							<span>Enter the file name for the header template</span>
						</li>
						<li>
							<label for="bodyTemplate">Body Template*</label>
							<div class="tripleInput">
								<input type="text" name="bodyTemplate" value="<?=isset( $data['bodyTemplate'] ) ? $data['bodyTemplate'] : ''?>"/>
								<button type="button" class="upload" accept=".html,.php,.htm,.xhtml,.dhtml"><i class="fa fa-upload"></i> Upload</button>
								<input type="button" value="Open Text Editor" disabled class="low" />
							</div>
							<span>Enter the file name for the body template</span>
						</li>
						<li>
							<label for="stylesheet">Custom Style Sheet</label>
							<div class="tripleInput">
								<input type="text" name="stylesheet" value="<?=isset( $data['stylesheet'] ) ? $data['stylesheet'] : ''?>"/>
								<button type="button" class="upload" accept=".css,.min"><i class="fa fa-upload"></i> Upload</button>
								<input type="button" value="Open Text Editor" disabled class="low" />
							</div>
							<span>Enter the file name for the custom stylesheet</span>
						</li>
						<li>
							<label for="javascript">Custom Javascript</label>
							<div class="tripleInput">
								<input type="text" name="javascript" value="<?=isset( $data['javascript'] ) ? $data['javascript'] : ''?>"/>
								<button type="button" class="upload" accept=".js,.map,.min"><i class="fa fa-upload"></i> Upload</button>
								<input type="button" value="Open Text Editor" disabled class="low" />
							</div>
							<span>Enter the file name for the custom javascript</span>
						</li>
						<li>
							<input type="submit" value="Save" />
							<a href="<?=CORE_URL?>/editPages">
								<input type="button" class="margin15Left low cancel" value="Cancel" />
							</a>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( 'assets/js/ui.js');
	Core::queueScript( 'assets/js/select2.js' );
	Core::queueScript( 'assets/js/pages.js');
	Core::includeScripts();
	if ( isset( $data['submission'] ) ) {
		?>
		<script>
			var modal = createModal( {
				title: 'Saved Successfully',
				buttons: [
					{
						value: 'Finished',
						focus : true,
						onclick: function () {
							location.href = CORE_URL + 'editPages'
						}
					},
					{ value: 'Edit Again', class: 'low' }
				]
			} );
			<?php
			if( isset( $data['submission']['msg'] ) ){
				echo "setModalContent( modal, '" . $data['submission']['msg'] . "');";
			} else if( isset( $data['submission']['error'] ) ) {
				echo "setModalContent( modal, '" . $data['submission']['error'] . "');";
			}
 			?>
			displayModal( modal );
		</script>
	<?php } ?>
<script>$('[name=discipline]').select2();</script>
</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
