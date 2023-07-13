<?php
class G_Employee_Work_Experience_Manager {
	public static function save(G_Employee_Work_Experience $e) {
		if (G_Employee_Work_Experience_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_WORK_EXPERIENCE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_WORK_EXPERIENCE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			company		   		= " . Model::safeSql($e->getCompany()) .",
			address 	  		= " . Model::safeSql($e->getAddress()) .",
			job_title			= " . Model::safeSql($e->getJobTitle()) .",
			from_date			= " . Model::safeSql($e->getFromDate()) .",
			to_date				= " . Model::safeSql($e->getToDate()) .",
			comment				= " . Model::safeSql($e->getComment()) ." 
			 "
			. $sql_end ."
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Work_Experience $e){
		if(G_Employee_Work_Experience_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_WORK_EXPERIENCE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>