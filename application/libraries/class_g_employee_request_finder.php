<?php
class G_Employee_Request_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByEmployeeId($employee_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByStatus($status, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST ." 
			WHERE status =" . Model::safeSql($status) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllBySettingsRequestId($settings_request_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST ." 
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
			FROM " . G_EMPLOYEE_REQUEST ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findByRequestId($request_id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST ." 
			WHERE request_id =". Model::safeSql($request_id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
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
		$ger = new G_Employee_Request();
		$ger->setId($row['id']);
		$ger->setEmployeeId($row['employee_id']);
		$ger->setSettingsRequestId($row['settings_request_id']);
		$ger->setRequestId($row['request_id']);
		$ger->setStartDate($row['start_date']);	
		$ger->setEndDate($row['end_date']);				
		$ger->setReason($row['reason']);				
		$ger->setStatus($row['status']);			
		$ger->setDateCreated($row['date_created']);				
		return $ger;
	}
}
?>