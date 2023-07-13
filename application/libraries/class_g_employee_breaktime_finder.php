<?php
class G_Employee_Breaktime_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAKTIME ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByEmployeeIdAndDate($employee_id, $date) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAKTIME ." 
			WHERE employee_id =". Model::safeSql($employee_id) ." 
				AND date =". Model::safeSql($date) ." 
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAKTIME ." 			
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
		$glt = new G_Employee_Breaktime();
		$glt->setId($row['id']);
		$glt->setEmployeeId($row['employee_id']);
		$glt->setDate($row['date']);
		$glt->setTimeIn($row['time_in']);					
		$glt->setTimeOut($row['time_out']);									
		$glt->setLateHours($row['late_hours']);									
		return $glt;
	}
}
?>