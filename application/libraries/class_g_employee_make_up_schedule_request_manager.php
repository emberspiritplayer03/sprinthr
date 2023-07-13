<?php
class G_Employee_Make_Up_Schedule_Request_Manager {
	public static function save(G_Employee_Make_Up_Schedule_Request $gemusr) {
		if (G_Employee_Make_Up_Schedule_Request_Helper::isIdExist($gemusr) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gemusr->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id   =" . Model::safeSql($gemusr->getEmployeeId()) . ",
			date_applied  =" . Model::safeSql($gemusr->getDateApplied()) . ",
			date_from 	  =" . Model::safeSql($gemusr->getDateFrom()) . ",			
			date_to 	  =" . Model::safeSql($gemusr->getDateTo()) . ",
			start_time	  =" . Model::safeSql($gemusr->getStartTime()) . ",  		
			end_time	  =" . Model::safeSql($gemusr->getEndTime()) . ",  		
			comment	 	  =" . Model::safeSql($gemusr->getComment()) . ",  		
			created_by	  =" . Model::safeSql($gemusr->getCreatedBy()) . ",  		
			is_approved	  =" . Model::safeSql($gemusr->getIsApproved()) . ",  
			is_archive	  =" . Model::safeSql($gemusr->getIsArchive()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		
		if($action == "update") {
			return $gemusr->getId();
		} else {
			return mysql_insert_id();
		}		
	}
		
	public static function delete(G_Employee_Make_Up_Schedule_Request $gemusr){
		if(G_Employee_Make_Up_Schedule_Request_Helper::isIdExist($gemusr) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ."
				WHERE id =" . Model::safeSql($gemusr->getId());
			Model::runSql($sql);
		}	
	}
}
?>