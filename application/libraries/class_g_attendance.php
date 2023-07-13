<?php
class G_Attendance extends Attendance {
	protected $day_type_string; // Legal, Special, Restday, Regular
    protected $employee_id;


    const CEILING_HOURS_WORKED = 99;
    const MIN_WORKING_HRS_REQ  = 2;
    const END_NIGHT_SHIFT_TIME = "08:00:00";

	public function __construct() {
		
	}

    public function setEmployeeId($employee_id) {
        $this->employee_id = $employee_id;
    }

    public function getEmployeeId() {
        return $this->employee_id;
    }

    public function syncFpLogsToattendance($date = '') {
    	$return       = false;
    	$timesheet    = array();
    	$to_find_date = $date;
    	if( !empty($date) ){    		            
    		$logs  = G_Attendance_Log_Helper::sqlGetAllLogsNotTransferredByDate($date);
    		foreach( $logs as $log ){
    			$emp_code = $log['employee_code'];
    			$date     = date("Y-m-d",strtotime($log['date']));
    			$type     = strtolower($log['type']);
    			$time     = date("H:i:s",strtotime($log['time']));
    			$timesheet[$emp_code][$type][$date][$time] = $date;
    		}
    		
    		if( !empty($timesheet) ){
    			$tr = new G_Timesheet_Raw_Filter($timesheet);
       			$tr->filterAndUpdateAttendance();
       			       			
       			$return = true;
    		}
    	}

    	return $return;
    }

    public function syncFpLogsToattendanceWithDateRange($from = '', $to = '') {
        $return       = false;
        $timesheet    = array();       
        if( $from != '' && $to != '' && strtotime($from) <= strtotime($to) ){            
            $total_records_updated = G_Attendance_Log_Manager::resetLogsToNotTransferredByDateRange($from, $to);
            $total_deleted = G_Attendance_Manager::deleteAllAttendanceByDateRange($from, $to);
            $logs  = G_Attendance_Log_Helper::sqlGetAllLogsNotTransferredByDateRange($from, $to);   

            foreach( $logs as $log ){
                $emp_code = $log['employee_code'];
                $date     = date("Y-m-d",strtotime($log['date']));
                $type     = strtolower($log['type']);
                $time     = date("H:i:s",strtotime($log['time']));
                $timesheet[$emp_code][$type][$date][$time] = $date;
            }

            //transfer breaklog on fp attendance to breaklog
          // G_Attendance_Log_Helper::sqlTransferBreakInFpLogToBreakLog($from, $to);
            //G_Attendance_Log_Manager::setBreakLogsToTransferredByDateRange($from, $to);

            //break
            $total_break_records_updated = G_Employee_Break_Logs_Manager::resetLogsToNotTransferredByDateRange($from, $to);
            $total_break_deleted = G_Employee_Break_Logs_Summary_Manager::deleteAllAttendanceByDateRange($from, $to);
            $break_logs  = G_Employee_Break_Logs_Helper::sqlGetAllLogsNotTransferredByDateRange($from, $to);   

            foreach( $break_logs as $break_log ){
                $emp_code = $break_log['employee_code'];
                $date     = date("Y-m-d",strtotime($break_log['date']));
                $type     = strtolower($break_log['type']);
                $time     = date("H:i:s",strtotime($break_log['time']));
                $new_date = true;

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

                if (isset($timesheet[$emp_code]['breaks'][$type])) {
                    $current_break_date_time = date("Y-m-d H:i:s",strtotime($date . ' ' . $time));
                    $existing_break_date_time = date("Y-m-d H:i:s",strtotime($timesheet[$emp_code]['breaks'][$type]['date'] . ' ' . $timesheet[$emp_code]['breaks'][$type]['time']));

                    if (in_array($type, $break_log_out_types)) {
                        if ($current_break_date_time >= $existing_break_date_time) {
                            $new_date = false;
                        }
                    }
                    else if (in_array($type, $break_log_in_types)) {
                        if ($current_break_date_time <= $existing_break_date_time) {
                            $new_date = false;
                        }
                    }
                }

                if ($new_date) {
                    $timesheet[$emp_code]['breaks'][$date . ' ' . $time] = array(
                        'id' => $break_log['id'],
                        'date' => $date,
                        'time' => $time,
                        'type' => $type
                    );
                }
            }
            
            if( !empty($timesheet) ){
                $tr = new G_Timesheet_Raw_Filter($timesheet);
                $tr->filterAndUpdateAttendance();
                                
                $return = true;
            }
        }

        return $return;
    }
	
