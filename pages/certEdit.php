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
		Core::includeStyles();
	?>
</head>
<body>
<div id="wrapper" class="admin">
	<div id="headerWrapper">
		<div id="header">
			<div class="clearfix">
				<div class="floatleft title"><a href="<?= CORE_URL ?>home">Computer Science <br> Computer Information Systems</a></div>
				<div class="floatleft subtitle">Join the Bitcoin Revolution.</div>
			</div>
			<div class="nav clearfix">
				<ul>
					<li><a href="<?= CORE_URL ?>admin">Admin Home</a></li>
					<li><a href="<?= CORE_URL ?>editClass">Classes</a></li>
					<li><a href="<?= CORE_URL ?>editCerts">Certificates</a></li>
				</ul>
				<div class="floatright">
					<a href="logout">Logout</a>
				</div>
			</div>
		</div>
	</div>
	<div id="main">
		<div class="admin">
			<div class="certs aligncenter margin15Bottom">
				<p>Editing <?=$data['title']?></p>
				<form action="certs/save/<?=$data['id']?>" method="post">
					<input type="hidden" name="id" value="<?=$data['id']?>" />
					<ul>
						<li>
							<label for="title">Title</label>
							<input type="text" name="title" value="<?=isset( $data['title'] ) ? $data['title'] : ''?>"/>
							<span>Enter the certificate description</span>
						</li>
						<li>
							<label for="code">Code</label>
							<input type="text" name="code" value="<?=isset( $data['code'] ) ? $data['code'] : ''?>"/>
							<span>Enter the certificate code number</span>
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
							<label for="description">Description</label>
							<textarea class="froala-editor" name="description" id="description" ><?=isset( $data['description'] ) ? $data['description'] : ''?></textarea>
							<span>Enter the certificate description</span>
						</li>
						<li>
							<label for="elo">Expected Learning Outcomes</label>
							<textarea class="froala-editor" name="elo" id="elo" ><?=isset( $data['elo'] ) ? $data['elo'] : ''?></textarea>
							<span>Enter the certificate expected learning outcome</span>
						</li>
						<li>
							<label for="schedule">Schedule</label>
							<textarea class="froala-editor" name="schedule" id="schedule" ><?=isset( $data['schedule'] ) ? $data['schedule'] : ''?></textarea>
							<span>Enter the certificate schedule</span>
						</li>
						<li>
							<input type="submit" value="Save" />
						</li>

					</ul>
				</form>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( "assets/tinymce/tinymce.min.js" );
	Core::queueScript( 'assets/js/ui.js');
	Core::includeScripts();
?>

</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
