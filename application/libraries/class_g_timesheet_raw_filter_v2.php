<?php
/*
	Filtering duplicate raw timesheet data. It has multiple time in and out. This class gets the first time in and last time out for a certain day

	$timesheet[1]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
	$timesheet[1]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
	$timesheet[1]['in']['2012-07-27']['13:10:42'] = '2012-07-27';		
	$timesheet[1]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
	$timesheet[1]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
	$timesheet[1]['in']['2012-08-05']['06:05:42'] = '2012-08-05';		
	$timesheet[1]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
	$timesheet[1]['out']['2012-07-27']['20:15:42'] = '2012-07-27';			
	$timesheet[1]['out']['2012-07-28']['19:10:42'] = '2012-07-28';		
	$timesheet[1]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
	$timesheet[1]['out']['2012-08-05']['18:05:42'] = '2012-08-05';		
	$timesheet[2]['in']['2012-07-27']['06:05:42'] = '2012-07-27';
	$timesheet[2]['in']['2012-07-27']['10:10:42'] = '2012-07-27';
	$timesheet[2]['in']['2012-07-27']['13:10:42'] = '2012-07-27';		
	$timesheet[2]['in']['2012-07-28']['06:05:42'] = '2012-07-28';
	$timesheet[2]['in']['2012-07-28']['06:10:42'] = '2012-07-28';
	$timesheet[2]['in']['2012-08-05']['06:05:42'] = '2012-08-05';		
	$timesheet[2]['out']['2012-07-27']['18:10:42'] = '2012-07-27';
	$timesheet[2]['out']['2012-07-27']['20:15:42'] = '2012-07-27';			
	$timesheet[2]['out']['2012-07-28']['19:10:42'] = '2012-07-28';		
	$timesheet[2]['out']['2012-08-05']['16:05:42'] = '2012-08-05';
	$timesheet[2]['out']['2012-08-05']['18:05:42'] = '2012-08-05';	
	
	$tr = new G_Timesheet_Raw_Filter($timesheet);
	$x = $tr->filter();
	$tr->getErrorsNoOut();
	$tr->getErrorsNoIn();
*/
class G_Timesheet_Raw_Filter_V2 {
	protected $timesheets;
	protected $current_timesheet;
	protected $current_employee_id;
	protected $errors_no_out;
	protected $errors_no_in;
	
	protected $date_out_with_date_in;
    protected $all_attendance = array();
	
	public function __construct($timesheets) {
		$this->timesheets = $timesheets;
	}
	
	public function filter() {
		$timesheets = $this->timesheets;		
		ksort($timesheets);
		foreach ($timesheets as $employee_id => $timesheet) {
			ksort($timesheet);
			$this->current_timesheet = $timesheet;
			$this->current_employee_id = $employee_id;
			$data = $this->getValidTimesheet($employee_id, false);
			if ($data) {
				$return[$employee_id] = $data;
			}
		}
		return $return;
	}
	
	public function filter_sub() {
		$timesheets = $this->timesheets;		
		ksort($timesheets);
		foreach ($timesheets as $employee_id => $timesheet) {
			ksort($timesheet);			
			$this->current_timesheet = $timesheet;
			$this->current_employee_id = $employee_id;
			$data = $this->getValidTimesheet($employee_id, false);
			if ($data) {
				$return[$employee_id] = $data;
			}
		}
		return $return;
	}
	
	public function filterAndAdd() {
		$timesheets = $this->timesheets;
		ksort($timesheets);
		foreach ($timesheets as $employee_id => $timesheet) {
			ksort($timesheet);
			$this->current_timesheet = $timesheet;
			$this->current_employee_id = $employee_id;
			$data = $this->getValidTimesheet($employee_id, true);
			if ($data) {
				$return[$employee_id] = $data;
			}
		}
		return $return;
	}

    public function filterAndGetAttendance() {
        $timesheets = $this->timesheets;
        ksort($timesheets);
        foreach ($timesheets as $employee_id => $timesheet) {
            ksort($timesheet);
            $this->current_timesheet = $timesheet;
            $this->current_employee_id = $employee_id;
            $data = $this->getValidTimesheet($employee_id, true);
            if ($data) {
                $return[$employee_id] = $data;
            }
        }
        return $this->all_attendance;
    }
	
