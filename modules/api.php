<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 11/7/2017
	 * Time: 13:39
	 */

class api extends Main{
	private $moduleName = 'api';

	public function course( $endpoints = '' ){
		if ( is_array( $endpoints ) ) {
			$endpoints = join( '/', $endpoints );
		}
		$this->loadModules('classes');
		header( 'Content-Type: application/vnd.api+json' );
		$validEndpoints = array( '', 'search' );
		$errors = array();
		if ( isset( $_GET ) ) {
			$_GET = Core::sanitize( $_GET );
		}
		if( isset( $_GET['authkey'] ) ){
			//check valid authkey
			if( $this->checkAuthkey( $_GET['authkey'] ) ){
				if ( !$this->isOverLimit( $_GET['authkey'] ) ) {
					//increment request for authkey
					$this->requestIncrement( $_GET['authkey'] );
					if( Core::inArray( $endpoints, $validEndpoints ) ){
						if ( isset( $_GET['id'] ) ) {
							$class = $this->classes->get( $_GET['id'], true );
							$d = array(
								'type' => 'course',
								'id' => $class['id'],
								'attributes' => array()
							);
							if ( isset( $_GET['fields'] ) && isset( $_GET['fields']['course'] ) ) {
								$keys = explode( ',', $_GET['fields']['course'] );
								$arr = array();
								foreach( $keys as $key ){
									$arr[$key] = $class[$key];
								}
								$d['attributes'] = $arr;
							} else {
								unset( $class['id'] );
								$d['attributes'] = $class;
							}
							$d['relationships'] = array(
								'discipline' => array(
									'data' => array(
										'id' => $class['discipline'],
										'type' => 'discipline'
									)
								)
							);
							$obj['data'] = $d;
						} else{
							array_push( $errors, $this->errorItem( 400, 4,'classes/'.$endpoints, "Missing id", "Missing required field id" ) );
						}
					} else{
						array_push( $errors, $this->errorItem( 400, 2,'classes/'.$endpoints, "Endpoint doesn't exist", "This endpoint does not exist" ) );
					}
				} else {
					array_push( $errors, $this->errorItem( 400, 5,'classes/'.$endpoints, "Exceeded request limit", "You have exceeded your monthly quota of requests for the authkey" ) );
				}
			} else{
				array_push( $errors, $this->errorItem( 401, 3, 'classes/'.$endpoints, 'Invalid Authkey Given', "A valid authkey must be provided to use this endpoint " ) );
			}
		} else {
			array_push( $errors, $this->errorItem( 401, 1, 'classes/'.$endpoints, 'No Authkey Given', "An authkey must be provided to use this endpoint" ) );
		}
		if( !empty( $errors ) ){
			$obj = $this->errorResponse( $errors );
		}
		echo json_encode( $obj );
	}

	public function certificate( $endpoints = '' ){
		if ( is_array( $endpoints ) ) {
			$endpoints = join( '/', $endpoints );
		}
		$this->loadModules('certs');
		header( 'Content-Type: application/vnd.api+json' );
		$validEndpoints = array( '', 'search' );
		$errors = array();
		if ( isset( $_GET ) ) {
			$_GET = Core::sanitize( $_GET );
		}
		if( isset( $_GET['authkey'] ) ){
			//check valid authkey
			if( $this->checkAuthkey( $_GET['authkey'] ) ){
				if ( !$this->isOverLimit( $_GET['authkey'] ) ) {
					//increment request for authkey
					$this->requestIncrement( $_GET['authkey'] );
					if( Core::inArray( $endpoints, $validEndpoints ) ){
						if ( isset( $_GET['id'] ) ) {
							$class = $this->certs->get( $_GET['id'], true );
							$d = array(
								'type' => 'certificate',
								'id' => $class['id'],
								'attributes' => array()
							);
							if ( isset( $_GET['fields'] ) && isset( $_GET['fields']['certificate'] ) ) {
								$keys = explode( ',', $_GET['fields']['certificate'] );
								$arr = array();
								foreach( $keys as $key ){
									try{
										$arr[$key] = $class[$key];
									} catch( Exception $e ){
										array_push( $errors, $this->errorItem( 400, 6,'certificate/'.$endpoints, "Non-existent field", "Missing required field id" ) );
									}

								}
								$d['attributes'] = $arr;
							} else {
								unset( $class['id'] );
								$d['attributes'] = $class;
							}
							$d['relationships'] = array(
								'discipline' => array(
									'data' => array(
										'id' => $class['discipline'],
										'type' => 'discipline'
									)
								)
							);
							$obj['data'] = $d;
						} else{
							array_push( $errors, $this->errorItem( 400, 4,'certificate/'.$endpoints, "Missing id", "Missing required field id" ) );
						}
					} else{
						array_push( $errors, $this->errorItem( 400, 2,'certificate/'.$endpoints, "Endpoint doesn't exist", "This endpoint does not exist" ) );
					}
				} else {
					array_push( $errors, $this->errorItem( 400, 5,'certificate/'.$endpoints, "Exceeded request limit", "You have exceeded your monthly quota of requests for the authkey" ) );
				}
			} else{
				array_push( $errors, $this->errorItem( 401, 3, 'certificate/'.$endpoints, 'Invalid Authkey Given', "A valid authkey must be provided to use this endpoint " ) );
			}
		} else {
			array_push( $errors, $this->errorItem( 401, 1, 'certificate/'.$endpoints, 'No Authkey Given', "An authkey must be provided to use this endpoint" ) );
		}
		if( !empty( $errors ) ){
			$obj = $this->errorResponse( $errors );
		}
		echo json_encode( $obj );
	}



	/**
	 * creates a simple jsonapi error block
	 * @param int $status apache error code related to error
	 * @param int $code custom error code
	 * @param string $source the endpoint the user is trying to reach
	 * @param string $title the title of the error
	 * @param string $detail the full details of the errors
	 * @return array
	 */
	private function errorItem( $status, $code, $source, $title, $detail ){
		$obj = array(
				'status' => $status,
				'code' => $code,
				'source' => array( 'pointer' => $source ),
				'title' =>  $title,
				'detail' => $detail
		);
		return $obj;
	}

	private function errorResponse( $errors ){
		return array(
			'errors' => $errors
		);
	}

	public function checkAuthkey( $key ){
		$query = "SELECT * FROM api WHERE authkey = '" . $key ."'";
		if( $result = $this->db->query($query) ) {
			if ( $result->num_rows > 0 ) {
				return true;
			}
		}
		return false;
	}

	public function requestIncrement( $key ){
		$query = "SELECT * FROM api WHERE authkey = '" . $key ."'";
		if( $result = $this->db->query($query) ) {
			if ( $result->num_rows > 0 ) {
				$row = $result->fetch_assoc();
				$result->close();
				$requests = $row['requests'] + 1;
				$monthUsage = $row['monthUsage'] + 1;
				$query = "UPDATE api SET requests=$requests,monthUsage=$monthUsage WHERE authkey='$key'";
				if( !$this->db->query( $query ) ){
					error_log( 'api->requestIncrement ' . $this->db->error );
				}
			}
		}
		return false;
	}

	public function isOverLimit( $key ){
		$query = "SELECT * FROM api WHERE authkey = '" . $key ."'";
		if( $result = $this->db->query($query) ) {
			if ( $result->num_rows > 0 ) {
				$row = $result->fetch_assoc();
				if( $row['quota'] == -1 ) return false;
				if( $row['monthUsage'] >= $row['quota'] ){
					return true;
				}
			}
		}
		return false;
	}
}
