<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 12:29
	 */
	include "../../../config.php";
	?>
<div>
	<div class="aligncenter">
		<div class="clearfix margin10">
			<p class="info">Certification Information <img class="floatright margin15Right back" data-to="top" width=20 src="<?=CORE_URL . 'assets/img/back.png'?>" /></p>
		</div>
		<p>C++ Programming Certificate (CE 803)</p>
		<p>Full Time Completion in 2 Years</p>
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
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c17a','CSC/CIS 17a')?>
			</td>
		</tr>
		<tr>
			<td>Year 2</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c17b','CSC/CIS 17B')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c17c','CSC/CIS 17C')?>
			</td>
		</tr>
	</table>
	<div>
		<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>
	</div>


	<div class="aligncenter margin25Top">
		<p>C++ Programming Certificate (CE 803)</p>
		<p>One Year Completion</p>
	</div>
	<table>
		<tr>
			<td>Year</td>
			<td>Winter</td>
			<td>Spring</td>
			<td>Summer</td>
			<td>Fall</td>
		</tr>
		<tr>
			<td>Year 1</td>
			<td>
				<?=Core::fakeLink( 'class','c1a','CIS 1A*')?>
				<?=Core::fakeLink( 'class','c5','CSC/CIS 5')?>
			</td>
			<td>
				<?=Core::fakeLink( 'class','c17a','CSC/CIS 17a')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c17b','CSC/CIS 17B')?>
				<?=Core::fakeLink( 'class','c17c','CSC/CIS 17C')?>
			</td>
		</tr>
	</table>
	<div>
		<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>
	</div>
</div>
