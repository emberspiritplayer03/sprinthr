<?php
class G_Settings_Language_Manager {
	public static function save(G_Settings_Language $gsl) {
		if (G_Settings_Language_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_LANGUAGE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_LANGUAGE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gsl->getCompanyStructureId()) . ",			
			language             =" . Model::safeSql($gsl->getLanguage()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Language $gsl){
		if(G_Settings_Language_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_LANGUAGE ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>