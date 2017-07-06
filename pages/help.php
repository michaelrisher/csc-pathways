<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/5/2017
	 * Time: 13:57
	 */
	$file = CORE_PATH . 'assets/misc/help.docx';

	header('Content-Description: File Transfer');
//	header('Content-Type: application/octet-stream');
	header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	header('Content-Disposition: attachment; filename="help.docx"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	readfile( $file );
	ob_clean();