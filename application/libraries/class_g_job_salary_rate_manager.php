<?php
class G_Job_Salary_Rate_Manager {
	public static function save(G_Job_Salary_Rate $g) {
		if (G_Job_Salary_Rate_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_JOB_SALARY_RATE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB_SALARY_RATE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($g->getCompanyStructureId()) ." ,
			job_level 			 = " . Model::safeSql($g->getJobLevel()) ." ,
			minimum_salary 	     = " . Model::safeSql($g->getMinimumSalary()) ." ,
			maximum_salary 		 = " . Model::safeSql($g->getMaximumSalary()) ." ,
			step_salary 		 = " . Model::safeSql($g->getStepSalary()) ." "
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Job_Salary_Rate $g){
		if(G_Job_Salary_Rate_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_JOB_SALARY_RATE ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>