	public function filterAndUpdateAttendanceTimeInStagger($a, $error_message) {
        $timesheets = $this->timesheets;
        ksort($timesheets);
		$employee_id = $a->getEmployeeId();
		$date_in = $a->getDate();
		$time = $a->getTime();
		$this->addTimesheetTimeInV2($employee_id, $date_in, $time, $a, $error_message);
    }

	public function filterAndUpdateAttendanceTimeOutStagger($a, $logsId, $error_message) {
		$employee_id = $a->getEmployeeId();
		$time_out = $a->getTimeOut();
		$this->addTimesheetTimeOutV2($employee_id, $logsId, $time_out, $a, $error_message);    
        
    }

	public function filterAndUpdateAttendanceTimeOutStaggerWithErrorMessage($a, $error_message) {
		$timesheets = $this->timesheets;
        ksort($timesheets);
		$employee_id = $a->getEmployeeId();
		$date_in = $a->getDate();
		$time_out = $a->getTimeOut();
		$date_out = $a->getDate();
        $this->addTimesheetTimeOutWithErrorV2($employee_id, $time_out, $a, $error_message);    
		
    }

    public function filterAndUpdateAttendance($a) {
        $timesheets = $this->timesheets;
        ksort($timesheets);
		
        foreach ($timesheets as $employee_id => $timesheet) {
            ksort($timesheet);
            $this->current_timesheet = $timesheet;
            $this->current_employee_id = $employee_id;
			$employee_id = G_Employee_Finder::findEmployeeCodeByEmployeeId($employee_id);
            $data = $this->getValidTimesheet($employee_id->getEmployeeCode(), true, $a);            
            if ($data) {
                $return[$employee_id] = $data;
            }
		}        
		
        G_Attendance_Manager_V2::recordToMultipleEmployees($this->all_attendance);
    }
	
	private function getValidTimesheetV2($employee_id, $is_add = false, $a) {
		$ins = $this->current_timesheet['actual_time_in'];		
		$prev_date_out = "";
		$prev_time_out = ""; 
			
	}
	
