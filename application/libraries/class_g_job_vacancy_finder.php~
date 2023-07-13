<?php
class G_Job_Vacancy_Finder {

	public static function findById($id) {
		$sql = "
			SELECT jv.id, jv.hiring_manager_id, CONCAT(e.lastname,', ' ,e.firstname) as hiring_manager_name, jv.job_title,jv.job_description,jv.job_id,jv.is_active,jv.publication_date,jv.advertisement_end  FROM 
			(SELECT g_job_vacancy.id,g_job.id as job_id,g_job_vacancy.job_description, g_job_vacancy.hiring_manager_id, g_job.title as job_title, g_job_vacancy.is_active,g_job_vacancy.publication_date,g_job_vacancy.advertisement_end  FROM g_job_vacancy LEFT JOIN g_job ON g_job_vacancy.job_id=g_job.id) 
			as jv 
			LEFT JOIN g_employee e ON jv.hiring_manager_id=e.id WHERE jv.id=".$id."
			LIMIT 1
		";
	
		return self::getRecord($sql);
	}
	
	public static function findByJobIdAndIsActive($jid) {
		$sql = "
			SELECT job_id,job_title,is_active 
			FROM " . G_JOB_VACANCY . "
			WHERE is_active =" . Model::safeSql(G_Job_Vacancy::IS_ACTIVE) . " AND job_id =" . Model::safeSql($jid) . " 
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function searchByCompanyName($query) {
			
	}
	
	public static function findByJobVacancy($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT jv.id, jv.hiring_manager_id, CONCAT(e.lastname,', ' ,e.firstname) as hiring_manager_name, jv.job_title,jv.job_id,jv.is_active,jv.publication_date,jv.advertisement_end  FROM 
			(SELECT g_job_vacancy.id,g_job.id as job_id, g_job_vacancy.hiring_manager_id, g_job.title as job_title, g_job_vacancy.is_active,g_job_vacancy.publication_date,g_job_vacancy.advertisement_end  FROM g_job_vacancy LEFT JOIN g_job ON g_job_vacancy.job_id=g_job.id) 
			as jv 
			LEFT JOIN g_employee e ON jv.hiring_manager_id=e.id
			
			".$order_by."
			".$limit."		
		";		

	
		return self::getRecords($sql);
	}
	
	public static function findAllActiveJobVacancy($order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
				
		$sql = "
			SELECT id, job_id, job_description, job_title, publication_date, advertisement_end, is_active 
			FROM " . G_JOB_VACANCY . "
			WHERE is_active =" . Model::safeSql(G_Job_Vacancy::IS_ACTIVE) . " 
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}	
		
	public static function findAll() {
		$sql = "
			SELECT jv.id, jv.hiring_manager_id, CONCAT(e.lastname,', ' ,e.firstname) as hiring_manager_name, jv.job_title,jv.job_id,jv.is_active  FROM 
			(SELECT g_job_vacancy.id,g_job.id as job_id, g_job_vacancy.hiring_manager_id, g_job.title as job_title, g_job_vacancy.is_active  FROM g_job_vacancy LEFT JOIN g_job ON g_job_vacancy.job_id=g_job.id) 
			as jv 
			LEFT JOIN g_employee e ON jv.hiring_manager_id=e.id
			
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
		
		$gcb = new G_Job_Vacancy($row['id']);
		$gcb->setJobId($row['job_id']);	
		$gcb->setJobDescription($row['job_description']);
		$gcb->setHiringManagerId($row['hiring_manager_id']);
		$gcb->setJobTitle($row['job_title']);
		$gcb->setHiringManagerId($row['hiring_manager_id']);
		$gcb->setHiringManagerName($row['hiring_manager_name']);
		$gcb->setPublicationDate($row['publication_date']);
		$gcb->setAdvertisementEnd($row['advertisement_end']);
		$gcb->setIsActive($row['is_active']);
		return $gcb;
	}
}
?>