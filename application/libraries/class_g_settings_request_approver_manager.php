<?php
class G_Settings_Request_Approver_Manager {
	public static function save(G_Settings_Request_Approver $gsra, G_Settings_Request $gsr) {
		if (G_Settings_Request_Approver_Helper::isIdExist($gsra) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_REQUEST_APPROVERS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsra->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_REQUEST_APPROVERS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			settings_request_id  =" . Model::safeSql($gsr->getId()) . ",
			position_employee_id =" . Model::safeSql($gsra->getPositionEmployeeId()) . ",
			type    		     =" . Model::safeSql($gsra->getType()) . ",			
			level 			     =" . Model::safeSql($gsra->getLevel()) . ",
			override_level	     =" . Model::safeSql($gsra->getOverrideLevel()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function updateOverrideLevel(G_Settings_Request_Approver $gsra){
		if(G_Settings_Request_Approver_Helper::isIdExist($gsra) > 0){
			$sql = "
				UPDATE ". G_SETTINGS_REQUEST_APPROVERS ."
				SET override_level = ''
				WHERE settings_request_id =" . Model::safeSql($gsra->getSettingsRequestId());
			Model::runSql($sql);
			
			$sql = "
				UPDATE ". G_SETTINGS_REQUEST_APPROVERS ."
				SET override_level = " . Model::safeSql(Settings_Request_Approver::GRANTED) . "
				WHERE id =" . Model::safeSql($gsra->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Settings_Request_Approver $gsra){
		if(G_Settings_Request_Approver_Helper::isIdExist($gsra) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_REQUEST_APPROVERS ."
				WHERE id =" . Model::safeSql($gsra->getId());
			Model::runSql($sql);
		}	
	}
}
?>