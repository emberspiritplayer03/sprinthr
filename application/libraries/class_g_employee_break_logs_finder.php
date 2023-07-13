<?php
class G_Employee_Break_logs_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAK_LOGS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
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
	
	private static function newObject($row) {
		
		$e = new G_Employee_Break_Logs;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setEmployeeCode($row['employee_code']);
		$e->setEmployeeName($row['employee_name']);
		$e->setDate($row['date']);
		$e->setTime($row['time']);
		$e->setType($row['type']);
		$e->setRemarks($row['remarks']);
		$e->setSync($row['sync']);
		$e->setIsTransferred($row['is_transferred']);
		$e->setEmployeeDeviceId($row['employee_device_id']);

        return $e;
	}

}
?>