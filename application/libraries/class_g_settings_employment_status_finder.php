<?php 
class G_Settings_Employment_Status_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYMENT_STATUS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByStatus($name) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYMENT_STATUS ." 
			WHERE status =". Model::safeSql($name) ."
		";
		return self::getRecord($sql);
	}

	public static function searchAllEmploymentStatus($status) {
		
		$sql = "
			SELECT * 
			FROM " . EMPLOYMENT_STATUS ." 	
			WHERE status LIKE '%{$status}%'		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';

		$sql = "
			SELECT * 
			FROM " . EMPLOYMENT_STATUS ." 
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	public static function findByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . EMPLOYMENT_STATUS ." 
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
		$g = new G_Settings_Employment_Status($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setCode($row['code']);
		$g->setStatus($row['status']);	
		return $g;
	}
}
?>