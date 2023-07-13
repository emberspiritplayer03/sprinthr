<?php
class G_Settings_Requirement_Manager {
	public static function save(G_Settings_Requirement $gsr) {
		if (G_Settings_Requirement_Helper::isIdExist($gsr) > 0 ) {			
			$sql_start = "UPDATE ". G_SETTINGS_REQUIREMENTS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsr->getId());		
		}else{			
			$sql_start = "INSERT INTO ". G_SETTINGS_REQUIREMENTS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gsr->getCompanyStructureId()) . ",
			title			  		   =" . Model::safeSql($gsr->getName()) . ",
			is_archive	  		   =" . Model::safeSql($gsr->getIsArchive()) . ",			
			date_created 		   =" . Model::safeSql($gsr->getDateCreated()) . " "				
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function archive(G_Settings_Requirement $gsr){
		if (G_Settings_Requirement_Helper::isIdExist($gsr) > 0 ) {
			$sql = "
				UPDATE ". G_SETTINGS_REQUIREMENTS ."
					SET is_archive =" . Model::safeSql(G_Settings_Requirement::YES) . " 
				WHERE id =" . Model::safeSql($gsr->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore(G_Settings_Requirement $gsr){
		if (G_Settings_Requirement_Helper::isIdExist($gsr) > 0 ) {
			$sql = "
				UPDATE ". G_SETTINGS_REQUIREMENTS ."
					SET is_archive =" . Model::safeSql(G_Settings_Requirement::NO) . " 
				WHERE id =" . Model::safeSql($gsr->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Settings_Requirement $gsr){
		if (G_Settings_Requirement_Helper::isIdExist($gsr) > 0 ) {
			$sql = "
				DELETE FROM ". G_SETTINGS_REQUIREMENTS ."
				WHERE id =" . Model::safeSql($gsr->getId());
			Model::runSql($sql);
		}	
	}
}
?>