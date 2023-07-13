<?php
class G_Payslip_Error_Manager {
	public function add($e) {
		$sql_start = "INSERT INTO ". G_PAYSLIP_ERROR;
		
		$sql = $sql_start ."
			SET
			employee_id        	= " . Model::safeSql($e->getEmployeeId()) .",
			message        	= " . Model::safeSql($e->getMessage()) .",
			is_fixed		= " . Model::safeSql($e->isFixed()) .",
			error_type_id   = " . Model::safeSql($e->getErrorTypeId()) .",
			time_logged	= " . Model::safeSql($e->getTimeLogged()) .",
			date_logged	= " . Model::safeSql($e->getDateLogged()) ."
		";	

		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		return mysql_insert_id();
	}
}
?>