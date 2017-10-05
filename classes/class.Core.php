<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/22/2017
	 * Time: 10:02
	 */
	class Core {
		public static function queueScript( $url ) {
			if ( file_exists( CORE_PATH . $url ) ) {
				$GLOBALS['scriptQueue'][$url] = CORE_URL . $url . '?' . filemtime( $url );
			} elseif( substr( $url, 0, 4 ) === "http" ) {
				$GLOBALS['scriptQueue'][$url] = $url;
			}
		}

		/**
		 * include the scripts in the page
		 */
		public static function includeScripts() {
			foreach ( $GLOBALS['scriptQueue'] as $url ) {
				?>
				<script src="<?= $url; ?>" type="text/javascript"></script><?php
			}
			$GLOBALS['scriptQueue'] = array();
		}

		/**
		 * Queue a style up to be included
		 * @param String $url
		 */
		public static function queueStyle( $url ) {
			if ( file_exists( CORE_PATH . $url ) ) {
				$GLOBALS['styleQueue'][$url] = CORE_URL . $url . '?' . filemtime( $url );
			} elseif( substr( $url, 0, 4 ) === "http" ) {
				$GLOBALS['styleQueue'][$url] = $url;
			}
		}

		/**
		 * include the scripts in the page
		 */
		public static function includeStyles() {
			foreach ( $GLOBALS['styleQueue'] as $url ) {
				?>
				<link rel="stylesheet" href="<?= $url; ?>" type="text/css" media="all" /><?php
			}
			$GLOBALS['styleQueue'] = array();
		}

		/**
		 * @param $goto string where to go to link cert or class
		 * @param $code string what to load
		 * @param $text string text to display
		 * @return string output
		 */
		public static function fakeLink( $goto, $code, $text, $float = null ){
			if( is_null( $float ) ){
				return "<a class='fakeLink' data-to='$goto' data-code='$code'>$text</a>";
			} else {
				return "<a class='fakeLink float$float' data-to='$goto' data-code='$code'>$text</a>";
			}
		}

		public static function initShortClassBlock(){
			$str = '';
			$str .= '<div class="8week aligncenter">'.
				'<span>8 Week Classes</span>';
			echo $str;
		}
		public static function shortClassBlock( $leftCode, $leftTitle, $rightCode = null, $rightTitle = null ){
			$str = '';
			$str .= '<div class="clearfix">';
			if( !is_null( $leftCode ) || !is_null( $leftTitle ) ){
				$str .= Core::fakeLink( 'class', $leftCode, $leftTitle, 'left' );
			}
			if( !is_null( $rightCode ) || !is_null( $rightTitle ) ){
				$str .= Core::fakeLink( 'class', $rightCode, $rightTitle, 'right' );
			}
			$str .= '</div>';
			echo $str;
		}

		public static function endShortClassBlock(){
			echo '</div>';
		}


		/**
		 * clean strings of bad stuffs
		 * @param $string
		 * @param bool|false $allowhtml
		 * @param bool|false $limit_range
		 * @return mixed|string
		 */
		public static function sanitize( $string, $allowhtml = false, $limit_range = false ) {
			if( gettype($string) == "array" ){
				return Core::sanitizePost( $string, $allowhtml, $limit_range );
			}
			$string = (string)$string;

			if ($limit_range) {
				$string = preg_replace('/[^(\t\x0-\x7F)]*/','', $string);
			}

			if ( get_magic_quotes_gpc() ) {
				$string = stripslashes( trim( $string ) );
			} else {
				$string = trim($string);
			}

			if ( !$allowhtml ) {
				$invalid = array('@<script[^>]*?>.*?</script>@si', '@<[\/\!]*?[^<>]*?>@si', '@<style[^>]*?>.*?</style>@siU', '@<![\s\S]*?--[ \t\n\r]*>@');
				$string = preg_replace($invalid, '', $string);
			}
			return $string;
		}

		/**
		 * clean strings of bad stuffs
		 * @param $string
		 * @param bool|false $allowhtml
		 * @param bool|false $limit_range
		 * @return mixed|string
		 */
		private static function sanitizePost( $post, $allowhtml = false, $limit_range = false ) {
			foreach( $post as $key => $value ){
				$post[$key] = Core::sanitize( $post[$key], $allowhtml, $limit_range );
			}
			return $post;
		}

		/**
		 * Makes a unique 42 character string from sha1
		 * @return string
		 */
		public static function uniqueId(){
			do {
				$str = microtime( true ) . $_SERVER['REQUEST_TIME_FLOAT'] . $_SERVER['HTTP_HOST'];
				$str = sha1( $str );
			} while( array_search( $str, Core::$uniqueIds ) );
			array_push( Core::$uniqueIds, sha1( $str ) );
			return sha1( $str );
		}

		public static function userFriendlyId( $len ){
			$string = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789";
			$size = strlen( $string ) - 1;
			$result = '';
			for( $i = 0; $i < $len; $i++ ){
				$result .= $string[rand(0, $size )];
			}
			return $result;
		}

		/**
		 * Give data to turn into a json response
		 * @param $data
		 * @param bool|true $status
		 * @param $msg
		 * @return string
		 */
		public static function ajaxResponse( $data, $status = true, $msg = null){
			$o['success'] = $status;
			$o['data'] = $data;
			if( isset( $msg ) ){
				$o['msg'] = $msg;
			}
			if( IS_AJAX ){
				if( json_encode( $o ) ){
					return json_encode( $o );
				} else{
					return self::jsonError( json_last_error() );
				}
			}
		}

		public static function jsonError( $code ){
			switch ( $code ) {
				case JSON_ERROR_NONE:
					return ' - No errors';
					break;
				case JSON_ERROR_DEPTH:
					return ' - Maximum stack depth exceeded';
					break;
				case JSON_ERROR_STATE_MISMATCH:
					return ' - Underflow or the modes mismatch';
					break;
				case JSON_ERROR_CTRL_CHAR:
					return ' - Unexpected control character found';
					break;
				case JSON_ERROR_SYNTAX:
					return ' - Syntax error, malformed JSON';
					break;
				case JSON_ERROR_UTF8:
					return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
					break;
				default:
					return ' - Unknown error';
					break;
			}
		}

		public static function phpRedirect( $page ){
			header('Location: ' . CORE_URL . $page );
		}

		/**
		 * debug var to screen
		 * @param $arr
		 */
		public static function debug( $arr ){
			echo '<pre>';
			print_r( $arr );
			echo '</pre>';
		}

		public static function errorPage( $code ){
			$data['error'] = $code;
			Core::queueStyle( 'assets/css/reset.css' );
			Core::queueStyle( 'assets/css/ui.css' );
			include( CORE_PATH . 'pages/error.php' );
			die;
		}

		public static function createTimer( $time ){
			return "<span class='timer' data-time='$time'>$time</span>";
		}

		/**
		 * parse the class link from the html editor into a link for user
		 * @param $string
		 * @return mixed
		 */
		public static function replaceClassLink( $string ){
			return preg_replace( '/\[class\s*id=[\'|\"](.+?)[\'|\"]\s*text=[\'|\"](.+?)[\'|\"]\s*\/\]/',
				"<a class='fakeLink' data-to='class' data-code='$1'>$2</a>",
				$string
			);//regex to match the link string
		}

		/**
		 * returns the users ip
		 * @return mixed
		 */
		public static function getIp(){
			return $_SERVER['REMOTE_ADDR'];
		}

		/**
		 * converts an assoc array to a flat indexed array
		 * @param array $array
		 * @return array
		 */
		public static function assocToFlat( $array ){
			$temp = array();
			foreach ( $array as $item ) {
				array_push( $temp, $item );
			}
			return $temp;
		}

		public static function inArray( $needle, $array ){
			if( array_search( $needle, $array ) !== false ){
				return true;
			} else {
				return false;
			}
		}
	}