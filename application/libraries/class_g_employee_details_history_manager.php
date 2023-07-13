<?php
class G_Employee_Details_History_Manager {
	public static function save(G_Employee_Details_History $e) {
		if (G_Employee_Details_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DETAILS_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_DETAILS_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id    	= " . Model::safeSql($e->getEmployeeId()) .",
			employee_code  	= " . Model::safeSql($e->getEmployeeCode()) .",
			modified_by		= " . Model::safeSql($e->getModifiedBy()) .",
			remarks			= " . Model::safeSql($e->getRemarks()) .",
			history_date	= " . Model::safeSql($e->getHistoryDate()) .",
			date_modified	= " . Model::safeSql($e->getDateModified()) .",
			is_archive		= " . Model::safeSql($e->getIsArchive()) ."
			 "
			. $sql_end ."	
		
		";	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Details_History $e){
		if(G_Employee_Details_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_DETAILS_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function archive(G_Employee_Details_History $e){
		if(G_Employee_Details_History_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_DETAILS_HISTORY ."
				SET is_archive =" . Model::safeSql(G_Employee_Details_History::YES) . "
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function addHash(G_Employee_Details_History $e,$hash) {
		if (G_Employee_Details_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DETAILS_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}
		
		$sql = $sql_start ."
			SET
			hash		        	= " . Model::safeSql($hash) .""
			. $sql_end ."
		";	

		Model::runSql($sql);
	}
}
?>