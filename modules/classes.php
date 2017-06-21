<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:15
	 */
	class classes extends Main{

		/**
		 * get a simple listing of classes only id and title are returned
		 * only allowed if the user is admin
		 * @param int $page not used yet
		 * @return array
		 */
		public function listing( $page = 1 ){
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM classes ORDER BY sort";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'title' => $row['title']
					);
					array_push( $return, $a );
				}
				if( IS_AJAX ){ echo Core::ajaxResponse( $return ); }
				return $return;
			}
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
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM classes WHERE id = '$id'";

				if ( !$result = $this->db->query( $query ) ) {
//					echo( 'There was an error running the query [' . $this->db->error . ']' );
					echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
					return;
				}
				$row = $result->fetch_assoc();
				$return = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'units' => $row['units'],
						'transfer' => $row['transfer'],
						'advisory' => $row['advisory'],
						'prereq' => $row['prereq'],
						'coreq' => $row['coreq'],
						'description' => $row['description']
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
		 * save a class from the admin page
		 * only allowed if the user is admin
		 */
		public function save(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if( $this->users->isLoggedIn() ) {
				$classes = array();
				preg_match_all("/~.+?~/", $_POST['advisory'], $classes );
				for( $j = 0; $j < count( $classes[0] ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[0][$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['advisory'] = str_replace( $classes[0][$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['advisory'] );
				}

				$classes = array();
				preg_match_all("/~.+?~/", $_POST['prereq'], $classes );
				for( $j = 0; $j < count( $classes[0] ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[0][$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['prereq'] = str_replace( $classes[0][$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['prereq'] );
				}

				$classes = array();
				preg_match_all("/~.+?~/", $_POST['coreq'], $classes );
				for( $j = 0; $j < count( $classes[0] ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['coreq'] = str_replace( $classes[$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['coreq'] );
				}

				$statement = $this->db->prepare("UPDATE classes SET title=?, units=?, transfer=?, prereq=?, advisory=?, coreq=?, description=? WHERE id=?");
				$statement->bind_param( "sdssssss", $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['coreq'], $_POST['description'], $_POST['id']);
				if( $statement->execute() ){
					$obj['msg'] = "Saved successfully.";
					$this->audit->newEvent( "Updated class: " . $_POST['title'] );
					echo Core::ajaxResponse( $obj );
				} else{
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else{
				$obj['error'] = "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * delete a class from the admin page
		 * only allowed if the user is admin
		 */
		public function delete(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST, true );
			if( $this->users->isLoggedIn() ) {
				$query = "SELECT title FROM classes WHERE id = '${_POST['id']}'";
				$event = '';
				if ( !$result = $this->db->query( $query ) ) {
					$event = $_POST['id'];
				}
				$row = $result->fetch_assoc();
				$event = $row['title'];

				$statement = $this->db->prepare("DELETE FROM classes WHERE id=?");
				$statement->bind_param( "s", $_POST['id']);
				if( $statement->execute() ){
					$obj['msg'] = "Deleted successfully.";
					$this->audit->newEvent( "Deleted class: " . $event );
					echo Core::ajaxResponse( $obj );
				} else{
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else{
				$obj['error'] = "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * Create a class from the admin page
		 * only allowed if the user is admin
		 */
		public function create(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if( $this->users->isLoggedIn() ) {
				$classes = array();
				preg_match("/~.+?~/", $_POST['advisory'], $classes );
				for( $j = 0; $j < count( $classes ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['advisory'] = str_replace( $classes[$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['advisory'] );
				}

				$classes = array();
				preg_match("/~.+?~/", $_POST['prereq'], $classes );
				for( $j = 0; $j < count( $classes ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['prereq'] = str_replace( $classes[$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['prereq'] );
				}

				$classes = array();
				preg_match("/~.+?~/", $_POST['coreq'], $classes );
				for( $j = 0; $j < count( $classes ); $j++ ){
					$classData = $this->get( str_replace( '~', '', $classes[$j] ), true );
					$code = explode( ' - ', $classData['title'] );
					$_POST['coreq'] = str_replace( $classes[$j], '<a class="fakeLink" data-to="class" data-code="' . $classData['id'] . '">' . $code[0] .'</a>', $_POST['coreq'] );
				}

				$statement = $this->db->prepare("INSERT INTO classes(id, title, units, transfer, prereq, advisory, coreq, description) VALUES (?,?,?,?,?,?,?,?)");
				$statement->bind_param( "ssdsssss", $_POST['id'], $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['coreq'], $_POST['description'] );
				if( $statement->execute() ){
					$obj['msg'] = "Created successfully.";
					echo Core::ajaxResponse( $obj );
					$this->audit->newEvent( "Created class: " . $_POST['title'] );
				} else{
					$obj['error'] = $statement->error;
					echo Core::ajaxResponse( $obj, false );
				}
			} else{
				$obj['error'] = "Session expired.<br>Please log in again";
				echo Core::ajaxResponse( $obj, false );
			}
		}

		/**
		 * adding a sort column keeping for future reference
		 * @deprecated
		 */
		private function update(){
			$classes = $this->listing();
			foreach ( $classes as $class ) {
				$data = $this->get( $class['id'] );
				$code = explode( ' - ', $data['title'] )[0];
				$code = preg_replace( '/\D/', '', $code );

				$statement = $this->db->prepare("UPDATE classes SET sort=? WHERE id=?");
				$statement->bind_param( "is", $code, $data['id'] );
				if( $statement->execute() ){
					echo $data['title'] . ' sort updated ' . $code . '<br>';
				}
			}

		}
	}