<?php
class G_Settings_Employee_Status_Finder {
	public static function findById($id) {
		$sql = "
			SELECT * 
				FROM " . G_SETTINGS_EMPLOYEE_STATUS . "
			WHERE id=" . Model::safeSql($id) . "
			LIMIT 1
		";		
		return self::getRecord($sql);
	}

	public static function findByName($name = '') {
		$name = strtolower($name);
		$name = trim($name);
		$sql = "
			SELECT * 
				FROM " . G_SETTINGS_EMPLOYEE_STATUS . "
			WHERE LOWER(name) = " . Model::safeSql($name) . "
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findAllIsArchiveByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ." 	
			WHERE is_archive =" . Model::safeSql(G_Settings_Employee_Status::YES) . "	
				AND company_structure_id =" . Model::safeSql($company_structure_id) . "	
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsNotArchiveByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ." 	
			WHERE is_archive =" . Model::safeSql(G_Settings_Employee_Status::NO) . "	
				AND company_structure_id =" . Model::safeSql($company_structure_id) . "	
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureId($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ." 			
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
		$gses = new G_Settings_Employee_Status();
		$gses->setId($row['id']);
		$gses->setCompanyStructureId($row['company_structure_id']);
		$gses->setName($row['name']);
		$gses->setIsArchive($row['is_archive']);		
		$gses->setDateCreated($row['date_created']);
		return $gses;
	}
}
?>