<?php
class G_Settings_Leave_Credit_Manager {
	public static function save(G_Settings_Leave_Credit $u) {
		if (G_Settings_Leave_Credit_Helper::isIdExist($u) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGG_LEAVE_CREDIT . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($u->getId());		
			$sql_cmd   = "update";
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGG_LEAVE_CREDIT . " ";
			$sql_end   = "";		
			$sql_cmd   = "insert";
		}
		
		$sql = $sql_start ."
			SET
            employment_years		=" . Model::safeSql($u->getEmploymentYears()) . ",
			default_credit	 		=" . Model::safeSql($u->getDefaultCredit()) . ",
			leave_id				=" . Model::safeSql($u->getLeaveId()) . ",			
			employment_status_id 	=" . Model::safeSql($u->getEmploymentStatusId()) . ",	
			is_archived 			=" . Model::safeSql($u->getIsArchived()) . " 
			"
			. $sql_end ."	
		";			

		Model::runSql($sql);
		if($sql_cmd == 'insert') {
			return mysql_insert_id();
		} else { return mysql_affected_rows(); }
		
	}
		
	public static function delete(G_Settings_Leave_Credit $u){
		if(G_Settings_Leave_Credit_Helper::isIdExist($u) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGG_LEAVE_CREDIT ."
				WHERE id =" . Model::safeSql($u->getId());
			Model::runSql($sql);
		}
	
	}
}
?>