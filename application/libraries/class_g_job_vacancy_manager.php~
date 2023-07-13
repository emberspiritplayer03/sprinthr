<?php
class G_Job_Vacancy_Manager {
	public static function save(G_Job_Vacancy $gcs) {
		if (G_Job_Vacancy_Helper::isIdExist($gcs) > 0 ) {
			$sql_start = "UPDATE ". G_JOB_VACANCY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcs->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB_VACANCY . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			id                		= " . Model::safeSql($gcs->getId()) . ",
			job_id             		= " . Model::safeSql($gcs->getJobId()) . ",
			job_description			= " . Model::safeSql($gcs->getJobDescription()) . ",
			hiring_manager_id       = " . Model::safeSql($gcs->getHiringManagerId()) . ",
			job_title		         = " . Model::safeSql($gcs->getJobTitle()) . ",
			hiring_manager_name     = " . Model::safeSql($gcs->getHiringManagerName()) . ",
			publication_date        = " . Model::safeSql($gcs->getPublicationDate()) . ",
			advertisement_end       = " . Model::safeSql($gcs->getAdvertisementEnd()) . ",
			is_active              	= " . Model::safeSql($gcs->getIsActive()) . " "
		
			. $sql_end ."	
		
		";
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Job_Vacancy $gcb){
		if(G_Job_Vacancy_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_JOB_VACANCY ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function open_job_vacancy(G_Job_Vacancy $gcb) {
		$sql = "UPDATE g_job_vacancy SET is_active=1 WHERE id=".$gcb->getId();
		Model::runSql($sql,true);
	}
	
	public static function close_job_vacancy(G_Job_Vacancy $gcb) {
		$sql = "UPDATE g_job_vacancy SET is_active=0 WHERE id=".$gcb->getId();
		Model::runSql($sql,true);
	}
}
?>