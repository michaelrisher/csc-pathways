<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 6/6/2017
	 * Time: 12:22
	 */
	class certs extends Main {
		public function listing( $page = 1 ) {
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM certificateList ORDER BY id";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'code' => $row['code'],
						'description' => $row['description']
					);
					array_push( $return, $a );
				}
				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				}
				return $return;
			}
		}

		public function edit( $id ) {
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				Core::queueStyle( 'assets/css/reset.css' );
				Core::queueStyle( 'assets/css/ui.css' );
				Core::queueStyle( 'assets/css/froala_editor.css' );
				Core::queueStyle( 'assets/css/froala_style.css' );

				//put the data onscreen
				$data = $this->get( $id );
				$data['categories'] = $this->listCategories();

				include( CORE_PATH . 'pages/certEdit.php' );

			}
		}


		public function get( $id, $forceReturn = false ) {
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				$query = <<<EOD
SELECT
    certificateList.id,
    certificateList.code,
    certificateList.hasAs,
    certificateList.hasCe,
    certificateList.units,
    certificateList.category,
    certificateList.description AS title,
    certificateList.sort,
    certificateData.description,
    certificateData.elo,
    certificateData.schedule
FROM
    `certificateList`
INNER JOIN enumCategories ON certificateList.category = enumCategories.id
INNER JOIN certificateData ON certificateList.id = certificateData.cert
WHERE certificateList.id = ${id}
EOD;
				if ( !$result = $this->db->query( $query ) ) {
					echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
					return null;
				}
				$row = $result->fetch_assoc();
				$return = $row;

				if ( IS_AJAX && !$forceReturn ) {
					echo Core::ajaxResponse( $return );
				} else {
					return $return;
				}
			} else {
				echo Core::ajaxResponse( array( 'error' => 'Session expired.<br>Please log in again' ), false );
			}
		}

		public function listCategories(){
			$this->loadModule( 'users' );
			if ( $this->users->isLoggedIn() ) {
				$query = "SELECT * FROM enumCategories";//remove limit for a time LIMIT $page,50

				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
				}

				$return = array();
				while ( $row = $result->fetch_assoc() ) {
					$a = array(
						'id' => $row['id'],
						'category' => $row['category'],
					);
					array_push( $return, $a );
				}
				if ( IS_AJAX ) {
					echo Core::ajaxResponse( $return );
				}
				return $return;
			}
		}
	}

	/*
	 *SELECT
    certificateList.id,
    certificateList.code,
    certificateList.hasAs,
    certificateList.hasCe,
    certificateList.units,
    certificateList.category,
    certificateList.description AS title,
    certificateList.sort,
    certificateData.description,
    certificateData.elo,
    certificateData.schedule
FROM
    `certificateList`
INNER JOIN enumCategories ON certificateList.category = enumCategories.id
INNER JOIN certificateData ON certificateList.id = certificateData.cert
	 */