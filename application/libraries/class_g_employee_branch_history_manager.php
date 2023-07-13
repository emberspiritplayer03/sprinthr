<?php
class G_Employee_Branch_History_Manager {
	public static function save(G_Employee_Branch_History $e) {
		if (G_Employee_Branch_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BRANCH_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			company_branch_id	= " . Model::safeSql($e->getCompanyBranchId()) .",
			branch_name   		= " . Model::safeSql($e->getBranchName()) .",
			start_date			= " . Model::safeSql($e->getStartDate()) .",
			end_date			= " . Model::safeSql($e->getEndDate()) ." 
			 "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Branch_History $e){
		if(G_Employee_Branch_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_BRANCH_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>