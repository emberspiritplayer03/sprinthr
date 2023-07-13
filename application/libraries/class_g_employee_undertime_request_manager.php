<?php
class G_Employee_Undertime_Request_Manager {
	public static function save(G_Employee_Undertime_Request $gur) {
		if (G_Employee_Undertime_Request_Helper::isIdExist($gur) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_EMPLOYEE_UNDERTIME_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gur->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_UNDERTIME_REQUEST . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gur->getCompanyStructureId()) . ",
			employee_id  	     =" . Model::safeSql($gur->getEmployeeId()) . ",
			date_applied 	     =" . Model::safeSql($gur->getDateApplied()) . ",
			date_of_undertime 	 =" . Model::safeSql($gur->getDateOfUndertime()) . ",			
			time_out 	 	  	 =" . Model::safeSql($gur->getTimeOut()) . ",
			reason	 		  	 =" . Model::safeSql($gur->getReason()) . ",  		
			created_by	 	  	 =" . Model::safeSql($gur->getCreatedBy()) . ",  		
			is_approved	 	  	 =" . Model::safeSql($gur->getIsApproved()) . ",  
			is_archive	 	  	 =" . Model::safeSql($gur->getIsArchive()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		
		if($action == "update") {
			return $gur->getId();
		} else  {
			return mysql_insert_id();
		}
	}
		
	public static function delete(G_Employee_Undertime_Request $gur){
		if(G_Employee_Undertime_Request_Helper::isIdExist($gur) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_UNDERTIME_REQUEST ."
				WHERE id =" . Model::safeSql($gur->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function approve(G_Employee_Undertime_Request $gur){
		if(G_Employee_Undertime_Request_Helper::isIdExist($gur) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_UNDERTIME_REQUEST ."
				SET is_approved =" . Model::safeSql(G_Employee_Undertime_Request::APPROVED) . "
				WHERE id =" . Model::safeSql($gur->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function disapprove(G_Employee_Undertime_Request $gur){
		if(G_Employee_Undertime_Request_Helper::isIdExist($gur) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_UNDERTIME_REQUEST ."
				SET is_approved =" . Model::safeSql(G_Employee_Undertime_Request::PENDING) . "
				WHERE id =" . Model::safeSql($gur->getId());
			Model::runSql($sql);
		}	
	}
}
?>