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
			<p class="title">Webmaster Certificate - Designer (CE 843)</p>
			<div class="alignjustify">
				<p></p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Apply design and visual communication principles to web site, page, and interface design.</li>
					<li>Use Photoshop to create and edit images for use on the web, including photographs, logos, navigation buttons, background images, image maps, and web page design mockups (tracing images).</li>
					<li>Use Flash to create web animations and interactive web sites.</li>

				</ul>
			</div>
		</div>
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
			<td><?=Core::fakeLink( 'class','c79','CIS 79')?></td>
			<td>
				<?=Core::fakeLink( 'class','c76b','CIS 76B')?>
				<?=Core::fakeLink( 'class','c78a','CIS 78A')?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock( 'c72a','CIS 72A','c72b','CIS 72B');
					Core::endShortClassBlock();
				?>
			</td>
			<td><?=Core::fakeLink( 'class','c81','CIS 81')?></td>
			<td>
				<?=Core::fakeLink( 'class','a67','ADM 67')?>
			</td>
		</tr>
	</table>

	<div class="aligncenter margin25Top">
		<p>Webmaster Certificate - Designer (CE 820) And Webmaster Certificate - Developer (CE 843)</p>
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
			<td><?=Core::fakeLink( 'class','c79','CIS 79')?></td>
			<td>
				<?=Core::fakeLink( 'class','c76b','CIS 76B')?>
				<?=Core::fakeLink( 'class','c78a','CIS 78A')?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock( 'c72a','CIS 72A','c72b','CIS 72B');
					Core::endShortClassBlock();
				?>
			</td>
			<td><?=Core::fakeLink( 'class','c81','CIS 81')?></td>
			<td>
				<?=Core::fakeLink( 'class','a67','ADM 67')?>
				<?=Core::fakeLink( 'class','c12','CIS 12')?>
				<?=Core::fakeLink( 'class','c14a','CIS 14A')?>
			</td>
		</tr>
	</table>
</div>
