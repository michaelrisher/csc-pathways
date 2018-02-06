<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 12/13/2017
	 * Time: 15:47
	 */
	$dismissed = isset( $_COOKIE['noticeDismissed'] ) ? $_COOKIE['noticeDismissed'] : false;
	if( MAINTENANCE && !$dismissed){
?>
<div id="warningWrapper" class="error">
	<div id="warning">
		<div class="flex notice">
			<strong><i class="fa fa-exclamation-circle"></i>&nbsp;Site is undergoing maintenance.&nbsp;</strong>
			<p>Some features may not work correctly.</p>
		</div>
		<div>
			<input type="button" class="low noticeDismiss" value="Dismiss" />
		</div>
	</div>
</div>
<?php }
	if( ADMIN_MAINTENANCE && !$dismissed ){
		$this->loadModule( 'users' );
		if( $this->users->isLoggedIn() ){
		?>
		<div id="warningWrapper" class="error">
			<div id="warning">
				<div class="flex notice">
					<strong><i class="fa fa-exclamation-circle"></i>&nbsp;Administration portion is undergoing maintenance.&nbsp;</strong>
					<p>Some features may not work correctly.</p>
				</div>
				<div>
					<input type="button" class="low noticeDismiss" value="Dismiss" />
				</div>
			</div>
		</div>
<?php } }
	if( NOTICE && !$dismissed ){
	?>
	<div id="warningWrapper" class="notice">
		<div id="warning">
			<div class="flex notice">
				<strong><i class="fa fa-exclamation-triangle"></i>&nbsp;Notice.&nbsp;</strong>
				<p><?= NOTICE_MESSAGE ?></p>
			</div>
			<div>
				<input type="button" class="low noticeDismiss" value="Dismiss" />
			</div>
		</div>
	</div>
<?php }
	if( ERROR && !$dismissed ){
		?>
		<div id="warningWrapper" class="error">
			<div id="warning">
				<div class="flex notice">
					<strong><i class="fa fa-exclamation-circle"></i>&nbsp;Error.&nbsp;</strong>
					<p><?= ERROR_MESSAGE ?></p>
				</div>
				<div>
					<input type="button" class="low noticeDismiss" value="Dismiss" />
				</div>
			</div>
		</div>
	<?php }