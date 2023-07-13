<?php
class G_Employee_Requirements_Manager {
	public static function save(G_Employee_Requirements $gcb) {
		if (G_Employee_Requirements_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_REQUIREMENTS. " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_REQUIREMENTS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id 			= " . Model::safeSql($gcb->getEmployeeId()) . ",
			requirements			= " . Model::safeSql($gcb->getRequirements()) . ",
			date_updated			= " . Model::safeSql($gcb->getDateUpdated()) . ",
			is_complete				= " . Model::safeSql($gcb->getIsComplete()) . "
			 "
			. $sql_end ."	
		
		";

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Requirements $gcb){
		if(G_Employee_Requirements_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_REQUIREMENTS ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>