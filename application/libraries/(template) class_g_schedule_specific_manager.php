<?php
class G_Schedule_Specific_Manager {
	/*
		$ss - Instance of G_Schedule_Specific class
	*/
	public static function save($ss) {
		if ($ss->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_SCHEDULE;
			$sql_end   = " WHERE id = ". Model::safeSql($ss->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SCHEDULE;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($ss->getEmployeeId()) .",
			date_start = ". Model::safeSql($ss->getDateStart()) .",
			date_end = ". Model::safeSql($ss->getDateEnd()) .",
			time_in = ". Model::safeSql($ss->getTimeIn()) .",
			time_out = ". Model::safeSql($ss->getTimeOut()) ."
			". $sql_end ."		
		";			
		
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}
	}
	
	/*
		Variables
		$sf - Instance of G_Schedule_Specific class
	*/		
	public static function delete($sf) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_SCHEDULE ."
			WHERE id = ". Model::safeSql($sf->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}		
}
?>