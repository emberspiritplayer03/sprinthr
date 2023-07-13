<?php
class G_Restday_Finder {

	public static function findAllByEmployee($e) {
		$sql = "
			SELECT o.id, o.employee_id, o.date, o.time_in, o.time_out, o.reason
			FROM ". G_EMPLOYEE_RESTDAY ." o
			WHERE o.employee_id = ". Model::safeSql($e->getId()) ."
		";
		return self::getRecords($sql);
	}

	public static function findById($id) {
		$sql = "
			SELECT o.id, o.employee_id, o.date, o.time_in, o.time_out, o.reason
			FROM ". G_EMPLOYEE_RESTDAY ." o
			WHERE o.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeAndDate($e, $date) {
		$sql = "
			SELECT o.id, o.employee_id, o.date, o.time_in, o.time_out, o.reason
			FROM ". G_EMPLOYEE_RESTDAY ." o
			WHERE o.employee_id = ". Model::safeSql($e->getId()) ."
			AND date = ". Model::safeSql($date) ."
			LIMIT 1
		";
		
		return self::getRecord($sql);
	}

	public static function findByEmployeeIdAndDate($id, $date) {
		$sql = "
			SELECT o.id, o.employee_id, o.date, o.time_in, o.time_out, o.reason
			FROM ". G_EMPLOYEE_RESTDAY ." o
			WHERE o.employee_id = ". Model::safeSql($id) ."
			AND date = ". Model::safeSql($date) ."
			LIMIT 1
		";
		
		return self::getRecord($sql);
	}
	
	public static function findAllByEmployeeAndPeriod(IEmployee $e, $from, $to) {
		$sql = "
			SELECT o.id, o.employee_id, o.date, o.time_in, o.time_out, o.reason
			FROM ". G_EMPLOYEE_RESTDAY ." o
			WHERE o.employee_id = ". Model::safeSql($e->getId()) ."
			AND o.date >= ". Model::safeSql($from) ."
			AND o.date <= ". Model::safeSql($to) ."
			ORDER BY o.date DESC
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
		$o = new G_Restday;
		$o->setId($row['id']);
		$o->setDate($row['date']);
		$o->setTimeIn($row['time_in']);
		$o->setTimeOut($row['time_out']);
		$o->setEmployeeId($row['employee_id']);
		$o->setReason($row['reason']);
		return $o;
	}
}
?>