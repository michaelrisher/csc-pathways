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
		<p>CISCO Networking Certificate (CE 810)</p>
		<p>One Semester Completion - Online</p>
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
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c26a', 'CIS 26A', 'c26b', 'CIS 26B');
					Core::endShortClassBlock();
				?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c26c', 'CIS 26C', 'c26d', 'CIS 26D');
					Core::endShortClassBlock();
				?>
			</td>
		</tr>
	</table>
</div>
