<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/30/2017
	 * Time: 09:47
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
			<div class="audit aligncenter">
				<p>Audit Log</p>
				<div class="listing alignleft">
					<ul>
					<?php
						$GLOBALS['main']->loadModule( 'audit' );

						if( is_numeric( $params ) ){
							$data = $GLOBALS['main']->audit->listing( $data['params'] );
						} else {
							$data = $GLOBALS['main']->audit->listing();
						}
						foreach ( $data['listing'] as $event ) {
							echo "<li>User ${event['username']}: ${event['event']}  <span class='floatright'>";
							echo date( 'm/d/Y h:i:s A', strtotime( $event['date'] ) );
							echo "</span>";
							echo "</li>";
						}
					?>
					</ul>
					<div class="pages aligncenter" >
						<p>Pages</p>
					<?php
						$pages = ceil( $data['count'] / $data['limit'] );
						$currentPage = $data['currentPage'];
						$amount = 3;
						if(  $currentPage > 1 ){
							echo "<a href='" . CORE_URL . "admin/1'/>|&lt;</a>";
						}
						//left side of current math
						if( $currentPage <= $amount ){
							$left = ( ( $currentPage - $amount ) + $amount ) - 1;
						} else{
							$left = $amount;
						}
						for( $i = $left; $i >= 1; $i-- ){
							echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage - $i ) . "'>"  . ( $currentPage - $i ) . "</a>";
						}
						echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage ) . "' class='current'>"  . ( $currentPage ) . "</a>";
						//right side of current math
						for( $i = 1; $i <= $amount; $i++ ){
							if( ( $currentPage + $i ) > $pages ){ break; }
							echo "<a href='" . CORE_URL . 'admin/' . ( $currentPage + $i ) . "'>"  . ( $currentPage + $i ) . "</a>";
						}
						if(  $currentPage < $pages ){
							echo "<a href='" . CORE_URL . "admin/" . $pages. "'>&gt;|</a>";
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once CORE_PATH . 'assets/inc/footer.php'; ?>
<div class="modalshadow">&nbsp;</div>
<div data-id="1" class="modal none" style="display: block;"><div class="modalWrapper"><div class="modalHeader clearfix"><span class="title">Edit Class</span><span class="close">×</span></div><div class="modalContent"><p>* fields are required</p><form><ul><input type="hidden" name="language" value="0"><li><label for="id">ID*</label><input name="id" type="text" value="e1" readonly=""><span>Enter the class ID</span></li><li><label for="title">Title*</label><input name="title" type="text" value="EXA-1 - Example class a simple test" class="tooltip" title="Class title should look like: CIS-1 - Title"><span>Enter the class title</span></li><li><label for="units">Units*</label><input name="units" type="number" value="1"><span>Enter the class units</span></li><li><label for="transfer">Transfer</label><input name="transfer" type="text" value="CSU, UC"><span>Enter the class transfer</span></li><li><label for="advisory">Advisory</label><input name="advisory" type="text" value="English"><span>Enter the class advisory<a class="addClass floatright">+ Add Class</a></span></li><li><label for="prereq">Prerequisite</label><input name="prereq" type="text" value="[class id=&quot;c1a&quot; text=&quot;CIS-1A&quot; /]"><span>Enter the class prerequisite<a class="addClass floatright">+ Add Class</a></span></li><li><label for="coreq">Corequisite</label><input name="coreq" type="text" value="[class id=&quot;c1b&quot; text=&quot;CIS-1B&quot; /]"><span>Enter the class corequisite<a class="addClass floatright">+ Add Class</a></span></li><li><label for="description">Description*</label><textarea onkeyup="adjustTextarea(this)" name="description" type="textarea" style="height: 206px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus aliquet ante at fringilla. Nunc a sapien et est facilisis hendrerit porta at dui. Nullam finibus, dolor quis porttitor finibus, purus turpis hendrerit ipsum, eget dictum justo lectus ac tortor. Nulla tempus nunc et facilisis facilisis.</textarea><span>Enter the class Description</span></li></ul></form></div><div class="modalFooter"><input type="button" value="Save" class="" name="save"><input type="button" value="Cancel" class="low" name=""></div></div></div>
</body>
</html>

<?php
//	Core::debug( $_SESSION );
//	Core::debug( $_SERVER );
?>
