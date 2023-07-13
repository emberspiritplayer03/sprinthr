<?php
class G_Employee_Attendance_Correction_Request_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
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
		$e = new G_Employee_Attendance_Correction_Request;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setDateApplied($row['date_applied']);
		$e->setDateIn($row['date_in']);
		$e->setTimeIn($row['time_in']);
		$e->setTimeOut($row['time_out']);
		$e->setCorrectDateIn($row['correct_date_in']);
		$e->setCorrectTimeIn($row['correct_time_in']);
		$e->setCorrectTimeOut($row['correct_time_out']);
		$e->setComment($row['comment']);
		$e->setIsApproved($row['is_approved']);
		$e->setIsArchive($row['is_archive']);
		return $e;
	}
}
?>