<?php
class G_Settings_Request_Manager {
	public static function save(G_Settings_Request $gsr) {
		if (G_Settings_Request_Helper::isIdExist($gsr) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsr->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_REQUEST . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			title 		 	       =" . Model::safeSql($gsr->getTitle()) . ",
			request_type 		   =" . Model::safeSql($gsr->getType()) . ",			
			applied_to_departments =" . Model::safeSql($gsr->getDepartments()) . ",
			applied_to_positions   =" . Model::safeSql($gsr->getPositions()) . ",
			applied_to_employees   =" . Model::safeSql($gsr->getEmployees()) . ",
			applied_to_description =" . Model::safeSql($gsr->getDescription()) . ",			
			is_active    		   =" . Model::safeSql($gsr->getIsActive()) . ",			
			is_archive  		   =" . Model::safeSql($gsr->getIsArchive()) . ",			
			date_created 		   =" . Model::safeSql($gsr->getDateCreated()) . " "			
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function archive(G_Settings_Request $gsr){
		if(G_Settings_Request_Helper::isIdExist($gsr) > 0){
			$sql = "
				UPDATE ". G_SETTINGS_REQUEST ."
				SET				
				is_archive =" . Model::safeSql($gsr->getIsArchive()) . " 
				WHERE id =" . Model::safeSql($gsr->getId()) . "				
				";		
			echo $sq;	
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Settings_Request $gsr){
		if(G_Settings_Request_Helper::isIdExist($gsr) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_REQUEST ."
				WHERE id =" . Model::safeSql($gsr->getId());
			Model::runSql($sql);
		}	
	}
}
?>