<?php
class G_Employee_Leave_Request_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT
				*
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." e
			WHERE e.id = ". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    public static function findByEmployeeIdAndLeaveDate($employee_id, $leave_date) {
        $sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			AND ". Model::safeSql($leave_date) ."
			BETWEEN e.date_start AND e.date_end
			ORDER BY e.id DESC
			LIMIT 1
		";
        return self::getRecord($sql);
    }

    public static function findByEmployeeIdAndLeaveDateHalfday($employee_id, $leave_date) {
        $sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			AND apply_half_day_date_start = 'Yes'
			AND ". Model::safeSql($leave_date) ."
			BETWEEN e.date_start AND e.date_end
			ORDER BY e.id DESC
			LIMIT 1
		";
        return self::getRecord($sql);
    }    

    public static function findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
            AND e.date_start = ". Model::safeSql($start_date) ."
            AND e.date_end = ". Model::safeSql($end_date) ."
			LIMIT 1
		";
		return self::getRecord($sql);
    }
	
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LEAVE_REQUEST." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
		";

		return self::getRecords($sql);
	}

	public static function findTopRecentRequestByEmployeeId($employee_id,$sort="",$limit="") {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST." e
			WHERE 
			e.employee_id = ". Model::safeSql($employee_id) ."
			$sort
			$limit
		";
		return self::getRecords($sql);
	}
	
	public static function findAllTopRecentRequest($is_approved,$sort="",$limit="") {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST." e
			WHERE 
			e.is_approved = " . Model::safeSql($is_approved) . "
			$sort
			$limit
		";
		return self::getRecords($sql);
	}
	
	public static function findDuplicateLeaveRequestUsingEmployeeIdLeaveTypeAndDatePeriod($employee_id,$leave_id,$date_start,$date_end) {
		$sql = "
			SELECT id
			FROM ". G_EMPLOYEE_LEAVE_REQUEST." e
			WHERE
				employee_id = " . Model::safeSql($employee_id) . " AND
				leave_id 	= " . Model::safeSql($leave_id) . " AND
				date_start 	= " . Model::safeSql($date_start) . " AND
				date_end 	= " . Model::safeSql($date_end) . "
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAllActiveLeaveByFromTo($from, $to) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_REQUEST." e
			WHERE
			date_start >= ". Model::safeSql($from) ." AND 
			date_end <= ". Model::safeSql($to) ." AND
			is_archive = " . Model::safeSql(G_Employee_Overtime_Request::NO) . "
		";
		return self::getRecords($sql);
	}

	public static function findAllByPeriodNoErrors($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "") {
		$implode_employee_ids = '';
		$implode_main_log_ids = '';
		$implode_break_log_ids = '';

		if (count($employee_ids) > 0) {
			$implode_employee_ids = ' AND e.id IN (' . implode(',', $employee_ids) . ')';
		}

		if ($filter > 0) {
			$implode_filter_department = ' AND e.department_company_structure_id IN (' . $filter . ')';
		}

		if (count($log_ids) > 0) {
			if (count($log_ids['main']) > 0) {
				$implode_main_log_ids = ' AND el.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND el.id IN (0)';
			}

			if (count($log_ids['break']) > 0) {
				$implode_break_log_ids = ' AND abl.id IN (' . implode(',', $log_ids['break']) . ')';
			}
			else {
				$implode_break_log_ids = ' AND abl.id IN (0)';
			}
		}

		if($machine_no){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($machine_no) . " ";
			if($machine_no == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}

		$sql = "
			SELECT 
				el.id, el.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, el.date_applied, el.date_start, el.date_end, el.time_applied, el.id, el.is_approved
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." el LEFT JOIN " . EMPLOYEE . " e
				ON el.employee_id = e.id  
			WHERE 
				el.date_start <= ". Model::safeSql($start_date) ." AND
				el.date_end >= ". Model::safeSql($start_date) ."
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
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

		$e = new G_Employee_Leave_Request;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setLeaveId($row['leave_id']);
		$e->setDateApplied($row['date_applied']);
        $e->setTimeApplied($row['time_applied']);
		$e->setDateStart($row['date_start']);
		$e->setDateEnd($row['date_end']);
		$e->setApplyHalfDayDateStart($row['apply_half_day_date_start']);
		$e->setApplyHalfDayDateEnd($row['apply_half_day_date_end']);
		$e->setLeaveComments($row['leave_comments']);
		$e->setIsApproved($row['is_approved']);
		$e->setIsPaid($row['is_paid']);
		$e->setCreatedBy($row['created_by']);
		$e->setIsArchive($row['is_archive']);
		return $e;
	}
}
?>