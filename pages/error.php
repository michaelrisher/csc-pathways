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
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
</body>
</html>
