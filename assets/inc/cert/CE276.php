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
		<p>Computer Applications Certificate (AS/CE 276)</p>
		<p>Full Time Completion in One Year</p>
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
			<td>
				<?=Core::fakeLink( 'class','c79','CIS 79')?>
				<?=Core::fakeLink( 'class','c95a','CIS 95A')?>
			</td>
			<td>
				<?=Core::fakeLink( 'class','c1a','CIS 1A')?>
				<?=Core::fakeLink( 'class','c1b','CIS 1B')?>
				<?=Core::fakeLink( 'class','c21','CIS 21')?>
				<?=Core::fakeLink( 'class','c72a','CIS 72A')?>
				<?=Core::fakeLink( 'class','c72b','CIS 72B')?>
			</td>
			<td>
				<span class="red"><?=Core::fakeLink( 'class','c95a','CIS 95A')?></span>
				<?=Core::fakeLink( 'class','c81','CIS 81')?>
			</td>
			<td>
				<?=Core::fakeLink( 'class','c2','CIS 2')?>
				<?=Core::fakeLink( 'class','c61','CIS 61')?>
				<?=Core::fakeLink( 'class','c5','CSC/CIS 5')?>Or
				<?=Core::fakeLink( 'class','c28a','CSC/CIS 28A')?>
				<?=Core::fakeLink( 'class','c98a','CIS/CAT 98A*')?>
				<?=Core::fakeLink( 'class','c98b','CSC/CIS 98B')?>
			</td>
		</tr>
	</table>
</div>
