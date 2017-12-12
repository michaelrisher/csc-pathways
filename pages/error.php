<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
	 */
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
<div id="wrapper">
	<?php include CORE_PATH . 'assets/inc/logo.php'; ?>
	<div id="main">
		<div class="errorPage">
			<div class="aligncenter">
				<p>Error <?= isset( $data['error'] )? $data['error'] : ''?></p>
				<p><?php
					switch( $data['error'] ){
						case 400: echo 'Bad Request'; break;
						case 401: echo 'Not Authorized'; break;
						case 402: echo 'Payment Required'; break;
						case 403: echo 'Forbidden'; break;
						case 404: echo 'Not Found'; break;
						case 405: echo 'Method Not Allowed'; break;
						case 406: echo 'Not Acceptable'; break;
					}
					?></p>
			</div>
		</div>
	</div>
</div>
<?php
	include_once CORE_PATH . 'assets/inc/footer.php';
	Core::includeScripts();
?>
</body>
</html>
