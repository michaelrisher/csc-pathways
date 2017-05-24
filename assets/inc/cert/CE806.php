<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/23/2017
	 * Time: 12:29
	 */
	include "../../../config.php";
?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<p class="info">Certification Information <img class="floatright margin15Right back" data-to="top" width=20 src="<?=CORE_URL . 'assets/img/back.png'?>" /></p>
		</div>
		<p>Systems Analysis Certificate (CE 806)</p>
		<p>One Semester Completion - Online</p>
	</div>
	<table>
		<tr>
			<td>Semester</td>
			<td colspan="2">Fall</td>
		</tr>
		<tr>
			<td>8 Week Classes</td>
			<td>
				<span>Early Start</span>
				<?=Core::fakeLink( 'class','c2','CSC/CIS 2')?>
				<?=Core::fakeLink( 'class','c62','CSC/CIS 62')?>
			</td>
			<td>
				<span>Late Start</span>
				<?=Core::fakeLink( 'class','c20','CIS 20')?>
				<?=Core::fakeLink( 'class','c91','CIS/CAT 91')?>
			</td>
		</tr>
		<tr>
			<td>16 Week Classes</td>
			<td colspan="2">
<!--				<span>8/28 - 12/4</span>-->
				<?=Core::fakeLink( 'class','c61','CSC/CIS 61')?>
			</td>
		</tr>
	</table>
</div>
