<?php
class G_Settings_Employee_Status_Manager {
	public static function save(G_Settings_Employee_Status $gses) {		
		if (G_Settings_Employee_Status_Helper::isIdExist($gses) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_EMPLOYEE_STATUS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gses->getId());		
			$action    = "update";			
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_EMPLOYEE_STATUS . " ";
			$sql_end   = "";		
			$action    = 'insert';
		}		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gses->getCompanyStructureId()) . ",
			name			  		   =" . Model::safeSql($gses->getName()) . ",
			is_archive	  		   =" . Model::safeSql($gses->getIsArchive()) . ",			
			date_created 		   =" . Model::safeSql($gses->getDateCreated()) . " "				
			. $sql_end ."	
		
		";
		
		Model::runSql($sql);
		
		if( $action == 'update' ){
			$id = $gses->getId();
		}else{
			$id = mysql_insert_id();		
		}

		return $id;
		
	}
	
	public static function restore(G_Settings_Employee_Status $gses){
		if (G_Settings_Employee_Status_Helper::isIdExist($gses) > 0 ) {
			$sql = "
				UPDATE ". G_SETTINGS_EMPLOYEE_STATUS ."
				SET is_archive =" . Model::safeSql(G_Settings_Employee_Status::NO) . "
				WHERE id =" . Model::safeSql($gses->getId());
			Model::runSql($sql);
		}	
	}

	public static function archive(G_Settings_Employee_Status $gses){
		if (G_Settings_Employee_Status_Helper::isIdExist($gses) > 0 ) {
			$sql = "
				UPDATE ". G_SETTINGS_EMPLOYEE_STATUS ."
				SET is_archive =" . Model::safeSql(G_Settings_Employee_Status::YES) . "
				WHERE id =" . Model::safeSql($gses->getId());				
			Model::runSql($sql);
		}	
	}	
	
	public static function delete(G_Settings_Employee_Status $gses){
		if (G_Settings_Employee_Status_Helper::isIdExist($gses) > 0 ) {
			$sql = "
				DELETE FROM ". G_SETTINGS_EMPLOYEE_STATUS ."
				WHERE id =" . Model::safeSql($gses->getId());
			Model::runSql($sql);
		}	
	}
}
?>