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
			<td>Year</td>
			<td>&nbsp;</td>
			<td>Summer</td>
			<td>Fall</td>
			<td>Winter</td>
			<td>Spring</td>
		</tr>
		<tr>
			<td rowspan="2">Year 1</td>
			<td>1<sup>st</sup> 8 Weeks</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c26a','CIS 26A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c26c','CIS 26C')?>
			</td>
		</tr>
		<tr>
			<td>2<sup>nd</sup> 8 Weeks</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c26b','CIS 26B')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c26d','CIS 26D')?>
			</td>
		</tr>
	</table>
</div>
