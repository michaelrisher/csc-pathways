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
			<p class="title">Java Programming Certificate (CE 809)</p>
			<div class="alignjustify">
				<p>Completion of this certificate provides the student with skills a new programmer would need to obtain employment programming Java applications.</p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Create structured and Object code in Java for business, gaming, mathematical and scientific problems by identifying the information input requirements, synthesizing the algorithmic steps needed to transform the data input into the required output information, and organizing the output format to facilitate user communication.</li>
					<li>Using Java libraries create and run Java programs that incorporate the following:
						<ul>
							<li>Multiprocessors</li>
							<li>Multimedia</li>
							<li>JDBC</li>
							<li>SQL</li>
							<li>Establish client/server relationship</li>
						</ul>
					</li>
					<li>Using Java libraries create and run Java programs that incorporate data structures.</li>
				</ul>
			</div>
		</div>
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
				<?=Core::fakeLink( 'class','c1a','CIS 1A*')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c5','CSC/CIS 5')?>
			</td>
		</tr>
		<tr>
			<td>Year 2</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c18a','CSC/CIS 18A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c18b','CSC/CIS 18B')?>
				<?=Core::fakeLink( 'class','c18c','CSC/CIS 18C')?>
			</td>
		</tr>
	</table>
	<div>
		<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>
	</div>


	<div class="aligncenter margin25Top">
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
			<td>
				<?=Core::fakeLink( 'class','c1a','CIS 1A*')?>
				<?=Core::fakeLink( 'class','c5','CSC/CIS 5')?>
			</td>
			<td>
				<?=Core::fakeLink( 'class','c18a','CSC/CIS 18A')?>
			</td>
			<td>&nbsp;</td>
			<td>
				<?=Core::fakeLink( 'class','c18b','CSC/CIS 18B')?>
				<?=Core::fakeLink( 'class','c18c','CSC/CIS 18C')?>
			</td>
		</tr>
	</table>
	<div>
		<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>
	</div>

</div>
