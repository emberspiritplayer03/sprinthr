<?php
class G_Attendance_Log_Helper {
	/*
		$logs = returned value from G_Attendance_Log_Finder::findAllYesterday();
	*/
	public static function convertLogsToTimesheets($logs) {
		foreach ($logs as $log) {
			$time = date('H:i:s', strtotime($log->getTime()));
			$date = $log->getDate();
			$employee_code = $log->getEmployeeCode();
			$type = $log->getType();
			$timesheets[$employee_code][$type][$date][$time] = $date;
		}
		return $timesheets;
	}

	public static function sqlGetDataById($id = 0) {
		$sql = "
			SELECT  fp.user_id, fp.employee_code, fp.date, fp.time, fp.type
			FROM ". G_ATTENDANCE_LOG ." fp
			WHERE fp.id =" . Model::safeSql($id) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetAllLogsByDate( $date = '' ) {
		$log_date = date("Y-m-d",strtotime($date));

		$sql = "
			SELECT  employee_code, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date =" . Model::safeSql($log_date) . "			
		";

		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetAllLogsNotTransferredByDate( $date = '' ) {
		$log_date = date("Y-m-d",strtotime($date));

		$sql = "
			SELECT  employee_code, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date =" . Model::safeSql($log_date) . "	
				AND is_transferred =" . Model::safeSql(G_Attendance_Log::ISNOT_TRANSFERRED) . "		
		";		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetAllLogsNotTransferredByDateRange( $from = '', $to = '' ) {
		$value_from = date("Y-m-d",strtotime($from));
		$value_to   = date("Y-m-d",strtotime($to));

		$sql = "
			SELECT  employee_code, date, time, type
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date BETWEEN " . Model::safeSql($value_from) . " AND " . Model::safeSql($value_to) . "
				AND is_transferred =" . Model::safeSql(G_Attendance_Log::ISNOT_TRANSFERRED) . "		
		";			
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlTransferBreakInFpLogToBreakLog( $from = '', $to = '' ) {
		$value_from = date("Y-m-d",strtotime($from));
		$value_to   = date("Y-m-d",strtotime($to));

		$sql = "insert into g_employee_break_logs 
		(id,employee_id,employee_code,employee_name,date, time, type, sync, is_transferred, employee_device_id)
		SELECT id, user_id,employee_code,employee_name,date, time, type, sync, is_transferred, employee_device_id
		FROM `g_fp_attendance_log`
		where `type`not in ('in', 'out')
		AND date BETWEEN " . Model::safeSql($value_from) . " AND " . Model::safeSql($value_to) . "
		AND is_transferred = 0";

		Model::runSql($sql);

		if (mysql_errno() > 0) {
            var_dump(mysql_error());exit;
            //return false;
        }
	}
	
	public static function countAllByPeriod($start_date, $end_date) {		
		$sql = "
			SELECT  COUNT(*) AS total
			FROM ". G_ATTENDANCE_LOG ."
			WHERE date >= ". Model::safeSql($start_date) ."
			AND date <= ". Model::safeSql($end_date) ."				
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countAllByPeriodAndEmployeeId($arrId, $start_date, $end_date) {	
		$arrId    = explode(",",$arrId);			
		$arr_size = count($arrId);		
		$counter  = 1;
		
		$sql = "
			SELECT  COUNT(*) AS total
			FROM ". G_ATTENDANCE_LOG ." al LEFT JOIN " . EMPLOYEE . " e
				ON al.employee_code = e.employee_code  
			WHERE (al.date >= ". Model::safeSql($start_date) ."
			AND al.date <= ". Model::safeSql($end_date) . ") ";
		
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
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		return $row['total'];
	}
	
	public static function countAllIncompleteSwipeByPeriodAndEmployeeId($arrId,$start_date, $end_date) {				
		$logs = G_Attendance_Log_Finder::findAllByPeriodAndEmployeeIdWithLimit($arrId,$start_date, $end_date);
		
		foreach ($logs as $l) {
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();
		}
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		
		$errors_no_in  = $tr->getErrorsNoIn();		
		$errors_no_out = $tr->getErrorsNoOut();
		
		//echo '<pre>';
		//print_r($errors);		
		$counter = 0;
		
		foreach ($errors_no_out as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$counter++;
			}
		}
		
		foreach ($errors_no_in as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$counter++;
			}
		}
		return $counter;
	}
	
	public static function countAllIncompleteSwipeByPeriodAndEmployeeIdDebug($arrId,$start_date, $end_date,$limit) {
		
		$logs = G_Attendance_Log_Finder::findAllByPeriodAndEmployeeIdWithLimit($arrId,$start_date, $end_date, $order_by, $limit,"GROUP BY e.employee_code,date");
		$counter = 1;		
		foreach ($logs as $l) {			
			$timesheets[$counter . '_' . $l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$counter . '_' . $l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();						
			$counter++;
		}			
							
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter_sub();
		
		$errors_no_out = $tr->getErrorsNoOut();
		$errors_no_in  = $tr->getErrorsNoIn();		
		
		$total_records = 0;
		foreach ($errors_no_out as $employee_code => $timesheet) {						
			foreach ($timesheet as $date => $time) {	
				$total_records++;
			}		
		}
								
		return $total_records;
	}
	
	public static function countAllIncompleteSwipeByPeriod($start_date, $end_date) {				
		$logs = G_Attendance_Log_Finder::findAllByPeriodWithLimit($start_date, $end_date);
		
		foreach ($logs as $l) {
			$timesheets[$l->getEmployeeCode()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
			$ids[$l->getEmployeeCode()][$l->getDate().'-'.$l->getTime()] = $l->getId();
		}
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filter();
		
		$errors_no_in  = $tr->getErrorsNoIn();		
		$errors_no_out = $tr->getErrorsNoOut();
		
		//echo '<pre>';
		//print_r($errors);		
		$counter = 0;
		
		foreach ($errors_no_out as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$counter++;
			}
		}
		
		foreach ($errors_no_in as $employee_code => $timesheet) {
			foreach ($timesheet as $date => $time) {
				$log_id = $ids[$employee_code][$date .'-'. $time];
				$e = new G_Attendance_Log;
				$e->setId($log_id);
				$e->setEmployeeCode($employee_code);
				$e->setDate($date);
				$e->setTime($time);
				$e->setType('out');
				$counter++;
			}
		}
		return $counter;
	}
	
	public static function countAllWithMultipleLogsAndEmployeeIdByPeriod($arrId, $start_date, $end_date) {				
		$arrId    = explode(",",$arrId);			
		$arr_size = count($arrId);		
		$counter  = 1;
		
		$sql = "
			SELECT COUNT(*) AS total
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
			AND a.date <= ". Model::safeSql($end_date) .") ";
		
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
			
		$sql .= " ORDER BY a.employee_code, a.date, a.type";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countAllWithMultipleLogsByPeriod($start_date, $end_date) {				
		$sql = "
			SELECT COUNT(*) AS total
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
			AND a.date <= ". Model::safeSql($end_date) ."
			ORDER BY a.employee_code, a.date, a.type	
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countLogByEmployeeCodeDateTimeType($employee_code, $date, $time, $type) {
		$sql = "
			SELECT COUNT(*) AS total
			FROM ". G_ATTENDANCE_LOG ."
			WHERE employee_code = ". Model::safeSql($employee_code) ."
			AND date = ". Model::safeSql($date) ."
			AND time = ". Model::safeSql($time) ."
			AND type = ". Model::safeSql($type) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countAllByPeriodWithBreakLogs($start_date, $end_date, $employee_ids = array()) {	
		$implode_employee_ids = '';

		if (count($employee_ids) > 0) {
			$implode_employee_ids = ' AND e.id IN (' . implode(',', $employee_ids) . ')';
		}

		$sql = "
			SELECT 
				COUNT(*) AS total
			FROM 
				". G_ATTENDANCE_LOG ."
			WHERE 
				date >= ". Model::safeSql($start_date) ." AND 
				date <= ". Model::safeSql($end_date) ."			
				". $implode_employee_ids ."	
			UNION
			SELECT 
				COUNT(*) AS total
			FROM 
				". G_EMPLOYEE_BREAK_LOGS ."
			WHERE 
				date >= ". Model::safeSql($start_date) ." AND 
				date <= ". Model::safeSql($end_date) ."			
				". $implode_employee_ids ."	
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getLogPairs($froms, $tos, $from_keys, $tos_keys)
	{
		$pairs = [];
		foreach($from_keys as $key => $from)
		{
			$virtual_tos = [];
			$virtual_tos = $tos_keys;
			$virtual_tos[] = $from;

			sort($virtual_tos);
			$to = null;
			foreach ($virtual_tos as $item) 
			{
				$item = strtotime($item);
				if(strtotime($from) < $item) 
				{
					if ($to === null || abs(strtotime($from) - strtotime($to)) > abs($item - strtotime($from))) {
						$to = date('Y-m-d H:i:s', $item);
					}
				}
			}

			$pairs[] = (object)
			[
				'from' => $froms[$from],
				'to' => $tos[$to],
			];


		}

		return $pairs;
	}
	public static function getIncompleteBreakLogs($pairs, $incomplete_break_log_ids)
	{

		foreach($pairs as $break_pair)
		{
			if(!isset($break_pair->from))
			{
				$incomplete_break_log_ids[] = $break_pair->to->id;
			}

			if(!isset($break_pair->to))
			{
				$incomplete_break_log_ids[] = $break_pair->from->id;
			}

		}

		return $incomplete_break_log_ids;
	}

	public static function getLogIdsWithErrorFilter($data, $filter = '', $from, $to) {	
	
		if ($filter) {
			$new_data = array();
			$multiple_in_log_ids = array();
			$multiple_out_log_ids = array();
			$incomplete_break_log_ids = array();
			$early_break_out_log_ids = array();
			$late_break_in_log_ids = array();
			$no_break_logs_ids = array();

			$break_log_out_types = array(
				G_Employee_Break_Logs::TYPE_BOUT,
				G_Employee_Break_Logs::TYPE_BOT_OUT,
				G_Employee_Break_Logs::TYPE_B1_OUT,
				G_Employee_Break_Logs::TYPE_B2_OUT,
				G_Employee_Break_Logs::TYPE_B3_OUT,
				G_Employee_Break_Logs::TYPE_OT_B1_OUT,
				G_Employee_Break_Logs::TYPE_OT_B2_OUT
			);
	
			$break_log_in_types = array(
				G_Employee_Break_Logs::TYPE_BIN,
				G_Employee_Break_Logs::TYPE_BOT_IN,
				G_Employee_Break_Logs::TYPE_B1_IN,
				G_Employee_Break_Logs::TYPE_B2_IN,
				G_Employee_Break_Logs::TYPE_B3_IN,
				G_Employee_Break_Logs::TYPE_OT_B1_IN,
				G_Employee_Break_Logs::TYPE_OT_B2_IN
			);

			foreach ($data as $key => $log) 
			{
				$new_data[$log->getEmployeeId()][$log->getDate() . ' ' . $log->getTime()] = $log;
			}

			$time_logs = [];

			foreach($data as $key => $time_log)
			{
				if(!in_array($time_log->getType(), $break_log_out_types) && !in_array($time_log->getType(), $break_log_in_types) )
				{
					$time_logs[] = 
					[
						'id' => $time_log->getId(),
						'employee_id' => $time_log->getEmployeeId(),
						'employee_code' => $time_log->getEmployeeCode(),
						'date' => $time_log->getDate(),
						'time' => $time_log->getTime(),
						'type' => $time_log->getType(),
					];	
				}
			}

			usort($time_logs,function($first,$second)
			{
				return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
			});	

			// Logs on timesheet
			foreach( $time_logs as $log )
			{
                $emp_code = $log['employee_code'];
                $date     = date("Y-m-d",strtotime($log['date']));
                $type     = strtolower($log['type']);
                $time     = date("H:i:s",strtotime($log['time']));
				$timesheet[$emp_code][$type][$date . ' ' .$time] = (object)['id' => $log['id'], 'datetime' => ($date . ' ' .$time)];
			}

            $break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($from, $to);   

			usort($break_logs,function($first,$second)
			{
				return (($first['date'] .' '.$first['time'])  >  ($second['date'] .' '.$second['time']));
			});	

			// Break logs on timesheet
			foreach( $break_logs as $break_log )
			{
                $employee_id = $break_log['employee_id'];
                $emp_code = $break_log['employee_code'];
                $date     = date("Y-m-d",strtotime($break_log['date']));
                $type     = strtolower($break_log['type']);
                $time     = date("H:i:s",strtotime($break_log['time']));

				$timesheet[$emp_code]['breaks'][$type][$date . ' ' .$time] = (object)['id' => $break_log['id'], 'datetime' => ($date . ' ' .$time)];
			}

 			foreach($timesheet as $employee_code => $sheet)
			{
				$employee  = G_Employee_Finder::findByEmployeeCode($employee_code);

				$breaks = $sheet['breaks'];

				$ins = $sheet[G_Attendance_Log::TYPE_IN];
				$outs = $sheet[G_Attendance_Log::TYPE_OUT];
				$ins_keys = array_keys($ins);
				$outs_keys = array_keys($outs);

				$break_outs = $breaks[G_Employee_Break_Logs::TYPE_BOUT];
				$break_ins = $breaks[G_Employee_Break_Logs::TYPE_BIN];
				$break_outs_keys = array_keys($break_outs);
				$break_ins_keys = array_keys($break_ins);

				$ot_break_outs = $breaks[G_Employee_Break_Logs::TYPE_BOT_OUT];
				$ot_break_in = $breaks[G_Employee_Break_Logs::TYPE_BOT_IN];
				$ot_break_outs_keys = array_keys($ot_break_outs);
				$ot_break_in_keys = array_keys($ot_break_in);

				$log_pairs = self::getLogPairs($ins, $outs, $ins_keys, $outs_keys);
				$break_pairs = self::getLogPairs($break_outs, $break_ins, $break_outs_keys, $break_ins_keys);
				$ot_break_pairs = self::getLogPairs($ot_break_outs, $ot_break_in, $ot_break_outs_keys, $ot_break_in_keys);

				$incomplete_break_log_ids = self::getIncompleteBreakLogs($break_pairs, $incomplete_break_log_ids);
				$incomplete_break_log_ids = self::getIncompleteBreakLogs($ot_break_pairs, $incomplete_break_log_ids);

				foreach($log_pairs as $lgkey => $pair)
				{
					
					if(isset($pair->from))
					{
					
						$date = date('Y-m-d', strtotime($pair->from->datetime));
						
						$attendance = G_Attendance_Helper::generateAttendance($employee, $date, true);
						$t = $attendance->getTimesheet();

						if($t)
						{	
							$time_in = date('Y-m-d H:i:s', strtotime($pair->from->datetime));
							$estimated_time_out = $t->getScheduledDateOut() . ' ' . $t->getScheduledTimeOut();
							$estimated_tomorrow_time_in = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($pair->from->datetime)));
	
							$breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($employee->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
						
							$breaks = [];
							foreach(array_merge($break_pairs, $ot_break_pairs) as $break_pair)
							{
								if(isset($break_pair->from))
								{
									$datetime = $break_pair->from->datetime;
									if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
									{
										$breaks[] = $break_pair;
									}
								}
								else
								{
									$datetime = $break_pair->to->datetime;
									if (($datetime >= $time_in) && ($datetime <= $estimated_tomorrow_time_in))
									{
										$breaks[] = $break_pair;
									}
								}
							}
	
							foreach($breaktime_data as $btkey => $schedule_break)
							{
								$is_required_logs = $schedule_break['to_required_logs'];
	
								$log_break = $breaks[$btkey];
								$log_break_from = isset($log_break->from) ? $log_break->from->datetime : null;
								$log_break_to = isset($log_break->to) ? $log_break->to->datetime : null;
								
								$break_from = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_in']));
								if($break_from < $log_break->from->datetime)
								{
									$break_from = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_in'])));
								}
	
								$break_to = date('Y-m-d H:i:s',strtotime($date . ', ' . $schedule_break['break_out']));
								if($break_to < $log_break->from->datetime)
								{
									$break_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ', ' . $schedule_break['break_out'])));
								}
	
								if($is_required_logs)
								{
									if(isset($log_break_from))
									{
										if($log_break_from < $break_from)
										{
											$early_break_out_log_ids[] = $log_break->from->id;
										}
									}
									
									if(isset($log_break_to))
									{
										if($log_break_to > $break_to)
										{
											$late_break_in_log_ids[] = $log_break->to->id;
										}
									}
	
									// if(!isset($log_break->from))
									// {
									// 	if(isset($log_break->to))
									// 	{
									// 		$incomplete_break_log_ids[] = $log_break->to->id;
									// 	}
									// }
	
									// if(!isset($log_break->to))
									// {
									// 	if(isset($log_break->from))
									// 	{
									// 		$incomplete_break_log_ids[] = $log_break->from->id;
									// 	}
									// }
									
									if(!isset($log_break_from) && !isset($log_break_to))
									{
										$no_break_logs_ids[] = $pair->from->id;
									}
	
								}
								
							}
						} 
					}
				}
			}
			
			foreach ($new_data as $key => $logs) {

				$previous_date = '';
				$types = array();
				$iteration = 1;
				$ot_iteration = 1;
				$previous_type = '';
				$count = 0;
				$ot_count = 0;
				$break_type = '';
				$matched_breaks = array();

				
				ksort($logs);

				foreach ($logs as $key => $log) {

					if ($previous_date != $log->getDate()) {
						$previous_date = $log->getDate();
						$types = array();
						$iteration = 1;
						$ot_iteration = 1;
						$previous_type = '';
						$count = 0;
						$ot_count = 0;
						$break_type = '';
					}
					
					if (strtolower($log->getType()) == 'in' || strtolower($log->getType()) == 'out') {
						$types[$log->getType()][] = array(
							'id' => $log->getId(),
							'type' => $log->getType()
						);
					}
					else {
						if ($previous_type == $log->getType()) {
							$count = 0;
							$iteration++;
						}
		
						$previous_type = $log->getType();

						if (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOUT)) {
							$break_type = constant("G_Employee_Break_Logs::TYPE_B" . $iteration . "_OUT");
						}
						elseif (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
							$break_type = constant("G_Employee_Break_Logs::TYPE_B" . $iteration . "_IN");
						}
						elseif (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOT_OUT)) {
							$break_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $ot_iteration . "_OUT");
						}
						elseif (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOT_IN)) {
							$break_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $ot_iteration . "_IN");
						}

