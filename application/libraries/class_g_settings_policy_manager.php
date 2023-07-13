<?php
class G_Settings_Policy_Manager {
	public static function save(G_Settings_Policy $sp) {
		if (G_Settings_Policy_Helper::isIdExist($sp) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_POLICY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($sp->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_POLICY . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			policy 		=" . Model::safeSql($sp->getPolicy()) . ",
			description =" . Model::safeSql($sp->getDescription()) . ",									
			is_active	=" . Model::safeSql($sp->getIsActive()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Policy $sp){
		if(G_Settings_Policy_Helper::isIdExist($sp) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_POLICY ."
				WHERE id =" . Model::safeSql($sp->getId());
			Model::runSql($sql);
		}	
	}
}
?>