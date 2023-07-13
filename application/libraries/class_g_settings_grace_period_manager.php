<?php
class G_Settings_Grace_Period_Manager {
	public static function save(G_Settings_Grace_Period $e) {
		if (G_Settings_Grace_Period_Helper::isIdExist($e) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_SETTINGS_GRACE_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_SETTINGS_GRACE_PERIOD . " ";
			$sql_end   = " ";		
		}
			
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			title					= " . Model::safeSql($e->getTitle()) .",
			description				= " . Model::safeSql($e->getDescription()) .",
			is_archive				= " . Model::safeSql($e->getIsArchive()) .",
			number_minute_default	= " . Model::safeSql($e->getNumberMinuteDefault()) .",
			is_default				= " . Model::safeSql($e->getIsDefault()) ."	"
			. $sql_end ."	
		";	
		
		Model::runSql($sql);
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return $e->getId();
		}		
	}
		
	public static function set_all_not_default(G_Settings_Grace_Period $e){
		$sql = "
			UPDATE ". G_SETTINGS_GRACE_PERIOD ."
			SET is_default =" . Model::safesql(G_Settings_Grace_Period::NO) . "
			WHERE company_structure_id =" . Model::safeSql($e->getCompanyStructureId());
		Model::runSql($sql);
	
	}
	
	public static function delete(G_Settings_Grace_Period $e){
		if(G_Settings_Grace_Period_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_GRACE_PERIOD ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function save_not_default(G_Settings_Grace_Period $e){
		if(G_Settings_Grace_Period_Helper::isIdExist($e) > 0){
			
			$action = "update";
			$sql_start = "UPDATE ". G_SETTINGS_GRACE_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
			$sql = $sql_start ."
				SET
				is_default				= " . Model::safeSql(0) ." "
				. $sql_end ."	
			";	
			Model::runSql($sql);
			}
	
	}
	
	public static function save_default(G_Settings_Grace_Period $e){
		if(G_Settings_Grace_Period_Helper::isIdExist($e) > 0){
			$sql = "
			UPDATE ". G_SETTINGS_GRACE_PERIOD ."
			SET is_default =" . Model::safesql(G_Settings_Grace_Period::YES) . "
			WHERE id =" . Model::safeSql($e->getId());
			
			Model::runSql($sql);
		}
	
	}
	
}
?>