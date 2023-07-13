<?php
class G_Employee_Request_Manager {
	public static function save(G_Employee_Request $ger, G_Settings_Request $gsr, G_Employee $ge) {
		if (G_Employee_Request_Helper::isIdExist($ger) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST . " ";
			$sql_end  = "";		
		}
	
		$sql = $sql_start ."
			SET
			employee_id  	    =" . Model::safeSql($ge->getId()) . ",
			settings_request_id =" . Model::safeSql($gsr->getId()) . ",
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",			
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function save_leave_request(G_Employee_Request $ger, G_Settings_Request $gsr, G_Employee $ge, G_Employee_Leave_Request $gelr) {
		
		
		if (G_Employee_Request_Helper::isIdExist($ger) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST . " ";
			$sql_end  = "";		
		}
	
		$sql = $sql_start ."
			SET
			employee_id  	    =" . Model::safeSql($ge->getId()) . ",
			settings_request_id =" . Model::safeSql($gsr->getId()) . ",
			request_id			=" . Model::safeSql($gelr->getId()) . ",
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",			
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function save_overtime_request(G_Employee_Request $ger, G_Settings_Request $gsr, G_Employee $ge, G_Employee_Overtime_Request $gelr) {
		
		
		if (G_Employee_Request_Helper::isIdExist($ger) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST . " ";
			$sql_end  = "";		
		}
	
		$sql = $sql_start ."
			SET
			employee_id  	    =" . Model::safeSql($ge->getId()) . ",
			settings_request_id =" . Model::safeSql($gsr->getId()) . ",
			request_id			=" . Model::safeSql($gelr->getId()) . ",
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",			
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function save_rest_day_request(G_Employee_Request $ger, G_Settings_Request $gsr, G_Employee $ge, G_Employee_Rest_Day_Request $gelr) {
		if (G_Employee_Request_Helper::isIdExist($ger) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST . " ";
			$sql_end  = "";		
		}
	
		$sql = $sql_start ."
			SET
			employee_id  	    =" . Model::safeSql($ge->getId()) . ",
			settings_request_id =" . Model::safeSql($gsr->getId()) . ",
			request_id			=" . Model::safeSql($gelr->getId()) . ",
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",			
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function save_change_schedule_request(G_Employee_Request $ger, G_Settings_Request $gsr, G_Employee $ge, G_Employee_Change_Schedule_Request $gelr) {
		if (G_Employee_Request_Helper::isIdExist($ger) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST . " ";
			$sql_end  = "";		
		}
	
		$sql = $sql_start ."
			SET
			employee_id  	    =" . Model::safeSql($ge->getId()) . ",
			settings_request_id =" . Model::safeSql($gsr->getId()) . ",
			request_id			=" . Model::safeSql($gelr->getId()) . ",
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",			
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	
	public static function update(G_Employee_Request $ger) {

		$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST . " ";
		$sql_end   = "WHERE id = ". Model::safeSql($ger->getId());		
		$sql = $sql_start ."
			SET
			start_date    		=" . Model::safeSql($ger->getStartDate()) . ",
			end_date    		=" . Model::safeSql($ger->getEndDate()) . ",
			start_time    		=" . Model::safeSql($ger->getStartTime()) . ",			
			end_time    		=" . Model::safeSql($ger->getEndTime()) . ",			
			reason    		    =" . Model::safeSql($ger->getReason()) . ",		
			status    		    =" . Model::safeSql($ger->getStatus()) . ",			
			date_created 		=" . Model::safeSql($ger->getDateCreated()) . " "			
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Request $ger){
		if(G_Employee_Request_Helper::isIdExist($ger) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_REQUEST ."
				WHERE id =" . Model::safeSql($ger->getId());
			Model::runSql($sql);
		}	
	}
}
?>