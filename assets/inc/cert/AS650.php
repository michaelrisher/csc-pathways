<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/24/2017
	 * Time: 12:29
	 */
	include "../../../config.php";
?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<p class="info">Certification Information <img class="floatright margin15Right back" data-to="top" width=20 src="<?=CORE_URL . 'assets/img/back.png'?>" /></p>
		</div>
		<p>AD-T Computer Science (AS 650)</p>
		<p>Full Time Completion in Two Years</p>
	</div>
	<table>
		<tr>
			<td>Year</td>
			<td>Summer</td>
			<td>Fall</td>
			<td>Winter</td>
			<td>Spring</td>
		</tr>
		<tr>
			<td>Year 1</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c1a','CIS 1A*')?>
				<?=Core::fakeLink( 'class','c5','CSC/CIS 5')?>
				<?=Core::fakeLink( 'class','m1a','MAT 1A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c17a','CSC/CIS 17A')?>
				<?=Core::fakeLink( 'class','m1b','MAT 1B')?>
			</td>
		</tr>
		<tr>
			<td>Year 2</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c11','CSC/CIS 11')?>
				<?=Core::fakeLink( 'class','p4a','PHY 4A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c7','CSC/CIS 7')?>
				<?=Core::fakeLink( 'class','p4b','PHY 4B')?>
			</td>
		</tr>
	</table>
</div>
