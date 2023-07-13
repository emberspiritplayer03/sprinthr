<?php
class G_Exam_Manager {
	public static function save(G_Exam $gsl) {
		if (G_Exam_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_EXAM . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EXAM . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gsl->getCompanyStructureId()) . ",
			title		        	   = " . Model::safeSql($gsl->getTitle()) . ",
			applicable_to_job    = " . Model::safeSql($gsl->getApplicableToJob()) . ",
			apply_to_all_jobs    = " . Model::safeSql($gsl->getApplyToAllJobs()) . ",
			description          = " . Model::safeSql($gsl->getDescription()) . ",
			passing_percentage   = " . Model::safeSql($gsl->getPassingPercentage()) . ",
			time_duration		   = " . Model::safeSql($gsl->getTimeDuration()) . ", 
			created_by           = " . Model::safeSql($gsl->getCreatedBy()) . ",
			date_created         = " . Model::safeSql($gsl->getDateCreated()) . "
			"
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Exam $gsl){
		if(G_Exam_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_Exam ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>