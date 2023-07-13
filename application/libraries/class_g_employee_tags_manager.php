<?php
class G_Employee_Tags_Manager {
	public static function save(G_Employee_Tags $get, G_Employee $e) {
		if (G_Employee_Tags_Helper::isIdExist($get) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_TAGS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($get->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_TAGS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id  =" . Model::safeSql($get->getCompanyStructureId()) . ",
			employee_id  		  =" . Model::safeSql($e->getId()) . ",
			tags  	     		  =" . Model::safeSql($get->getTags()) . ",
			is_archive   		  =" . Model::safeSql($get->getIsArchive()) . ",											
			date_created 		  =" . Model::safeSql($get->getDateCreated()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Tags $get){
		if(G_Employee_Tags_Helper::isIdExist($get) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_TAGS ."
				WHERE id =" . Model::safeSql($get->getId());
			Model::runSql($sql);
		}	
	}
}
?>