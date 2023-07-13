<?php
class G_Attendance_Error_Manager {
	public function add($e) {		
		if ($e->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_ATTENDANCE_ERROR;
			$sql_end   = " WHERE id = ". Model::safeSql($e->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_ATTENDANCE_ERROR;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			message        	= " . Model::safeSql($e->getMessage()) .",
			is_fixed		= " . Model::safeSql($e->isFixed()) .",
			error_type_id	= " . Model::safeSql($e->getErrorTypeId()) .",
			employee_id	= " . Model::safeSql($e->getEmployeeId()) .",
			employee_code	= " . Model::safeSql($e->getEmployeeCode()) .",
			date_attendance	= " . Model::safeSql($e->getDate()) ."
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
}
?>