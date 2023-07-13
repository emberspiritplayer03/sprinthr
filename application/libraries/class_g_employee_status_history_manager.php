<?php
class G_Employee_Status_History_Manager {
	public static function save(G_Employee_Status_History $gel) {
		if (G_Employee_Status_History_Helper::isIdExist($gel) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_STATUS_HISTORY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gel->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_STATUS_HISTORY . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id  		 =" . Model::safeSql($gel->getEmployeeId()) . ",
			employee_status_id 	 =" . Model::safeSql($gel->getEmployeeStatusId()) . ",			
			status	  			 =" . Model::safeSql($gel->getStatus()) . ",  
			start_date	  		 =" . Model::safeSql($gel->getStartDate()) . ",  		
			end_date	  	     =" . Model::safeSql($gel->getEndDate()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Status_History $gel) {
		if(G_Employee_Status_History_Helper::isIdExist($gel) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_STATUS_HISTORY ."
				WHERE id =" . Model::safeSql($gel->getId());
			Model::runSql($sql);
		}	
	}
}
?>