<?php
class G_Settings_Employee_Field_Manager {
	public static function save(G_Settings_Employee_Field $e) {
		if (G_Settings_Employee_Field_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_EMPLOYEE_FIELD . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_EMPLOYEE_FIELD . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			title		   		= " . Model::safeSql($e->getTitle()) .",
			screen				= " . Model::safeSql($e->getScreen()) .",
			default		  		= " . Model::safeSql($e->getDefault()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Employee_Field $e){
		if(G_Settings_Employee_Field_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_EMPLOYEE_FIELD ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>