<?php
class G_Settings_Notifications_Manager {
	public static function save(G_Settings_Notifications $at) {
		if (G_Settings_Notifications_Helper::isIdExist($at) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_NOTIFICATIONS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($at->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_NOTIFICATIONS . " ";
			$sql_end  = "";		
		}
				
		$sql = $sql_start ."
			SET
			title			=" . Model::safeSql($at->getTitle()) . ",
			sub_module		=" . Model::safeSql($at->getSubModule()) . ",
			is_enable 		=" . Model::safeSql($at->getIsEnable()) . " "				
			. $sql_end ."	
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Notifications $al){
		if(G_Settings_Notifications_Helper::isIdExist($al) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_NOTIFICATIONS ."
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
}
?>