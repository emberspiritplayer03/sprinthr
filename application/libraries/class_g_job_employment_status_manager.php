<?php
class G_Job_Employment_Status_Manager {
	public static function save(G_Job_Employment_Status $g) {
		if (G_Job_Employment_Status_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". G_JOB_EMPLOYMENT_STATUS . " ";
			$sql_end   = " WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB_EMPLOYMENT_STATUS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($g->getCompanyStructureId()) .",
			job_id        			= " . Model::safeSql($g->getJobId()) .",
			employment_status_id 	= " . Model::safeSql($g->getEmploymentStatusId()) .",
			employment_status		= " . Model::safeSql($g->getEmploymentStatus()) .""
			. $sql_end ."	
		
		";		

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
			
	public static function delete(G_Job_Employment_Status $g){
		if(G_Job_Employment_Status_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". G_JOB_EMPLOYMENT_STATUS ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>