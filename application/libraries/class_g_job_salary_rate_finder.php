<?php
class G_Job_Salary_Rate_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_JOB_SALARY_RATE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByJobLevel($category_name) {
		$sql = "
			SELECT * 
			FROM " . G_JOB_SALARY_RATE ." 
			WHERE 	job_level =". Model::safeSql($category_name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_JOB_SALARY_RATE ."
			ORDER BY job_level ASC			
		";
		return self::getRecords($sql);
	}
	
	public static function findByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_JOB_SALARY_RATE ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."	
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
		$g = new G_Job_Salary_Rate($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setJobLevel($row['job_level']);	
		$g->setMinimumSalary($row['minimum_salary']);	
		$g->setMaximumSalary($row['maximum_salary']);	
		$g->setStepSalary($row['step_salary']);	
		return $g;
	}
}
?>