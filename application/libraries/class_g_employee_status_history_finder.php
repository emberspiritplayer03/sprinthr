<?php
class G_Employee_Status_History_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findCurrentEmployeeStatus($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_id =". Model::safeSql($id) ."
			AND end_date = ''
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findCurrentEmployeeStatusWithStatusId($id, $status_id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_id =". Model::safeSql($id) ."
			AND employee_status_id =". Model::safeSql($status_id) ." 
			AND end_date = ''
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findInactiveEmployeeByIdAndInBetweenDates($e, $start_date, $end_date) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(5) ." 
			AND employee_id = ". Model::safeSql($e->getId()) ." 
			AND " . Model::safeSql($start_date) . " >= start_date
			AND " . Model::safeSql($end_date) . " <= end_date
			AND end_date != ''
			AND start_date != ''
			LIMIT 1
		";

		return self::getRecord($sql);
	}

	public static function findInactiveEmployeeInBetweenDates($start_date, $end_date) {
		/*$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(5) ." 
			AND start_date BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . "
			AND end_date BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . "
			AND end_date != ''
			AND start_date != ''
		";*/

		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(5) ." 
			AND " . Model::safeSql($start_date) . " >= start_date
			AND " . Model::safeSql($end_date) . " <= end_date
			AND end_date != ''
			AND start_date != ''
		";

		return self::getRecords($sql);
	}			
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 			
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
		$gel = new G_Employee_Status_History();
		$gel->setId($row['id']);
		$gel->setEmployeeId($row['employee_id']);
		$gel->setEmployeeStatusId($row['employee_status_id']);
		$gel->setStatus($row['status']);				
		$gel->setStartDate($row['start_date']);				
		$gel->setEndDate($row['end_date']);
		return $gel;
	}
}
?>