	public function getDayTypeString() {
		if ($this->is_restday) {
			return "Rest Day";	
		} else if ($this->is_holiday) {
			if ($this->holiday_type == Holiday::LEGAL) {
				return "Legal Holiday";
			} else if ($this->holiday_type == Holiday::SPECIAL) {
				return "Special Holiday";
			}
		} else {
			return "Regular";	
		}
	}
	
	/*
	Usage:
		Insert:
		$a = new G_Attendance;
		$a->setDate('2011-01-10');
		$a->setAsPaid();
		$a->setAsPresent();
		$a->setAsRestday();
		$a->setLeaveId(Leave::MATERNITY);
		$a->setAsSuspended();
		$h = G_Holiday_Finder::findById(9);
		$a->setHoliday($h);
		
		$t = new G_Timesheet;
		$t->setScheduledTimeIn('9:00:00');
		$t->setScheduledTimeOut('18:00:00');
		$t->setTimeIn('09:00:00');
		$t->setTimeOut('18:00:00');
		$t->setOverTimeIn('18:00:00');
		$t->setOverTimeOut('19:00:00');
		$t->setTotalHoursWorked(8);
		
		$t->setNightShiftHours(23);
		$t->setNightShiftHoursSpecial(23);
		$t->setNightShiftHoursLegal(23);
		$t->setHolidayHoursSpecial(23);
		$t->setHolidayHoursLegal(23);
		$t->setOvertimeHours(23);
		$t->setLateHours(23);
		$t->setUndertimeHours(23);		
		
		$a->setTimesheet($t);
		
		$e = G_Employee_Finder::findById(1);
		$a->recordToEmployee($e);
		
		Update:
		$e = G_Employee_Finder::findById(1);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, '2011-01-11');
		$a->setAsPresent();
		$t = $a->getTimesheet();
		$t->setTotalHoursWorked(20);
		$a->setTimesheet($t);
		$a->recordToEmployee($e);
	*/
	public function recordToEmployee(IEmployee $e) {
		return G_Attendance_Manager::recordToEmployee($e, $this);
	}

	public function recordToEmployeeV2(IEmployee $e, $v2_dtr, $logsId, $error_message, G_Employee_Schedule_Type $sched) {
		return G_Attendance_Manager_V2::recordToEmployee($e, $this, $v2_dtr, $logsId, $error_message, $sched);
	}
	/*
		Usage:
		$a = G_Attendance_Finder::findById(1);
		$a->delete();
	*/
	public function delete() {
		return G_Attendance_Manager::delete($this);	
	}
	
	public function getTardinessData($query, $add_query) {
		return G_Attendance_Helper::getTardinessData($query, $add_query);	
	}
	
	public function getTardinessWithBreakLogsData($query, $add_query) {
		return G_Attendance_Helper::getTardinessWithBreakLogsData($query, $add_query);	
	}
	
	public function countTardinessData($query, $add_query) {
		return G_Attendance_Helper::countTardinessData($query, $add_query);	
	}
	
	public function countTardinessWithBreakLogsData($query, $add_query) {
		return G_Attendance_Helper::countTardinessWithBreakLogsData($query, $add_query);	
	}
	
	public function getAttendanceAbsenceData($query, $add_query) {

	return G_Attendance_Helper::getAttendanceAbsenceData($query, $add_query);	
	}

