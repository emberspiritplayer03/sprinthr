<?php
class G_Employee_Extend_Contract_Manager {
	public static function save(G_Employee_Extend_Contract $e) {
		if (G_Employee_Extend_Contract_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_EXTEND_CONTRACT . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_EXTEND_CONTRACT . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			start_date	   		= " . Model::safeSql($e->getStartDate()) .",
			end_date			= " . Model::safeSql($e->getEndDate()) .",
			attachment			= " . Model::safeSql($e->getAttachment()) .",
			remarks				= " . Model::safeSql($e->getRemarks()) .",
			is_done				= " . Model::safeSql($e->getIsDone()) ."
			"
		
			. $sql_end ."	
		
		";	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Extend_Contract $e){
		if(G_Employee_Extend_Contract_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_EXTEND_CONTRACT ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>