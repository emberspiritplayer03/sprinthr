<?php
class G_Job_Manager {
	public static function save(G_Job $g) {
		if (G_Job_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_JOB . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB . " ";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($g->getCompanyStructureId()) .",
			job_specification_id = " . Model::safeSql($g->getJobSpecificationId()) .",
			title  				 = " . Model::safeSql($g->getTitle()) .",
			is_active 			 = ". Model::safeSql($g->getIsActive()) ." "
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function setAsActive(G_Job $g) {		
		$sql_start = "UPDATE ". G_JOB . "";
		$sql_end   = " WHERE id = ". Model::safeSql($g->getId());		
		$sql = $sql_start ."
			SET			
			is_active =" . Model::safeSql(1)			
			. $sql_end ."			
		";
		Model::runSql($sql);
	}
	
	public static function setAsNotActive(G_Job $g) {		
		$sql_start = "UPDATE ". G_JOB . "";
		$sql_end   = " WHERE id = ". Model::safeSql($g->getId());		
		$sql       = $sql_start ."
			SET			
			is_active =" . Model::safeSql(0)			
			. $sql_end ."			
		";	
		Model::runSql($sql);
	}
	
	public static function setAllNotActiveToActive() {		
		$sql_start = "UPDATE ". G_JOB . "";
		$sql_end = " WHERE is_active = ". Model::safeSql(0);		
		$sql = $sql_start ."
			SET			
			is_active =" . Model::safeSql(1)			
			. $sql_end ."			
		";		
		Model::runSql($sql);
	}
	
	public static function deleteAllActive() {		
		$sql = "
			DELETE  FROM ". G_JOB ." 
			WHERE is_active = " . Model::safeSql(0);	
		Model::runSql($sql);		
	}
		
	public static function delete(G_Job $g){
		if(G_Job_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_JOB ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
	
	public static function saveToEmployee(G_Job $j, G_Employee $e, $start_date, $end_date) {
		if (G_Job_Helper::isEmployeeJobHistoryExist($e, $j, $start_date, $end_date) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "WHERE employee_id = ". Model::safeSql($e->getId()) ." AND job_id=".Model::safeSql($j->getId())." 
						  AND name = ".Model::safeSql($j->getTitle()). " AND start_date = ".Model::safeSql($start_date)." AND end_date = " . Model::safeSql($end_date) . "
			";		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "";		
		}
		$status = ($j->getTitle())? 'Terminated' : $j->getTitle();
		$sql = $sql_start ."
			SET
			employee_id     	= " . Model::safeSql($e->getId()) .",
			job_id 				= " . Model::safeSql($j->getId()) .",
			name 				= " . Model::safeSql($j->getTitle()) .",
			employment_status 	= " . Model::safeSql($status) .",
			start_date 			= " . Model::safeSql($start_date) .",
			end_date 			= ". Model::safeSql($end_date) . ""
		. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		return mysql_insert_id();
	}
}
?>