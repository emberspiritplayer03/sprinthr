<?php
class G_Leave_Error_Finder {
	
	public static function findImportError($total_error) {
		$sql = "
			SELECT id,employee_code,employee_name, date_applied, date_start, date_end, time_in,time_out,message FROM
			(SELECT id,employee_code,date_attendance,time_in,time_out,message FROM " . G_ERROR_LEAVE . " ORDER BY ID DESC LIMIT " . Model::safeSql($total_error) .")	 as db
			ORDER BY date_attendance ASC
		";
		return self::getRecords($sql);
	}
	
	public static function findAllErrorsNotFixed() {
		$sql = "
			SELECT * FROM " . G_ERROR_LEAVE . "
			WHERE is_fixed = " . Model::safeSql(NO) . "
		";
		return self::getRecords($sql);
	}
	
	public static function countAllErrorsNotFixed() {
		$sql = "
			SELECT COUNT(id) AS total FROM " . G_ERROR_LEAVE . "
			WHERE is_fixed = " . Model::safeSql(NO) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
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
		$error = new G_Leave_Error;
		$error->setId($row['id']);
		$error->setEmployeeId($row['employee_id']);
		$error->setEmployeeCode($row['employee_code']);
		$error->setEmployeeName($row['employee_name']);
		$error->setDateApplied($row['date_applied']);
		$error->setDateStart($row['date_start']);
		$error->setDateEnd($row['date_end']);
		$error->setMessage($row['message']);
		$error->setErrorTypeId($row['error_type_id']);
		
		if ($row['is_fixed']) {
			$error->setAsFixed();
		}
		
		return $error;
	}
}
?>