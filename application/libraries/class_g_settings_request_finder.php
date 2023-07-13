<?php
class G_Settings_Request_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllActiveApproversByType($type, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE request_type =" . Model::safeSql($type) . " AND 
			is_active 	= " . Model::safeSql(Settings_Request::YES) . " AND
			is_archive 	= " . Model::safeSql(Settings_Request::NO) . "
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	public static function findByType($type) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE request_type =" . Model::safeSql($type) . "
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAllByIsActive($is_active,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE is_active =" . Model::safeSql($is_active) . "
			".$order_by."
			".$limit."		
		";

		return self::getRecords($sql);
	}
	
	public static function findAllByIsNotArchiveAndIsActive($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE is_archive =" . Model::safeSql(G_Settings_Request::NO) . " AND is_active =" . Model::safeSql(G_Settings_Request::YES) . "
			".$order_by."
			".$limit."		
		";

		return self::getRecords($sql);
	}
	
	public static function findAllByIsArchive($is_archive,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST ." 
			WHERE is_archive =" . Model::safeSql($is_archive) . "
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
			FROM " . G_SETTINGS_REQUEST ." 			
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
		$gsr = new G_Settings_Request();
		$gsr->setId($row['id']);
		$gsr->setTitle($row['title']);
		$gsr->setType($row['request_type']);		
		$gsr->setDepartments($row['applied_to_departments']);
		$gsr->setPositions($row['applied_to_positions']);
		$gsr->setEmployees($row['applied_to_employees']);
		$gsr->setDescription($row['applied_to_description']);		
		$gsr->setIsActive($row['is_active']);	
		$gsr->setIsArchive($row['is_archive']);	
		$gsr->setDateCreated($row['date_created']);				
		return $gsr;
	}
}
?>