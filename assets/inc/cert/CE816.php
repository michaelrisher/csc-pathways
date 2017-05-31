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
			<p class="title">Relational Database Certificate (CE 816)</p>
			<div class="alignjustify">
				<p>Provides the skills necessary to present a view of data as a collection   of   rows   and   columns   and   manage    these relational databases based on a variety of data models.</p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Present the data to the user as a set of relations.</li>
					<li>Provide relational operators to manipulate the data in tabular form.</li>
					<li>Use a modeling language to define the schema of each database hosted in the DBMS, according to the DBMS data model.</li>
					<li>Optimize data structures (fields, records, files and objects) to deal with very large amounts of data stored on a permanent data storage device.</li>
					<li>Create a database query language and report writer to allow users to interactively interrogate the database, analyze its data and update it according to the users privileges on data.</li>
					<li>Develop a transaction mechanism, that would guarantee the ACID properties, in order to ensure data integrity, despite concurrent user accesses and faults.</li>
				</ul>
			</div>
		</div>
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
