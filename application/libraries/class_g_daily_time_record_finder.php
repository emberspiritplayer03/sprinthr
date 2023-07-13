<?php
class G_Daily_Time_Record_Finder {
	public static function findAll() {
		$sql = "
			SELECT id, employee_code, date_entry, time_entry
			FROM ". G_DAILY_TIME_RECORD ."
			ORDER BY date_entry DESC, time_entry DESC
		";
		return self::getRecords($sql);
	}
	
	public static function findAllWithLimit($limit = 20) {
		$sql = "
			SELECT id, employee_code, employee_name, date_entry, time_entry
			FROM ". G_DAILY_TIME_RECORD ."
			ORDER BY date_entry DESC, time_entry DESC
			LIMIT $limit
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
		$c = new Daily_Time_Record;
		$c->setEmployeeCode($row['employee_code']);
		$c->setEmployeeName($row['employee_name']);
		$c->setDate($row['date_entry']);
		$c->setTime($row['time_entry']);
		return $c;
	}
}
?>