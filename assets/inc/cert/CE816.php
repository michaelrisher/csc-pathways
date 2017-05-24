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
		<p>Relational Database Certificate (CE 806)</p>
		<p>One Year Completion - Online</p>
	</div>
	<table>
		<tr>
			<td>Semester</td>
			<td colspan="2">Fall</td>
			<td>Spring</td>
		</tr>
		<tr>
			<td>8 Week Classes</td>
			<td>
				<span>8/28 - 10/20</span>
				<?=Core::fakeLink( 'class','c2','CSC/CIS 2')?>
				<?=Core::fakeLink( 'class','c62','CSC/CIS 62')?>
			</td>
			<td>
				<span>10/23 - 12/14</span>
				<?=Core::fakeLink( 'class','c20','CIS 20')?>
				<?=Core::fakeLink( 'class','c91','CIS/CAT 91')?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>16 Week Classes</td>
			<td colspan="2">
				<span>8/28 - 12/4</span>
				<?=Core::fakeLink( 'class','c61','CSC/CIS 61')?>
			</td>
			<td>
				<span>2/20 - 6/8</span>
				<?=Core::fakeLink( 'class','c28a','CSC/CIS 28A')?>
				<?=Core::fakeLink( 'class','c63','CSC/CIS 63')?>
			</td>
		</tr>
	</table>
</div>
