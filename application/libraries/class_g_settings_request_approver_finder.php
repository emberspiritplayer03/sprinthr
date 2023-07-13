<?php
class G_Settings_Request_Approver_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByType($type, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE type =" . Model::safeSql($type) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByPositionEmployeeId($position_employee_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE position_employee_id =" . Model::safeSql($position_employee_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByLevel($level,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE level =" . Model::safeSql($level) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findLastEntryBySettingsRequestId($settings_request_id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE settings_request_id =". Model::safeSql($settings_request_id) ."
			ORDER BY id DESC 
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllBySettingsRequestId($settings_request_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 
			WHERE settings_request_id =" . Model::safeSql($settings_request_id) . " 
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
			FROM " . G_SETTINGS_REQUEST_APPROVERS ." 			
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
		$gsra = new G_Settings_Request_Approver();
		$gsra->setId($row['id']);
		$gsra->setSettingsRequestId($row['settings_request_id']);
		$gsra->setPositionEmployeeId($row['position_employee_id']);
		$gsra->setType($row['type']);	
		$gsra->setLevel($row['level']);				
		$gsra->setOverrideLevel($row['override_level']);				
		return $gsra;
	}
}
?>