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
		<p>Information Security Certificate (CE XXX)</p>
		<p>Completion in One Year</p>
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
				<?=Core::fakeLink( 'class','c21','CIS 21')?>
				<?=Core::fakeLink( 'class','c25','CIS 25')?>
				<?=Core::fakeLink( 'class','c27','CIS 27')?>
				<?=Core::fakeLink( 'class','c27a','CIS 27A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c21a','CIS 21A')?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock( 'c26a','CIS 26A','c26b','CIS 26B');
					Core::endShortClassBlock();
				?>
			</td>
		</tr>
	</table>


	<div class="aligncenter margin25Top">
		<p>Information Security & CISCO Certificates</p>
		<p>Completion in Two Years</p>
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
				<?=Core::fakeLink( 'class','c21','CIS 21')?>
				<?=Core::fakeLink( 'class','c25','CIS 25')?>
				<?=Core::fakeLink( 'class','c27','CIS 27')?>
				<?=Core::fakeLink( 'class','c27a','CIS 27A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c21a','CIS 21A')?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c26a','CIS 26A','c26b','CIS 26B');
					Core::endShortClassBlock()
				?>

			</td>
		</tr>
		<tr>
			<td>Year 2</td>
			<td>&nbsp;</td>
			<td>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c26c','CIS 26C','c26d','CIS 26D');
					Core::endShortClassBlock()
				?>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>

</div>
