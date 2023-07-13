<?php
class G_Job_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_JOB ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		

		return self::getRecord($sql);
	}
	
	public static function findByCompanyId($id) {
		$sql = "
			SELECT * 
			FROM " . G_JOB ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByTitle($name) {
		$sql = "
			SELECT * 
			FROM " . G_JOB ." 
			WHERE title =". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findAllActive() {
		$sql = "
			SELECT * 
			FROM " . G_JOB ."
			WHERE is_active = 1
			ORDER BY id ASC			
		";
		return self::getRecords($sql);
	}	
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_JOB ."
			ORDER BY id ASC			
		";
		return self::getRecords($sql);
	}
	
	public static function findByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "  
			SELECT g_job.id, g_job.company_structure_id, g_job.job_specification_id , g_job.title ,g_job.is_active,
			 g_job_specification.name, g_job_specification.description,g_job_specification.duties 
			FROM g_job
			LEFT JOIN g_job_specification
			ON g_job.job_specification_id=g_job_specification.id
			WHERE g_job.company_structure_id =". Model::safeSql($csid) ." AND is_active=1
			" . $order_by . "
			" . $limit . "					
		";	
		//echo $sql;
		return self::getRecords($sql);
	}
	
	public static function searchByTitle($query, $csid) {
		$sql = "SELECT gj.id, gj.title, gj.company_structure_id 
				FROM " . G_JOB .  " gj 
				WHERE (gj.title LIKE '%{$query}%' AND gj.company_structure_id =" . Model::safeSql($csid) . ")
				";
		return self::getRecords($sql);
	}
	
	public static function findByCompanyStructureId2($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : 'ORDER BY id';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_JOB ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."	
			AND is_active=1
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
		$g = new G_Job($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setJobSpecificationId($row['job_specification_id']);	
		$g->setTitle($row['title']);		
		$g->setIsActive($row['is_active']);

		return $g;
	}
}
?>