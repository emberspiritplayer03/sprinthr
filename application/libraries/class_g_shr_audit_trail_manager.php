<?php
class G_Shr_Audit_Trail_Manager {


	public static function save_shr_audit_trail_manager(G_Shr_Audit_Trail $at) {

		if (G_Shr_Audit_Trail_Helper::isIdExist($at) > 0 ) {
			$sql_start = "UPDATE ". SHR_AUDIT_TRAIL . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($at->getShrId());		
		}else{

			$sql_start = "INSERT INTO ". SHR_AUDIT_TRAIL . " ";
			$sql_end  = "";		
		}
	

		$sql = $sql_start ."
			SET
			username				=" . Model::safeSql($at->getShrUser()) . ",
			role					=" . Model::safeSql($at->getShrRole()) . ",
			module					=" . Model::safeSql($at->getShrModule()) . ",
			activity_action			=" . Model::safeSql($at->getShrActivityAction()) . ",
			activity_type			=" . Model::safeSql($at->getShrActivityType()) . ",
			audited_action			=" . Model::safeSql($at->getShrAuditedAction()) . ",
			action_from				=" . Model::safeSql($at->getShrFrom()) . ",
			action_to				=" . Model::safeSql($at->getShrTo()) . ",
			event_status			=" . Model::safeSql($at->getShrEventStatus()) . ",
			position				=" . Model::safeSql($at->getShrPosition()) . ",
			department				=" . Model::safeSql($at->getShrDepartment()) . ",
			audit_date				=" . Model::safeSql($at->getShrAuditDate()) . ",
			audit_time				=" . Model::safeSql($at->getShrAuditTime()) . ",
			ip_address 				=" . Model::safeSql($at->getShrIpAddress()) . " "				
			. $sql_end ."	
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function delete_shr_audit_trail_manager(G_Shr_Audit_Trail $al){
		if(G_Shr_Audit_Trail_Helper::isIdExist($al) > 0){
			$sql = "
				DELETE FROM ". SHR_AUDIT_TRAIL ."
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
	
}
?>