<?php
class G_Settings_Application_Status_Manager {
	public static function save(G_Settings_Application_Status $g) {
		if (G_Settings_Application_Status_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_APPLICATION_STATUS . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_APPLICATION_STATUS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id     = " . Model::safeSql($g->getCompanyStructureId()) .",
			status					 = " . Model::safeSql($g->getStatus()) .""
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
			
	public static function delete(G_Settings_Application_Status $g){
		if(G_Settings_Application_Status_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_APPLICATION_STATUS ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>