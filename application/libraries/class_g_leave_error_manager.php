<?php
class G_Leave_Error_Manager {
	public function add($e) {		
		if ($e->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_ERROR_LEAVE;
			$sql_end   = " WHERE id = ". Model::safeSql($e->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_ERROR_LEAVE;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			employee_id   	= " . Model::safeSql($e->getEmployeeId()) .",
			employee_code  	= " . Model::safeSql($e->getEmployeeCode()) .",
			employee_name  	= " . Model::safeSql($e->getEmployeeName()) .",
			date_applied	= " . Model::safeSql($e->getDateApplied()) .",
			date_start		= " . Model::safeSql($e->getDateStart()) .",
			date_end		= " . Model::safeSql($e->getDateEnd()) .",
			message        	= " . Model::safeSql($e->getMessage()) .",
			is_fixed		= " . Model::safeSql($e->isFixed()) .",
			error_type_id	= " . Model::safeSql($e->getErrorTypeId()) ."
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