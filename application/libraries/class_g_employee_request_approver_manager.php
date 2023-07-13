<?php
class G_Employee_Request_Approver_Manager {
	public static function save(G_Employee_Request_Approver $gera,G_Settings_Request_Approver $gsra) {
		if (G_Employee_Request_Approver_Helper::isIdExist($gera) > 0 ) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST_APPROVERS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gera->getId());		
		}else{
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST_APPROVERS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			request_type  		 =" . Model::safeSql($gera->getRequestType()) . ",
			request_type_id		 =" . Model::safeSql($gera->getRequestTypeId()) . ",
			position_employee_id =" . Model::safeSql($gsra->getPositionEmployeeId()) . ",
			type    		     =" . Model::safeSql($gera->getType()) . ",
			level    		     =" . Model::safeSql($gera->getLevel()) . ",
			override_level	     =" . Model::safeSql($gera->getOverrideLevel()) . ",
			message    		     =" . Model::safeSql($gera->getMessage()) . ",
			status 		         =" . Model::safeSql($gera->getStatus()) . ",
			remarks		         =" . Model::safeSql($gera->getRemarks()) . ""			
			. $sql_end ."	
		";
		Model::runSql($sql);
		
		if($action == "insert") {
			return mysql_insert_id();
		} else {
			return $gera->getId();	
		}
		
	}
		
	public static function delete(G_Employee_Request_Approver $gera){
		if(G_Employee_Request_Approver_Helper::isIdExist($gera) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_REQUEST_APPROVERS ."
				WHERE id =" . Model::safeSql($gera->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function update(G_Employee_Request_Approver $gera) {
		if (G_Employee_Request_Approver_Helper::isIdExist($gera) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUEST_APPROVERS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gera->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUEST_APPROVERS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			request_type  		 =" . Model::safeSql($gera->getRequestType()) . ",
			request_type_id		 =" . Model::safeSql($gera->getRequestTypeId()) . ",
			type    		     =" . Model::safeSql($gera->getType()) . ",
			level    		     =" . Model::safeSql($gera->getLevel()) . ",
			override_level	     =" . Model::safeSql($gera->getOverrideLevel()) . ",
			message    		     =" . Model::safeSql($gera->getMessage()) . ",
			status 		         =" . Model::safeSql($gera->getStatus()) . ",
			remarks		         =" . Model::safeSql($gera->getRemarks()) . ""			
			. $sql_end ."	
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
}
?>