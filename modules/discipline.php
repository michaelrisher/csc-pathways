<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 11/1/2017
	 * Time: 18:06
	 */
	class discipline extends Main{
		private $moduleName = 'discipline';

		/**
		 * get the listing of disciplines. didnt see fit to make this its own module
		 * @param bool|false $forceReturn
		 * @return array
		 */
		public function listing( $forceReturn = false){
			//to kinda standardize it for later needs
			$query = "SELECT * FROM disciplines";

			if ( !$result = $this->db->query( $query ) ) {
				echo( 'There was an error running the query [' . $this->db->error . ']' );
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				$a = array(
					'id' => $row['id'],
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
		 * Gets a single discipline
		 * @param int $did
		 * @param bool|false $forceReturn
		 * @return array|void
		 */
		public function get( $did, $forceReturn = false ){
			$query = "SELECT * FROM disciplines WHERE id = '$did'";

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
		 * edit action for discipline
		 * @param $id
		 */
		public function edit( $id = -1){
			$this->loadModules( 'users roles');
			if ( $this->users->isLoggedIn() ) {
				if ( $this->roles->haveAccess( 'dataManage', Core::getSessionId(), -1 ) ) {
					if( $id != -1 ){
						$discipline = $this->get( $id, true );
						$discipline['create'] = 0;
						$discipline['usage'] = $this->getUsage( $id );
					} else{
						$discipline = array(
							'id' => $id,
							'name' => '',
							'description' => '',
							'usage' => array( 'classes' => 0, 'certs' => 0, 'users' => 0 ),
							'create' => 1
						);
					}
					?>
					<p>* fields are required</p>
					<form>
						<?php if( $discipline['create'] ){?>
						<input type="hidden" name="create" value="<?=$discipline['create']?>">
						<?php } ?>
						<input type="hidden" name="id" value="<?=$discipline['id']?>">
						<ul>
							<li>
								<label for="name">Name*</label>
								<input name="name" type="text" value="<?=$discipline['name']?>">
								<span>Enter a short name for the discipline</span>
							</li>
							<li>
								<label for="description">Description*</label>
								<input name="description" type="text" value="<?=$discipline['description']?>">
								<span>Enter a description for the discipline</span>
							</li>
							<li>
								<label for="">Classes with discipline</label>
								<input disabled type="number" value="<?=$discipline['usage']['classes']?>">
								<span>Classes with discipline</span>
							</li>
							<li>
								<label for="">Certificates with discipline</label>
								<input disabled type="number" value="<?=$discipline['usage']['certs']?>">
								<span>Certificates with discipline</span>
							</li>
							<li>
								<label for="">Users with discipline</label>
								<input disabled type="number" value="<?=$discipline['usage']['users']?>">
								<span>Classes with discipline</span>
							</li>
						</ul>
					</form><?php
				 }else {
					echo "<p>You do not have access to edit classes</p>";
				}
			} else {
				echo "<p>Session expired. Please log in again.</p>";
			}
		}

		public function save( $id ){
			$this->loadModules( 'roles users audit');
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if( $this->users->isLoggedIn() ){
				if( $this->roles->haveAccess( 'dataManage', Core::getSessionId(), -1 ) ){
					$statement = null;
					$msg = '';
					if( isset( $_POST['create'] ) || $_POST['id'] == -1 ){
						$query = "INSERT INTO disciplines (name,description) VALUES (?,?)";
						$statement = $this->db->prepare( $query );
						$statement->bind_param( 'ss', $_POST['name'], $_POST['description'] );
						$msg = 'Created';
					} else {
						$query = "UPDATE disciplines SET name=?, description=? WHERE id=?;";
						$statement = $this->db->prepare( $query );
						$statement->bind_param( 'ssi', $_POST['name'], $_POST['description'], $_POST['id'] );
						$msg = 'Saved';
					}

					if( $statement->execute() ){
						if( isset( $_POST['create'] ) || $_POST['id'] == -1 ){
							$lastId = $this->db->insert_id;
							$obj['id'] = $lastId;
						}
						$obj['msg'] = $msg . ' discipline successfully';
						$this->audit->newEvent( $msg . ' discipline: ' . $_POST['name'] );
						echo Core::ajaxResponse( $obj );
					} else{
						error_log( 'discipline.php save: ' . $this->db->error );
						$obj['error'] = "An error occurred";
						echo Core::ajaxResponse( $obj, false );
					}
				} else {
					$obj['error'] = "Insufficient permissions to edit disciplines";
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = "Session expired. Please log in again.";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * deletes items from the database
		 * @param int $id id for the item to delete
		 */
		public function delete( $id ){
			$this->loadModules( 'users roles audit');
			$obj = array();
			if( $this->users->isLoggedIn() ){
				if( $this->roles->haveAccess( 'dataManage', Core::getSessionId(), -1 ) ) {
					$id = Core::sanitize( $id );
					$discipline = $this->get( $id, true );
					$query = "DELETE FROM disciplines WHERE id=?";
					$state = $this->db->prepare( $query );
					$state->bind_param( 'i', $id );
					if( $state->execute() ){
						$obj['msg'] = "Deleted successfully";
						$this->audit->newEvent( "Deleted discipline: " . $discipline['name'] );
						echo Core::ajaxResponse( $obj );
					} else{
						error_log( 'discipline.php delete: ' . $this->db->error );
						$obj['error'] = "An error occurred";
						echo Core::ajaxResponse( $obj, false );
					}
				} else {
					$obj['error'] = 'Insufficient permissions to edit disciplines';
					echo Core::ajaxResponse( $obj, false );
				}
			} else {
				$obj['error'] = 'Session expired. Please log in again.';
				echo Core::ajaxResponse( $obj, false );
			}
		}

		public function getUsage( $did ){
			$usage = array();
			$query = "SELECT count(*) as count from classes where discipline = $did";
			if ( $result = $this->db->query( $query ) ) {
				$row = $result->fetch_assoc();
				$usage['classes'] = $row['count'];
				$result->close();
			}
			$query = "SELECT count(*) as count from certificateList where discipline = $did";
			if ( $result = $this->db->query( $query ) ) {
				$row = $result->fetch_assoc();
				$usage['certs'] = $row['count'];
				$result->close();
			}
			$query = "SELECT count(*) as count from userXdiscipline where disciplineId = $did";
			if ( $result = $this->db->query( $query ) ) {
				$row = $result->fetch_assoc();
				$usage['users'] = $row['count'];
				$result->close();
			}

			return $usage;
		}

		/**
		 * gets all the discipline for a user
		 * @param int $uid user id
		 * @return array|void
		 */
		public function getForUser( $uid ){
			$query = <<<EOD
SELECT
    users.id as userId,
    disciplines.id as disciplineId,
    disciplines.name,
    disciplines.description
FROM
    userXdiscipline,
    users,
    disciplines
WHERE
    userXdiscipline.userId = users.id AND disciplines.id = userXdiscipline.disciplineId AND users.id = $uid
EOD;

			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, array(
					'id' => $row['disciplineId'],
					'name' => $row['name'],
					'description' => $row['description']
				) );
			}
			return $return;
		}

		public function getIdsForUser( $uid ){
			$query = <<<EOD
SELECT
    users.id as userId,
    disciplines.id as disciplineId
FROM
    userXdiscipline,
    users,
    disciplines
WHERE
    userXdiscipline.userId = users.id AND disciplines.id = userXdiscipline.disciplineId AND users.id = $uid
EOD;

			if( !$result = $this->db->query( $query ) ){
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return;
			}

			$return = array();
			while ( $row = $result->fetch_assoc() ) {
				array_push( $return, $row['disciplineId'] );
			}
			return $return;
		}
	}