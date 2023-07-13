<?php
class G_Employee_Attendance_Finder_V2 {
	
	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_ATTENDANCE_V2 ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
	
	public static function findAllNoTimeOutByToday($start_date, $end_date, $device_id = "", $project_site_id) {
		if($device_id){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($device_id) . " ";
			if($device_id == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE date_attendance >= ". Model::safeSql($start_date) ."
			AND date_attendance <= ". Model::safeSql($end_date) .' '.$device_no_filter."
			AND actual_time_out = ''
			AND project_site_id = ". Model::safeSql($project_site_id) ."
			ORDER BY date_attendance, actual_time_in DESC
			LIMIT 1
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByPeriod($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.schedule_type 	= ". Model::safeSql($schedule_type) ."
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByPeriodNoErrors($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.has_error 		= '0' AND
				al.schedule_type 	= ". Model::safeSql($schedule_type) ."
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllScheduleMain($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.schedule_type 	!= 'Leave' AND
				al.schedule_type 	!= 'OB' AND
				al.schedule_type 	!= 'Rest Day'
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllScheduleNoError($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.has_error 		= '0' AND
				al.schedule_type 	!= 'Leave' AND
				al.schedule_type 	!= 'OB' AND
				al.schedule_type 	!= 'Rest Day'
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllScheduleIncompleteLogs($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.has_error 		= '1' AND
				(al.error_message 	= 'multiple in' OR
				al.error_message 	= 'multiple out') AND
				al.schedule_type 	!= 'Leave' AND
				al.schedule_type 	!= 'OB' AND
				al.schedule_type 	!= 'Rest Day'
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllScheduleMultipleIn($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.has_error 		= '1' AND
				al.error_message 	= 'multiple in' AND
				al.schedule_type 	!= 'Leave' AND
				al.schedule_type 	!= 'OB' AND
				al.schedule_type 	!= 'Rest Day'
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllScheduleMultipleOut($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "", $schedule_type) {
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
				$implode_main_log_ids = ' AND al.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND al.id IN (0)';
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
				al.id, al.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.date_attendance, al.employee_schedule_id, al.schedule_type, al.actual_time_in, al.actual_time_out, al.project_site_id, al.activity_id, al.has_error, al.error_message
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_id = e.id  
			WHERE 
				al.date_attendance 	= ". Model::safeSql($start_date) ." AND 
				al.has_error 		= '1' AND
				al.error_message 	= 'multiple out' AND
				al.schedule_type 	!= 'Leave' AND
				al.schedule_type 	!= 'OB' AND
				al.schedule_type 	!= 'Rest Day'
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
		$e = new G_Employee_Attendance_V2;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id'] == NULL ? $row['user_id'] : $row['employee_id']);
		$e->setDate($row['date_attendance']);
		$e->setEmployeeScheduleId($row['employee_schedule_id']);
		$e->setScheduleType($row['schedule_type']);
		$e->setTimeIn($row['actual_time_in']);
		$e->setTimeOut($row['actual_time_out']);
		$e->setProjectSiteId(strtolower($row['project_site_id']));
		$e->setActivityId(strtolower($row['activity_id']));
		$e->setHasError(strtolower($row['has_error']));
		$e->setErrorMessage(strtolower($row['error_message']));
		return $e;
	}

}
?>