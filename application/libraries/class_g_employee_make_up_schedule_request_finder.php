<?php
class G_Employee_Make_Up_Schedule_Request_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 
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
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllApproved($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Make_Up_Schedule_Request::APPROVED) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPendings($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Make_Up_Schedule_Request::PENDING) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllDisApproved($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Make_Up_Schedule_Request::DISAPPROVED) . "
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
			FROM " . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findTopRecentRequestByEmployeeId($employee_id,$sort="",$limit="") {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST." e
			WHERE 
			e.employee_id = ". Model::safeSql($employee_id) ."
			$sort
			$limit
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
		$gemusr = new G_Employee_Make_Up_Schedule_Request();
		$gemusr->setId($row['id']);
		$gemusr->setEmployeeId($row['employee_id']);
		$gemusr->setDateApplied($row['date_applied']);
		$gemusr->setDateFrom($row['date_from']);	
		$gemusr->setDateTo($row['date_to']);				
		$gemusr->setStartTime($row['start_time']);	
		$gemusr->setEndTime($row['end_time']);
		$gemusr->setComment($row['comment']);					
		$gemusr->setIsApproved($row['is_approved']);				
		$gemusr->setCreatedBy($row['created_by']);				
		$gemusr->setIsArchive($row['is_archive']);				
		return $gemusr;
	}
}
?>