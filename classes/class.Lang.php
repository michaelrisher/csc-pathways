<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 7/10/2017
	 * Time: 09:24
	 */
	class Lang {
		private $languageCode;
		private $definitions;
/*
		public static $list = array(
			'en' => 'English',
			'es' => 'Español'
		);

		public static function getCode(){
			if( isset( $_COOKIE['lang'] ) || isset( $_GET['lang'] ) ) {
				if( $_COOKIE['lang'] ) {
					return $_COOKIE['lang'];
				} else{
					return $_GET['lang'];
				}
			} else {
				return 'en';
			}
		}

		public static function codeToText( $code ){
			return self::$list[$code];
		}

		public function __construct( $code ){
			$this->languageCode = $code;
			require_once( CORE_PATH . 'locale/' . $this->languageCode . '/lang.' . $this->languageCode . '.php' );
			$this->definitions = new LanguageDefinitions();
		}

		public function o( $msgid ){
			if( isset( $this->definitions ) ){
				if( isset( $this->definitions->translations[$msgid] ) ){
					return $this->definitions->translations[$msgid];
				} else{
					return '';
				}
			} else{
				return '';
			}
		}

*/

	}