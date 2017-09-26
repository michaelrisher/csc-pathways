<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/6/2017
	 * Time: 12:54
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
		Core::queueStyle( 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="admin">
			<div class="certs aligncenter margin15Bottom">
				<p>
				<?php
					if( isset( $_GET['create'] ) ){
						echo "Create";
					} else{
						echo "Editing ${data['title']}";
					}
				?>
				</p>
				<form action="certs/save/<?=$data['id']?>" method="post">
					<input type="hidden" name="id" value="<?=$data['id']?>" />
					<input type="hidden" name="language" value="<?=$data['language']?>" />
					<p class="alignleft margin15Bottom">* Required fields</p>
					<ul>
						<li>
							<label for="title">Title*</label>
							<input type="text" name="title" value="<?=isset( $data['title'] ) ? $data['title'] : ''?>"/>
							<span>Enter the certificate description</span>
						</li>
						<li>
							<label for="code">Code*</label>
							<input type="text" name="code" value="<?=isset( $data['code'] ) ? $data['code'] : ''?>"/>
							<span>Enter the certificate code number</span>
						</li>
						<li>
							<label for="units">Units*</label>
							<input type="number" name="units" value="<?=isset( $data['units'] ) ? $data['units'] : 0?>"/>
							<span>Enter the certificate units</span>
						</li>
						<li class="alignleft">
							<label for="">Certificate or associate program</label>
							<div><input type="checkbox" name="hasCe" <?=$data['hasCe'] == 1 ? 'checked' : ''?>/>Has a Certificate</div>
							<div><input type="checkbox" name="hasAs" <?=$data['hasAs'] == 1 ? 'checked' : ''?>/>Has an Associate</div>
							<span>Check if the program has a certificate and/or associate</span>
						</li>
						<li>
							<label for="category">Category</label>
							<select name="category">
								<?php
									foreach ( $data['categories'] as $cat ) {
										echo "<option value='${cat['id']}'";
										if( $cat['id'] == $data['category'] ){
											echo " selected";
										}
										echo ">${cat['category']}</option>";
									}

								?>
							</select>
							<span>Enter the certificate category</span>
						</li>
						<li>
							<label for="description">Description*</label>
							<textarea class="froala-editor" name="description" id="description" ><?=isset( $data['description'] ) ? $data['description'] : ''?></textarea>
							<span>Enter the certificate description</span>
						</li>
						<li>
							<label for="elo">Expected Learning Outcomes*</label>
							<textarea class="froala-editor" name="elo" id="elo" ><?=isset( $data['elo'] ) ? $data['elo'] : ''?></textarea>
							<span>Enter the certificate expected learning outcome</span>
						</li>
						<li>
							<label for="schedule">Schedule*</label>
							<textarea class="froala-editor" name="schedule" id="schedule" ><?=isset( $data['schedule'] ) ? $data['schedule'] : ''?></textarea>
							<span>Enter the certificate schedule</span>
						</li>
						<li>
							<label for="sort">Position in list</label>
							<input type="number" name="sort" value="<?=isset( $data['sort'] ) ? $data['sort'] : 0?>">
							<span>Enter the number to place it in the listing</span>
						</li>
						<li class="alignleft">
							<label for="active">Active Certificate</label>
							<div><input type="checkbox" name="active" <?=$data['active'] == 1 ? 'checked' : ''?>/>Check if this certificate is active</div>
							<span>Enter the number to place it in the listing</span>
						</li>
						<li>
							<input type="submit" value="Save" />
						</li>

					</ul>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( "assets/tinymce/tinymce.min.js" );
	Core::queueScript( 'assets/js/ui.js');
	Core::queueScript( 'assets/js/certs.js');
	Core::queueScript( 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js' );
	Core::includeScripts();
?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
