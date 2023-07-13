<?php
class G_Settings_Salutation_Manager {
	public static function save(G_Settings_Salutation $gss) {
		if (G_Settings_Salutation_Helper::isIdExist($gss) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_SALUTATION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gss->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_SALUTATION . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gss->getCompanyStructureId()) . ",
			salutation  	     =" . Model::safeSql($gss->getSalutation()) . ",				
			description          =" . Model::safeSql($gss->getDescription()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Salutation $gss){
		if(G_Settings_Salutation_Helper::isIdExist($gss) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_SALUTATION ."
				WHERE id =" . Model::safeSql($gss->getId());
			Model::runSql($sql);
		}	
	}
}
?>