<?php
class G_Employee_Leave_Credit_Tracking_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LEAVE_CREDIT_TRACKING ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LEAVE_CREDIT_TRACKING ." 			
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
		$lt = new G_Employee_Leave_Credit_Tracking();
		$lt->setId($row['id']);
		$lt->setEmployeeId($row['employee_id']);
		$lt->setLeaveId($row['leave_id']);
		$lt->setCredit($row['credit']);	
		$lt->setDate($row['date']);						
		return $lt;
	}
}
?>