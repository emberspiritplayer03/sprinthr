<?php
class G_Attendance_Error_Finder {
	
	public static function findNotFixedByEmployeeCodeAndDate($employee_code, $date) {
		$sql = "
			SELECT id, employee_id, employee_code, date_attendance, message, is_fixed, error_type_id
			FROM ". G_ATTENDANCE_ERROR ." e
			WHERE employee_code = ". Model::safeSql($employee_code) ."	
			AND date_attendance = ". Model::safeSql($date) ."	
			AND is_fixed = ". NO ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findNotFixedByEmployeeCodeAndDateAndErrorType($employee_code, $date, $error_type_id) {
		$sql = "
			SELECT id, employee_id, employee_code, date_attendance, message, is_fixed, error_type_id
			FROM ". G_ATTENDANCE_ERROR ." e
			WHERE employee_code = ". Model::safeSql($employee_code) ."	
			AND date_attendance = ". Model::safeSql($date) ."	
			AND is_fixed = ". NO ."
			AND error_type_id = ". Model::safeSql($error_type_id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAllNotFixedByPeriodAndErrorType($date_start, $date_end, $error_type_id) {
		$sql = "
			SELECT id, employee_id, employee_code, date_attendance, message, is_fixed, error_type_id
			FROM ". G_ATTENDANCE_ERROR ." e
			WHERE date_attendance >= ". Model::safeSql($date_start) ."	
			AND date_attendance <= ". Model::safeSql($date_end) ."
			AND is_fixed = ". NO ."
			AND error_type_id = ". Model::safeSql($error_type_id) ."
			ORDER BY date_attendance	
		";
		return self::getRecords($sql);
	}	
	
	public static function findAllNoTimeInAndOutNotFixedByPeriod($date_start, $date_end) {
		$sql = "
			SELECT id, employee_id, employee_code, date_attendance, message, is_fixed, error_type_id
			FROM ". G_ATTENDANCE_ERROR ." e
			WHERE date_attendance >= ". Model::safeSql($date_start) ."	
			AND date_attendance <= ". Model::safeSql($date_end) ."
			AND is_fixed = ". NO ."
			AND (error_type_id = ". Model::safeSql(G_Attendance_Error::ERROR_NO_OUT) ." OR error_type_id = ". Model::safeSql(G_Attendance_Error::ERROR_NO_IN) .")
			ORDER BY date_attendance	
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
		$error = new G_Attendance_Error;
		$error->setId($row['id']);
		$error->setMessage($row['message']);
		$error->setErrorTypeId($row['error_type_id']);
		$error->setDate($row['date_attendance']);
		if ($row['is_fixed']) {
			$error->setAsFixed();
		}
		$error->setEmployeeId($row['employee_id']);
		$error->setEmployeeCode($row['employee_code']);
		return $error;
	}
}
?>