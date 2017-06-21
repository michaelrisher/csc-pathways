<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/31/2017
	 * Time: 11:05
	 */
	class users extends Main{
		/**
		 * hass the password and the username check the database and login
		 */
		public function login(){
			//clean the post of any injects
			$_POST = Core::sanitize( $_POST );
			//hash the password and username to salt the password
			$password[0] = hash( 'sha512', substr($_POST['password'], 0, 64 ) );
			$password[1] = hash( 'sha512', substr($_POST['password'], 64 ) );
			$passwordHash = hash( 'sha512', $password[0] . $password[1] . $_POST['user'] );
			//create the query to check if the user exists and to verify hashs
			$query = "SELECT * FROM users WHERE username = '${_POST['user']}' AND password = '$passwordHash'";

			//return array
			$obj = array();
			//query the database
			if( !$result = $this->db->query($query) ){
//				die('There was an error running the query [' . $this->db->error . ']');
				//if there was an error report it to the user
				$obj['error'] = "An error occurred please try again";
				echo Core::ajaxResponse( $obj, false );
				return;
			}
			$row = null;
			//if there was a user matching
			if( $result->num_rows == 1 ){
				$row = $result->fetch_assoc();
				$obj['user'] = $row['username'];
				//if the user is set to active meaning they are allowed to access report that error to them
				if( $row['active'] == 0 ){
					$obj['errors'] = "Account has been deactivated.";
					echo Core::ajaxResponse( $obj, false );
				} else {
					//if user is active then log in
					$obj['msg'] = 'Successfully logged in.';
					//set redirect url
					$obj['redirect'] = 'admin';
					//update date and ip
					$query = "UPDATE users SET lastIP = '${_SERVER['REMOTE_ADDR']}', latestDate = CURRENT_TIMESTAMP WHERE id = ${row['id']}";
					if( $this->db->query( $query ) === true ) {
						//todo log this error
					}
					//echo the reponse to user
					echo Core::ajaxResponse( $obj );
					//update session to reflect login status
					$_SESSION['session'] = array();
					$_SESSION['session']['username'] = $row['username'];
					$_SESSION['session']['id'] = $row['id'];
					$_SESSION['session']['expires'] = time() + ( 60 * 10 ); //session set for 10 minutes
					$_SESSION['session']['started'] = time();
					//load audit module and report the login
					$this->loadModule( 'audit' );
					$this->audit->newEvent( "Logged into administration" );
				}
			} else{
				$obj['error'] = "Your username or email is incorrect";
				echo Core::ajaxResponse( $obj, false );
			}
			$result->close();
		}

		/**
		 * logout the user
		 */
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
				//if the expire time is less than the current time log the user out
				if( $_SESSION['session']['expires'] <= time() ){
					$this->logout();
				} else {
					//if not expired then refresh the expire time to current time
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