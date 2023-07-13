<?php
class G_Settings_Default_Leave_Manager {
	public static function save(G_Settings_Default_Leave $e) {
		if (G_Settings_Default_Leave_Helper::isIdExist($e) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_SETTINGS_DEFAULT_LEAVE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_SETTINGS_DEFAULT_LEAVE . " ";
			$sql_end   = " ";		
		}

		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			leave_type_id			= " . Model::safeSql($e->getLeaveTypeId()) .",
			number_of_days_default	= " . Model::safeSql($e->getNumberOfDaysDefault()) ."
			"
	
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return $e->getId();
		}		
	}
		
	public static function delete(G_Settings_Default_Leave $e){
		if(G_Settings_Default_Leave_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_DEFAULT_LEAVE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>