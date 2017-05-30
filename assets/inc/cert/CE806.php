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
			<p class="title">Systems Analysis Certificate (CE 806)</p>
			<div class="alignjustify">
				<p>The Systems Development mini certificate gives students the skills necessary to analyze, design, and develop an information system in any business environment that is involved in keeping data about various entities up-to-date and/or processing daily transactions.</p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Demonstrate an understanding of systems analysis as applied to the effective use of computers in business operations.</li>
					<li>Analyze user requirements in business operations applying structured analysis tools like Data Flow Diagrams, Data Dictionary and Process Description.</li>
					<li>Design various system components like output, input and user interface screens, reports, and normalized files.</li>
					<li>Demonstrate  an  understanding  of  various  developmental methodologies including the use of CASE tools.</li>
					<li>Design  relational  database  tables,  queries,  forms,  reports, macros, validation rules in MS Access.</li>
					<li>Demonstrate how to document a database and how MS Access can interface with the Web, demonstrate error trapping, database security, and automating ActiveX Controls with VBA.</li>
					<li>Demonstrate an understanding of System Architecture, Implementation, Operations, Support and Security plus various tools for cost benefit analysis  and  project management.</li>
				</ul>
			</div>
		</div>
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
				<?=Core::fakeLink( 'class','c61','CSC/CIS 61')?>
				<?php
					Core::initShortClassBlock();
					Core::shortClassBlock('c2', 'CSC/CIS 2', 'c20', 'CSC/CIS 20');
					Core::shortClassBlock('c62', 'CSC/CIS 62', 'c91', 'CSC/CIS 91');
					Core::endShortClassBlock();
				?>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</div>
