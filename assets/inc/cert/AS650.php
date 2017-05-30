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
			<div class="alignjustify">
				<p>The Associate in Science in Computer Science for Transfer degree provides a solid preparation for transfer majors in computer science including an emphasis on object oriented programming logic in C++, computer architecture, calculus and calculus based physics . The intent of this degree is to assist students in seamlessly transferring to a CSU . With this degree the student will be prepared for transfer to the university upper division level in preparation for the eventual conferral of the Bachelor's Degree in Computer Science . The degree aligns with the approved Transfer Model Curriculum (TMC) in Computer Science .</p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Write programs utilizing the following data structures: arrays, records, strings, linked lists, stacks, queues, and hash tables</li>
					<li>Write and execute programs in assembly language illustrating typical mathematical and business applications .</li>
					<li>Demonstrate different traversal methods of trees and graphs</li>
				</ul>
			</div>
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
