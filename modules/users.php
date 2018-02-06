<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 5/31/2017
	 * Time: 11:05
	 */
	class users extends Main{
		private $moduleName = 'users';

		public function listing( $order = 'username' ){
			$this->loadModules( 'roles discipline' );
			$fullRoles = $this->roles->getAllForUser( Core::getSessionId() );
			$userDisciplines = $this->discipline->getIdsForUser( Core::getSessionId() );
			$query = "SELECT * FROM users WHERE id != -1 ORDER BY " . $order;//remove limit for a time LIMIT $page,50
			if ( !$result = $this->db->query( $query ) ) {
				echo( 'There was an error running the query [' . $this->db->error . ']' );
			}

			$return = array();
//			$canEdit = Core::inArray( 'gUserEdit', $ROLES );
//			$canDelete = Core::inArray( 'gUserDelete', $ROLES );
			while ( $row = $result->fetch_assoc() ) {
				//get the roles for users
				$targetDisciplines = $this->discipline->getIdsForUser( $row['id'] );
				if( $this->roles->haveAccess( 'UserView', Core::getSessionId(), $targetDisciplines, $fullRoles, $userDisciplines ) ) {
					$canEdit = $this->roles->haveAccess( 'UserEdit', Core::getSessionId(), $targetDisciplines, $fullRoles, $userDisciplines );
					$canDelete = $this->roles->haveAccess( 'UserDelete', Core::getSessionId(), $targetDisciplines, $fullRoles, $userDisciplines );
					$a = array(
						'id' => $row['id'],
						'username' => $row['username'],
						'edit' => $canEdit,
						'delete' => $canDelete
					);
					array_push( $return, $a );
				}
			}
			if( IS_AJAX ){ echo Core::ajaxResponse( $return ); }
			return $return;

		}

		/**
		 * get the data from the database for a class
		 * only allowed if the user is admin
		 * echos json if ajaxed
		 * returns an array if forced to return
		 * @param $id string id of the class
		 * @param bool|false $forceReturn force a return if the get is not called through ajax
		 * @return array|void array if forceReturn is true echos echos json otherwise
		 */
		public function get( $id, $forceReturn = false ){
			if( $this->isLoggedIn() ) {
				$query = "SELECT * FROM users WHERE id = '$id'";

				if ( !$result = $this->db->query( $query ) ) {
					echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
					return;
				}
				$row = $result->fetch_assoc();
				$return = array(
					'id' => $row['id'],
					'username' => $row['username'],
					'isAdmin' => $row['isAdmin'],
					'active' => $row['active'],
					'lastIP' => $row['lastIP'],
					'creationDate' => $row['creationDate'],
					'latestDate' => $row['latestDate']
				);

				if ( IS_AJAX  && !$forceReturn) {
					echo Core::ajaxResponse( $return );
				} else {
					return $return;
				}
			} else {
				echo Core::ajaxResponse( array( 'error' => 'Session expired.<br>Please log in again'), false );
			}
		}

		/**
		 * this function is used to ajax the edit modal from javascript
		 * @param $id
		 */
		public function edit( $id ){
			$this->loadModules( "roles discipline" );
			//check if user can edit
			if( $this->isLoggedIn() ) {
				//get user and force return
				if ( $id != -1 ) {
					$user = $this->get( $id, true );
					$user['disciplines'] = $this->discipline->getIdsForUser( $user['id'] );
				} else {
					$user = array(
						'id' => -1,
						'username' => '',
						'isAdmin' => 0,
						'active' => 0,
						'lastIP' => '',
						'creationDate' => date( 'Y-m-d'),
						'latestDate' => '',
						'disciplines' => -1
					);
				}
				$user['creationDate'] = explode(' ',  $user['creationDate'] )[0];
				$user['latestDate'] = explode(' ',  $user['latestDate'] )[0];
				if ( $this->roles->haveAccess( 'UserView', Core::getSessionId(), $user['disciplines'] ) && IS_AJAX ) {
//				Core::debug( $user );
					$canEdit = $this->roles->haveAccess( 'UserEdit', Core::getSessionId(), $user['disciplines'] );
					$disabled = $canEdit ? '' : 'disabled=disabled';
					?>
					<div class='tabWrapper users'>
						<div class="tabs">
							<div class='tab active' data-tab='edit'>Edit</div>
							<div class='tab' data-tab='roles'>Roles</div>
							<div class='tab' data-tab='dept'>Discipline</div>
						</div>

						<div class="tabContent" data-tab="edit">
							<form>
								<ul>
									<input type="hidden" name="id" value="<?= $user['id'] ?>">
									<input name="isAdmin" type="hidden" value="<?= $user['isAdmin'] ? 'checked' : '' ?>">
									<?php if( $user['id'] == -1 ) echo '<input name="create" type="hidden" value="1">'; ?>
									<li>
										<label for="username">User name</label>
										<input name="username" type="text" value="<?= $user['username'] ?>" <?=$disabled?>>
										<span>Enter the username</span>
									</li>
									<li>
										<label for="active">Active User</label>
										<input name="active" type="checkbox" value="1" <?= $user['active'] ? 'checked' : '' ?> <?=$disabled?>>Check if the user
										should be allowed to login
										<span>Check if the user should be allowed to login</span>
									</li>
									<li>
										<label for="">Last Ip</label>
										<input disabled type="text" value="<?= $user['lastIP'] ?>">
										<span>Last used ip</span>
									</li>
									<li>
										<label for="">Latest Login</label>
										<input disabled type="date" value="<?= $user['latestDate'] ?>">
										<span>Latest login date</span>
									</li>
									<li>
										<label for="">Creation Date</label>
										<input disabled type="date" value="<?= $user['creationDate'] ?>">
										<span>Date of creation</span>
									</li>
								</ul>
							</form>
						</div>
						<div class="tabContent none" data-tab="roles">
							<div class="userRoles">
								<form>
									<?php
										$canAssign = false;
										if( $user['id'] == $_SESSION['session']['id'] && $this->roles->haveAccess( "UserRoles", Core::getSessionId(), $user['disciplines'] ) ){
											$canAssign = true;
										}
										if( $this->roles->haveAccess( "UserRoles", Core::getSessionId(), $user['disciplines'] ) ){
											$canAssign = true;
										}

										$userRoles = $this->roles->getAllForUser( $user['id'] );
										$list = array();
										foreach ( $userRoles as $r ) {
											array_push( $list, $r['id'] );
										}

										$rawRoles = $this->roles->listing( 'module,id', true );
										$roles = array();
										foreach( $rawRoles as $r ) {
											$roles[$r['name']] = $r;
										}

										$disabled = ( ( $canAssign ) ? ( '' ) : ( 'disabled' ) );
									?>
									<table id="roleTable">
										<tr>
											<th>Module</th>
											<th colspan="4" class="aligncenter">Globally</th>
											<th colspan="4" class="aligncenter">Within Discipline</th>
										</tr>
										<tr class="doubleBorder">
											<?php
												if( $this->roles->haveAccess( 'dataManage', Core::getSessionId(), -1 ) ) {
													$isChecked = array_search( $roles['dataManage']['id'], $list );
													$checkStr = ( ( $isChecked !== false ) ? ( 'checked=checked' ) : ( '' ) );
													echo "<td>DataManage<input type='checkbox' $disabled $checkStr data='all-dataManage' name='tester[all][dataManage]' value='".$roles['dataManage']['id']."'/></td>";
												} else {
													echo "<td>&nbsp;</td>";
												}
											?>

											<?php for($i = 0; $i < 2; $i++ ){ ?>
												<td class="aligncenter">View</td>
												<td class="aligncenter">Edit</td>
												<td class="aligncenter">Delete</td>
												<td class="aligncenter">Assign Roles</td>
											<?php } ?>
										</tr>
										<?php
											$arr = array( 'gView', 'gEdit', 'gDelete', 'gRoles', 'dView', 'dEdit', 'dDelete', 'dRoles'  );
											$modules = array( 'class', 'cert', 'user' );
											$titles = array( 'Classes', 'Certs', 'Users' );
											for( $i = 0; $i < count( $titles ); $i++ ){
												echo '<tr>';
												echo '<td>' . $titles[$i] . '</td>';
												for( $j = 0; $j < count( $arr ); $j++ ){
													$name = $arr[$j][0] . ucwords( $modules[$i] ) . substr( $arr[$j], 1 );
													if( isset( $roles[$name] ) ) {
														$isChecked = array_search( $roles[$name]['id'], $list );
														$checkStr = ( ( $isChecked !== false) ? ( 'checked=checked' ) : ( '' ) );
														echo "<td><label><input type='checkbox' $disabled $checkStr data='${modules[$i]}-${arr[$j]}' value='" . $roles[$name]['id'] ."' name='tester[${modules[$i]}][${arr[$j]}]' /></label></td>";
													} else{
														echo "<td>&nbsp;</td>";
													}
												}
												echo '</tr>';
											}
										?>
									</table>
								</form>
							</div>
						</div>
						<div class="tabContent none" data-tab="dept">
							<div class="userDept">
								<?php
									$canAssign = true;
									$list = array();
									$canAssign = false;
									if( $user['id'] == $_SESSION['session']['id'] && $this->roles->haveAccess( "UserEdit", Core::getSessionId(), $user['disciplines'] ) ){
										$canAssign = true;
									}
									if( $this->roles->haveAccess( "UserEdit", Core::getSessionId(), $user['disciplines'] ) ){
										$canAssign = true;
									}
									$disciplines = $this->discipline->getForUser( $user['id'] );
									$list = array();
									foreach ( $disciplines as $discipline ) {
										array_push( $list, $discipline['id'] );
									}

									echo "<form class='none'>" .
										"<input type='hidden' value='" . json_encode( $list ) . "' name='disciplines'/>" .
										"</form>"
								?>
								<ul class="listing">
									<?php
										foreach ( $disciplines as $d ) {
											echo "<li data-id='${d['id']}'>";
											echo $d['name'] . ' ' . $d['description'];
											if( $canAssign )
												echo '<img class="delete tooltip" src="' . CORE_URL . 'assets/img/delete.png" title="Delete Discipline">';
											echo "</li>";
										}
									?>
								</ul>
								<?php if( $canAssign ){?>
									<div class="add">
										<input type="button" value="Add Discipline" name="addDiscipline">
									</div>
								<?php } ?>
							</div>
						</div>

					</div>
					<?php
				} else {
					echo "<p>You do not have access to edit users</p>";
				}
			} else {
				echo "<p>Session expired. Please log in again.</p>";
			}
		}

		private function find( $username ){
			$query = "SELECT * FROM users WHERE username = '$username'";

			if ( !$result = $this->db->query( $query ) ) {
				return false;
			}
			$row = $result->fetch_assoc();
			$return = $row;

			$result->close();
			return $return;
		}

		/**
		 * save a class from the admin page
		 * only allowed if the user is admin
		 */
		public function save(){
			//new save method
			$this->loadModules( 'audit roles discipline' );
			$USER_ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], 'user' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
//			$_POST['id'] = Core::sanitize( $_POST['id'] );
//			$_POST['username'] = Core::sanitize( $_POST['username'] );
//			$_POST['active'] = Core::sanitize( $_POST['active'] );
//			$_POST['isAdmin'] = Core::sanitize( $_POST['isAdmin'] );
			$obj['error'] = ''; //initialize for later use

			if( $this->isLoggedIn() ) {
				if ( $_POST['id'] == -1 ) {
					$this->create( $_POST );
				} else {
					//save the normal stuff
					$error = false;
					if ( $this->roles->haveAccess( "UserEdit", Core::getSessionId(), json_decode( $_POST['disciplines'] ) ) ) {
						$statement = $this->db->prepare( "UPDATE users SET username=?, isAdmin=?, active=? WHERE id=? " );
						$statement->bind_param( "siii", $_POST['username'], $_POST['isAdmin'], $_POST['active'], $_POST['id'] );
						if ( $statement->execute() ) {
							$obj['msg'] = "User saved successfully.";
							$this->audit->newEvent( "Updated user: " . $_POST['username'] );
						} else {
							$error = true;
							$obj['error'] .= "An error occurred saving a user.<br>";
						}

						//deal with discipline
						$disciplines = $this->discipline->getForUser( $_POST['id'] );
						$flatDisciplines = array();
						foreach ( $disciplines as $val ) {
							array_push( $flatDisciplines, $val['id'] );
						}
						//disciplines that were posted
						$postedDisciplines = json_decode( $_POST['disciplines'] );
						//get any changes in the roles
						$addedDisciplines = Core::assocToFlat( array_diff( $postedDisciplines, $flatDisciplines ) );
						$removedDisciplines = Core::assocToFlat( array_diff( $flatDisciplines, $postedDisciplines ) );

						$disciplineError = false;
						//remove roles
						foreach ( $removedDisciplines as $item ) {
							$temp = $this->deleteDiscipline( $_POST['id'], $item );
							if ( $temp == false )
								$disciplineError = true;
						}

						//add roles
						foreach ( $addedDisciplines as $item ) {
							$temp = $this->addDiscipline( $_POST['id'], $item );
							if ( $temp == false )
								$disciplineError = true;
						}

						if ( $disciplineError ) {
							$obj['error'] .= "An error occurred saving the disciplines<br>";
							$error = true;
						}
					}
					//if editing roles
					if ( $this->roles->haveAccess( "UserEdit", Core::getSessionId(), json_decode( $_POST['disciplines'] ) ) ) { //Core::inArray( 'gUserRoles', $USER_ROLES ) ) {
						//get all the roles for user and compare to whats is in the database already
						$roles = $this->roles->getAllForUser( $_POST['id'] );
						//convert roles into a flat array
						$flatRoles = array();
						foreach ( $roles as $val ) {
							array_push( $flatRoles, $val['id'] );
						}
						//roles that were posted
						//convert to a flat array
						$postedRoles = array();
						foreach ( $_POST['tester'] as $modules ) {
							foreach ( $modules as $k => $r ) {
								array_push( $postedRoles, $r );
							}
						}

						//get any changes in the roles
						$addedRoles = Core::assocToFlat( array_diff( $postedRoles, $flatRoles ) );
						$removedRoles = Core::assocToFlat( array_diff( $flatRoles, $postedRoles ) );

						$roleError = false;
						//remove roles
						foreach ( $removedRoles as $item ) {
							$temp = $this->roles->delete( $_POST['id'], $item );
							if ( $temp == false )
								$roleError = true;
						}

						//add roles
						foreach ( $addedRoles as $item ) {
							$temp = $this->roles->add( $_POST['id'], $item );
							if ( $temp == false )
								$roleError = true;
						}

						if ( $roleError ) {
							$obj['error'] .= "An error occurred saving the roles<br>";
							$error = true;
						}

					}

					if ( $error ) {
						echo Core::ajaxResponse( $obj, false );
					} else {
						echo Core::ajaxResponse( $obj );
					}
				}
			} else {
				$obj['error'] .= "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * Create a user for the first time
		 * @param array $data this is the post data from save function
		 */
		private function create( $data ){
			$obj = array();
			if( $this->isLoggedIn() ) {
				$error = false;
				if ( $this->roles->haveAccess( "UserEdit", Core::getSessionId(), -1 ) ) {
					$statement = $this->db->prepare( "INSERT INTO users (username,isAdmin,active) VALUES (?,?,?) " );
					$statement->bind_param( "sii", $_POST['username'], $_POST['isAdmin'], $_POST['active'] );
					if ( $statement->execute() ) {
						$obj['msg'] = "User created successfully.";
						$this->audit->newEvent( "Created user: " . $_POST['username'] );
					} else {
						$error = true;
						$obj['error'] .= "An error occurred saving a user.<br>";
						echo Core::ajaxResponse( $obj, false );
						return;
					}
					//get an id
					$id = $this->db->insert_id;

					//deal with discipline
					$postedDisciplines = json_decode( $_POST['disciplines'] );

					$disciplineError = false;

					//add disciplines
					foreach ( $postedDisciplines as $item ) {
						$temp = $this->addDiscipline( $id, $item );
						if ( $temp == false )
							$disciplineError = true;
					}

					if ( $disciplineError ) {
						$obj['error'] .= "An error occurred saving the disciplines<br>";
						$error = true;
					}
				}

				//if editing roles
				if ( $this->roles->haveAccess( "UserRoles", Core::getSessionId(), -1 )) {
					//get all the roles for user and compare to whats is in the database already

					$postedRoles = json_decode( $_POST['roles'] );

					$roleError = false;

					//add roles
					foreach ( $postedRoles as $item ) {
						$temp = $this->roles->add( $id, $item );
						if ( $temp == false )
							$roleError = true;
					}

					if ( $roleError ) {
						$obj['error'] .= "An error occurred saving the roles<br>";
						$error = true;
					}
				}

				if ( $error ) {
					echo Core::ajaxResponse( $obj, false );
				} else {
					$token = $this->createResetPassword( $id, true, false );
					$obj['msg'] = "Created User successfully<br>";
					$obj['msg'] .= "Give the user this link so they can set their password<br>";
					$obj['msg'] .= '<a href="' . CORE_URL . 'users/resetPassword&token=' . $token . '">';
					$obj['msg'] .= CORE_URL . 'users/resetPassword&token=' . $token . "</a>";
					echo Core::ajaxResponse( $obj );
				}
			} else {
				$obj['error'] .= "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * delete a class from the admin page
		 * only allowed if the user is admin
		 */
		public function delete(){
			$this->loadModules( 'roles audit discipline' );
//			$ROLES = $this->roles->getRolesByModule( $_SESSION['session']['id'], 'user' );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if( $this->isLoggedIn() ) {
				$target = $this->discipline->getIdsForUser( $_POST['id'] );
				if ( $this->roles->haveAccess( 'UserDelete', Core::getSessionId(), $target ) && $this->isLoggedIn() ) {
					$query = "SELECT username FROM users WHERE id = '${_POST['id']}'";
					$event = '';
					if ( !$result = $this->db->query( $query ) ) {
						$event = $_POST['id'];
					}
					$row = $result->fetch_assoc();
					$event = $row['username'];

					$statement = $this->db->prepare( "DELETE FROM users WHERE id=?" );
					$statement->bind_param( "s", $_POST['id'] );



					if ( $statement->execute() ) {
						//delete any roles they have and disciplines
						$this->deleteAllDisciplines( $_POST['id'] );
						$this->roles->deleteAll( $_POST['id'] );

						$obj['msg'] = "Deleted successfully.";
						$this->audit->newEvent( "Deleted user: " . $event );
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = $statement->error;
						echo Core::ajaxResponse( $obj, false );
					}
				} else {
					$obj['error'] = "Insufficient permissions to delete users";
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * create a reset password token
		 * @param $id
		 * @param bool $forceReturn
		 * @param bool $noLog
		 * @return string
		 */
		public function createResetPassword( $id, $forceReturn = false, $noLog = false ){
			$this->loadModules( 'audit roles discipline' );
			if( $this->isLoggedIn() ) {
				$hasToken = false;
				$id = Core::sanitize( $id );
				$targetDisciplines = $this->discipline->getIdsForUser( $id );
				if( $this->roles->haveAccess( 'UserEdit', Core::getSessionId(), $targetDisciplines ) ) {
					//check if token already exists
					$query = "SELECT * FROM tokens WHERE forUser = " . (int)$id . " AND used = 0";
					if ( $result = $this->db->query( $query ) ) {
						$row = null;
						//if there was a user matching
						if ( $result->num_rows == 1 ) {
							$row = $result->fetch_assoc();
							$token = $row['token'];
							$hasToken = true;
						}
					}

					if ( !$hasToken ) {
						//create the token since none exist
						$token = Core::userFriendlyId( 15 );
						$statement = $this->db->prepare( "INSERT INTO tokens(token, forUser, byUser) VALUES (?,?,?)" );
						$statement->bind_param( "sii", $token, $id, $_SESSION['session']['id'] );
						if ( $statement->execute() ) {
							$obj['msg'] = "Give the user this link so they can set their password<br>";
							$obj['msg'] .= '<a href="' . CORE_URL . 'users/resetPassword&token=' . $token . '">';
							$obj['msg'] .= CORE_URL . 'users/resetPassword&token=' . $token . "</a>";
							if ( !$noLog ) {
								$user = $this->get( $id, true );
								$this->audit->newEvent( "Reset password for user: " . $user['username'] );
							}
							if ( !$forceReturn ) {
								echo Core::ajaxResponse( $obj );
							} else {
								return $token;
							}
						} else {
							$obj['error'] = $statement->error;
							if ( !$forceReturn ) {
								echo Core::ajaxResponse( $obj, false );
							} else {
								return $token;
							}
						}
					} else {
						$obj['msg'] = "User already has an existing unused token<br>Give the user this link so they can set their password<br>";
						$obj['msg'] .= '<a href="' . CORE_URL . 'users/resetPassword&token=' . $token . '">';
						$obj['msg'] .= CORE_URL . 'users/resetPassword&token=' . $token . "</a>";
						if ( !$forceReturn ) {
							echo Core::ajaxResponse( $obj );
						} else {
							return $token;
						}
					}
				}
			}
		}

		/**
		 * displays the reset password
		 */
		public function resetPassword(){
			Core::queueStyle( 'assets/css/reset.css' );
			Core::queueStyle( 'assets/css/ui.css' );
			//put the data onscreen
			include( CORE_PATH . 'pages/resetPassword.php' );
		}

		/**
		 * verify that the user and the token match up
		 */
		public function verifyToken(){
			$_POST = Core::sanitize( $_POST );
			$query = "SELECT * FROM tokens WHERE token = '" . $_POST['token'] . "'";

			$obj = array();
			if( !$result = $this->db->query($query) ){
				$obj['error'] = "An error occurred please try again";
				echo Core::ajaxResponse( $obj, false );
				return;
			}

			$row = null;
			//if there was a user matching
			if( $result->num_rows == 1 ){
				$row = $result->fetch_assoc();
				$user = $this->find( $_POST['user'] );
				if( $row['forUser'] == $user['id'] ){
					if( $row['used'] == 0 ){
						$obj['msg'] = 'Enter a new password';
						echo Core::ajaxResponse( $obj );
					} else {
						$obj['error'] = 'Token has already been used';
						echo Core::ajaxResponse( $obj, false );
					}
				} else {
					$obj['error'] = "Token doesn't match for the username";
					echo Core::ajaxResponse( $obj, false );
				}
				$result->close();
			} else {
				$obj['error'] = "Invalid token";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 *
		 */
		public function setPassword(){
			$_POST = Core::sanitize($_POST);
			$user = $this->find( $_POST['user'] );
			$obj = array();
			if( $user ){
				$statement = $this->db->prepare( "UPDATE users SET password=? WHERE id = ?" );
				$pass = $this->hashPassword( $_POST['password'], $_POST['user'] );
				$uid = (int)$user['id'];
				$statement->bind_param( "si", $pass, $uid );
				if ( $statement->execute() ) {
					$obj['msg'] = "Password changed successfully";
					$obj['redirect'] = 'login';
					echo Core::ajaxResponse( $obj );
					if ( isset( $_POST['token'] ) ) {
						$statement = $this->db->prepare( "UPDATE tokens SET used=1 WHERE token = ?" );
						$statement->bind_param( "s", $_POST['token'] );
						$statement->execute();
						$statement->close();
					}
				} else {
					$obj['error'] = "An error occurred please try again";
					echo Core::ajaxResponse( $obj );
				}
			} else{
				$obj['error'] = 'Username does not exist';
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * has the password and the username check the database and login
		 */
		public function login(){
			//load translations
			$lang = new Lang( Lang::getCode() );
			//clean the post of any injects
			$_POST = Core::sanitize( $_POST );
			//return array
			$obj = array();

			//check we can even attempt
			$query = "SELECT * FROM loginBan WHERE ip = '" . Core::getIp() ."'";
			if( $result = $this->db->query($query) ){
				if( $result->num_rows > 0 ){
					$row = $result->fetch_assoc();
					if( $row['expires'] > time() ){
						//can't run yet still blocked
						$diff = ( $row['expires'] - time() ) / 60 ;
						$obj['error'] = "You have put in your password incorrectly to many times<br>You can try again in " . ceil( $diff ) . " minutes.";
						echo Core::ajaxResponse( $obj, false );
						return;
					}
				}
			}

			//hash the password and username to salt the password
			$passwordHash = $this->hashPassword( $_POST['password'], $_POST['user'] );
			//create the query to check if the user exists and to verify hashs
			$query = "SELECT * FROM users WHERE username = '${_POST['user']}' AND password = '$passwordHash'";


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
					$obj['error'] = $lang->o( 'ajaxLoginDisabled' ); //"Account has been deactivated.";
					echo Core::ajaxResponse( $obj, false );
				} else {
					//if user is active then log in
					$obj['msg'] = $lang->o( 'ajaxLogin' ); //'Successfully logged in.';
					//set redirect url
					$obj['redirect'] = 'admin';
					//update date and ip
					$query = "UPDATE users SET lastIP = '${_SERVER['REMOTE_ADDR']}', latestDate = CURRENT_TIMESTAMP WHERE id = ${row['id']}";
					if( $this->db->query( $query ) === true ) {
						error_log( 'Database error: ' . $this->db->error . ' [users.php login()]');
					}
					//echo the reponse to user
					echo Core::ajaxResponse( $obj );
					//update session to reflect login status
					$_SESSION['session'] = array();
					$_SESSION['session']['username'] = $row['username'];
					$_SESSION['session']['id'] = $row['id'];
					$_SESSION['session']['expires'] = time() + ( 60 * 10 ); //session set for 10 minutes
					$_SESSION['session']['started'] = time();
					$this->deleteRecord( 'loginBan', 'ip = "' .Core::getIp() . '"' );
					unset( $_SESSION['loginAttempts'] );
					//load audit module and report the login
					$this->loadModule( 'audit' );
					$this->audit->newEvent( "Logged into administration" );
				}
			} else{
				//you entered a wrong password
				if( isset( $_SESSION['loginAttempts'] ) ){
					$_SESSION['loginAttempts']++;
				} else{
					$_SESSION['loginAttempts'] = 0;
				}
				$this->upsertRecord( 'loginBan', 'ip = "' . Core::getIp() . '"', array(
					'attempt' => $_SESSION['loginAttempts'],
					'expires' => max( time(), time() + ( 120 * $_SESSION['loginAttempts'] ) ), //2 minutes times attempts
					'ip' => Core::getIp(),
					'created' => time()
				) );
				$obj['error'] = $lang->o( 'ajaxLoginIncorrect' ); //"Your username or email is incorrect";
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
				if( $_SESSION['session']['expires'] <= time() && !NO_TIMEOUT ){
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
				if ( $_SESSION['session']['expires'] > time() || NO_TIMEOUT) {
					return true;
				}
			}
			return false;
		}

		/**
		 * check if user is a global admin used to create users and manage them
		 */
		public function isAdmin(){
			if( $this->isLoggedIn() ){
				$query = "SELECT id, isAdmin FROM users WHERE username = '" . $_SESSION['session']['username'] . "'";
				if( !$result = $this->db->query($query) ){
					return false;
				}
				$row = null;
				//if there was a user matching
				if( $result->num_rows == 1 ) {
					$row = $result->fetch_assoc();
					if( $row['isAdmin'] == 1 ){
						return true;
					}
				}
				$result->close();
			}

			return false;
		}

		/**
		 * Displays modal for the add discipline in the edit user modal
		 */
		public function modalAddDiscipline(){
			$this->loadModule( 'discipline' );
			//get disciplines
			$disciplines = $this->discipline->listing( -1, true );
			?>
			<form>
				<ul>
					<li>
						<label for="roles">Roles</label>
						<select name="class">
							<?php
								foreach( $disciplines['listing'] as $discipline ){
									echo '<option value="' . $discipline['id'] . '">' . $discipline['name'] . ' ' . $discipline['description'] . '</option>';
								}
							?>
						</select>
						<span>Pick a discipline to add</span>
					</li>
				</ul>
			</form>
			<?php

		}

		/**
		 * Add a discipline to a user
		 * @param int $uid user id
		 * @param int $did discipline id
		 * @return bool
		 */
		public function addDiscipline( $uid, $did ){
			$this->loadModules( 'audit discipline' );
			$user = $this->get( $uid, true );
			$discipline = $this->discipline->get( $did, true );
			$statement = $this->db->prepare( "INSERT INTO userXdiscipline( userId, disciplineId ) VALUES(?,?)" );
			$statement->bind_param( "ii", $uid, $did );
			if( $statement->execute() ){
				$this->audit->newEvent( "Deleted " . $discipline['description'] . " role from " . $user['username'] );
				return true;
			}
			return false;
		}

		/**
		 * Deleting a discipline from a user
		 * @param int $uid
		 * @param int $did
		 * @return bool
		 */
		public function deleteDiscipline( $uid, $did ){
			$this->loadModules( 'audit discipline' );
			$user = $this->get( $uid, true );
			$discipline = $this->discipline->get( $did, true );
			$statement = $this->db->prepare( "DELETE FROM userXdiscipline where userId = ? AND disciplineId = ?" );
			$statement->bind_param( "ii", $uid, $did );
			if( $statement->execute() ){
				$this->audit->newEvent( $_SESSION['session']['username'] . " deleted " . $discipline['description'] . " from " . $user['username'] );
				return true;
			}
			return false;
		}

		/**
		 * Deleting all discipline from a user
		 * @param int $uid user id
		 */
		public function deleteAllDisciplines( $uid ){
			$this->loadModules( 'audit discipline' );
			$disciplines = $this->discipline->getIdsForUser( $uid );
			foreach ( $disciplines as $discipline ) {
				$statement = $this->db->prepare( "DELETE FROM userXdiscipline where userId = ? AND disciplineId = ?" );
				$statement->bind_param( "ii", $uid, $discipline );
				$statement->execute();
			}
		}

		/**
		 * creates the complex hash for user passwords
		 * @param string $inPass string of password
		 * @param string $inUser string of username
		 * @return string
		 */
		private function hashPassword( $inPass, $inUser ){
			$password[0] = hash( 'sha512', substr( $inPass, 0, 64 ) );
			$password[1] = hash( 'sha512', substr( $inPass, 64 ) );
			$passwordHash = hash( 'sha512', $password[0] . $password[1] . $inUser );
			return $passwordHash;
		}

	}