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
		 * check if user has a role
		 * @param $userId int id for the user
		 * @param $roleName string name of the role
		 */
		public function doesUserHaveRole( $userId, $roleName ){

		}

		public function modalUserAddRole(){
			$roles = $this->listing( 'module', true );
//			Core::debug( $roles );
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
<!--							<option value=''>test</option>-->
						</select>
						<span>Pick a class to add</span>
					</li>
				</ul>
			</form>
			<?php
		}
	}