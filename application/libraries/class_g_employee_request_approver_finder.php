<?php
class G_Employee_Request_Approver_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		
		return self::getRecord($sql);
	}	
	
	public static function findAllByEmployeeRequestId($employee_request_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE employee_request_id =" . Model::safeSql($employee_request_id) . "
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
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE status =" . Model::safeSql($status) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllApproversByRequestTypeRequestTypeIdType($request_type, $request_type_id, $type, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE
			request_type 	= " . Model::safeSql($request_type) . " AND
			request_type_id = " . Model::safeSql($request_type_id) . " AND 
			type 			= " . Model::safeSql($type) . "
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByRequestTypeRequestTypeIdPositionEmployeeId($request_type, $request_type_id, $position_employee_id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE
			request_type 			= " . Model::safeSql($request_type) . " AND
			request_type_id 		= " . Model::safeSql($request_type_id) . " AND
			position_employee_id 	= " . Model::safeSql($position_employee_id) . "
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAllByPositionEmployeeId($position_employee_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
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
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE level =" . Model::safeSql($level) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByRequestTypeRequestTypeId($request_type, $request_type_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE 
			request_type =" . Model::safeSql($request_type) . " AND
			request_type_id =" . Model::safeSql($request_type_id) . "
			".$order_by."
			".$limit."
		";
		
		return self::getRecords($sql);
	}
	
	public static function findByRequestTypeRequestTypeIdLevel($request_type, $request_type_id, $level) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 
			WHERE
			request_type 		= " . Model::safeSql($request_type) . " AND
			request_type_id 	= " . Model::safeSql($request_type_id) . " AND
			level 				= " . Model::safeSql($level) . "
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ." 			
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
		$gera = new G_Employee_Request_Approver();
		$gera->setId($row['id']);
		$gera->setRequestType($row['request_type']);
		$gera->setRequestTypeId($row['request_type_id']);
		$gera->setPositionEmployeeId($row['position_employee_id']);
		$gera->setType($row['type']);
		$gera->setLevel($row['level']);
		$gera->setOverrideLevel($row['override_level']);
		$gera->setMessage($row['message']);
		$gera->setStatus($row['status']);
		$gera->setRemarks($row['remarks']);
		return $gera;
	}
}
?>