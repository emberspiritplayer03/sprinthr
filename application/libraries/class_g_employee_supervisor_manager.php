<?php
class G_Employee_Supervisor_Manager {
	public static function save(G_Employee_Supervisor $e) {
		if (G_Employee_Supervisor_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_SUPERVISOR . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SUPERVISOR . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			supervisor_id  		= " . Model::safeSql($e->getSupervisorId()) ."
			 "
			. $sql_end ."	
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Supervisor $e){
		if(G_Employee_Supervisor_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_SUPERVISOR ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>