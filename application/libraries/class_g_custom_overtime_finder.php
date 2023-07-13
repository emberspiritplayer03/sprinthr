<?php
class G_Custom_Overtime_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_CUSTOM_OVERTIME ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByEmployeeIdAndDate($employee_id = '', $date = '') {
		$sql = "
			SELECT * 
			FROM " . G_CUSTOM_OVERTIME ." 
			WHERE employee_id =". Model::safeSql($employee_id) ."
				AND date =" . Model::safeSql($date) . "
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_CUSTOM_OVERTIME ." 			
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
		$co = new G_Custom_Overtime();
		$co->setId($row['id']);
		$co->setEmployeeId($row['employee_id']);
		$co->setDate($row['date']);
		$co->setStartTime($row['start_time']);					
		$co->setEndTime($row['end_time']);					
		$co->setDayType($row['day_type']);	
		$co->setStatus($row['status']);						
		return $co;
	}
}
?>