						if ($break_type) {
							$matched_breaks[$log->getDate()][$break_type] = $log;
					
							if (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOUT) || strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
								$count++;
				
								if ($count >= 2) {
									$count = 0;
									$iteration++;
								}
							}
							elseif (strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOT_OUT) || strtolower($log->getType()) == strtolower(G_Employee_Break_Logs::TYPE_BOT_IN)) {
								$ot_count++;
				
								if ($ot_count >= 2) {
									$ot_count = 0;
									$ot_iteration++;
								}
							}
						}
					}

					if (count($types['in']) > 1) {
						foreach ($types['in'] as $key => $log_in) {
							$multiple_in_log_ids[$log_in['id']] = $log_in['id'];
						}
					}
	
					if (count($types['out']) > 1) {
						foreach ($types['out'] as $key => $log_out) {
							$multiple_out_log_ids[$log_out['id']] = $log_out['id'];
						}
					}

				}
			
				
				// $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($e->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
				// var_dump($schedules);exit;
				// foreach ($matched_breaks as $date => $matched_break) {
				// 	if (isset($matched_break[G_Employee_Break_Logs::TYPE_B1_OUT]) || isset($matched_break[G_Employee_Break_Logs::TYPE_B1_IN])) {
				// 		if (!isset($matched_break[G_Employee_Break_Logs::TYPE_B1_OUT])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B1_IN]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B1_IN]->getId();
				// 		}
				// 		elseif (!isset($matched_break[G_Employee_Break_Logs::TYPE_B1_IN])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B1_OUT]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B1_OUT]->getId();
				// 		}
				// 	}
					
				// 	if (isset($matched_break[G_Employee_Break_Logs::TYPE_B2_OUT]) || isset($matched_break[G_Employee_Break_Logs::TYPE_B2_IN])) {
				// 		if (!isset($matched_break[G_Employee_Break_Logs::TYPE_B2_OUT])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B2_IN]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B2_IN]->getId();
				// 		}
				// 		elseif (!isset($matched_break[G_Employee_Break_Logs::TYPE_B2_IN])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B2_OUT]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B2_OUT]->getId();
				// 		}
				// 	}
					
				// 	if (isset($matched_break[G_Employee_Break_Logs::TYPE_B3_OUT]) || isset($matched_break[G_Employee_Break_Logs::TYPE_B3_IN])) {
				// 		if (!isset($matched_break[G_Employee_Break_Logs::TYPE_B3_OUT])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B3_IN]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B3_IN]->getId();
				// 		}
				// 		elseif (!isset($matched_break[G_Employee_Break_Logs::TYPE_B3_IN])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_B3_OUT]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_B3_OUT]->getId();
				// 		}
				// 	}
					
				// 	if (isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B1_OUT]) || isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B1_IN])) {
				// 		if (!isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B1_OUT])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_OT_B1_IN]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_OT_B1_IN]->getId();
				// 		}
				// 		elseif (!isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B1_IN])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_OT_B1_OUT]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_OT_B1_OUT]->getId();
				// 		}
				// 	}
					
				// 	if (isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B2_OUT]) || isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B2_IN])) {
				// 		if (!isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B2_OUT])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_OT_B2_IN]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_OT_B2_IN]->getId();
				// 		}
				// 		elseif (!isset($matched_break[G_Employee_Break_Logs::TYPE_OT_B2_IN])) {
				// 			$incomplete_break_log_ids[$matched_break[G_Employee_Break_Logs::TYPE_OT_B2_OUT]->getId()] = $matched_break[G_Employee_Break_Logs::TYPE_OT_B2_OUT]->getId();
				// 		}
				// 	}

				// }
			}
		}

		return array
		(
			'multiple_in_log_ids' 		=> $multiple_in_log_ids,
			'incomplete_break_log_ids' 	=> $incomplete_break_log_ids,
			'multiple_out_log_ids' 		=> $multiple_out_log_ids,
			'early_break_out_log_ids' 	=> $early_break_out_log_ids,
			'late_break_in_log_ids' 	=> $late_break_in_log_ids,
			'no_break_logs_ids' 		=> $no_break_logs_ids,
		);
	}
}
?>