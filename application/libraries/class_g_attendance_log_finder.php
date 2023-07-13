<?php
class G_Attendance_Log_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_ATTENDANCE_LOG ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
	public static function findByEmployeeCodeAndDate($employee_code, $date) {
		$sql = "
			SELECT * 
			FROM " . G_ATTENDANCE_LOG ." 
			WHERE employee_code =". Model::safeSql($employee_code) ." 
			AND date = ". Model::safeSql($date) ."  
			ORDER BY id DESC LIMIT 1

		";		
		return self::getRecord($sql);
	}	

	public static function findByEmployeeCodeAndDate2($employee_code, $date) {
		$sql = "
			SELECT * 
			FROM " . G_ATTENDANCE_LOG ." 
			WHERE employee_code =". Model::safeSql($employee_code) ." 
			AND date = ". Model::safeSql($date) ." 
			AND (type = 'in' OR type='out') 
			ORDER BY id DESC LIMIT 1

		";		
		return self::getRecord($sql);
	}	

	
	//use in checking ob
	public static function FindEmployeeInByDate($e, $date){

		$sql = "
			SELECT * 
			FROM " . G_ATTENDANCE_LOG ." 
			WHERE user_id =". Model::safeSql($e->getId()) ." 
			AND date = ". Model::safeSql($date) ."  
			AND type = 'in'
			ORDER BY id ASC LIMIT 1

		";		
		return self::getRecord($sql);

	}

	//use in checking ob
	public static function FindEmployeeOutByDate($e, $date){

		$sql = "
			SELECT * 
			FROM " . G_ATTENDANCE_LOG ." 
			WHERE user_id =". Model::safeSql($e->getId()) ." 
			AND date = ". Model::safeSql($date) ."  
			AND type = 'out'
			ORDER BY id DESC LIMIT 1

		";		
		return self::getRecord($sql);

	}

	
	public static function findAllWithMultipleLogsByPeriod($start_date, $end_date, $device_id = "") {
		if($device_id){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($device_id) . " ";
			if($device_id == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}

		$sql = "
			SELECT a.id, a.user_id as employee_id, a.employee_code, a.employee_name, a.date, a.time, a.type, a.remarks
			FROM (
				SELECT b.employee_code, b.date, b.type
				FROM ". G_ATTENDANCE_LOG ." b
				WHERE b.date >= ". Model::safeSql($start_date) ."
				AND b.date <= ". Model::safeSql($end_date) ."
				GROUP BY b.employee_code, b.date, b.type
				HAVING count(*) > 1) x, ". G_ATTENDANCE_LOG ." a
			WHERE x.employee_code = a.employee_code
			AND x.date = a.date
			AND x.type = a.type
			AND a.date >= ". Model::safeSql($start_date) ."
			AND a.date <= ". Model::safeSql($end_date) ." ".
			$machine_no."
			ORDER BY a.employee_code, a.date, a.type	
		";
		return self::getRecords($sql);
	}
	
	public static function findAllWithoutOutLogsByPeriod($start_date, $end_date) {		
		$logs = self::findAllByPeriod($start_date, $end_date);
		foreach ($logs as $l) {
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();
		}
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		$errors = $tr->getErrorsNoOut();
		//echo '<pre>';
		//print_r($errors);
		foreach ($errors as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('in');
				$data[$log_id] = $e;
			}
		}
		return $data;
	}
	
	public static function findAllWithoutInLogsByPeriod($start_date, $end_date) {
		$logs = self::findAllByPeriod($start_date, $end_date);
		foreach ($logs as $l) {
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();
		}
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		$errors = $tr->getErrorsNoIn();
		//echo '<pre>';
		//print_r($errors);		
		foreach ($errors as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$data[$log_id] = $e;
			}
		}
		return $data;
	}
	
	public static function findByEmployeeCodeDateTimeType($employee_code, $date, $time, $type) {
		$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE employee_code = ". Model::safeSql($employee_code) ."
			AND date = ". Model::safeSql($date) ."
			AND time = ". Model::safeSql($time) ."
			AND type = ". Model::safeSql($type) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAllByPeriod($start_date, $end_date, $device_id = "") {
		if($device_id){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($device_id) . " ";
			if($device_id == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}
		$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type, remarks
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($start_date) ."
			AND date <= ". Model::safeSql($end_date) .' '.$device_no_filter."	
			ORDER BY date, time
		";

		return self::getRecords($sql);
	}

	public static function findAllByPeriodAndLimit($start_date, $end_date, $limit = 30) {
		/*$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($start_date) ."
			AND date <= ". Model::safeSql($end_date) ."	
			ORDER BY date DESC, time DESC, id DESC
			LIMIT ". Model::safeSql($limit) ."
		";*/
		$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($start_date) ."
			AND date <= ". Model::safeSql($end_date) ."	
			ORDER BY id DESC, date DESC, time DESC
			LIMIT ". Model::safeSql($limit) ."
		";
		return self::getRecords($sql);
	}
	
	public static function findAllWithMultipleLogsByPeriodWithLimit($start_date, $end_date, $order_by, $limit) {
		$sql = "
			SELECT a.id, a.user_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, a.employee_code, a.date, a.time, a.type
			FROM (
				SELECT b.employee_code, b.date, b.type
				FROM ". G_ATTENDANCE_LOG ." b
				WHERE b.date >= ". Model::safeSql($start_date) ."
				AND b.date <= ". Model::safeSql($end_date) ."
				GROUP BY b.employee_code, b.date, b.type
				HAVING count(*) > 1) x, ". G_ATTENDANCE_LOG ." a LEFT JOIN " . EMPLOYEE . " e
				ON a.employee_code = e.employee_code  
			WHERE x.employee_code = a.employee_code
			AND x.date = a.date
			AND x.type = a.type
			AND a.date >= ". Model::safeSql($start_date) ."
			AND a.date <= ". Model::safeSql($end_date) ."
			" . $order_by . " 
			" . $limit . "	
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllWithMultipleLogsByPeriodAndEmployeeIdWithLimit($arrId, $start_date, $end_date, $order_by, $limit, $device_id = "") {
		$arrId    = explode(",",$arrId);
		$arr_size = count($arrId);		
		$counter  = 1;

		if($device_id){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($device_id) . " ";
			if($device_id == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}

		$sql = "
			SELECT a.id, a.user_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, e.id as e_employee_id, a.employee_code, a.date, a.time, a.type. a.remarks
			FROM (
				SELECT b.employee_code, b.date, b.type
				FROM ". G_ATTENDANCE_LOG ." b
				WHERE b.date >= ". Model::safeSql($start_date) ."
				AND b.date <= ". Model::safeSql($end_date) ."
				GROUP BY b.employee_code, b.date, b.type
				HAVING count(*) > 1) x, ". G_ATTENDANCE_LOG ." a LEFT JOIN " . EMPLOYEE . " e
				ON a.employee_code = e.employee_code  
			WHERE (x.employee_code = a.employee_code
			AND x.date = a.date
			AND x.type = a.type
			AND a.date >= ". Model::safeSql($start_date) ."
			AND a.date <= ". Model::safeSql($end_date) .") ".
			$device_no_filter;
			
			foreach($arrId as $key => $value){
				if($counter == 1){
				  $sql .= "AND (";
				}else{}
				
				$sql .= "e.id=" . Model::safeSql(Utilities::decrypt($value));
				
				if($counter < $arr_size){
				  $sql .= " OR ";
				}else{$sql .= ")";}
				$counter++;
			}		
			
		$sql .= " " . $order_by . " " . $limit . " ";			
		return self::getRecords($sql);
	}
	
	public static function findAllWithoutInOutLogsByPeriod($start_date, $end_date) {
		$logs = self::findAllByPeriod($start_date, $end_date);
		foreach ($logs as $l) {			
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();			
			$idn[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getEmployeeName();		
		}		
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		
		$errors_no_in  = $tr->getErrorsNoIn();		
		$errors_no_out = $tr->getErrorsNoOut();
		//echo '<pre>';
		//print_r($idn);
		foreach ($errors_no_out as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$ename  = $idn[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setEmployeeName($ename);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('in');
				$data[$log_id] = $e;
			}
		}
		
		
		//echo '<pre>';
		//print_r($errors);		
		foreach ($errors_no_in as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {				
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$data[$log_id] = $e;
			}
		}
		
		return $data;
	}
	
	public static function findAllWithoutInOutLogsByPeriodWithLimit($start_date, $end_date, $order_by, $limit) {
		$logs = self::findAllByPeriodWithLimit($start_date, $end_date, $order_by, $limit);
		foreach ($logs as $l) {			
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();			
			$idn[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getEmployeeName();		
		}		
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		
		$errors_no_in  = $tr->getErrorsNoIn();		
		$errors_no_out = $tr->getErrorsNoOut();
	
		//echo '<pre>';
		//print_r($idn);
		foreach ($errors_no_out as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$ename  = $idn[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setEmployeeName($ename);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('in');
				$data[$log_id] = $e;
			}
		}
		
		
		/*//echo '<pre>';
		//print_r($errors);
				
		foreach ($errors_no_out as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {								
				$log_id = $ids[$counter . $employee_code][$date .'-'. $time];
				$ename  = $idn[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setEmployeeName($ename);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$data[$log_id] = $e;
				$counter++;
			}
		}*/
		
		return $data;
	}
	
	public static function findAllWithoutInOutLogsByPeriodAndEmployeeIdWithLimitDebug($arrId,$start_date, $end_date, $order_by, $limit) {
		$logs = self::findAllByPeriodAndEmployeeIdWithLimit($arrId,$start_date, $end_date, $order_by, $limit,"GROUP BY e.employee_code,date");
		$counter = 1;		
		foreach ($logs as $l) {			
			$timesheets[$counter . '_' . $l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$counter . '_' . $l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();			
			$idn[$counter . '_' . $l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getEmployeeName();					
			$counter++;
		}			
							
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter_sub();
		
		$errors_no_out = $tr->getErrorsNoOut();
		$errors_no_in  = $tr->getErrorsNoIn();		
		/*echo '<pre>';
		print_r($logs);
		exit;*/
		foreach ($errors_no_out as $employee_code => $timesheet) {						
			foreach ($timesheet as $date => $time) {							
					$log_id = $ids[$employee_code][$date .'-'. $time];
					$ename  = $idn[$employee_code][$date .'-'. $time];
					$eCode  = explode("_",$employee_code);
					$employee_code = $eCode[1];					
					$e = new G_Attendance_Log;
					$e->setId($log_id);
					$e->setEmployeeCode($employee_code);
					$e->setEmployeeName($ename);
					$e->setDate($date);
					$e->setTime($time);
					$e->setType('in');					
					$data[$log_id] = $e;
			}		
		}
		
		
		//echo '<pre>';
		//print_r($errors);		
		
		/*foreach ($errors_no_in as $employee_code => $timesheet) {
			$counter = 1;
			foreach ($timesheet as $date => $time) {
					$log_id = $ids[$employee_code][$date .'-'. $time];
					$ename  = $idn[$employee_code][$date .'-'. $time];
					
					$eCode  = explode("_",$employee_code);
					$employee_code = $eCode[1];					
					
					$e = new G_Attendance_Log;
					$e->setId($log_id);
					$e->setEmployeeCode($employee_code);
					$e->setEmployeeName($ename);
					$e->setDate($date);
					$e->setTime($time);
					$e->setType('out');
					$data[$log_id] = $e;
			}
		}*/
		
		return $data;
	}
	
	public static function findAllWithoutInOutLogsByPeriodAndEmployeeIdWithLimit($arrId,$start_date, $end_date, $order_by, $limit) {
		$logs = self::findAllByPeriodAndEmployeeIdWithLimit($arrId,$start_date, $end_date, $order_by, $limit);
		$counter = 1;		
		foreach ($logs as $l) {			
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();			
			$idn[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getEmployeeName();		
			$counter++;
		}		
				
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		
		$errors_no_in  = $tr->getErrorsNoIn();		
		$errors_no_out = $tr->getErrorsNoOut();
		
		/*echo '<pre>';
		print_r($logs);
		exit;*/
		foreach ($errors_no_out as $employee_code => $timesheet) {
			$counter = 1;
			foreach ($timesheet as $date => $time) {
				//for($x=1;$x<=$counter;$x++){
					$log_id = $ids[$employee_code][$date .'-'. $time];
					$ename  = $idn[$employee_code][$date .'-'. $time];
					$e = new G_Attendance_Log;
					$e->setId($log_id);
					$e->setEmployeeCode($employee_code);
					$e->setEmployeeName($ename);
					$e->setDate($date);
					$e->setTime($time);
					$e->setType('in');
					$data[$log_id] = $e;
					//$x++;
				//}
			}		
		}
		
		
		//echo '<pre>';
		//print_r($errors);		
		foreach ($errors_no_in as $employee_code => $timesheet) {
			$counter = 1;
			foreach ($timesheet as $date => $time) {				
				//for($x=1;$x<=$counter;$x++){
					$log_id = $ids[$employee_code][$date .'-'. $time];
					$ename  = $idn[$employee_code][$date .'-'. $time];
					$e = new G_Attendance_Log;
					$e->setId($log_id);
					$e->setEmployeeCode($employee_code);
					$e->setEmployeeName($ename);
					$e->setDate($date);
					$e->setTime($time);
					$e->setType('out');
					$data[$log_id] = $e;
					//$x++;
				//}
			}
		}
		
		return $data;
	}
	
	public static function findAllByPeriodWithLimit($start_date, $end_date, $order_by, $limit) {
		$sql = "
			SELECT al.id, al.user_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.employee_code, al.date, al.time, al.type
			FROM ". G_ATTENDANCE_LOG ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_code = e.employee_code  
			WHERE al.date >= ". Model::safeSql($start_date) ."
			AND al.date <= ". Model::safeSql($end_date) ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
		

	public static function findAllByPeriodAndEmployeeIdWithLimit($arrId,$start_date,$end_date,$order_by,$limit,$group, $device_id = "") {			
		if($arrId != ''){
			$arrId    = explode(",",$arrId);			
			$arr_size = count($arrId);				
			$counter  = 1;	
		}
		if($device_id){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($device_id) . " ";
			if($device_id == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}
		$sql = "
			SELECT al.id, al.user_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, e.id as e_employee_id, al.employee_code, al.date, al.time, al.type, al.remarks
			FROM ". G_ATTENDANCE_LOG ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_code = e.employee_code  
			WHERE (al.date >= ". Model::safeSql($start_date) ."
			AND al.date <= ". Model::safeSql($end_date) . ") ".$device_no_filter;
				foreach($arrId as $key => $value){
					if($counter == 1){
					  $sql .= "AND (";
					}else{}
					
					$sql .= "e.id=" . Model::safeSql(Utilities::decrypt($value));
					
					if($counter < $arr_size){
					  $sql .= " OR ";
					}else{ $sql .= ")";}
					$counter++;
				}					
		$sql .=	" " . $order_by . " " . $group . " " . $limit . " ";	
		
		return self::getRecords($sql);
	}
	
	
	public static function findAllYesterdayUntilNow() {
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		$now = date('Y-m-d', strtotime('now'));
		$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($yesterday) ."
			AND date <= ". Model::safeSql($now) ."
			ORDER BY date, time
		";
		return self::getRecords($sql);
	}
	
	public static function findAllNow() {
		$date = date('Y-m-d', strtotime('now'));
		$sql = "
			SELECT id, user_id as employee_id, employee_code, employee_name, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($date) ."
			ORDER BY date, time
		";
		return self::getRecords($sql);
	}	
	
	public static function findAllByPeriodWithBreakLogs($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "") {
		$implode_employee_ids = '';
		$implode_main_log_ids = '';
		$implode_break_log_ids = '';

		if (count($employee_ids) > 0) {
			$implode_employee_ids = ' AND e.id IN (' . implode(',', $employee_ids) . ')';
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
				al.id, al.user_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, al.employee_code, al.date, al.time, al.type, al.remarks,SUBSTRING_INDEX(`remarks`, ':', -1 ) as device_no
			FROM ". G_ATTENDANCE_LOG ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_code = e.employee_code  
			WHERE 
				al.date >= ". Model::safeSql($start_date) ." AND 
				al.date <= ". Model::safeSql($end_date) ." 
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			UNION
			SELECT 
				abl.id, abl.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, abl.employee_code, abl.date, abl.time, abl.type, abl.remarks,SUBSTRING_INDEX(`remarks`, ':', -1 ) as device_no
			FROM ". G_EMPLOYEE_BREAK_LOGS ." abl LEFT JOIN " . EMPLOYEE . " e
				ON abl.employee_code = e.employee_code  
			WHERE 
				abl.date >= ". Model::safeSql($start_date) ." AND 
				abl.date <= ". Model::safeSql($end_date) ." 
				". $implode_employee_ids ."	
				". $implode_break_log_ids ."
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
		$e = new G_Attendance_Log;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id'] == NULL ? $row['user_id'] : $row['employee_id']);
		$e->setEmployeeCode($row['employee_code']);
		$e->setEmployeeName($row['employee_name']);
		$e->setDate($row['date']);
		$e->setTime($row['time']);
		$e->setType(strtolower($row['type']));
		$e->setRemarks(strtolower($row['remarks']));
		return $e;
	}



	//dtr module getting device no
	public static function findDevice($machine_no)
	{
		$sql = 
		"
			SELECT * FROM zk_device where machine_no = ".Model::safeSql($machine_no)." LIMIT 1 
		";


		$result = Model::runSql($sql);

		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$model = (object)$row;	
		return $model;
	}

	public static function getDevices()
	{
		$sql = 
		"
			SELECT * FROM zk_device
		";

		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return [];	
		}
		$collection = [];

		while ($model = Model::fetchAssoc($result)) 
		{
			$collection[$model['id']] = (object)$model;
		}

		return $collection;
	}



}
?>