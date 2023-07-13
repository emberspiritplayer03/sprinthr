<?php
class G_Job_Employment_Status_Helper {
	public static function isIdExist(G_Job_Employment_Status $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_EMPLOYMENT_STATUS ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isJobIdExist(G_Job_Employment_Status $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_EMPLOYMENT_STATUS ."
			WHERE job_id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countEmployee(G_Job_Employment_Status $j) {
		$sql = "
			SELECT
			count(*) as total
			FROM
			`g_job_employment_status` AS status,
			`g_employee_job_history` AS `job_history`
			WHERE
			`g_job_employment_status`.`job_id` =  `job_history`.`job_id` AND
			`job_history`.`end_date` =  '' AND status=".$j->getId() ."
			AND company_structure_id=".$j->getCompanyStructureId()."

		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_EMPLOYMENT_STATUS			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_EMPLOYMENT_STATUS ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByJobId(G_Job $job) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_EMPLOYMENT_STATUS ."
			WHERE job_id = ". Model::safeSql($job->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
}
?>