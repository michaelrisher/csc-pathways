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
		<p>Relational Database Certificate (CE 816)</p>
		<p>One Year Completion - Online</p>
	</div>
	<table>
		<tr>
			<td class="width10">Year</td>
			<td>Summer</td>
			<td>Fall</td>
			<td>Winter</td>
			<td>Spring</td>
		</tr>
		<tr>
			<td>Year 1</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink('class','c61','CSC/CIS 61');?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c2', 'CSC/CIS 2', 'c20', 'CSC/CIS 20');
					Core::shortClassBlock('c62', 'CSC/CIS 62', 'c91', 'CSC/CIS 91');
					Core::endShortClassBlock();
				?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink('class','c28a','CSC/CIS 28A');?>
				<?=Core::fakeLink('class','c63','CSC/CIS 63');?>
			</td>
		</tr>
	</table>
</div>
