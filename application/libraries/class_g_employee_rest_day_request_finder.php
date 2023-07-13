<?php
class G_Employee_Rest_Day_Request_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_REST_DAY_REQUEST ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByEmployeeIdAndDate($employee_id, $date) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_RESTDAY ." 
			WHERE employee_id = ". Model::safeSql($employee_id) ."	
			AND date =  ". Model::safeSql($date) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_REST_DAY_REQUEST." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
		";

		return self::getRecords($sql);
	}
	
	public static function findTopRecentRequestByEmployeeId($employee_id,$sort="",$limit="") {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_REST_DAY_REQUEST." e
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
		$e = new G_Employee_Rest_Day_Request;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setScheduleId($row['schedule_id']);
		$e->setDateApplied($row['date_applied']);
		$e->setDateStart($row['date_start']);
		$e->setDateEnd($row['date_end']);
		$e->setRestDayComments($row['rest_day_comments']);
		$e->setIsApproved($row['is_approved']);
		$e->setIsArchive($row['is_archive']);
		return $e;
	}
}
?>