<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/5/2017
	 * Time: 10:15
	 */
	class classes extends Main{

		public function listing( $page = 1 ){
			$this->loadModule( 'users' );
			if( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM classes LIMIT $page,50";

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'title' => $row['title']
//						'units' => $row['units'],
//						'transfer' => $row['transfer'],
//						'prereq' => $row['prereq'],
//						'advisory' => $row['advisory'],
//						'description' => $row['description']
					);
					array_push( $return, $a );
				}
				return $return;
			}
		}

		public function get( $id ){
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
						'prereq' => $row['prereq'],
						'advisory' => $row['advisory'],
						'description' => $row['description']
				);

				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				} else {
					return $return;
				}
			} else {
				echo Core::ajaxResponse( array( 'error' => 'Session expired.<br>Please log in again'), false );
			}
		}

		public function save(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if( $this->users->isLoggedIn() ) {
				$statement = $this->db->prepare("UPDATE classes SET title=?, units=?, transfer=?, prereq=?, advisory=?, description=? WHERE id=?");
				$statement->bind_param( "sisssss", $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['description'], $_POST['id']);
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


		public function delete(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
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

		public function create(){
			$this->loadModule( 'users' );
			$this->loadModule( 'audit' );
			$obj = array();
			$_POST = Core::sanitize( $_POST );
			if( $this->users->isLoggedIn() ) {
				$statement = $this->db->prepare("INSERT INTO classes(id, title, units, transfer, prereq, advisory, description) VALUES (?,?,?,?,?,?,?)");
				$statement->bind_param( "ssissss", $_POST['id'], $_POST['title'], $_POST['units'], $_POST['transfer'], $_POST['prereq'], $_POST['advisory'], $_POST['description'] );
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
	}