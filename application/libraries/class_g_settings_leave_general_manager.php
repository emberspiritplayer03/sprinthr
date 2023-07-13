<?php
class G_Settings_Leave_General_Manager {
	public static function save(G_Settings_Leave_General $u) {
		if (G_Settings_Leave_General_Helper::isIdExist($u) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_LEAVE_GENERAL . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($u->getId());		
			$sql_cmd   = "update";
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_LEAVE_GENERAL . " ";
			$sql_end   = "";		
			$sql_cmd   = "insert";
		}
		
		$sql = $sql_start ."
			SET
            convert_leave_criteria	=" . Model::safeSql($u->getConvertLeaveCriteria()) . ",
			leave_id				=" . Model::safeSql($u->getLeaveId()) . "
			"
			. $sql_end ."	
		";			

		Model::runSql($sql);
		if($sql_cmd == 'insert') {
			return mysql_insert_id();
		} else { return mysql_affected_rows(); }
		
	}
		
	public static function delete(G_Settings_Leave_General $u){
		if(G_Settings_Leave_General_Helper::isIdExist($u) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_LEAVE_GENERAL ."
				WHERE id =" . Model::safeSql($u->getId());
			Model::runSql($sql);
		}
	
	}
}
?>