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
			<p class="title">CISCO Networking Certificate (CE 810)</p>
			<div class="alignjustify">
				<p>Cisco Certified Network Associate (CCNA) certificate validates the ability to install, configure, operate, and troubleshoot medium-size router and switched networks, including implementation and verification of connections to remote sites in a WAN. CCNA curriculum includes basic mitigation of security  threats, introduction to wireless networking concepts and terminology, and performance-based skills. This includes (but is not limited to) the use of these protocols: IP, Enhanced Interior Gateway Routing Protocol (EIGRP), Serial Line  Interface Protocol Frame  Relay, Routing Information Protocol Version 2 (RIPv2),VLANs, Ethernet, access control lists (ACLs). This certificate is designed for students with advanced problem solving and analytical skills. The curriculum offers a comprehensive and theoretical learning experience for analytical students, and uses language that aligns well with engineering concepts. Interactive activities are embedded in the curriculum, along with detailed, theoretical labs.</p>
				<p class="bold margin15Top">Program Learning Outcomes</p>
				<p>Upon successful completion of this program, students should be able to:</p>
				<ul>
					<li>Demonstrate an understanding of routing fundamentals, subnets and IP addressing schemes.</li>
					<li>Explain the command and steps required to configure router host tables, and interfaces within the RIP, EIGRP and OSPF protocols.</li>
					<li>Demonstrate an understanding of switching concepts and LAN design to include the use of Virtual LANs with LAN trunking configured by the Spanning Tree Protocol.</li>
					<li>Define and demonstrate the concepts of Cisco's implementation of ISDN networking including WAN link options.</li>
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
