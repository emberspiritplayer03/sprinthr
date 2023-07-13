<?php
class G_Employee_Leave_Available_Manager {
	public static function save(G_Employee_Leave_Available $e) {
		if (G_Employee_Leave_Available_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LEAVE_AVAILABLE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LEAVE_AVAILABLE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			leave_id		   	= " . Model::safeSql($e->getLeaveId()) .",
			no_of_days_alloted	= " . Model::safeSql($e->getNoOfDaysAlloted()) .",
			no_of_days_available= " . Model::safeSql($e->getNoOfDaysAvailable()) ."
			"
	
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Leave_Available $e){
		if(G_Employee_Leave_Available_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>