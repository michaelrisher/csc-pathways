<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: Michael Risher
	 * Date: 10/26/2017
	 * Time: 12:00
	 */
	class update extends Main{
		/**
		 * updates the class table to change the id to an auto increment number.
		 * have to search for usage
		 * 1. in class data table
		 * 2. in cert data table
		 */
		public function classChangeId(){
			$this->loadModule( 'classes' );
			$this->loadModule( 'roles' );
			$this->loadModule( 'users' );
			$this->loadModule( 'certs' );
			if( $this->roles->doesUserHaveRole( Core::getSessionId(), 'dataManage' ) ){
				//alter table
				$query = <<<EOD
ALTER TABLE `classes`
CHANGE COLUMN `id` `oldId` VARCHAR(5) NOT NULL ,
DROP INDEX `id`,
ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT FIRST ,
ADD UNIQUE INDEX `id_UNIQUE` (`id` ASC),
ADD PRIMARY KEY (`id`);
EOD;
				if( !$this->db->query( $query ) ){
					Core::htmlEcho( 'error updating the class table' );
					Core::htmlEcho( 'assuming alter already ran' );
				}

				//alter the classdata
				$query = <<<EOD
ALTER TABLE `classData`
CHANGE COLUMN `class` `oldClass` VARCHAR(5) NOT NULL ,
DROP INDEX `class`,
ADD COLUMN `class` INT(11) NOT NULL FIRST ,
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
ADD INDEX (`class`);
EOD;
				if( !$this->db->query( $query ) ){
					Core::htmlEcho( 'error updating the classdata table' );
					Core::htmlEcho( 'assuming alter already ran' );
				}

				//look at each of the class data
				$query = "SELECT * FROM classData";
				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
					return null;
				}

				while ( $row = $result->fetch_assoc() ) {
					//change the class column to be the id of the class
					$class = $this->helper_classChangeIdGetOldId( $row['oldClass'] );
					$prereq = $this->helper_classChangeIdReplaceString( $row['prereq'] );
					$coreq = $this->helper_classChangeIdReplaceString( $row['coreq'] );
					$advisory = $this->helper_classChangeIdReplaceString( $row['advisory'] );
					$query = "UPDATE classData SET class=?, prereq=?, coreq=?, advisory=? WHERE id=?";
					$statement = $this->db->prepare( $query );
					$statement->bind_param( 'isssi', $class['id'], $prereq, $coreq, $advisory, $row['id'] );
					if( $statement->execute() ){
						Core::htmlEcho( "Setting class to ${class['id']} from ${row['oldClass']}" );
						Core::htmlEcho( "prereq: $prereq,  coreq: $coreq,  advisory: $advisory" );
						Core::htmlEcho( "<hr>" );
					} else{
						Core::htmlEcho( $statement->error );
						Core::htmlEcho( "<span style='color:red'>Setting class to ${class['id']} from ${row['oldClass']}</span><hr>"  );
					}
					$statement->close();
				}
				$result->close();


				Core::htmlEcho("<hr>Certificate data corrections<hr>");
				//scan cert data to fix the class links
				$query = "SELECT * FROM certificateData";
				if ( !$result = $this->db->query( $query ) ) {
					echo( 'There was an error running the query [' . $this->db->error . ']' );
					return null;
				}


				while ( $row = $result->fetch_assoc() ) {
					$description = $this->helper_classChangeIdReplaceString( $row['description'] );
					$elo = $this->helper_classChangeIdReplaceString( $row['elo'] );
					$schedule = $this->helper_classChangeIdReplaceString( $row['schedule'] );
					$statement = $this->db->prepare( "UPDATE certificateData SET description=?, elo=?, schedule=? WHERE id=?");
					$statement->bind_param( 'sssi', $description, $elo, $schedule, $row['id'] );
					if( $statement->execute() ){
						Core::htmlEcho( "cert id #". $row['id'] );
						Core::debug( array( $description, $elo, $schedule ) );
						Core::htmlEcho( '<hr>' );
					} else{
						Core::htmlEcho( $statement->error );
						Core::htmlEcho( "<span style='color:red'>changing cert ${row['id']}</span><hr>"  );
					}
					$statement->close();
				}

				$result->close();

			} else{
				echo 'no rights to run';
			}
		}

		private function helper_classChangeIdGetOldId( $id ){
			$query = "SELECT * FROM classes WHERE oldId = '$id'";

			if ( !$result = $this->db->query( $query ) ) {
				echo Core::ajaxResponse( array( 'error' => "An error occurred please try again" ), false );
				return null;
			}
			$row = $result->fetch_assoc();
			$return = array(
				'id' => $row['id'],
				'oldId' => $row['oldId'],
			);
			$result->close();
			return $return;
		}

		private function helper_classChangeIdReplaceString( $string ){
			$matchArray = array();
			preg_match_all('/\[class\s*id=[\'|\\"](.+?)[\'|\\"]\s*text=[\'|\\"](.+?)[\'|\\"]\s*\/\]/', $string, $matchArray);
			if( !empty( $matchArray[0] ) ) {
				$newIds = array();
				$newStr = array();
				for( $i = 0; $i < count( $matchArray[1] ); $i++ ){
					$newIds[$i] = $this->helper_classChangeIdGetOldId( $matchArray[1][$i] )['id'];
					$newStr[$i] = '[class id="' . $newIds[$i] . '" text="'. $matchArray[2][$i] . '" /]';
					Core::htmlEcho( $matchArray[1][$i] ." to ${newIds[$i]}");
				}
//				Core::debug( $newStr );

				$s = preg_replace( '/\[class\s*id=[\'|\"](.+?)[\'|\"]\s*text=[\'|\"](.+?)[\'|\"]\s*\/\]/',
					'~!~',
					$string
				);//regex to match the link string
				for( $i = 0; $i < count( $newStr ); $i++ ){
					$s = preg_replace( '/~!~/', $newStr[$i], $s, 1 );
				}

				return $s;
			}
			return $string;
		}
	}