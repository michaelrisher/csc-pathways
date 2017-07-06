<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/5/2017
	 * Time: 13:57
	 * http://www.media-division.com/php-download-script-with-resume-option
	 */

	//turn off compression
	@apache_setenv( 'no-gzip', 1 );
	@ini_set( 'zlib.output_compression', 'Off' );

	$file = CORE_PATH . 'assets/misc/help.docx';
	if ( !file_exists( $file ) ) {
		header( "HTTP/1.0 400 Bad Request" );
		exit;
	}



//	header('Content-Type: application/octet-stream');
	header('Pragma: public');
	header('Expires: -1');
	header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
	header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	header('Content-Disposition: attachment; filename="help.docx"');
	header('Content-Length: ' . filesize($file));
	readfile( $file );
	ob_flush();
	ob_clean();