    public function getAttendanceHalfdayData($query, $add_query) {
        return G_Attendance_Helper::getAttendanceHalfdayData($query, $add_query);     
    }  
     public function getAttendanceHalfdayDataDistinct($query, $add_query) {
        return G_Attendance_Helper::getAttendanceHalfdayDataDistinct($query, $add_query);     
    }     
	
	public function countAttendanceAbsenceData($query, $add_query) {
		return G_Attendance_Helper::countAttendanceAbsenceData($query, $add_query);	
	}

    /*
     * string $type 'in' or 'out'
     */
    private function changeTime($time, $type) {
        $time = date("H:i:s",strtotime($time));        
        $t = $this->getTimesheet();
        if ($t) {
            if ($type == 'in') {
                $t->setTimeIn($time);
            } else if ($type == 'out') {
                $t->setTimeOut($time);
            }
        }
        $this->setTimesheet($t);

        G_Attendance_Manager::recordToSingleEmployee($this);

        // UPDATE ATTENDANCE
        $employee_id = $this->getEmployeeId();
        $e = G_Employee_Finder::findById($employee_id);

        if ($e) {
            $a = G_Attendance_Helper::generateAttendance($e, $this->getDate());
            $as[] = $a;
            G_Attendance_Helper::updateAttendanceByMultipleAttendance($as);
        }
    }

    public function changeTimeIn($time) {
        //$eb = new G_Employee_Breaktime();
        //$is_e_breaktime = $eb->validateBreaktime($this);
        $this->changeTime($time, 'in');
    }

    public function changeTimeOut($time) {
        $this->changeTime($time, 'out');
    }

