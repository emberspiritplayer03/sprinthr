<?php
class G_Attendance_Helper {
    /*
     * Update attendance by G_Attendance in array
     * @param $multiple_attendance Array with instance of G_Attendance. It is from G_Attendance_Helper::generateAttendance()
     */
    public static function updateAttendanceByMultipleAttendance($multiple_attendance) {
        return G_Attendance_Manager::recordToMultipleEmployees($multiple_attendance);
    }

    /*
     * Update attendance by G_Attendance
     * @param $attendance Instance of G_Attendance. It is from G_Attendance_Helper::generateAttendance()
     */
    public static function updateAttendanceBySingleAttendance($attendance) {
        return G_Attendance_Manager::recordToSingleEmployee($attendance);
    }
	
	public static function updateAttendanceByAllActiveEmployees($date) {
		$employees = G_Employee_Finder::findAllActiveByDate($date);
		foreach ($employees as $e) {
			self::updateAttendance($e, $date);
		}
	}
	
	public static function updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date) {
		$is_true = false;
		$is_updated = false;
		$dates = Tools::getBetweenDates($start_date, $end_date);
		if ($e) {
			foreach ($dates as $date) {
				//$is_true = self::updateAttendance($e, $date);
                $as[] = self::generateAttendance($e, $date);
			}
            $is_true = self::updateAttendanceByMultipleAttendance($as);
    		if ($is_true) {
    			$is_updated = true;
    		}
		}
		return $is_updated;
	}
    /**
    *   Update attendance by employees and period
    *
    *   $employees = Array of G_Employee
    */
	public static function updateAttendanceByEmployeesAndPeriod($employees, $start_date, $end_date) {
		$is_true = false;
		$is_updated = false;
		$dates = Tools::getBetweenDates($start_date, $end_date);
        foreach ($employees as $e) {
    		if ($e) {
    			foreach ($dates as $date) {
                    $as[] = self::generateAttendance($e, $date);
    			}
    		}
        }

        $is_true = self::updateAttendanceByMultipleAttendance($as);
  		if ($is_true) {
  			$is_updated = true;
  		}
		return $is_updated;
	}
	
	public static function updateAttendanceByEmployeeAndPeriodWithCheckAttendanceValidation($e, $start_date, $end_date) {
		$is_true = false;
		$is_updated = false;
		$dates = Tools::getBetweenDates($start_date, $end_date);
		if ($e) {
			foreach ($dates as $date) {
				$check   = self::isEmployeeWithAttendance($e,$date);
				if($check){
					$is_true = self::updateAttendance($e, $date);
					if ($is_true) {
						$is_updated = true;	
					}		
				}							
			}
		}
		return $is_updated;
	}		
	
	public static function isEmployeeWithAttendance(G_Employee $e,$date,$order_by,$limit)
	{
		$sql = "
			SELECT COUNT(*) as total						
			FROM ". G_EMPLOYEE_ATTENDANCE ." a
			WHERE
					a.date_attendance = ". Model::safeSql($date) ." AND 
					a.employee_id=" . Model::safeSql($e->getId()) . "
			".$order_by."
			".$limit."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function updateAttendanceByPeriod($start_date, $end_date) {
		$is_true = false;
		$is_updated = false;
	
		$employees = G_Employee_Finder::findAllActiveByDate($end_date);
		#$employees = G_Employee_Finder::findAllActiveByDateReturnIdOnly($end_date);
		#self::test_curl();
		#Tools::showArray($employees);
		
		$dates = Tools::getBetweenDates($start_date, $end_date);
		foreach ($employees as $e) {
			foreach ($dates as $date) {
				$is_true = self::updateAttendance($e, $date);
				if ($is_true) {
					$is_updated = true;	
				}
												
			}
		}
		return $is_updated;
	}

	public static function test_curl() {
		$parts=parse_url( url('attendance/show_curl'));
		$fp = fsockopen($parts['host'], 
		isset($parts['port'])?$parts['port']:80,$errno, $errstr, 30);
		
		if (!$fp) {
		  return false;
		} else {
		  $out = "POST ".$parts['path']." HTTP/1.1\r\n";
		  $out.= "Host: ".$parts['host']."\r\n";
		  $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		  $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
		  $out.= "Connection: Close\r\n\r\n";
		  if (isset($parts['query'])) $out.= $parts['query'];
		
		  fwrite($fp, $out);
		  fclose($fp);
		}	
	}
	
	public static function updateAllNoAttendanceDateByPeriod($date_start, $date_end) {
		$attendance = G_Attendance_Helper::getAllEmployeeIdAndAttendanceDateByPeriod($date_start, $date_end);
		$date = Tools::getBetweenDates($date_start, $date_end);
		foreach ($attendance as $employee_id => $dates) {
			$a = array_diff($date, $dates);	
			$employees[$employee_id] = $a;
		}		
		
		foreach ($employees as $employee_id => $no_dates) {
			foreach ($no_dates as $the_date) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendance($e, $the_date);
				}
			}
		}
	}
	
	//original generateAttendance
	public static function generateAttendance(IEmployee $e, $date) {
		list($year, $month, $day) = explode('-', $date);

		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if (!$a) {
			$a = new G_Attendance;
			$a->setDate($date);				
		}
		$t = $a->getTimesheet();
		if (!$t) {
			$t = new G_Timesheet;
		}
		
		$has_actual_schedule = false;
		if (strtotime($t->getTimeIn()) && strtotime($t->getTimeOut())) {
			$has_actual_schedule = true;
			$actual_time_in = $t->getTimeIn();
			$actual_time_out = $t->getTimeOut();
			$actual_date_in = $t->getDateIn();
			$actual_date_out = $t->getDateOut();
			
			$sched_time_in = $t->getScheduledTimeIn();
		    
			if (!Tools::isTimeNightShift($actual_time_in)) {
				#$hours_worked = Tools::computeHoursDifference($actual_time_in, $actual_time_out);
				
				// working..
				#$hours_worked = Tools::computeHoursDifference($sched_time_in, $actual_time_out);
				$diff = Tools::getDayHourDifference($actual_date_in,$actual_date_out,$sched_time_in, $actual_time_out);
				$hours_worked = $diff['total_hour'];
				#$hours_worked--; //minus lunchbreak
			} else {
				#$hours_worked = Tools::getHoursDifference($actual_time_in, $actual_time_out);
				//$hours_worked = Tools::computeHoursDifferenceWithDate($sched_time_in, $actual_time_out, $actual_date_in, $actual_date_out);
				
				#$hours_worked = Tools::computeHoursDifference($sched_time_in, $actual_time_out);
				$diff = Tools::getDayHourDifference($actual_date_in,$actual_date_out,$sched_time_in, $actual_time_out);
				$hours_worked = $diff['total_hour'];
			}
		}
		if ($has_actual_schedule) {
			
			// if hours work is greater than 0, that means the computation is correct else recompute the date (add plus 1).
			if (strtotime($actual_date_in) && strtotime($actual_date_out) && $hours_worked > 0) {
				$date_in = $actual_date_in;
				$date_out = $actual_date_out;
			} else {
				if (Tools::getAfternoon($actual_time_in) && Tools::getMorning($actual_time_out)) {
					$date_in = $date;
					$t->setDateIn($date);
					/*if ($hours_worked <= 4) {
						$date_out = $date;
						$t->setDateOut($date);
					} else {
						$date_out = date('Y-m-d', strtotime($date . '+1 day'));
						$t->setDateOut(date('Y-m-d', strtotime($date . '+1 day')));
					}*/
					
					$date_out = date('Y-m-d', strtotime($date . '+1 day'));
					$t->setDateOut($date_out);

				} else if (Tools::getMorning($actual_time_in) && Tools::getMorning($actual_time_out) && $hours_worked > 15) {
					$date_in = $date;
					$date_out = date('Y-m-d', strtotime($date . '+1 day'));
					$t->setDateIn($date);
					$t->setDateOut(date('Y-m-d', strtotime($date . '+1 day')));
				} else {
					$date_in = $date;
					$date_out = $date;
					$t->setDateIn($date);
					$t->setDateOut($date);
				}
			} 
			
			#$time = Tools::getTimeDifference("{$date_in} {$actual_time_in}", "{$date_out} {$actual_time_out}");
			#$hours_worked = number_format(($time['days'] * 24) + (($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
			if($hours_worked <= 0) { // kapag negative ung hours work, recompute using correct computed date
				$diff = Tools::getDayHourDifference($t->getDateIn(),$t->getDateOut(),$sched_time_in, $actual_time_out);
				$hours_worked = $diff['total_hour'];	
			}
			
			$diff2 	= Tools::getDayHourDifference($t->getDateIn(),$t->getDateOut(),$actual_time_in, $actual_time_out);
			$ahw 	= $total_hours_worked = $diff2['total_hour'];
			$t->setTotalHoursWorked($ahw);
		}		
			
		// HOLIDAY
		$has_holiday = false;
		$h = G_Holiday_Finder::findByMonthAndDay($month, $day);
		if ($h) {
			$has_holiday = true;	
			$a->setAsHoliday();
			$a->setAsPaid();
			$a->setHoliday($h);
		} else {
			$a->setAsNotHoliday();
			$a->setAsNotPaid();
			$a->setHoliday('');
		}
		
		// SCHEDULE
		$has_schedule = false;
		$ss = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
		$rd = G_Restday_Finder::findByEmployeeAndDate($e, $date);
		if ($ss) {
			$has_schedule = true;
			$a->setAsNotRestday();
			$a->setAsPaid();
			$scheduled_time_in = $ss->getTimeIn();
			$scheduled_time_out = $ss->getTimeOut();			
			$t->setScheduledTimeIn($scheduled_time_in);
			$t->setScheduledTimeOut($scheduled_time_out);	
		} else if ($rd) {
			$a->setAsRestday();
			$a->setAsPaid();
			$scheduled_time_in = $rd->getTimeIn();
			$scheduled_time_out = $rd->getTimeOut();
			$t->setScheduledTimeIn($scheduled_time_in);
			$t->setScheduledTimeOut($scheduled_time_out);
		} else {
			$s = G_Schedule_Finder::findByEmployeeAndDate($e, $date);
			if (!$s) {
				$active_schedule = G_Schedule_Finder::findActiveByEmployee($e, $date);
				if ($active_schedule) {
					$a->setAsRestday();
					$a->setAsPaid();
					$t->setScheduledTimeIn('');
					$t->setScheduledTimeOut('');
				} else {
					$groups = G_Group_Finder::findAllByEmployee($e);
					$sg = G_Schedule_Finder::findByGroupsAndDate($groups, $date);
					if (!$sg) {
						$active_group = G_Schedule_Finder::findActiveByGroups($groups);
						$default_schedule = G_Schedule_Finder::findDefaultByDate($date);
						if ($active_group) {							
							$a->setAsRestday();
							$a->setAsPaid();
							$t->setScheduledTimeIn('');
							$t->setScheduledTimeOut('');
						} else if ($default_schedule) {								
							$has_schedule = true;
							$a->setAsNotRestday();
							$a->setAsPaid();
							$scheduled_time_in = $default_schedule->getTimeIn();
							$scheduled_time_out = $default_schedule->getTimeOut();
							$t->setScheduledTimeIn($scheduled_time_in);
							$t->setScheduledTimeOut($scheduled_time_out);			
						} else {			
							$a->setAsRestday();
							$a->setAsPaid();
							$t->setScheduledTimeIn('');
							$t->setScheduledTimeOut('');						
						}						
					} else {						
						$has_schedule = true;
						$a->setAsNotRestday();
						$a->setAsPaid();
						$scheduled_time_in = $sg->getTimeIn();
						$scheduled_time_out = $sg->getTimeOut();
						$t->setScheduledTimeIn($scheduled_time_in);
						$t->setScheduledTimeOut($scheduled_time_out);								
					}
				}
			} else {
				$has_schedule = true;
				$a->setAsNotRestday();
				$a->setAsPaid();
				$scheduled_time_in = $s->getTimeIn();
				$scheduled_time_out = $s->getTimeOut();					
				$t->setScheduledTimeIn($scheduled_time_in);
				$t->setScheduledTimeOut($scheduled_time_out);							
			}
		}
		
		if ($has_schedule) {
			$total_schedule_hours = Tools::getHoursDifference($scheduled_time_in, $scheduled_time_out);
		}
		
		// RESTDAY IF SUNDAY		
		$is_sunday = Tools::isDateSunday($date);		
		if ($is_sunday) {			
			$a->setAsRestday();
		}

		// BREAK TIME		
		if ($has_schedule) {
			if (Tools::isTimeNightShift($scheduled_time_in)) {
				$break_time_in = '00:00:00';
				$break_time_out = '01:00:00';
			} else {
				$break_time_in = '12:00:00';
				$break_time_out = '13:00:00';
			}
		} else {
			$break_time_in = '';
			$break_time_out = '';
		}
		
		// OVERTIME
		$has_overtime = false;
		$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);

		if ($o) {
			$has_overtime = true;
			$overtime_in = $o->getTimeIn();
			$overtime_out = $o->getTimeOut();
			$t->setOverTimeIn($overtime_in);
			$t->setOverTimeOut($overtime_out);				
		} else {
			$t->setOverTimeIn('');
			$t->setOverTimeOut('');
			$t->setOvertimeHours(0);
			$t->setOvertimeExcessHours(0);
			$t->setNightShiftOvertimeHours(0);
			$t->setNightShiftOvertimeExcessHours(0);
			$t->setRestDayOvertimeHours(0);		
		}
		// LATE, UNDERTIME
		if ($has_schedule && $has_actual_schedule && !$a->isHoliday() && !$a->isRestday()) {
		 	$active_schedule = G_Schedule_Finder::findActiveByEmployee($e);
		 	if($active_schedule){
		 		$grace_period = $active_schedule->getGracePeriod();
		 	}else{
		 		$default_schedule = G_Schedule_Finder::findDefaultByDate($date);				
		 		if($default_schedule){
		 			$grace_period = $default_schedule->getGracePeriod();
		 		}else{
		 			$grace_period = 0;
		 		}	
		 	}

            // LATE
			$l = new Late_Calculator;			
			$l->setGracePeriod($grace_period);
			$l->setBreakTimeIn($break_time_in);
			$l->setBreakTimeOut($break_time_out);
			$l->setScheduledTimeIn($scheduled_time_in);
			$l->setScheduledTimeOut($scheduled_time_out);
			$l->setActualTimeIn($actual_time_in);
			$l->setActualTimeOut($actual_time_out);
			$late_hours = $l->computeLateHours();
			//echo " <br> Grace Period:{$grace_period} / Date:{$date} / Late:{$late_hours} / <br> ";
			$t->setLateHours($late_hours);

            // UNDERTIME
			//if ($t->getTotalHoursWorked() < 8) {
				$u = new Undertime_Calculator;
				$u->setScheduledTimeIn($scheduled_time_in);
				$u->setScheduledTimeOut($scheduled_time_out);
				$u->setActualTimeIn($actual_time_in);
				$u->setActualTimeOut($actual_time_out);
				$u->setBreakTimeIn($break_time_in);
				$u->setBreakTimeOut($break_time_out);
				$undertime_hours = $u->computeUndertimeHours();
				$t->setUndertimeHours($undertime_hours);
			//} else {
				//$t->setUndertimeHours(0);
			//}
			
			$has_actual_time = false;
			if($actual_time_in != "" && $actual_time_out != "") {
				$has_actual_time = true;
			}

			/*if($total_schedule_hours > $total_hours_worked && $a->isPresent()) {
				#$undertime_hours = $total_schedule_hours - $total_hours_worked;
				#$t->setUndertimeHours($undertime_hours);
				$u = new Undertime_Calculator;
				$u->setActualDateIn($actual_date_in);
				$u->setActualDateOut($actual_date_out);
				$u->setScheduledTimeIn($scheduled_time_in);
				$u->setScheduledTimeOut($scheduled_time_out);
				$u->setActualTimeIn($scheduled_time_in);
				$u->setActualTimeOut($actual_time_out);
				$u->setBreakTimeIn($break_time_in);
				$u->setBreakTimeOut($break_time_out);			
				#$undertime_hours = $u->computeUndertimeHours();
				$undertime_hours = $u->computeUndertime();
				$t->setUndertimeHours($undertime_hours);
			} else {
				$t->setUndertimeHours(0);
			}*/
		} else {
			$t->setLateHours(0);
			$t->setUndertimeHours(0);
		}
		
		// NIGHTSHIFT
		if ($has_actual_schedule && !$a->isHoliday() && !$a->isRestday() && $has_schedule) {			
			$ns = new Nightshift_Calculator;
			$ns->setScheduledTimeIn($scheduled_time_in);
			$ns->setScheduledTimeOut($scheduled_time_out);
			$ns->setOvertimeIn($overtime_in);
			$ns->setOvertimeOut($overtime_out);
			$ns->setActualTimeIn($actual_time_in);
			$ns->setActualTimeOut($actual_time_out);
			$ns_hours = $ns->compute();
			$t->setNightShiftHours($ns_hours);
			
			#echo 'Nightshift : '.$ns_hours;
		}
						
		// OVERTIME COMPUTATION
		if ($has_overtime) {
			$o = new G_Overtime_Calculator;
			$o->setDate($t->getDateIn());
			$o->setLimitHours(8);
			
			if(self::isDateOutGreaterThanDateIn($t->getDateIn(),$t->getDateOut())) {
				/*$o->setScheduleIn($t->getDateIn());
				$o->setScheduleOut($t->getDateOut());*/
				$o->setScheduleIn($scheduled_time_in);
				$o->setScheduleOut($scheduled_time_out);
			} else {				
				$o->setScheduleIn($scheduled_time_in);
				$o->setScheduleOut($scheduled_time_out);
			}			
			
			$ot_dates = Tools::getDateInAndOut($overtime_in, $overtime_out, $date);		
			
			$o->setOvertimeDateIn($ot_dates['date_in']);
			$o->setOvertimeDateOut($ot_dates['date_out']);		
			$o->setOvertimeIn($overtime_in);
			$o->setOvertimeOut($overtime_out);
			
			if ($a->isHoliday() || $a->isRestday() || $total_schedule_hours == 4 || Tools::isDateSaturday($t->getDateIn()) || Tools::isDateSunday($t->getDateIn())) {								
				$o->setSubtractBreak();					
			}
			
			$ot_hours		 = $o->computeHours();
			$ot_excess_hours = $o->computeExcessHours();			
			$ot_nd 		     = $o->computeNightDiff();
			$ot_excess_nd    = $o->computeExcessNightDiff();
			
			//echo "OT Excess Hours:{$ot_excess_hours}";
			
			//if (Tools::isDateSaturday($date) && $total_schedule_hours == 4 && !$a->isHoliday()) {
            if ($a->isRestday()) {
				$t->setOvertimeHours($ot_hours); // value either regular or rest day
				$t->setOvertimeExcessHours($ot_excess_hours); // value either regular or rest day
                $t->setOvertimeNightShiftHours($ot_nd);
                $t->setOvertimeNightShiftExcessHours($ot_excess_nd);

                // Deprecated - Start
				$t->setNightShiftOvertimeHours($ot_nd); // value either regular or rest day
				$t->setNightShiftOvertimeExcessHours($ot_excess_nd); // value either regular or rest day
                // Deprecated - End

				$t->setRestDayOvertimeHours($ot_hours);
				$t->setRestDayOvertimeExcessHours($ot_excess_hours);
				$t->setRestDayOvertimeNightShiftHours($ot_nd);
				$t->setRestDayOvertimeNightShiftExcessHours($ot_excess_nd);

				$t->setRegularOvertimeHours(0);
				$t->setRegularOvertimeExcessHours(0);
				$t->setRegularOvertimeNightShiftHours(0);
				$t->setRegularOvertimeNightShiftExcessHours(0);
			} else { // Regular
				$t->setOvertimeHours($ot_hours); // value either regular or rest day
				$t->setOvertimeExcessHours($ot_excess_hours); // value either regular or rest day
                $t->setOvertimeNightShiftHours($ot_nd);
                $t->setOvertimeNightShiftExcessHours($ot_excess_nd);

                // Deprecated - Start
				$t->setNightShiftOvertimeHours($ot_nd); // value either regular or rest day
				$t->setNightShiftOvertimeExcessHours($ot_excess_nd); // value either regular or rest day
                // Deprecated - End

				$t->setRegularOvertimeHours($ot_hours);
				$t->setRegularOvertimeExcessHours($ot_excess_hours);
				$t->setRegularOvertimeNightShiftHours($ot_nd);
				$t->setRegularOvertimeNightShiftExcessHours($ot_excess_nd);
			
				$t->setRestDayOvertimeHours(0);
				$t->setRestDayOvertimeExcessHours(0);
				$t->setRestDayOvertimeNightShiftHours(0);
				$t->setRestDayOvertimeNightShiftExcessHours(0);									
			}
		}
		
		// LEAVE
		$l = G_Leave_Finder::findApprovedByEmployeeAndDate($e, $date);

		if ($l) {
			$a->setAsLeave();
			$a->setLeaveId($l->getId());	
		}

        // NO LATE AND UNDERTIME HOURS IF HOLIDAY OR RESTDAY
		if ($a->isHoliday() || $a->isRestday()) {
			$t->setNightShiftHours(0);	
			$t->setLateHours(0);
			$t->setUndertimeHours(0);
		}
		$a->setTimesheet($t);
        if ($e) {
            $a->setEmployeeId($e->getId()); // set employee id under attendance object
        }
		return $a;
	}
	
	public static function isDateOutGreaterThanDateIn($date_in, $date_out) {
		if($date_in == "" && $date_out == "" ) {
			return false;
		} else if(strtotime($date_out) > strtotime($date_in)) {
			return true;	
		} else {
			return false;
		}
	}
	
	
	//this is for early ot
	public static function generateAttendanceFOREARLYOT(IEmployee $e, $date) {
		list($year, $month, $day) = explode('-', $date);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if (!$a) {
			$a = new G_Attendance;
			$a->setDate($date);				
		}
		$t = $a->getTimesheet();
		if (!$t) {
			$t = new G_Timesheet;	
		}
		$has_actual_schedule = false;
		if (strtotime($t->getTimeIn()) && strtotime($t->getTimeOut())) {
			$has_actual_schedule = true;
			$actual_time_in = $t->getTimeIn();
			$actual_time_out = $t->getTimeOut();
			$actual_date_in = $t->getDateIn();
			$actual_date_out = $t->getDateOut();
						
			if (!Tools::isTimeNightShift($actual_time_in)) {
				$hours_worked = Tools::computeHoursDifference($actual_time_in, $actual_time_out);
			} else {
				$hours_worked = Tools::getHoursDifference($actual_time_in, $actual_time_out);
			}
			
		}
		
		if ($has_actual_schedule) {
			if (strtotime($actual_date_in) && strtotime($actual_date_out)) {
				$date_in = $actual_date_in;
				$date_out = $actual_date_out;
			} else {
				if (Tools::getAfternoon($actual_time_in) && Tools::getMorning($actual_time_out)) {
					$date_in = $date;
					$t->setDateIn($date);
					if ($hours_worked <= 4) {
						$date_out = $date;
						$t->setDateOut($date);
					} else {
						$date_out = date('Y-m-d', strtotime($date . '+1 day'));
						$t->setDateOut(date('Y-m-d', strtotime($date . '+1 day')));
					}
				} else if (Tools::getMorning($actual_time_in) && Tools::getMorning($actual_time_out) && $hours_worked > 15) {
					$date_in = $date;
					$date_out = date('Y-m-d', strtotime($date . '+1 day'));
					$t->setDateIn($date);
					$t->setDateOut(date('Y-m-d', strtotime($date . '+1 day')));
				} else {
					$date_in = $date;
					$date_out = $date;
					$t->setDateIn($date);
					$t->setDateOut($date);
				}
			}
			
			$time = Tools::getTimeDifference("{$date_in} {$actual_time_in}", "{$date_out} {$actual_time_out}");
			$hours_worked = number_format(($time['days'] * 24) + (($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
			//$hours_worked = number_format((($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');			
			$t->setTotalHoursWorked($hours_worked);
		}		
		
		// HOLIDAY
		$has_holiday = false;
		$h = G_Holiday_Finder::findByMonthAndDay($month, $day);
		if ($h) {
			$has_holiday = true;	
			$a->setAsHoliday();
			$a->setAsPaid();
			$a->setHoliday($h);
		} else {
			$a->setAsNotHoliday();
			$a->setAsNotPaid();
			$a->setHoliday('');
		}
		
		// SCHEDULE
		$has_schedule = false;
		$ss = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
		$rd = G_Restday_Finder::findByEmployeeAndDate($e, $date);
		if ($ss) {
			$has_schedule = true;
			$a->setAsNotRestday();
			$a->setAsPaid();
			$scheduled_time_in = $ss->getTimeIn();
			$scheduled_time_out = $ss->getTimeOut();			
			$t->setScheduledTimeIn($scheduled_time_in);
			$t->setScheduledTimeOut($scheduled_time_out);	
		} else if ($rd) {
			$a->setAsRestday();
			$a->setAsPaid();
			$scheduled_time_in = $rd->getTimeIn();
			$scheduled_time_out = $rd->getTimeOut();
			$t->setScheduledTimeIn($scheduled_time_in);
			$t->setScheduledTimeOut($scheduled_time_out);
		} else {
			$s = G_Schedule_Finder::findByEmployeeAndDate($e, $date);
			if (!$s) {				
				$active_schedule = G_Schedule_Finder::findActiveByEmployee($e);
				if ($active_schedule) {					
					$a->setAsRestday();
					$a->setAsPaid();
					$t->setScheduledTimeIn('');
					$t->setScheduledTimeOut('');
				} else {					
					$groups = G_Group_Finder::findAllByEmployee($e);
					$sg = G_Schedule_Finder::findByGroupsAndDate($groups, $date);
					if (!$sg) {						
						$active_group = G_Schedule_Finder::findActiveByGroups($groups);
						$default_schedule = G_Schedule_Finder::findDefaultByDate($date);
						if ($active_group) {							
							$a->setAsRestday();
							$a->setAsPaid();
							$t->setScheduledTimeIn('');
							$t->setScheduledTimeOut('');
						} else if ($default_schedule) {								
							$has_schedule = true;
							$a->setAsNotRestday();
							$a->setAsPaid();
							$scheduled_time_in = $default_schedule->getTimeIn();
							$scheduled_time_out = $default_schedule->getTimeOut();
							$t->setScheduledTimeIn($scheduled_time_in);
							$t->setScheduledTimeOut($scheduled_time_out);			
						} else {			
							$a->setAsRestday();
							$a->setAsPaid();
							$t->setScheduledTimeIn('');
							$t->setScheduledTimeOut('');						
						}						
					} else {						
						$has_schedule = true;
						$a->setAsNotRestday();
						$a->setAsPaid();
						$scheduled_time_in = $sg->getTimeIn();
						$scheduled_time_out = $sg->getTimeOut();
						$t->setScheduledTimeIn($scheduled_time_in);
						$t->setScheduledTimeOut($scheduled_time_out);								
					}
				}
			} else {
				$has_schedule = true;
				$a->setAsNotRestday();
				$a->setAsPaid();
				$scheduled_time_in = $s->getTimeIn();
				$scheduled_time_out = $s->getTimeOut();					
				$t->setScheduledTimeIn($scheduled_time_in);
				$t->setScheduledTimeOut($scheduled_time_out);							
			}
		}
		
		if ($has_schedule) {
			$total_schedule_hours = Tools::getHoursDifference($scheduled_time_in, $scheduled_time_out);
		}
		
		// RESTDAY IF SUNDAY		
		$is_sunday = Tools::isDateSunday($date);		
		if ($is_sunday) {			
			$a->setAsRestday();
		}

		// BREAK TIME		
		if ($has_schedule) {
			if (Tools::isTimeNightShift($scheduled_time_in)) {
				$break_time_in = '00:00:00';
				$break_time_out = '01:00:00';
			} else {
				$break_time_in = '12:00:00';
				$break_time_out = '13:00:00';
			}
		} else {
			$break_time_in = '';
			$break_time_out = '';
		}
		
		// OVERTIME
		$has_overtime = false;
		$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
		if ($o) {
			$has_overtime = true;
			$overtime_in = $o->getTimeIn();
			$overtime_out = $o->getTimeOut();
			$t->setOverTimeIn($overtime_in);
			$t->setOverTimeOut($overtime_out);				
		} else {
			$t->setOverTimeIn('');
			$t->setOverTimeOut('');
			$t->setOvertimeHours(0);
			$t->setOvertimeExcessHours(0);
			$t->setNightShiftOvertimeHours(0);
			$t->setNightShiftOvertimeExcessHours(0);
			$t->setRestDayOvertimeHours(0);		
		}
		
		/*$o = G_Early_Overtime_Finder::findByEmployeeAndDate($e, $date);
		if ($o) {
			$has_overtime = true;
			$early_overtime_in = $o->getEarlyTimeIn();
			$early_overtime_out = $o->getEarlyTimeOut();
			$t->setEarlyOverTimeIn($early_overtime_in);
			$t->setEarlyOverTimeOut($early_overtime_out);				
		} else {
			$t->setEarlyOverTimeIn('');
			$t->setEarlyOverTimeOut('');
		}*/
		
		// LATE, UNDERTIME
		if ($has_schedule && $has_actual_schedule && !$a->isHoliday() && !$a->isRestday()) {
			$l = new Late_Calculator;
			$l->setGracePeriod(0);
			$l->setBreakTimeIn($break_time_in);
			$l->setBreakTimeOut($break_time_out);			
			$l->setScheduledTimeIn($scheduled_time_in);
			$l->setScheduledTimeOut($scheduled_time_out);
			$l->setActualTimeIn($actual_time_in);
			$l->setActualTimeOut($actual_time_out);
			$late_hours = $l->computeLateHours();
			$t->setLateHours($late_hours);
			
			if ($hours_worked < 8) {
				$u = new Undertime_Calculator;
				$u->setScheduledTimeIn($scheduled_time_in);
				$u->setScheduledTimeOut($scheduled_time_out);
				$u->setActualTimeIn($actual_time_in);
				$u->setActualTimeOut($actual_time_out);
				$u->setBreakTimeIn($break_time_in);
				$u->setBreakTimeOut($break_time_out);			
				$undertime_hours = $u->computeUndertimeHours();
				$t->setUndertimeHours($undertime_hours);
			} else {
				$t->setUndertimeHours(0);
			}
		} else {
			$t->setLateHours(0);
			$t->setUndertimeHours(0);
		}
		
		// NIGHTSHIFT
		if ($has_actual_schedule && !$a->isHoliday() && !$a->isRestday() && $has_schedule) {			
			$ns = new Nightshift_Calculator;
			$ns->setScheduledTimeIn($scheduled_time_in);
			$ns->setScheduledTimeOut($scheduled_time_out);
			$ns->setOvertimeIn($overtime_in);
			$ns->setOvertimeOut($overtime_out);
			$ns->setActualTimeIn($actual_time_in);
			$ns->setActualTimeOut($actual_time_out);
			$ns_hours = $ns->compute();
			$t->setNightShiftHours($ns_hours);			
		}
						
		// OVERTIME COMPUTATION
		if ($has_overtime) {
			
			$o = new G_Overtime_Calculator;
			$o->setLimitHours(8);
			$o->setScheduleIn($scheduled_time_in);
			$o->setScheduleOut($scheduled_time_out);
			$o->setOvertimeIn($overtime_in);
			$o->setOvertimeOut($overtime_out);
			if ($a->isHoliday() || $a->isRestday() || $total_schedule_hours == 4) {
				$o->setSubtractBreak();	
			}
			
			$ot_hours = $o->computeHours();
			$ot_excess_hours = $o->computeExcessHours();
			$ot_nd = $o->computeNightDiff();
			$ot_excess_nd = $o->computeExcessNightDiff();
		
			/*if($early_overtime_in && $early_overtime_out) {
				$eo = new G_Early_Overtime_Calculator;
				$eo->setLimitHours(8);
				$eo->setScheduleIn($scheduled_time_in);
				$eo->setScheduleOut($scheduled_time_out);
				$eo->setOvertimeIn($early_overtime_in);
				$eo->setOvertimeOut($early_overtime_out);
				if ($a->isHoliday() || $a->isRestday() || $total_schedule_hours == 4) {
					$eo->setSubtractBreak();	
				}
				
				$eot_hours = $eo->computeHours();
				$eot_excess_hours = $eo->computeExcessHours();
				$eot_nd = $eo->computeNightDiff();
				$eot_excess_nd = $eo->computeExcessNightDiff();
			}*/
			
			$total_ot_hours = $ot_hours + $eot_hours;
			if($total_ot_hours > 8) {
				$total_excess_hours = $total_ot_hours - 8;
				$total_ot_hours=8;
			}
			
			$total_ot_nd_hours = $ot_nd + $eot_excess_nd;
			if($total_ot_nd_hours > 8) {
				$total_excess_nd_hours = $total_ot_nd_hours - 8;
				$total_ot_nd_hours=8;
			}
			/*echo "total ot hours : {$total_ot_hours} <br/>";
			echo "total excess	: {$total_excess_hours} <br/>";
			echo "total otnd hours : {$total_ot_nd_hours} <br/>";
			echo "total excess nd	: {$total_excess_nd_hours} <br/>";*/
			
			
			if (Tools::isDateSaturday($date) && $total_schedule_hours == 4 && !$a->isHoliday()) {
				$t->setRestDayOvertimeHours($total_ot_hours);
				$t->setRestDayOvertimeExcessHours($total_excess_hours);
				$t->setRestDayOvertimeNightShiftHours($total_ot_nd_hours);
				$t->setRestDayOvertimeNightShiftExcessHours($total_excess_nd_hours);	

				$t->setOvertimeHours(0);
				$t->setOvertimeExcessHours(0);
				$t->setNightShiftOvertimeHours(0);
				$t->setNightShiftOvertimeExcessHours(0);								
			} else {
				$t->setOvertimeHours($total_ot_hours);
				$t->setOvertimeExcessHours($total_excess_hours);
				$t->setNightShiftOvertimeHours($total_ot_nd_hours);
				$t->setNightShiftOvertimeExcessHours($total_excess_nd_hours);
				
				$t->setRestDayOvertimeHours(0);
				$t->setRestDayOvertimeExcessHours(0);
				$t->setRestDayOvertimeNightShiftHours(0);
				$t->setRestDayOvertimeNightShiftExcessHours(0);									
			}
		}
		
		// LEAVE
		$l = G_Leave_Finder::findApprovedByEmployeeAndDate($e, $date);
		if ($l) {
			$a->setAsLeave();
			$a->setLeaveId($l->getId());	
		}
		
		if ($a->isHoliday() || $a->isRestday()) {
			$t->setNightShiftHours(0);	
			$t->setLateHours(0);
			$t->setUndertimeHours(0);
		}
		
		//Tools::showArray($t);
		
		$a->setTimesheet($t);	
		return $a;
	}
	
	// DEPRECATED
	public static function getUpdatedAttendance(IEmployee $e, $date) {
		return self::generateAttendance($e, $date);
	}
	
	/*
		Update attendance of particular employee.
		Update:
			holiday, schedule (restday or not), leave
	*/
	/*public static function updateAttendance(IEmployee $e, $date) {
		$a = self::generateAttendance($e, $date);
		$rd = new G_Restday();
		$c  = $rd->checkWeekNumberIfWithRestDayByEmployeeNumber($date,$e);

		$is_sunday = Tools::isDateSunday($date);

		if ($is_sunday && $c > 0) {
			//$a->setAsRestday();
		}else{
			$is_updated = $a->recordToEmployee($e);
		}

		$is_updated = true;


		// IF ATTENDANCE IS UPDATED, UPDATE PAYSLIP AS WELL IF PAYSLIP IS ALREADY GENERATED
		G_Payslip_Helper::updatePayslipIfExistByEmployeeAndDate($e, $date);

		return $is_updated;
	}*/

	public static function updateAttendance(IEmployee $e, $date) {
		if ($e) {
            $a = self::generateAttendance($e, $date);
            $is_true = self::updateAttendanceBySingleAttendance($a);
    		if ($is_true) {
    			$is_updated = true;
    		}
		}
		return $is_updated;
	}
	
	public static function recordTimecard(IEmployee $e, $date, $time_in, $time_out, $date_in, $date_out, $overtime_in, $overtime_out, $grace_period = 0) {
		
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if (!$a) {
			$a = new G_Attendance;
		}
		$a->setDate($date);
		$a->setAsPaid();
		$a->setAsPresent();
		$t = new G_Timesheet;
		$t->setTimeIn($time_in);
		$t->setTimeOut($time_out);
		$t->setDateIn($date_in);
		$t->setDateOut($date_out);		
		$t->setOvertimeIn($overtime_in);
		$t->setOvertimeOut($overtime_out);	
		$a->setTimesheet($t);						
		return $a->recordToEmployee($e);
	}
	
	public static function getAllAttendanceByEmployeesAndPeriod($employees, $start_date, $end_date) {
		foreach ($employees as $e) {
			if ($e) {
				$at = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
				foreach ($at as $a) {
					$attendance[$e->getId()] = $a;
				}
			}
		}
		return $attendance;
	}
	
	public static function getAllAttendanceGroupByEmployeeAndDate($employees, $start_date, $end_date) {
		foreach ($employees as $e) {
			if ($e) {
				if($e->getTerminatedDate() != '0000-00-00') {
					$at = G_Attendance_Finder::findByEmployeeAndPeriodFilterByTerminatedDate($e, $start_date, $end_date);
					foreach ($at as $a) {
						$attendance[$e->getId()][$a->getDate()] = $a;
					}					
				} else {
					$at = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
					foreach ($at as $a) {
						$attendance[$e->getId()][$a->getDate()] = $a;
					}					
				}
			}
		}
		return $attendance;
	}
	
	public static function importTimesheet($file) {
        error_reporting(1);

		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		/*$file = BASE_PATH . 'files/files/DTR_08A_complete.xls';
		$file = BASE_PATH . 'files/files/DTR_08A.xls';
		
		// DEFAULT FORMAT
		$time = new Timesheet_Import($file);
				
		// IM FORMAT					
		$time = new G_Timesheet_Import_IM($file);
		$is_imported = $time->import();*/
		
		$time = new Timesheet_Raw_Converter_IM($file);
		$raw_timesheet = $time->convert();

		$r = new G_Timesheet_Raw_Logger($raw_timesheet);
        $r->logTimesheet();
		
		$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $tr->filterAndUpdateAttendance();
		
		/*
		$dates = $time->getInvolvedDates();
		$cutoffs = G_Cutoff_Period_Helper::getAllByDates($dates);
		foreach ($cutoffs as $c) {
			if ($c) {
				G_Attendance_Helper::updateAllNoAttendanceDateByPeriod($c->getStartDate(), $c->getEndDate());	
			}
		}*/
		//return $is_imported;
		return true;
	}
	
	public static function getAllEmployeeIdAndAttendanceDateByPeriod($date_start, $date_end) {
		$sql = "
			SELECT employee_id, date_attendance
			FROM ". G_EMPLOYEE_ATTENDANCE ."
			WHERE date_attendance >= ". Model::safeSql($date_start) ."
			AND date_attendance <= ". Model::safeSql($date_end) ."
			ORDER BY employee_id ASC, date_attendance ASC
		";
		$result = Model::runSql($sql);
		while ($row = Model::fetchAssoc($result)) {
			$data[$row['employee_id']][$row['date_attendance']] = $row['date_attendance'];	
		}
		return $data;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalRestdayNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftHours();
						$total += $temp_total;
					}
				//}
			}
		}	
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialRestdayNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftHours();
						$total += $temp_total;
					}
				//}
			}
		}	
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalRestdayOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}	
		return $total;
	}	
		
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialRestdayOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}	
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalRestdayHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getTotalHoursWorked();
						//$time_in = $t->getTimeIn();
						//$time_out = $t->getTimeOut();
						//$temp_total = Tools::getHoursDifference($time_in, $time_out);
						$total += $temp_total;	
					}
				//}
			}
		}	
		return $total;
	}	
			
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialRestdayHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getTotalHoursWorked();
						//$time_in = $t->getTimeIn();
						//$time_out = $t->getTimeOut();
						//$temp_total = Tools::getHoursDifference($time_in, $time_out);
						$total += $temp_total;	
					}
				//}
			}
		}	
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}	
			
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}	
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}	
		
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeExcessHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getTotalHoursWorked();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
			
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getTotalHoursWorked();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getOvertimeExcessHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	public static function getTotalHolidayLegalNightShiftOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftOvertimeExcessHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}	
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidayLegalNightShiftOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::LEGAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHolidaySpecialNightShiftOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftOvertimeHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}
	
	public static function getTotalHolidaySpecialNightShiftOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				//$holiday = $a->getHoliday();
				//if ($holiday) {
					if ($a->getHolidayType() == G_Holiday::SPECIAL) {
						$t = $a->getTimesheet();
						$temp_total = $t->getNightShiftOvertimeExcessHours();
						$total += $temp_total;
					}
				//}
			}
		}
		return $total;
	}	
			
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getNightShiftHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayNightShiftOvertimeHours($attendance) {
		$total = 0;
		$other_total = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				$temp_total = $t->getNightShiftOvertimeHours();
				$total += $temp_total;
			}
			if ($t) {
				$temp_other_total = $t->setRestDayOvertimeNightShiftHours();
				$other_total += $temp_other_total;
			}			
		}
		return $total + $other_total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayNightShiftOvertimeExcessHours($attendance) {
		$total = 0;
		$other_total = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				$temp_total = $t->getNightShiftOvertimeExcessHours();
				$total += $temp_total;
			}
			if ($t) {
				$temp_other_total = $t->setRestDayOvertimeNightShiftExcessHours();
				$other_total += $temp_other_total;
			}
		}
		return $total + $other_total;
	}			
		
	/*

		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayOvertimeHours($attendance) {
		$total = 0;
		$other_total = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && $t) {
				$temp_total = $t->getOvertimeHours();
				$total += $temp_total;
			}
			if ($t) {
				$temp_other_total = $t->getRestDayOvertimeHours();
				$other_total += $temp_other_total;
			}
		}
		return $total + $other_total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayOvertimeExcessHours($attendance) {
		$total = 0;
		$other_total = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				$temp_total = $t->getOvertimeExcessHours();
				$total += $temp_total;
			}
			if ($t) {
				$temp_other_total = $t->getRestDayOvertimeExcessHours();
				$other_total += $temp_other_total;
			}			
		}
		return $total + $other_total;
	}		
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRestDayHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getTotalHoursWorked();//Tools::getHoursDifference($time_in, $time_out);
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRegularHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getTotalHoursWorked();
				$total += $temp_total;
			}
		}
		return $total;
	}	
		
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalLateHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getLateHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
			
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$total = $t->getOvertimeHours();
			}
		}
		return $total;
	}
	
	public static function getTotalOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			//if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
			if ($a->isPresent() && !$a->isRestday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getOvertimeExcessHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalUndertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getUndertimeHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalHoursWorked($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getTotalHoursWorked();
				$total += $temp_total;
			}
		}
		return $total;
	}		
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getNightShiftHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalRegularNightShiftHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getNightShiftHours();
				$total += $temp_total;
			}
		}
		return $total;
	}	
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/	
	public static function getTotalNightShiftOvertimeHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getNightShiftOvertimeHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	public static function getTotalNightShiftOvertimeExcessHours($attendance) {
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$temp_total = $t->getNightShiftOvertimeExcessHours();
				$total += $temp_total;
			}
		}
		return $total;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countPresentDays($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$count++;	
			}
		}
		return $count;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countPresentDaysWithPay($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isPaid() && !$a->isRestday() && !$a->isHoliday()) {
				$count++;	
			}
		}
		return $count;
	}	
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countAbsentDaysWithPay($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if (!$a->isPresent() && $a->isPaid() && !$a->isRestday() && !$a->isLeave()) {
				$count++;	
			}
		}
		return $count;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countAbsentDaysWithoutPay($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if (!$a->isPresent() && !$a->isPaid() && !$a->isRestday() && !$a->isLeave()) {
				$count++;	
			}
		}
		return $count;
	}
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countAbsentDays($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if (!$a->isPresent() && !$a->isRestday() && !$a->isLeave()) {
				$count++;	
			}
		}
		return $count;
	}	
	
	/*
		$attendance - array produced by G_Attendance_Finder
	*/
	public static function countSuspendedDays($attendance) {
		$count = 0;
		foreach ($attendance as $a) {
			if ($a->isSuspended()) {
				$count++;	
			}
		}
		return $count;
	}
	
	public static function computeNDHRS($arr) {		
		$start_nd = '22:00:00';
		$end_nd   = '24:00:00';		
		
		//echo '<pre>';
		//print_r($arr);
		
		if($arr['s_date_in'] != $arr['s_date_out']){				
			if($arr['a_time_in'] <= $start_nd){				
				$start_var = $start_nd;							
			}else{					
				$start_var = $arr['a_time_in'];		
			}
			
			if($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd){
				$total_nd_hrs = Tools::computeHoursDifference($start_var,$arr['a_time_out']);				
				//$total_nd_hrs = $arr['a_time_out'] - $start_var;
			}else{
				if(($arr['a_date_out'] == $arr['a_date_in']) && ($arr['a_time_out'] < $start_nd)){					
					$total_nd_hrs = 0;
				}else{					
					$total_nd_hrs = Tools::computeHoursDifference($start_var,$end_nd);				
				}
				//$total_nd_hrs = $end_nd - $start_var;
			}
		}else{			
			if($arr['s_time_out'] <= $end_nd && $arr['s_time_out'] >= $start_nd){
				if($arr['a_time_in'] <= $start_nd){
					$start_var = $start_nd;					
				}else{
					$start_var = $arr['a_time_in'];		
				}
				
				if($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd){
					$total_nd_hrs = Tools::computeHoursDifference($start_var,$arr['a_time_out']);	
					//$total_nd_hrs = $start_var - $arr['a_time_out'];
				}else{
					$total_nd_hrs = Tools::computeHoursDifference($start_var,$end_nd);
					//$total_nd_hrs = $start_var - $end_nd;
				}
			}else{
				if($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd){				
					if($arr['a_time_in'] <= $start_nd){
						$start_var = $start_nd;							
					}else{				
						$start_var = $arr['a_time_in'];		
					}
					$total_nd_hrs = Tools::computeHoursDifference($start_var,$arr['a_time_out']);
					//$total_nd_hrs = $arr['a_time_out'] - $start_var;
				}else{
					$total_nd_hrs = 0;
				}
			}
		}
		
		return $total_nd_hrs;
	
	}
		
	/*
		$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		$attendance = G_Attendance_Helper::changeArrayKeyToDate($a);
	*/
	public static function changeArrayKeyToDate($attendance) {
		$records = array();
		foreach($attendance as $a) {
			$records[$a->getDate()] = $a;
		}
		return $records;
	}
	
	public static function findByDate($date,$order_by,$limit)
	{
		$sql = "
			SELECT 
				a.*,
				CONCAT(e.firstname, ' ', e.lastname) as employee_name,
				e.employee_code				
			FROM ". G_EMPLOYEE_ATTENDANCE ." a,g_employee e
			WHERE
					a.date_attendance = ". Model::safeSql($date) ." AND 
					e.id=a.employee_id
			".$order_by."
			".$limit."
		";

		return Model::runSql($sql,true);
	}
	
	public static function findByDateAndEmployee(G_Employee $e,$date,$order_by,$limit)
	{
		$sql = "
			SELECT 
				a.*								
			FROM ". G_EMPLOYEE_ATTENDANCE ." a
			WHERE
					a.date_attendance = ". Model::safeSql($date) ." AND 
					a.employee_id=" . Model::safeSql($e->getId()) . "
			".$order_by."
			".$limit."
		";

		return Model::runSql($sql,true);
	}
	
	public static function countTotalRecordsByDate($date) {
		$sql = "
			SELECT COUNT(*) as total
			FROM ". G_EMPLOYEE_ATTENDANCE ." a, g_employee e
			WHERE
					a.date_attendance = ". Model::safeSql($date) ." AND 
					e.id=a.employee_id
		
		";

		$total = Model::runSql($sql,true);
		return $total[0]['total'];
	}
	
	public static function getDatesByEmployeeNumberAndWeekNumber(G_Employee $e,$week_number) {		
		$sql = "
			SELECT id, employee_id, date_attendance, WEEK( a.date_attendance ) AS week_number,is_present,is_paid,is_restday
			FROM " . G_EMPLOYEE_ATTENDANCE ." a
			WHERE WEEK(a.date_attendance) = ". Model::safeSql($week_number) ." AND a.employee_id = " . Model::safeSql($e->getId()) . "
		";		
		//echo $sql;	
		$result = Model::runSql($sql,true);
		return $result;		
	}
	
	public static function countAttendanceAbsenceData($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			$search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);			
		}
		
		if($query['position_applied'] != '' && $query['position_applied'] != 'all'){
			$search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
		
		$sql = "
			SELECT e.employee_code, e.lastname,	e.firstname, 	
				ejh.name AS position_name, 	
				esh.name AS department_name,		
				ea.date_attendance, COUNT(ea.is_present) AS total_absent 
				
			FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh        
			WHERE (ea.employee_id = e.id) 
				AND (e.id = esh.employee_id AND esh.end_date = '')
				AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
				AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
				AND(ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND is_leave = 0 AND ea.is_restday = 0)				
				" . $search . "
			GROUP BY e.employee_code
		";				
		$result = Model::runSql($sql);		
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['employee_code']  = $row['employee_code'];
			$return[$counter]['name']  			= $row['lastname'] . ", " . $row['firstname'];
			$return[$counter]['position']  		= $row['position_name'];
			$return[$counter]['department']		= $row['department_name'];
			$return[$counter]['total_absent']   = $row['total_absent'];			
			$counter++;
		}
		return $return;
	}
	
	public static function getAttendanceAbsenceData($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			$search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);			
		}
		
		if($query['position_applied'] != '' && $query['position_applied'] != 'all'){
			$search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
		
		$sql = "
			SELECT e.employee_code, e.lastname,	e.firstname, 	
				ejh.name AS position_name, 		
				esh.name AS department_name, 	
				ea.date_attendance
				
			FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh    
			WHERE (ea.employee_id = e.id) 
				AND (e.id = esh.employee_id AND esh.end_date = '')
				AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
				AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
				AND(ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND is_leave = 0 AND ea.is_restday = 0)				
				" . $search . "
			ORDER BY ea.date_attendance DESC
		";		
				
		$result = Model::runSql($sql);		
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['employee_code']  = $row['employee_code'];
			$return[$counter]['name']  			= $row['lastname'] . ", " . $row['firstname'];
			$return[$counter]['department']		= $row['department_name'];
			$return[$counter]['position']  		= $row['position_name'];
			$return[$counter]['date_attendance']= $row['date_attendance'];			
			$counter++;
		}
		return $return;
	}
	
	public static function getTardinessData($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			$search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);			
		}
		
		if($query['position_applied'] != '' && $query['position_applied'] != 'all'){
			$search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
		
		$sql = "
			SELECT e.employee_code, e.lastname,	e.firstname, 	
				ejh.name AS position_name, 		
				esh.name AS department_name, 	
				ea.date_attendance, ea.scheduled_time_in, ea.scheduled_time_out, ea.actual_time_in, ea.actual_time_out, ea.late_hours 
				
			FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh    
			WHERE (ea.employee_id = e.id) 
				AND (e.id = esh.employee_id AND esh.end_date = '')
				AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
				AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
				AND(ea.late_hours <> '')				
				" . $search . "
			ORDER BY ea.date_attendance DESC
		";		
	
		$result = Model::runSql($sql);		
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['employee_code']     = $row['employee_code'];
			$return[$counter]['name']  			   = $row['lastname'] . ", " . $row['firstname'];
			$return[$counter]['department']		   = $row['department_name'];
			$return[$counter]['position']  		   = $row['position_name'];
			$return[$counter]['date_attendance']   = $row['date_attendance'];			
			$return[$counter]['scheduled_time_in'] = $row['scheduled_time_in'];			
			$return[$counter]['scheduled_time_out']= $row['scheduled_time_out'];			
			$return[$counter]['actual_time_in']	   = $row['actual_time_in'];			
			$return[$counter]['actual_time_out']   = $row['actual_time_out'];			
			$return[$counter]['late_hours']		   = $row['late_hours'];			
			$counter++;
		}
		return $return;
	}
	
	public static function countTardinessData($query) {
		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			$search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);			
		}
		
		if($query['position_applied'] != '' && $query['position_applied'] != 'all'){
			$search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);			
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
		
		$sql = "
			SELECT e.employee_code, e.lastname,	e.firstname, 	
				ejh.name AS position_name, 	
				esh.name AS department_name,		
				ea.date_attendance, CAST(SUM(ea.late_hours) AS DECIMAL(7,2)) AS total_late 
				
			FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh        
			WHERE (ea.employee_id = e.id) 
				AND (e.id = esh.employee_id AND esh.end_date = '')
				AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
				AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
				AND(ea.late_hours <> '')				
				" . $search . "
			GROUP BY e.employee_code
		";				
		$result = Model::runSql($sql);		
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['employee_code']     = $row['employee_code'];
			$return[$counter]['name']  			   = $row['lastname'] . ", " . $row['firstname'];
			$return[$counter]['department']		   = $row['department_name'];
			$return[$counter]['position']  		   = $row['position_name'];			
			$return[$counter]['total_late']		   = number_format($row['total_late'],2);		
			$counter++;
		}
		return $return;
	}
}
?>