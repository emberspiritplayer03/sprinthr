<?php
class G_Exam_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EXAM ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findAllExamByJobId($job_id) {
		$sql = "
			SELECT id, applicable_to_job, title, description, company_structure_id, passing_percentage  
			FROM " . G_EXAM ." 
			WHERE FIND_IN_SET(" . Model::safeSql($job_id) . ",applicable_to_job) 
		";
		
		return self::getRecords($sql);
	}	
	
	public static function findAllExamByJobIdAndApplyToAllJobs($job_id) {
		$sql = "
			SELECT id, applicable_to_job, title, description, company_structure_id, passing_percentage  
			FROM " . G_EXAM ." 
			WHERE FIND_IN_SET(" . Model::safeSql($job_id) . ",applicable_to_job) OR apply_to_all_jobs =" . Model::safeSql(G_Exam::YES) . " 
		";
	
		return self::getRecords($sql);
	}	
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . G_EXAM ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."			
		";
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EXAM ."
			".$order_by."
			".$limit."	
		";
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$gsl = new G_Exam($row['id']);
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setTitle($row['title']);
		$gsl->setApplicableToJob($row['applicable_to_job']);
		$gsl->setApplyToAllJobs($row['apply_to_all_jobs']);
		$gsl->setDescription($row['description']);	
		$gsl->setPassingPercentage($row['passing_percentage']);
		$gsl->setTimeDuration($row['time_duration']);
		$gsl->setCreatedBy($row['created_by']);
		$gsl->setDateCreated($row['date_created']);	
		return $gsl;
	}
}
?>