   public function groupTimesheetData() {
        $timesheet   = self::getTimesheet();                                
        $employee_id = $this->employee_id;        
        if( !empty($timesheet) ){           
            //Breaktime
            $total_hrs_deductible = 0;
            $fields   = array("DATE_FORMAT(break_in,'%r') AS `Break In`","DATE_FORMAT(break_out,'%r') AS `Break Out`");
            $obj_id   = $this->employee_id;
            $obj_type = G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE;

            $schedule['schedule_in']  = $timesheet->getScheduledTimeIn();
            $schedule['schedule_out'] = $timesheet->getScheduledTimeOut();
            $day_type = array();
           /* if( $this->$this->is_restday == 1 ){
                $day_type[] = "applied_to_restday";
            }else{
                $day_type[] = "applied_to_regular_day";
            }*/

            if( $this->is_holiday == 1 && !empty($this->holiday) ){
                $h = $this->holiday;
                if( $h->getType() == Holiday::LEGAL ){
                    $day_type[] = "applied_to_legal_holiday";
                }else{
                    $day_type[] = "applied_to_special_holiday";
                }
            }elseif( $this->is_restday == 1 ){

                if( $timesheet->getTotalScheduleHours() > 0 ){                   
                    $day_type[] = "applied_to_restday";
                }else{                    
                    $day_type[] = "applied_to_regular_day";
                }
            }else{
                $day_type[] = "applied_to_regular_day";
            }
            
            $e = new G_Employee();
            $e->setId($employee_id);
            $break_time_schedules = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);     
            /*if( $is_restday ){                
                echo "Date : {$date}<br />";
                Utilities::displayArray($break_time_schedules);
            }  */   
            
            if( !empty($break_time_schedules) ){
                //$total_hrs_deductible    = $e->getTotalBreakTimeHrsDeductible($schedule);
                //$total_hrs_deductible    = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);
                $group_data['breaktime'] = $break_time_schedules; 
                
                $group_data['breaktime_hrs'] = 0;
                
                foreach ($break_time_schedules as $key => $break_time_schedule) {
                    $explode_break_time_schedule = explode('to', $break_time_schedule);
                    
                    if (count($explode_break_time_schedule) >= 2) {
                        $break_in = date("Y-m-d H:i", strtotime($explode_break_time_schedule[0]));
                        $break_out = date("Y-m-d H:i", strtotime($explode_break_time_schedule[1]));

                        if ($break_out < $break_in) {
                            $break_out = date('Y-m-d H:i', strtotime($break_out. ' + 1 days'));
                        }

                        $break_in = strtotime($break_in);
                        $break_out = strtotime($break_out);

                        $group_data['breaktime_hrs'] += (($break_out - $break_in)/60)/60;
                    }
                }

                $group_data['breaktime_hrs'] = round($group_data['breaktime_hrs'], 2);
            }

            $break = G_Employee_Breaktime_Finder::findByEmployeeIdAndDate($employee_id,$this->date);         

            if(!empty($break)) {
                $group_data['break'] = $break;
            }

            $total_hrs_deductible = $timesheet->getTotalDeductibleBreaktimeHours();

            //echo 'Deductable breaktime: ' . $total_hrs_deductible;

            $time_in  = $timesheet->getDateIn() . ' ' . $timesheet->getTimeIn();
            $time_out = $timesheet->getDateOut() . ' ' . $timesheet->getTimeOut();
            //echo "{$time_in} / {$time_out}";
            //$total_working_hrs = Tools::computeHoursDifferenceByDateTime($time_in, $time_out);
            $total_working_hrs = $timesheet->getTotalHoursWorked();

            if($timesheet->getTotalOvertimeHours() > $timesheet->getTotalHoursWorked()) {
                $total_working_hrs = $timesheet->getTotalScheduleHours() + $timesheet->getTotalOvertimeHours();
            }

            $schedule_in  = $timesheet->getScheduledDateIn() . ' ' . $timesheet->getScheduledTimeIn();
            $schedule_out = $timesheet->getScheduledDateOut() . ' ' . $timesheet->getScheduledTimeOut();
            $total_schedule_working_hrs = Tools::computeHoursDifferenceByDateTime($schedule_in, $schedule_out);

            //Holiday
            if($this->is_holiday){
                switch ($this->holiday_type) {
                    case 1:
                        $group_data['holiday']['Legal Holiday Date']                   = $this->date;  
                        $group_data['holiday']['Legal Holiday Total HRS']             = $total_working_hrs > 0  ? number_format($total_working_hrs,2) : 0;

                        $group_data['holiday']['Legal Holiday Night Differential HRS'] = $timesheet->getNightShiftHoursLegal() > 0 ? number_format($timesheet->getNightShiftHoursLegal(),2) : 0;                        
                        break;
                    case 2:
                        $group_data['holiday']['Special Holiday Date']                   = $timesheet->getDateIn();  
                        $group_data['holiday']['Special Holiday Total HRS']              = $total_working_hrs > 0 ? number_format($total_working_hrs,2) : 0;
                        $group_data['holiday']['Special Holiday Night Differential HRS'] = $timesheet->getNightShiftHoursSpecial() > 0 ? number_format($timesheet->getNightShiftHoursSpecial(),2) : 0.0;                
                        break;
                    default:
                        break;
                }
            }

            //Restday
            if($this->is_restday){  
                switch ($this->holiday_type) {
                    case 1:
                        $group_data['restday']['Legal Holiday Date']      = $timesheet->getDateIn();  
                        $group_data['restday']['Legal Holiday Total HRS'] = $total_working_hrs;
                        $group_data['restday']['Legal Holiday Night Differential HRS'] = number_format($timesheet->getNightShiftHoursLegal(),2);                        
                        break;
                    case 2:
                        $group_data['restday']['Special Holiday Date']     = $timesheet->getDateIn();  
                        $group_data['restday']['Special Holiday Total HRS'] = $total_working_hrs;
                        $group_data['restday']['Special Holiday Night Differential HRS'] = number_format($timesheet->getNightShiftHoursSpecial(),2);                
                        break;
                    default:
                        $group_data['restday']['Date']      = $timesheet->getDateIn();  
                        $group_data['restday']['Total HRS'] = $total_working_hrs;
                        $group_data['restday']['Night Differential HRS'] =  number_format($timesheet->getNightShiftHours(),2);
                }

            }

            //Schedule            
            if( $this->isRestday() ){                
                $group_data['schedule']['Date']     = $this->date . "(<b>Restday</b>)";   
                if(  $timesheet->getTotalScheduleHours() > 0 ){                    
                    $group_data['schedule']['Time In']  = date("h:i A",strtotime($timesheet->getScheduledTimeIn()));  
                    $group_data['schedule']['Time Out'] = date("h:i A",strtotime($timesheet->getScheduledTimeOut()));
                    //$group_data['schedule']['Total Required Working HRS'] = number_format($total_schedule_working_hrs,1);     
                    $group_data['schedule']['Total Required Working HRS'] = number_format($timesheet->getTotalScheduleHours(),2);     
                }else{
                    $group_data['schedule']['Time In']  = "No schedule";
                    $group_data['schedule']['Time Out'] = "No schedule";
                    $group_data['schedule']['Total Required Working HRS'] = 0.0;
                }                
            }else{
                $group_data['schedule']['Date']     = $timesheet->getScheduledDateIn();    
                $group_data['schedule']['Time In']  = date("h:i A",strtotime($timesheet->getScheduledTimeIn()));  
                $group_data['schedule']['Time Out'] = date("h:i A",strtotime($timesheet->getScheduledTimeOut()));
                //$group_data['schedule']['Total Required Working HRS'] = number_format($total_schedule_working_hrs,1);     
                $group_data['schedule']['Total Required Working HRS'] = number_format($timesheet->getTotalScheduleHours(),2);     
            }           
            
            
            //$group_data['schedule']['Total Required Working HRS'] = $timesheet->getTotalScheduleHours();
            //$group_data['schedule']['Total Required Working HRS'] = number_format($total_schedule_working_hrs - $total_hrs_deductible,1);
            //$group_data['schedule']['Total Required Working HRS (Less Break Time)'] = $timesheet->getTotalScheduleHours() - $total_hrs_deductible;            
            $group_data['schedule']['Total Required Working HRS'] = number_format($timesheet->getTotalScheduleHours(),2);

            //Attendance
            if( strtotime($timesheet->getTimeOut()) > 0 ){
                $time_out = date("h:i A",strtotime($timesheet->getTimeOut())); 
            }else{
                $time_out = "No time-in";
            }

            if( $total_working_hrs < $total_hrs_deductible ){
                $total_working_hrs_less_break = 0;
            }else{
                $total_working_hrs_less_break = $total_working_hrs - $total_hrs_deductible;
            }
            $group_data['total_hrs_deductible'] = $total_hrs_deductible;
            if( $this->isRestDay() && $this->is_present  ){
                $group_data['attendance']['Date']     = $timesheet->getDateIn();    
                $group_data['attendance']['Time In']  = date("h:i A",strtotime($time_in));
                $group_data['attendance']['Time Out'] = date("h:i A",strtotime($time_out));
                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = number_format($total_working_hrs_less_break,2);
                $group_data['attendance']['Total Night Shift Hours']            = number_format($timesheet->getNightShiftHours(),2);    
            }elseif( !$this->is_present && $timesheet->getTimeIn() != '' && $timesheet->getTimeOut() != ''){

                $group_data['attendance']['Date']     = $this->date . "(<b>Incorrect Shift</b>)";   
                $group_data['attendance']['Time In']  = date("h:i A",strtotime($time_in));
                $group_data['attendance']['Time Out'] = date("h:i A",strtotime($time_out));            

            }elseif( $this->isRestDay() && !$this->is_present ){
                $group_data['attendance']['Date']     = $this->date . "(<b>Restday</b>)";    
                if(  $timesheet->getTotalScheduleHours() > 0 ){            
                    $group_data['attendance']['Time In']  = "Absent";
                    $group_data['attendance']['Time Out'] = "Absent";
                }else{
                    $group_data['attendance']['Time In']  = "No Schedule";
                    $group_data['attendance']['Time Out'] = "No Schedule";
                }

                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = 0.0;
                $group_data['attendance']['Total Night Shift Hours'] = 0.0;    
            }elseif( !$this->is_present && $this->is_holiday ){
                $group_data['attendance']['Date']     = $this->date . "(<b>Holiday</b>)";
                $group_data['attendance']['Time In']  = "Absent";
                $group_data['attendance']['Time Out'] = "Absent";
                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = 0.0;
                $group_data['attendance']['Total Night Shift Hours'] = 0.0;
            }elseif( !$this->is_present && !$this->is_holiday && !$this->isRestDay() ){
                $group_data['attendance']['Date']     = $this->date;
                $group_data['attendance']['Time In']  = "Absent";
                $group_data['attendance']['Time Out'] = "Absent";
                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = 0.0;
                $group_data['attendance']['Total Night Shift Hours'] = 0.0;
            }elseif( $this->is_present && $this->is_ob ){
              
                $group_data['attendance']['Date']     = $this->date;
                   if($timesheet->getOBIn() != '' && $timesheet->getOBOut() != ''){
                    $group_data['attendance']['Time In']  = date("h:i A",strtotime($time_in));
                    $group_data['attendance']['Time Out'] = date("h:i A",strtotime($time_out));
                    $group_data['attendance']['OB Time In']  = date("h:i A",strtotime($timesheet->getOBIn()));
                    $group_data['attendance']['OB Time Out'] = date("h:i A",strtotime($timesheet->getOBOut()));
                    $group_data['attendance']['OB Total Hrs'] = $timesheet->getOBTotalHrs();
                }
                else{
                    $group_data['attendance']['Time In']  = "OB";
                    $group_data['attendance']['Time Out'] = "OB";
                }
               
                
                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = 0.0;
                $group_data['attendance']['Total Night Shift Hours'] = 0.0;
            }else{
                $group_data['attendance']['Date']     = $timesheet->getDateIn();    
                $group_data['attendance']['Time In']  = date("h:i A",strtotime($time_in));
                $group_data['attendance']['Time Out'] = date("h:i A",strtotime($time_out));
                //$group_data['attendance']['Total HRS Worked'] = $timesheet->getTotalHoursWorked();

                $group_data['attendance']['Total HRS Worked (Less Break Time)'] = number_format($total_working_hrs_less_break,2);
                $group_data['attendance']['Total Night Shift Hours'] = number_format($timesheet->getNightShiftHours(),2);
            }

            //Tardiness
            $date_from = $this->date;
            $date_to   = $this->date;
            $breaktime_late = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($employee_id, $date_from, $date_to);

            if( $timesheet->getLateHours() > 0.00 ){
                $group_data['tardiness']['Morning Late HRS']      = number_format($timesheet->getLateHours(),2);
            }              

            if($breaktime_late) {
                $group_data['tardiness']['Breaktime Late HRS']      = number_format($breaktime_late,2); //round($breaktime_late,2);  
            }

            if($breaktime_late) {
                if( $timesheet->getLateHours() > 0.00 ){
                    $group_data['tardiness']['Total Late HRS']      = number_format($timesheet->getLateHours() + $breaktime_late,2);
                } 
            } else {
                if( $timesheet->getLateHours() > 0.00 ){
                    $group_data['tardiness']['Total Late HRS']      = number_format($timesheet->getLateHours(),2);
                }                 
            }

            if( $timesheet->getUndertimeHours() > 0.00 ){
                $group_data['tardiness']['Total Undertime HRS'] = number_format($timesheet->getUndertimeHours(),2);
            }
            
            $time_in  = strtotime($timesheet->getTimeIn()); 
            $time_out = strtotime($timesheet->getTimeOut()); 
            if( ($time_in <= 0 || $time_out <= 0) && !$this->isRestday() && !$this->is_ob ){                
                $group_data['tardiness']['Total Absent Days'] += 1;
            }
            
            //Overtime
            if( $timesheet->getOvertimeHours() > 0.00 ){
                $group_data['overtime']['Regular Overtime HRS'] = number_format($timesheet->getOvertimeHours(),2);
                $total_overtime_hrs += $timesheet->getOvertimeHours();
            }

            $total_overtime_hrs = $timesheet->getTotalOvertimeHours();

            if( $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Overtime IN']        = date("h:i A",strtotime($timesheet->getOverTimeIn()));
                $group_data['overtime']['Overtime OUT']       = date("h:i A",strtotime($timesheet->getOverTimeOut()));                          
            }

            if( $timesheet->getRegularOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                //$group_data['overtime']['Regular Overtime HRS'] = number_format($timesheet->getRegularOvertimeHours(),2);
                $group_data['overtime']['Regular Overtime HRS'] = number_format($timesheet->getRegularOvertimeHours() + $timesheet->getRegularOvertimeExcessHours(),2);             
            }

            if( $timesheet->getRegularOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                //$group_data['overtime']['Regular Night Shift Overtime HRS'] = number_format($timesheet->getRegularOvertimeNightShiftHours(),2);  
                $group_data['overtime']['Regular Night Shift Overtime HRS'] = number_format($timesheet->getRegularOvertimeNightShiftHours() + $timesheet->getRegularOvertimeNightShiftExcessHours(),2);    
                            
            }

            if( $timesheet->getRestDayOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Overtime HRS'] = number_format($timesheet->getRestDayOvertimeHours(),2);               
            }

            if( $timesheet->getRestDayOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Overtime NS HRS'] = number_format($timesheet->getRestDayOvertimeNightShiftHours(),2);                
            }

            if( $timesheet->getRestDaySpecialOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Special Holiday Overtime HRS'] = number_format($timesheet->getRestDaySpecialOvertimeHours(),2);                
            }

            if( $timesheet->getRestDaySpecialOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Special Holiday Night Differential Overtime HRS'] = number_format($timesheet->getRestDaySpecialOvertimeNightShiftHours(),2);               
            }

            if( $timesheet->getRestDayLegalOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Legal Holiday Overtime HRS'] = number_format($timesheet->getRestDayLegalOvertimeHours(),2);                
            }

            if( $timesheet->getRestDayLegalOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Restday Legal Holiday Night Differential Overtime HRS'] = number_format($timesheet->getRestDayLegalOvertimeNightShiftHours(),2);                
            }

            if( $timesheet->getLegalOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Legal Holiday Overtime HRS'] = number_format($timesheet->getLegalOvertimeHours(),2);               
            }

            if( $timesheet->getLegalOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Legal Holiday Night Differential Overtime HRS'] = number_format($timesheet->getLegalOvertimeNightShiftHours(),2);          
            }

            if( $timesheet->getSpecialOvertimeHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Special Holiday Overtime HRS'] = number_format($timesheet->getSpecialOvertimeHours(),2);               
            }

            if( $timesheet->getSpecialOvertimeNightShiftHours() > 0.00 && $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Special Holiday Night Differential Overtime HRS'] = number_format($timesheet->getSpecialOvertimeNightShiftHours(),2);              
            }

            if( $total_overtime_hrs > 0.00 ){
                $group_data['overtime']['Total Overtime HRS'] = number_format($total_overtime_hrs,2);
            }
            //END OT
        }

        return $group_data;
    }

    public function countAttendancePerfectData($query, $add_query) {
        return G_Attendance_Helper::countAttendancePerfectData($query, $add_query); 
    } 

    public function getActualHoursData($query, $add_query) {
        return G_Attendance_Helper::getActualHoursData($query, $add_query); 
    }  
}
?>