	private function findDateOutAndTimeOutV2($date_in, $time_ins) {
		$outs = $this->getDateOuts();
		$matched_date = '';
		foreach ($outs as $date_out => $time_outs) {
			$matched = $this->getMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs);
			if ($matched) {
				$date['date_in'] = $matched['date_in'];
				$date['time_in'] = $matched['time_in'];
				$date['date_out'] = $matched['date_out'];
				$date['time_out'] = $matched['time_out'];

				if ($matched_date == '') {					
					unset($this->errors_no_in[$this->current_employee_id][$date['date_out']]);
					$this->addDateOutWithDateIn($this->current_employee_id, $date['date_out'], $date['time_out']);
					$matched_date = $date;
				}
				//return $date;
			} else {
				if (!$this->isDateOutWithDateIn($this->current_employee_id, $date_out)) {
					$time_out = $this->getLastTime($time_outs);
					$this->addErrorNoIn($this->current_employee_id, $date_out, $time_out);
				}
			}
		}
		return $matched_date;
	}

	private function getValidTimesheet($employee_id, $is_add = false, $a) {
		$ins = $this->getDateIns();		
		$prev_date_out = "";
		$prev_time_out = ""; 
		
		foreach ($ins as $date_in => $times) {
			
			if ($last_date_in) {
				$first_time = $this->getFirstTime($times);
				$days  = Tools::getDayDifference($last_date_in, $date_in);
				$hours = Tools::computeHoursDifference($last_time, $first_time);				
				if (($hours <= 8) && $days <= 1 && $this->isNightShift($last_times) && $this->isMorning($times)) {
					continue;
				}
			}
			
			//if($employee_id=='A80-1198' && $date_in =='2023-02-01'){
				$date_time_out = $this->findDateOutAndTimeOut($date_in, $times);
				//utilities::displayArray($date_time_out);exit;
			//}
		
			
			
			if ($date_time_out) {
				//$time_in = $this->getFirstTime($times);
				$time_in = $date_time_out['time_in'];
				$date_out = $date_time_out['date_out'];
				$time_out = $date_time_out['time_out'];
				
				$return[$date_in]['in'] = "{$time_in} {$date_in}";
				$return[$date_in]['out'] = "{$time_out} {$date_out}";

				$date_time_in = date("Y-m-d H:i:s",strtotime($date_in . ' ' . $time_in));
				$date_time_out = date("Y-m-d H:i:s",strtotime($date_out . ' ' . $time_out));
				$breaks = $this->findMatchedBreaks($date_time_in, $date_time_out);
				
				if ($is_add) {
					//condition for out coming from previous date timesheet
					if(($date_out == $prev_date_out) &&  ($time_out==$prev_time_out))
					{
						$date_out = "";
						$time_out = "";
					}
					$this->addTimesheet($employee_id, $date_in, $time_in, $date_out, $time_out, $breaks, $a);
					$prev_time_out = $time_out;
					$prev_date_out = $date_out;
				}

			} else {
				if ($employee_id) {
					$time_in = $this->getFirstTime($times);
					$this->addErrorNoOut($employee_id, $date_in, $time_in);
				}
			}
			$last_time = $this->getLastTime($times);
			$last_times = $times;
			$last_date_in = $date_in;
		}
		return $return;		
	}

	private function findDateOutAndTimeOut($date_in, $time_ins) {
		$outs = $this->getDateOuts();
		$matched_date = '';
		foreach ($outs as $date_out => $time_outs) {
			$matched = $this->getMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs);
			if ($matched) {
				$date['date_in'] = $matched['date_in'];
				$date['time_in'] = $matched['time_in'];
				$date['date_out'] = $matched['date_out'];
				$date['time_out'] = $matched['time_out'];

				if ($matched_date == '') {					
					unset($this->errors_no_in[$this->current_employee_id][$date['date_out']]);
					$this->addDateOutWithDateIn($this->current_employee_id, $date['date_out'], $date['time_out']);
					$matched_date = $date;
				}
				//return $date;
			} else {
				if (!$this->isDateOutWithDateIn($this->current_employee_id, $date_out)) {
					$time_out = $this->getLastTime($time_outs);
					$this->addErrorNoIn($this->current_employee_id, $date_out, $time_out);
				}
			}
		}
		return $matched_date;
	}
	
	private function findMatchedBreaks($date_time_in, $date_time_out) {
		$breaks = $this->getBreaks();
		$matched_breaks = array();
		$break_ins = array();
		$break_ot_ins = array();
		$break_outs = array();
		$break_ot_outs = array();
		$iteration = 1;
		$ot_iteration = 1;
		$previous_type = '';
		$count = 0;
		$ot_count = 0;
		$break_type = '';
		
		ksort($breaks);
		
		foreach ($breaks as $datetime => $break) {
			$break_date_time = date("Y-m-d H:i:s",strtotime($break['date'] . ' ' . $break['time']));

			if ($break_date_time >= $date_time_in && $break_date_time <= $date_time_out) {
				if ($previous_type == $break['type'] || strtolower($previous_type) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
					$count = 0;
					$iteration++;
				}

				$previous_type = $break['type'];

				if (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOUT)) {
					$break_type = constant("G_Employee_Break_Logs::TYPE_B" . $iteration . "_OUT");
				}
				elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
					$break_type = constant("G_Employee_Break_Logs::TYPE_B" . $iteration . "_IN");
				}
				elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_OUT)) {
					$break_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $ot_iteration . "_OUT");
				}
				elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_IN)) {
					$break_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $ot_iteration . "_IN");
				}

				if ($break_type) {
					$matched_breaks[$break_type] = $break;
					
					// if (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOUT) || strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
					// 	$count++;
		
					// 	if ($count >= 2) {
					// 		$count = 0;
					// 		$iteration++;
					// 	}
					// }
					// else
					if (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_OUT) || strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_IN)) {
						$ot_count++;
		
						if ($ot_count >= 2) {
							$ot_count = 0;
							$ot_iteration++;
						}
					}
				}


				// if (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BIN)) {
				// 	$break_ins[count($break_ins)] = array(
				// 		'id' 	=> $break['id'],
				// 		'date' 	=> $break['date'],
				// 		'time' 	=> $break['time'],
				// 		'type' 	=> $break['type']
				// 	);
				// }
				// elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_IN)) {
				// 	$break_ot_ins[count($break_ot_ins)] = array(
				// 		'id' 	=> $break['id'],
				// 		'date' 	=> $break['date'],
				// 		'time' 	=> $break['time'],
				// 		'type' 	=> $break['type']
				// 	);
				// }
				// elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOUT)) {
				// 	$break_outs[count($break_outs)] = array(
				// 		'id' 	=> $break['id'],
				// 		'date' 	=> $break['date'],
				// 		'time' 	=> $break['time'],
				// 		'type' 	=> $break['type']
				// 	);
				// }
				// elseif (strtolower($break['type']) == strtolower(G_Employee_Break_Logs::TYPE_BOT_OUT)) {
				// 	$break_ot_outs[count($break_ot_outs)] = array(
				// 		'id' 	=> $break['id'],
				// 		'date' 	=> $break['date'],
				// 		'time' 	=> $break['time'],
				// 		'type' 	=> $break['type']
				// 	);
				// }
			}
		}

		// $total_pair = count($break_ins) > count($break_outs) ? count($break_ins) : count($break_outs);

		// for ($i=1; $i <= $total_pair; $i++) { 
		// 	$break_in_type = constant("G_Employee_Break_Logs::TYPE_B" . $i . "_IN");
		// 	if ($break_in_type) {
		// 		$matched_breaks[$break_in_type] = $break_ins[$i-1];
		// 	}

		// 	$break_out_type = constant("G_Employee_Break_Logs::TYPE_B" . $i . "_OUT");
		// 	if ($break_out_type) {
		// 		$matched_breaks[$break_out_type] = $break_outs[$i-1];
		// 	}
		// }

		// $total_ot_pair = count($break_ot_ins) > count($break_ot_outs) ? count($break_ot_ins) : count($break_ot_outs);

		// for ($i=1; $i <= $total_ot_pair; $i++) { 
		// 	$break_ot_in_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $i . "_IN");
		// 	if ($break_ot_in_type) {
		// 		$matched_breaks[$break_ot_in_type] = $break_ot_ins[$i-1];
		// 	}

		// 	$break_ot_out_type = constant("G_Employee_Break_Logs::TYPE_OT_B" . $i . "_OUT");
		// 	if ($break_ot_out_type) {
		// 		$matched_breaks[$break_ot_out_type] = $break_ot_outs[$i-1];
		// 	}
		// }

		return $matched_breaks;
	}
	
	private function isDateOutWithDateIn($employee_id, $date_out) {
		if ($this->date_out_with_date_in[$employee_id][$date_out]) {
			return true;	
		} else {
			return false;	
		}
	}
	
	private function addDateOutWithDateIn($employee_id, $date, $time) {
		$this->date_out_with_date_in[$employee_id][$date] = $time;	
	}


	private function checkNextDayMorningOut($date){
		$out = $this->getDateOuts();
		$out_next_day = $out[$date];
		$next_time_out = '';
		$next_day_first_in = "";

		$ins = $this->getDateIns();

		$next_day_in = $ins[$date];

		$next_day_first_in = $this->getFirstTime($next_day_in);
		$next_time_out = $this->getMorning($out_next_day);

		if($next_time_out ==''){
			$next_time_out = $this->getAfternoon($out_next_day);
		}

		if( ($next_time_out != '' && $next_day_first_in != '') && ( strtotime($next_time_out) <= strtotime($next_day_first_in)  ) ){
			return $next_time_out;
		}
		elseif($next_time_out != '' && $next_day_first_in == ''){
			return $next_time_out;
		}

	}

	
	private function getMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs) {
//		echo $date_in;
//		print_r($time_ins);
//		echo $date_out;
//		print_r($time_outs);
//		echo "<br>======<br>";

		$date_in_tomorrow = date('Y-m-d', strtotime($date_in . ' +1 day'));
		if (($date_in == $date_out && ($time_out = $this->getAfternoon($time_outs)))) {
			
			$date['date_in'] = $date_in;
			$date['time_in'] = $this->getFirstTime($time_ins);
			$date['date_out'] = $date_out;
			$date['time_out'] = $this->getLastTime($time_outs);

			//double check if theres morning out next day
			$check = self::checkNextDayMorningOut($date_in_tomorrow);
			if($check != ''){
				$date['date_out'] = $date_in_tomorrow;
				$date['time_out'] = $check;
			}

			return $date;
		} else if ($date_in_tomorrow == $date_out && ($time_out = $this->getMorning($time_outs))) {
			
			$date['date_in'] = $date_in;
			$date['time_in'] = $this->getFirstTime($time_ins);
			$date['date_out'] = $date_out;
			$date['time_out'] = $time_out;
			return $date;
		} else if ($date_in_tomorrow == $date_out && ($time_in = $this->getNightShift($time_ins)) && ($time_out = $this->getMorning($time_outs))) {
			
			$date['date_in'] = $date_in;
			$date['time_in'] = $time_in;
			$date['date_out'] = $date_out;
			$date['time_out'] = $time_out;
			return $date;
		}else if ($date_in == $date_out) {
			
			$pm_time_in = $this->getAfternoon($time_ins);
			$pm_time_out = $this->getAfternoon($time_outs);
			$am_time_in = $this->getMorning($time_ins);
			$am_time_out = $this->getMorning($time_outs);
			if (($pm_time_in && $pm_time_out) || ($am_time_in && $am_time_out)) {
				$date['date_in'] = $date_in;
				$date['time_in'] = $this->getFirstTime($time_ins);
				$date['date_out'] = $date_out;
				$date['time_out'] = $this->getLastTime($time_outs);
				return $date;
			}
		} else {
			return false;
		}
	}	
	protected function addTimesheetTimeInV2($employee_id, $date, $time, $a, $error_message) {
		$e = G_Employee_Finder::findById($employee_id);
		$employee_schedule = G_Employee_Schedule_Type_Finder::findByEmployeeAndDate($e, $date);
		if ($e) {
			if($error_message){
				$is_true = G_Attendance_Helper_V2::recordTimecardTimeIn($e, $date, $time, $a, $error_message, $employee_schedule);
			}else{
				$is_true = G_Attendance_Helper_V2::recordTimecardTimeIn($e, $date, $time, $a, null, $employee_schedule);
			}
			
			
			if ($is_true) {
                $this->all_attendance[] = G_Attendance_Helper_V2::generateAttendance($e, $date);
			}
		} //else {
			//$error = new G_Attendance_Error;			
			//$error->setMessage("Employee code can't find: {$employee_code}");
			//$error->setErrorTypeId(G_Attendance_Error::ERROR_INVALID_EMPLOYEE_ID);
			//$error->setDate($date_in);
			//$error->setEmployeeCode($employee_code);
			//$error->addError();
		//}
	}

	protected function addTimesheetTimeOutV2($employee_id, $logsId, $time, $a, $error_message) {
		$e = G_Employee_Finder::findById($employee_id);
		if ($e) {
			$is_true = G_Attendance_Helper_V2::recordTimecardTimeOut($e, $logsId, $time, $a, $error_message);
		}
	}

	protected function addTimesheetTimeOutWithErrorV2($employee_id, $time, $a, $error_message) {
		$e = G_Employee_Finder::findById($employee_id);
		if ($e) {
			$is_true = G_Attendance_Helper_V2::recordTimecardTimeOutWithError($e, $time, $a, $error_message);
		}
	}
	protected function addTimesheet($employee_code, $date_in, $time_in, $date_out, $time_out, $breaks = array(), $a) {
		$e = G_Employee_Finder::findByEmployeeCode($employee_code);
		if ($e) {
			$is_true = G_Attendance_Helper_V2::recordTimecard($e, $date_in, $time_in, $time_out, $date_in, $date_out, null, null, 0, $breaks, $a);
			
			if ($is_true) {
                $this->all_attendance[] = G_Attendance_Helper_V2::generateAttendance($e, $date_in);
			}
		} //else {
			//$error = new G_Attendance_Error;			
			//$error->setMessage("Employee code can't find: {$employee_code}");
			//$error->setErrorTypeId(G_Attendance_Error::ERROR_INVALID_EMPLOYEE_ID);
			//$error->setDate($date_in);
			//$error->setEmployeeCode($employee_code);
			//$error->addError();
		//}
	}
	
	private function addErrorNoOut($employee_id, $date_in, $time_in) {
		$this->errors_no_out[$employee_id][$date_in] = $time_in;
	}
	
	public function getErrorsNoOut() {
		return $this->errors_no_out;	
	}
	
	private function addErrorNoIn($employee_id, $date_out, $time_out) {
		$this->errors_no_in[$employee_id][$date_out] = $time_out;
	}
	
	public function getErrorsNoIn() {
		return $this->errors_no_in;	
	}	
	
	private function getDateIns() {
		$ins = $this->current_timesheet['in'];
		ksort($ins);
		return $ins;
	}
	
	private function getDateOuts() {
		$outs = $this->current_timesheet['out'];
		ksort($outs);
		return $outs;
	}
	
	private function getBreaks() {
		$breaks = $this->current_timesheet['breaks'];

		return $breaks;
	}
	
	private function isMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs) {
//		echo '<pre>';
//		echo "{$date_in}";		
//		print_r($time_ins);
//		echo $date_out;
//		print_r($time_outs);
//		echo '<br><br>';
		
		//$is_ns = $this->isNightShift($time_ins);
		$date_in_tomorrow = date('Y-m-d', strtotime($date_in . ' +1 day'));
		if (($date_in == $date_out && !$this->isMorning($time_outs))) {
			return true;
		} else if ($date_in_tomorrow == $date_out && $this->isNightShift($time_ins) && $this->isMorning($time_outs)) {
			return true;
		} else {
			return false;	
		}
	}
	
	private function getLastTime($times) {
		$last_time = '';

		foreach ($times as $time => $date) {
			if($last_time == ''){
				$last_time = $time;
			}
			else{
				if(strtotime($time) >= strtotime($last_time)){
					$last_time = $time;
				}
			}
		}
		return $last_time;
	}
	
	private function getFirstTime($times) {

		$first_time = '';

		foreach ($times as $time => $date) {
			if($first_time == ''){
				$first_time = $time;
			}
			else{
				if(strtotime($time) <= strtotime($first_time)){
					$first_time = $time;
				}
			}
						
		}
		return $first_time;
	}	
	
	private function isNightShift($times) {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		$return = false;
		foreach ($times as $time => $date) {
			$time = strtotime($time);
			if ($time >= $ns_time_start && $time <= $ns_time_end) {
				$return = true;
			}
		}
		return $return;
	}
	
	private function getNightShift($times) {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		$ns_time = '';
		foreach ($times as $time => $date) {
			$mktime = strtotime($time);
			if ($mktime >= $ns_time_start && $mktime <= $ns_time_end) {
				$ns_time = $time;
				return $ns_time;
			}
		}
		return ($ns_time == '') ? false : true ;
	}	
	
	private function getMorning($times) {
		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		$morning_time = '';
		foreach ($times as $time => $date) {
			$mktime = strtotime($time);
			if ($mktime >= $time_start && $mktime <= $time_end) {
				$morning_time = $time;
			}
		}
		return ($morning_time == '') ? false : $morning_time ;
	}
	
	private function getAfternoon($times) {

		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		$afternoon_time = '';

		//$last_time = '';

		foreach ($times as $time => $date) {
			$mktime = strtotime($time);
			if ($mktime >= $time_start && $mktime <= $time_end) {
				//$afternoon_time = $time;
			} else {
				$afternoon_time = $time;	
			}
		}
		return ($afternoon_time == '') ? false : $afternoon_time ;
	}			
	
	private function isMorning($times) {
		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		
		foreach ($times as $time => $date) {
			$time = strtotime($time);
			if ($time >= $time_start && $time <= $time_end) {
				return true;
			} else {
				return false;	
			}
		}
	}	
}
?>