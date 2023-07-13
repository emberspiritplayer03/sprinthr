<?php
class G_Employee_Language_Manager {
	public static function save(G_Employee_Language $e) {
		if (G_Employee_Language_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LANGUAGE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LANGUAGE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			language		   	= " . Model::safeSql($e->getLanguage()) .",
			fluency			   	= " . Model::safeSql($e->getFluency()) .",
			competency		   	= " . Model::safeSql($e->getCompetency()) .",
			comments		   	= " . Model::safeSql($e->getComments()) ."
			"
		
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Language $e){
		if(G_Employee_Language_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LANGUAGE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>