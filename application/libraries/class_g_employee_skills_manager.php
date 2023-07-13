<?php
class G_Employee_Skills_Manager {
	public static function save(G_Employee_Skills $e) {
		if (G_Employee_Skills_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_SKILLS . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SKILLS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			skill			   	= " . Model::safeSql($e->getSkill()) .",
			years_experience   	= " . Model::safeSql($e->getYearsExperience()) .",
			comments		   	= " . Model::safeSql($e->getComments()) ."
			"
		
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Skills $e){
		if(G_Employee_Skills_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_SKILLS ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>