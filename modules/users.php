<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/31/2017
	 * Time: 11:05
	 */
	class users extends Main{
		public function login(){
			$_POST = Core::sanitize( $_POST );
			$password[0] = hash( 'sha512', substr($_POST['password'], 0, 64 ) );
			$password[1] = hash( 'sha512', substr($_POST['password'], 64 ) );
			$passwordHash = hash( 'sha512', $password[0] . $password[1] . $_POST['user'] );
			$query = "SELECT * FROM users WHERE username = '${_POST['user']}' AND password = '$passwordHash'";

			$obj = array();
			if( !$result = $this->db->query($query) ){
//				die('There was an error running the query [' . $this->db->error . ']');
				$obj['error'] = "An error occurred please try again";
				echo Core::ajaxResponse( $obj, false );
				return;
			}
			$row = null;
			if( $result->num_rows == 1 ){
				$row = $result->fetch_assoc();
				$obj['user'] = $row['username'];
				if( $row['active'] == 0 ){
					$obj['errors'] = "Account has been suspended.";
					echo Core::ajaxResponse( $obj, false );
				} else {
					$obj['msg'] = 'Successfully logged in.';
					$obj['redirect'] = 'admin';
					//update date and ip
					$query = "UPDATE users SET lastIP = '${_SERVER['REMOTE_ADDR']}', latestDate = CURRENT_TIMESTAMP WHERE id = ${row['id']}";
					if( $this->db->query( $query ) === true ) {
						//todo log this error
					}
					echo Core::ajaxResponse( $obj );
					//update session to reflect login status
					$_SESSION['session'] = array();
					$_SESSION['session']['username'] = $row['username'];
					$_SESSION['session']['id'] = $row['id'];
					$_SESSION['session']['expires'] = time() + ( 60 * 10 ); //session set for 10 minutes
					$_SESSION['session']['started'] = time();
					$this->loadModule( 'audit' );
					$this->audit->newEvent( "Logged into administration" );
				}
			} else{
				$obj['error'] = "Your username or email is incorrect";
				echo Core::ajaxResponse( $obj, false );
			}
			$result->close();
		}

		public function logout(){
			if ( isset( $_SESSION['session'] ) ) {
				unset( $_SESSION['session'] );
			}
		}

		/**
		 * check if the session expired if so log them out if not expired update the expire time
		 */
		public function checkExpiredSession( $refresh = true ){
			if ( isset( $_SESSION['session'] ) ) {
				if( $_SESSION['session']['expires'] <= time() ){
					$this->logout();
				} else { //not expired they reloaded
					if( $refresh ) {
						$_SESSION['session']['expires'] = time() + ( 60 * 10 ); //session set for 10 minutes
					}
				}

			}
		}

		/**
		 * check if they have an active session with the server
		 * @return bool true if has ative session false otherwise
		 */
		public function isLoggedIn(){
			if( isset( $_SESSION['session'] ) && isset( $_SESSION['session']['username'] ) ) {
				if ( $_SESSION['session']['expires'] > time() ) {
					return true;
				}
			}
			return false;
		}
	}