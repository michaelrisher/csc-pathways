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
				return Core::sanitizePost( $string );
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
	}