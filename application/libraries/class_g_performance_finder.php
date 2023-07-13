<?php
class G_Performance_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($id) {
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE ." 
			WHERE company_structure_id =". Model::safeSql($id) ."
		";
	
		return self::getRecords($sql);
	}
	
	public static function findActivePerformance()
	{
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE ." 
			WHERE is_archive =". Model::safeSql(0) ."
		";
	
		return self::getRecords($sql);		
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_PERFORMANCE ."
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
		$gsl = new G_Performance($row['id']);
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setTitle($row['title']);
		$gsl->setJobId($row['job_id']);
		$gsl->setDescription($row['description']);
		$gsl->setDateCreated($row['date_created']);
		$gsl->setCreatedBy($row['created_by']);
		$gsl->setIsArchive($row['is_archive']);
		return $gsl;
	}
}
?>