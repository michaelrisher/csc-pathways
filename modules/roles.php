<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 9/27/2017
	 * Time: 18:49
	 */
	class roles extends Main {

		/**
		 *
		 * @param string $order
		 * @return array
		 */
		public function listing( $order = 'id', $forceReturn = false ) {
			//to kinda standardize it for later needs
			$query = "SELECT * FROM roles ORDER BY $order";

			if ( !$result = $this->db->query( $query ) ) {
				echo( 'There was an error running the query [' . $this->db->error . ']' );
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				$a = array(
					'id' => $row['id'],
					'module' => $row['module'],
					'name' => $row['name'],
					'description' => $row['description']
				);
				array_push( $return, $a );
			}


			if ( IS_AJAX && !$forceReturn ) {
				echo Core::ajaxResponse( $return );
			}
			return $return;
		}

		/**
		 * get a single role with a role id
		 * @param $id int id of the role
		 * @param bool|false $forceReturn
		 * @return array|void
		 */
		public function get( $id, $forceReturn = false ){
			$query = "SELECT * FROM roles WHERE id = '$id'";

			if ( !$result = $this->db->query( $query ) ) {
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}
			$row = $result->fetch_assoc();
			$return = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'description' => $row['description']
			);

			if ( IS_AJAX  && !$forceReturn) {
				echo Core::ajaxResponse( $return );
			} else {
				return $return;
			}
		}

		/**
		 * Get all the permissions for a user
		 * @param $uid
		 * @return array|void
		 */
		public function getAllForUser( $uid ){
			$query = <<<EOD
SELECT
    users.id as userId,
    roles.id as roleId,
    roles.name,
    roles.description
FROM
    userXroles,
    users,
    roles
WHERE
    userXroles.userId = users.id AND roles.id = userXroles.roleId AND users.id = $uid
ORDER BY
    roles.module ASC, roles.id DESC
EOD;

			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, array(
					'id' => $row['roleId'],
					'name' => $row['name'],
					'description' => $row['description']
				) );
			}

			return $return;
		}

		/**
		 * gets all the roles for a user based on module in a flat array of the role name
		 * eg ['gClassView', 'gClassEdit' ]
		 * return null if has no roles
		 * @param int $userId id for the user
		 * @param string $module name of module ie ( class, cert, user )
		 * @return array|null
		 */
		public function getRolesByModule( $userId, $module ){
			$query = <<<EOD
SELECT
    users.id as userId,
    roles.id as roleId,
    roles.module,
    roles.name,
    roles.description
FROM
    userXroles,
    users,
    roles
WHERE
    userXroles.userId = users.id AND roles.id = userXroles.roleId AND users.id = $userId AND roles.module LIKE '%$module%'
ORDER BY
    roles.module ASC, roles.id DESC
EOD;
			if ( !$result = $this->db->query( $query ) ) {
				return array();
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, $row['name'] );
			}

			return $return;
		}

		/**
		 * this function assumes that you have gUserRole || dUserRole
		 * @param $uid
		 * @param $rid
		 * @return bool
		 */
		public function delete( $uid, $rid ){
			$this->loadModule( 'audit' );
			$this->loadModule( 'users' );
			$user = $this->users->get( $uid, true );
			$role = $this->get( $rid, true );
			$statement = $this->db->prepare( "DELETE FROM userXroles where userId = ? AND roleId = ?" );
			$statement->bind_param( "ii", $uid, $rid );
			if( $statement->execute() ){
				$this->audit->newEvent( $_SESSION['session']['username'] . " deleted " . $role['description'] . " from " . $user['username'] );
				return true;
			}
			return false;
		}

		/**
		 * Add a userRole
		 * @param $uid
		 * @param $rid
		 * @return bool
		 */
		public function add( $uid, $rid ){
			$this->loadModule( 'audit' );
			$this->loadModule( 'users' );
			$user = $this->users->get( $uid, true );
			$role = $this->get( $rid, true );
			$statement = $this->db->prepare( "INSERT INTO userXroles( userId, roleId ) VALUES(?,?)" );
			$statement->bind_param( "ii", $uid, $rid );
			if( $statement->execute() ){
				$this->audit->newEvent( "Deleted " . $role['description'] . " role from " . $user['username'] );
				return true;
			}
			return false;
		}

		/**
		 * check if user has a role
		 * @param $userId int id for the user
		 * @param $roleName string name of the role
		 * @return bool
		 */
		public function doesUserHaveRole( $userId, $roleName ){
			$query = <<<EOD
SELECT
    users.id as userId,
    roles.id as roleId,
    roles.name,
    roles.description
FROM
    userXroles,
    users,
    roles
WHERE
    userXroles.userId = users.id AND roles.id = userXroles.roleId AND users.id = $userId AND roles.name = '$roleName'
ORDER BY
    roles.module ASC, roles.id DESC
EOD;
			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return false;
			}

			if( $result->num_rows == 1 ){
				//double check
				$row = $result->fetch_assoc();
				if( $row['name'] == $roleName ){
					return true;
				}
			}

			return false;
		}

		public function modalUserAddRole(){
			$roles = $this->listing( 'module', true );
			?>
			<form>
				<ul>
					<li>
						<label for="roles">Roles</label>
						<select name="class">
							<?php
								$module='';
								$firstRun = true;
								foreach( $roles as $role ){
									if( $module != $role['module'] ){
										if( !$firstRun ){
											echo '</optgroup>';
										}
										echo '<optgroup label="' . ucwords( $role['module'] ). ' Roles">';
										$module = $role['module'];
									}
									echo '<option value="' . $role['id'] . '">' . $role['description'] . '</option>';
									$firstRun = false;
								}
							?>
						</select>
						<span>Pick a role to add</span>
					</li>
				</ul>
			</form>
			<?php
		}

		/**
		 * @param string $perm string of the permission attempting to run
		 * @param int $userId users id
		 * @param int $disciplineId discipline of the object in question
		 * @return bool
		 */
		public function haveAccess( $perm, $userId, $disciplineId, $fullRoles = null, $disciplines = null ){
			$this->loadModule( 'discipline' );
			$fullRoles = ( ( isset( $fullRoles ) ) ? ( $fullRoles ) : ( $this->getAllForUser( $userId ) ) );
			$roles = array();
			foreach ( $fullRoles as $role ) {
				array_push( $roles, $role['name'] );
			}

			$disciplines = ( ( isset( $disciplines ) ) ? ( $disciplines ) : ( $this->discipline->getIdsForUser( $userId ) ) );;

			if( Core::inArray( 'g' . $perm, $roles ) ){
				return true;
			} else {
				if( Core::inArray( 'd' . $perm, $roles ) ){
					if ( $disciplineId == -1 ) {
						return true;
					} else if( Core::inArray( $disciplineId, $disciplines ) ){
						return true;
					}
				}
			}
			return false;
		}
	}