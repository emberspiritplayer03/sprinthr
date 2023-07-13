<?php
class G_Eeo_Job_Category_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EEO_JOB_CATEGORY ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findByCategoryName($category_name) {
		$sql = "
			SELECT * 
			FROM " . G_EEO_JOB_CATEGORY ." 
			WHERE 	category_name =". Model::safeSql($category_name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_EEO_JOB_CATEGORY ."
			ORDER BY category_name ASC			
		";
		return self::getRecords($sql);
	}
	
	public static function findByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EEO_JOB_CATEGORY ." 
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
		$g = new G_Eeo_Job_Category($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setCategoryName($row['category_name']);
		$g->setDescription($row['description']);	
		return $g;
	}
}
?>