<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 11/7/2017
	 * Time: 13:39
	 */

class api extends Main{
	private $moduleName = 'api';

	public function classes( $endpoints = '' ){
		$validEndpoints = array( 'save', 'delete' );
		if ( isset( $_GET ) ) {
			$_GET = Core::sanitize( $_GET );
		}
		if( isset( $_GET['authkey'] ) ){
			if( Core::inArray( $endpoints, $validEndpoints ) ){

			} else{
				$obj = $this->errorResponse( 400, 'classes/'.$endpoints, "Endpoint doesn't exist", "This endpoint does not exist" );
			}

		} else {
			$obj = $this->errorResponse( 401, 'classes/'.$endpoints, 'No Authkey Given', "An authkey must be provided to use this endpoint" );
		}
		Core::debug( $obj );
	}

	/**
	 * creates a simple jsonapi error block
	 * @param int $status apache error code related to error
	 * @param string $source the endpoint the user is trying to reach
	 * @param string $title the title of the error
	 * @param string $detail the full details of the errors
	 * @return array
	 */
	private function errorResponse( $status, $source, $title, $detail ){
		$obj = array(
			'errors' => array(
				'status' => $status,
				'source' => array( 'pointer' => $source ),
				'title' =>  $title,
				'detail' => $detail
			)
		);
		return $obj;
	}
}
