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
		Core::queueStyle( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css' );
		Core::queueStyle( 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css' );
//		Core::queueStyle( 'https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.1/css/froala_editor.min.css' );
//		Core::queueStyle( 'https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.1/css/froala_style.min.css' );
//		Core::queueStyle( 'assets/css/froala_editor.css');
//		Core::queueStyle( 'assets/css/froala_style.css');
		Core::queueStyle( 'assets/css/froala/table.min.css');
		Core::queueStyle( 'assets/css/froala/code_view.min.css');
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
				<form>
					<ul>
						<li>
							<label for="title">Title</label>
							<input type="text" name="title" value="<?=isset( $data['title'] ) ? $data['title'] : ''?>"/>
							<span>Enter the certificate description</span>
						</li>
						<li>
							<label for="title">Code</label>
							<input type="text" name="title" value="<?=isset( $data['code'] ) ? $data['code'] : ''?>"/>
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
							<span>Enter the certificate code number</span>
						</li>
						<li>
							<label for="description">Description</label>
							<textarea class="froala-editor" name="description" ><?=isset( $data['description'] ) ? $data['description'] : ''?></textarea>
							<span>Enter the certificate code number</span>
						</li>
						<li>
							<label for="elo">Expected Learning Outcomes</label>
							<textarea class="froala-editor" name="elo" ><?=isset( $data['elo'] ) ? $data['elo'] : ''?></textarea>
							<span>Enter the certificate code number</span>
						</li>
						<li>
							<label for="schedule">Schedule</label>
							<textarea class="froala-editor" name="schedule" ><?=isset( $data['schedule'] ) ? $data['schedule'] : ''?></textarea>
							<span>Enter the certificate code number</span>
						</li>
						<li>
							<input type="button" value="Save" />
						</li>

					</ul>
				</form>
				<div class="listing alignleft">
					<?php
						Core::debug( $data );
					?>
				</div>
				<div class="margin25Top">
					<input type="button" value="Create Certificate" name="createCert"/>
				</div>
			</div>
		</div>
	</div>
	<img id="loadOff" src="<?=CORE_URL?>assets/img/ajax-loader.gif" />
</div>
<?php
	Core::queueScript( 'assets/js/core.js');
	Core::queueScript( 'assets/js/froala_editor.js');
	Core::queueScript( 'assets/js/froala/table.min.js');
	Core::queueScript( 'assets/js/froala/code_view.min.js');
	Core::queueScript( "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js" );
	Core::queueScript( "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js" );
	Core::queueScript( 'assets/js/ui.js');
	Core::includeScripts();
?>


<!-- Include JS file. -->
<script>
//
//	$.FroalaEditor.DefineIcon('insertClass', {NAME: 'plus'});
//	$.FroalaEditor.RegisterCommand('insertClass', {
//		title: 'Insert Class',
//		focus: true,
//		undo: true,
//		refreshAfterCallback: true,
//		callback: function () {
//			this.html.insert('My New Class');
//		}
//	});
//
//	$('#froala-editor').froalaEditor({
//		toolbarButtons: [
//			'fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'inlineStyle', 'paragraphStyle', '|',
//			'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote','|', 'undo', 'redo', '|', 'help', 'html', '-', 'insertLink', 'insertImage', 'insertFile', 'insertTable',
//			'|', 'specialCharacters', 'insertHR', 'selectAll', 'clearFormatting', '|', 'help', 'html', '|', 'insertClass'
//		],
//		charCounterCount: false,
//	})
</script>
</body>
</html>

<?php
	//	Core::debug( $_SESSION );
	//	Core::debug( $_SERVER );
?>
