<?php
class G_Settings_Application_Status_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_APPLICATION_STATUS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByName($name) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_APPLICATION_STATUS ." 
			WHERE name =". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';

		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_APPLICATION_STATUS ." 
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
		$g = new G_Settings_Application_Status($row['id']);
		$g->setId($row['id']);
		$g->setCompanyStructureId($row['company_structure_id']);
		$g->setStatus($row['status']);	
		return $g;
	}
}
?>