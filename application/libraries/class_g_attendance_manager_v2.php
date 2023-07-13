<?php
class G_Attendance_Manager_V2 {
    //Original from Daiichi
    public static function recordToMultipleEmployees($attendances) {
        $insert_sql_value = '';
        
        foreach ($attendances as $key => $a) {

            if ($a) {
                $h = $a->getHoliday();
                if ($h) {
                    $a->setAsHoliday();
                    $holiday_id = $h->getId();
                    $holiday_title = $h->getTitle();
                    $holiday_type = $h->getType();
                } else {
                    $a->setAsNotHoliday();
                    $holiday_id = '';
                    $holiday_title = '';
                    $holiday_type = '';
                }

                $t = $a->getTimesheet();

                
                if ($t) {
                    $actual_time_in = $t->getTimeIn();
                    $actual_time_out = $t->getTimeOut();
                    $actual_date_in = $t->getDateIn();
                    $actual_date_out = $t->getDateOut();
                    $total_hours_worked = $t->getTotalHoursWorked();    
                    $total_schedule_hours = $t->getTotalScheduleHours();
                    $undertime_hours = $t->getUndertimeHours();
                    $project_site_id = $a->getProjectSiteId();

                      //new ob timebase
                    $ob_in = $t->getOBIn();
                    $ob_out = $t->getOBOut();
                    $ob_total_hrs = $t->getOBTotalHrs();

                    $eb = new G_Employee_Breaktime();
                    $is_e_breaktime = $eb->validateBreaktime($a);
                }
                
                if($is_e_breaktime['update_attendance']) {
                    $insert_sql_values[] = 
                        "(". Model::safeSql($a->getId()) .",
                        ". Model::safeSql($a->getEmployeeId()) .",
                        ". Model::safeSql($a->getDate()) .",
                        ". Model::safeSql($actual_time_in) .",
                        ". Model::safeSql($actual_time_out) .",
                        ". Model::safeSql($a->isLeave()) .",
                        ". Model::safeSql($a->isRestday()) .",
                        ". Model::safeSql($total_hours_worked) .",
                        ". Model::safeSql($undertime_hours) .",
                        ". Model::safeSql($project_site_id) .")";
                } else {}
                
            }
        }
        
        $insert_sql_value = implode(',', $insert_sql_values);
        

        $sql_insert = "
            INSERT INTO ". G_EMPLOYEE_ATTENDANCE_V2 ." (
                id,
                employee_id,
                date,
                in,
                out,
                is_leave,
                is_rest_day,
                total_hours_worked,
                undertime_hours,
                project_site_id,
            )
            VALUES ". $insert_sql_value ."
        ";

        /*$sql_insert_orig = "
            INSERT INTO ". G_EMPLOYEE_ATTENDANCE_V2 ." (
                id,
                employee_id,
                date,
                schedule_template_id,
                schedule_in,
                schedule_out,
                schedule_break_out,
                schedule_break_in,
                schedule_snack_out,
                schedule_snack_in,
                schedule_ot_out,
                schedule_ot_in,
                is_auto_sched,
                early_ot_in,
                early_ot_out,
                early_break_in,
                early_break_out,
                in,
                out,
                snack_in,
                snack_out,
                break_in,
                break_out,
                ot_in,
                ot_out,
                ot_break_in,
                ot_break_out,
                is_leave,
                is_leave_with_pay,
                is_ob,
                is_half_day,
                is_absent,
                is_rest_day,
                undertime_hours,
                late_hours,
                work_day_type,
                calendar_holiday,
                calendar_holiday_id,
                project_site_id,
                has_error,
                error_message,
                has_approval
            )
            VALUES ". $insert_sql_value ."
            ON DUPLICATE KEY UPDATE
                employee_id = VALUES(employee_id),
                date = VALUES(date),
                schedule_template_id = VALUES(schedule_template_id),
                schedule_in = VALUES(schedule_in),
                schedule_out = VALUES(schedule_out),
                schedule_break_out = VALUES(schedule_break_out),
                schedule_break_in = VALUES(schedule_break_in),
                schedule_snack_out = VALUES(schedule_snack_out),
                schedule_snack_in = VALUES(schedule_snack_in),
                schedule_ot_out = VALUES(schedule_ot_out),
                schedule_ot_in = VALUES(schedule_ot_in),
                is_auto_sched = VALUES(is_auto_sched),
                early_ot_in = VALUES(early_ot_in),
                early_ot_out = VALUES(early_ot_out),
                early_break_in = VALUES(early_break_in),
                early_break_out = VALUES(early_break_out),
                in = VALUES(in),
                out = VALUES(out),
                snack_in = VALUES(snack_in),
                snack_out = VALUES(snack_out),
                break_in = VALUES(break_in),
                break_out = VALUES(break_out),
                ot_in = VALUES(ot_in),
                ot_out = VALUES(ot_out),
                ot_break_in = VALUES(ot_break_in),
                ot_break_out = VALUES(ot_break_out),
                is_leave = VALUES(is_leave),
                is_leave_with_pay = VALUES(is_leave_with_pay),
                is_ob = VALUES(is_ob),
                is_half_day = VALUES(is_half_day),
                is_absent = VALUES(is_absent),
                is_rest_day = VALUES(is_rest_day),
                undertime_hours = VALUES(undertime_hours),
                late_hours = VALUES(late_hours),
                work_day_type = VALUES(work_day_type),
                calendar_holiday = VALUES(calendar_holiday),
                calendar_holiday_id = VALUES(calendar_holiday_id),
                project_site_id = VALUES(project_site_id),
                has_error = VALUES(has_error),
                error_message = VALUES(error_message),
                has_approval = VALUES(has_approval)
        ";*/

        if ($insert_sql_value) {
            Model::runSql($sql_insert);
            // self::generateAutoFileOvertime($attendances);
        }

        if (mysql_errno() > 0) {
            return false;
        } else {
            return true;
        }
    }

    private static function generateAutoFileOvertime($attendance){
        // var_dump($attendance); exit;
        foreach ($attendance as $key => $a) {

            if ($a) {
               

                $t = $a->getTimesheet();
                $has_auto_overtime = false;
                // var_dump($a);
                // var_dump($t); exit;
                if ($t) {
                    $data['date_in']  = $t->getDateIn();
                    $data['date_out'] = $t->getDateOut();
                    $data['time_in']  = $t->getTimeIn();
                    $data['time_out'] = $t->getTimeOut();
        
                    $data['scheduled_date_in'] = $t->getScheduledDateIn();
                    $data['schedule_date_out'] = $t->getScheduledDateOut();
                    $data['schedule_time_in']  = $t->getScheduledTimeIn();
                    $data['schedule_time_out'] = $t->getScheduledTimeOut();

                    if(isset($data['time_out']) && !empty($data['time_out'])){
                        $auto_file_ot_date_in = $data['schedule_date_out'];
                        $auto_file_ot_time_in = $data['schedule_time_out'];
                        $auto_file_ot_date_out = $data['date_out'];
                        $auto_file_ot_time_out = $data['time_out'];
                        $auto_file_ot_date = $data['date_in'];

                        $allowed_hr_start = MINIMUM_OVERTIME_MINS; // cofing_client
                        $auto_file_ot_minimum = $auto_file_ot_time_in + $allowed_hr_start ;


                        if($auto_file_ot_time_out >= $auto_file_ot_minimum)
                        {
                            if(($auto_file_ot_date_out.' '.$auto_file_ot_time_out) < ($auto_file_ot_date_in.' '.$auto_file_ot_time_in) )
                            {
                                $auto_file_ot_date_out = date('Y-m-d', strtotime('+1 day', $auto_file_ot_date_out));
                            }

                            if(($auto_file_ot_date_out.' '.$auto_file_ot_time_out) > ($auto_file_ot_date_in.' '.$auto_file_ot_time_in) )
                            {   
                                $employee = G_Employee_Finder::findById($a->getEmployeeId());
                                if($employee)
                                {
                                    $overtime = G_Overtime_Finder::findByEmployeeAndDate($employee, $auto_file_ot_time_in);
                                    $is_exist = G_Overtime_Helper::isOvertimeExist($a->getEmployeeId(), $auto_file_ot_date);
                                    if((int)$is_exist == 0) 
                                    {
                                    
                                        $overtime = new G_Overtime();
                                        $override_status = $overtime->getStatus() == G_Overtime::STATUS_PENDING ? $overtime->getStatus() : G_Overtime::STATUS_PENDING;
                                        $overtime->setDate($auto_file_ot_date_in);
                                        $overtime->setTimeIn($auto_file_ot_time_in);
                                        $overtime->setTimeOut($auto_file_ot_time_out);
                                        $overtime->setDateIn($auto_file_ot_date_in);
                                        $overtime->setDateOut($auto_file_ot_date_out);
                                        $overtime->setEmployeeId($a->getEmployeeId());
                                        $overtime->setReason('System Generated');   
                                        $data = $overtime->autoOvertime($data, $break_schedules);

                                        $has_auto_overtime = $data['has_auto_overtime'];
                                        if( $has_auto_overtime )
                                        {
                                            $auto_ot_hours        = $data['ot_hours'];
                                            $auto_ot_excess_hours = $data['ot_excess_hours'];
                                            $auto_ot_nd           = $data['ot_nd'];
                                            $auto_ot_excess_nd    = $data['ot_excess_nd'];
                            
                                            $ot_hours        = $auto_ot_hours;
                                            $ot_excess_hours = $auto_ot_excess_hours;
                                            $ot_nd           = $auto_ot_nd;
                                            $ot_excess_nd    = $auto_ot_excess_nd;
                                        }
                                        
                                
                                        // //$overtime->setStatus($_POST['status']);
                                        $overtime->setStatus($override_status);
                                        $overtime->setDateCreated(date("Y-m-d H:i:s"));
                                        $request_id = $overtime->save();
                                            // var_dump($overtime); exit;
                                        $approvers    = $_POST['approvers'];
                                        $approvers    = '';
                                        $requestor_id = $a->getEmployeeId();
                                        $request_type = G_Request::PREFIX_OVERTIME;
                        
                                        $r = new G_Request();
                                        $r->setRequestorEmployeeId($requestor_id);
                                        $r->setRequestId($request_id);
                                        $r->setRequestType($request_type);
                                        $r->saveEmployeeRequest($approvers); //Save request approvers
                                    
                                        // G_Attendance_Helper::updateAttendance($employee, $auto_file_ot_date_in);
                                        // var_dump($auto_file_ot_time_in - $auto_file_ot_time_out); exit;

                                    }
                                }
                            }
                        }
                    } 
                }
            }
        }
        return true;
    }
    //From Artnature
    public static function recordToMultipleEmployeesA($attendances) {
        $insert_sql_value = '';
        foreach ($attendances as $key => $a) {
            
            if ($a) {
                $h = $a->getHoliday();
                if ($h) {
                    $a->setAsHoliday();
                    $holiday_id = $h->getId();
                    $holiday_title = $h->getTitle();
                    $holiday_type = $h->getType();
                } else {
                    $a->setAsNotHoliday();
                    $holiday_id = '';
                    $holiday_title = '';
                    $holiday_type = '';
                }

                $t = $a->getTimesheet();
                if ($t) {
                    $actual_time_in = $t->getTimeIn();
                    $actual_time_out = $t->getTimeOut();
                    $actual_date_in = $t->getDateIn();
                    $actual_date_out = $t->getDateOut();
                    $total_hours_worked = $t->getTotalHoursWorked();
                    $scheduled_time_in = $t->getScheduledTimeIn();
                    $scheduled_time_out = $t->getScheduledTimeOut();
                    $night_shift_hours = $t->getNightShiftHours();
                    $night_shift_overtime_hours = $t->getNightShiftOvertimeHours();
                    $night_shift_overtime_excess_hours = $t->getNightShiftOvertimeExcessHours();
                    $night_shift_hours_special = $t->getNightShiftHoursSpecial();
                    $night_shift_hours_legal = $t->getNightShiftHoursLegal();
                    $holiday_hours_special = $t->getHolidayHoursSpecial();
                    $holiday_hours_legal = $t->getHolidayHoursLegal();
                    $overtime_hours = $t->getOvertimeHours();
                    $overtime_excess_hours = $t->getOvertimeExcessHours();
                    $restday_overtime_hours = $t->getRestDayOvertimeHours();
                    $restday_overtime_excess_hours = $t->getRestDayOvertimeExcessHours();
                    $restday_overtime_nightshift_hours = $t->getRestDayOvertimeNightShiftHours();
                    $restday_overtime_nightshift_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
                    $regular_overtime_hours = $t->getRegularOvertimeHours();
                    $regular_overtime_excess_hours = $t->getRegularOvertimeExcessHours();
                    $regular_overtime_nightshift_hours = $t->getRegularOvertimeNightShiftHours();
                    $regular_overtime_nightshift_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();

                    $restday_legal_overtime_hours = $t->getRestDayLegalOvertimeHours();
                    $restday_legal_overtime_excess_hours = $t->getRestDayLegalOvertimeExcessHours();
                    $restday_legal_overtime_ns_hours = $t->getRestDayLegalOvertimeNightShiftHours();
                    $restday_legal_overtime_ns_excess_hours = $t->getRestDayLegalOvertimeNightShiftExcessHours();
                    $restday_special_overtime_hours = $t->getRestDaySpecialOvertimeHours();
                    $restday_special_overtime_excess_hours = $t->getRestDaySpecialOvertimeExcessHours();
                    $restday_special_overtime_ns_hours = $t->getRestDaySpecialOvertimeNightShiftHours();
                    $restday_special_overtime_ns_excess_hours = $t->getRestDaySpecialOvertimeNightShiftExcessHours();
                    $legal_overtime_hours = $t->getLegalOvertimeHours();
                    $legal_overtime_excess_hours = $t->getLegalOvertimeExcessHours();
                    $legal_overtime_ns_hours = $t->getLegalOvertimeNightShiftHours();
                    $legal_overtime_ns_excess_hours = $t->getLegalOvertimeNightShiftExcessHours();
                    $special_overtime_hours = $t->getSpecialOvertimeHours();
                    $special_overtime_excess_hours = $t->getSpecialOvertimeExcessHours();
                    $special_overtime_ns_hours = $t->getSpecialOvertimeNightShiftHours();
                    $special_overtime_ns_excess_hours = $t->getSpecialOvertimeNightShiftExcessHours();

                    $late_hours = $t->getLateHours();
                    $undertime_hours = $t->getUndertimeHours();
                    $overtime_time_in = $t->getOverTimeIn();
                    $overtime_time_out = $t->getOverTimeOut();
                    $early_over_time_in = $t->getEarlyOverTimeIn();
                    $early_over_time_out = $t->getEarlyOverTimeOut();
                    $overtime_date_in = $t->getOvertimeDateIn();
                    $overtime_date_out = $t->getOvertimeDateOut();
                    $scheduled_date_in = $t->getScheduledDateIn();
                    $scheduled_date_out = $t->getScheduledDateOut();
                    $total_schedule_hours = $t->getTotalScheduleHours();
                    $total_overtime_hours = $t->getTotalOvertimeHours();
                    $total_breaktime_deductible_hours = $t->getTotalDeductibleBreaktimeHours();

                }

                $insert_sql_values[] = "(". Model::safeSql($a->getId()) .",
                    ". Model::safeSql($a->getEmployeeId()) .",
                    ". Model::safeSql($a->getDate()) .",
                    ". Model::safeSql($a->isPresent()) .",
                    ". Model::safeSql($a->isPaid()) .",
                    ". Model::safeSql($a->isRestday()) .",
                    ". Model::safeSql($a->isHoliday()) .",
                    ". Model::safeSql($a->isOfficialBusiness()) .",
                    ". Model::safeSql($a->isLeave()) .",
                    ". Model::safeSql($a->getLeaveId()) .",
                    ". Model::safeSql($a->isSuspended()) .",
                    ". Model::safeSql($holiday_id) .",
                    ". Model::safeSql($holiday_title) .",
                    ". Model::safeSql($holiday_type) .",
                    ". Model::safeSql($actual_time_in) .",
                    ". Model::safeSql($actual_time_out) .",
                    ". Model::safeSql($actual_date_in) .",
                    ". Model::safeSql($actual_date_out) .",
                    ". Model::safeSql($total_hours_worked) .",
                    ". Model::safeSql($scheduled_time_in) .",
                    ". Model::safeSql($scheduled_time_out) .",
                    ". Model::safeSql($night_shift_hours) .",
                    ". Model::safeSql($night_shift_overtime_hours) .",
                    ". Model::safeSql($night_shift_overtime_excess_hours) .",
                    ". Model::safeSql($night_shift_hours_special) .",
                    ". Model::safeSql($night_shift_hours_legal) .",
                    ". Model::safeSql($holiday_hours_special) .",
                    ". Model::safeSql($holiday_hours_legal) .",
                    ". Model::safeSql($overtime_hours) .",
                    ". Model::safeSql($overtime_excess_hours) .",
                    ". Model::safeSql($restday_overtime_hours) .",
                    ". Model::safeSql($restday_overtime_excess_hours) .",
                    ". Model::safeSql($restday_overtime_nightshift_hours) .",
                    ". Model::safeSql($restday_overtime_nightshift_excess_hours) .",
                    ". Model::safeSql($regular_overtime_hours) .",
                    ". Model::safeSql($regular_overtime_excess_hours) .",
                    ". Model::safeSql($regular_overtime_nightshift_hours) .",
                    ". Model::safeSql($regular_overtime_nightshift_excess_hours) .",
                    ". Model::safeSql($restday_legal_overtime_hours) .",
                    ". Model::safeSql($restday_legal_overtime_excess_hours) .",
                    ". Model::safeSql($restday_legal_overtime_ns_hours) .",
                    ". Model::safeSql($restday_legal_overtime_ns_excess_hours) .",
                    ". Model::safeSql($restday_special_overtime_hours) .",
                    ". Model::safeSql($restday_special_overtime_excess_hours) .",
                    ". Model::safeSql($restday_special_overtime_ns_hours) .",
                    ". Model::safeSql($restday_special_overtime_ns_excess_hours) .",
                    ". Model::safeSql($legal_overtime_hours) .",
                    ". Model::safeSql($legal_overtime_excess_hours) .",
                    ". Model::safeSql($legal_overtime_ns_hours) .",
                    ". Model::safeSql($legal_overtime_ns_excess_hours) .",
                    ". Model::safeSql($special_overtime_hours) .",
                    ". Model::safeSql($special_overtime_excess_hours) .",
                    ". Model::safeSql($special_overtime_ns_hours) .",
                    ". Model::safeSql($special_overtime_ns_excess_hours) .",
                    ". Model::safeSql($late_hours) .",
                    ". Model::safeSql($undertime_hours) .",
                    ". Model::safeSql($overtime_time_in) .",
                    ". Model::safeSql($overtime_time_out) .",
                    ". Model::safeSql($early_over_time_in) .",
                    ". Model::safeSql($early_over_time_out) .",
                    ". Model::safeSql($overtime_date_in) .",
                    ". Model::safeSql($overtime_date_out) .",
                    ". Model::safeSql($scheduled_date_in) .",
                    ". Model::safeSql($scheduled_date_out) .",
                    ". Model::safeSql($total_schedule_hours) .",
                    ". Model::safeSql($total_overtime_hours) .",
                    ". Model::safeSql($total_breaktime_deductible_hours) .")";
            }
        }
        $insert_sql_value = implode(',', $insert_sql_values);
        $sql_insert = "
            INSERT INTO ". G_EMPLOYEE_ATTENDANCE ." (
                id,
                employee_id,
                date_attendance,
                is_present,
                is_paid,
                is_restday,
                is_holiday,
                is_ob,
                is_leave,
                leave_id,
                is_suspended,
                holiday_id,
                holiday_title,
                holiday_type,
                actual_time_in,
                actual_time_out,
                actual_date_in,
                actual_date_out,
                total_hours_worked,
                scheduled_time_in,
                scheduled_time_out,
                night_shift_hours,
                night_shift_overtime_hours,
                night_shift_overtime_excess_hours,
                night_shift_hours_special,
                night_shift_hours_legal,
                holiday_hours_special,
                holiday_hours_legal,
                overtime_hours,
                overtime_excess_hours,
                restday_overtime_hours,
                restday_overtime_excess_hours,
                restday_overtime_nightshift_hours,
                restday_overtime_nightshift_excess_hours,
                regular_overtime_hours,
                regular_overtime_excess_hours,
                regular_overtime_nightshift_hours,
                regular_overtime_nightshift_excess_hours,
                restday_legal_overtime_hours,
                restday_legal_overtime_excess_hours,
                restday_legal_overtime_ns_hours,
                restday_legal_overtime_ns_excess_hours,
                restday_special_overtime_hours,
                restday_special_overtime_excess_hours,
                restday_special_overtime_ns_hours,
                restday_special_overtime_ns_excess_hours,
                legal_overtime_hours,
                legal_overtime_excess_hours,
                legal_overtime_ns_hours,
                legal_overtime_ns_excess_hours,
                special_overtime_hours,
                special_overtime_excess_hours,
                special_overtime_ns_hours,
                special_overtime_ns_excess_hours,
                late_hours,
                undertime_hours,
                overtime_time_in,
                overtime_time_out,
                early_overtime_in,
                early_overtime_out,
                overtime_date_in,
                overtime_date_out,
                scheduled_date_in,
                scheduled_date_out,
                total_schedule_hours,
                total_overtime_hours,
                total_breaktime_deductible_hours
            )
            VALUES ". $insert_sql_value ."
            ON DUPLICATE KEY UPDATE
                date_attendance = VALUES(date_attendance),
                is_present      = VALUES(is_present),
                is_paid         = VALUES(is_paid),
                is_restday      = VALUES(is_restday),
                is_holiday      = VALUES(is_holiday),
                is_ob           = VALUES(is_ob),
                is_leave        = VALUES(is_leave),
                leave_id        = VALUES(leave_id),
                is_suspended    = VALUES(is_suspended),
                holiday_id      = VALUES(holiday_id),
                holiday_title   = VALUES(holiday_title),
                holiday_type    = VALUES(holiday_type),
                actual_time_in  = VALUES(actual_time_in),
                actual_time_out = VALUES(actual_time_out),
                actual_date_in  = VALUES(actual_date_in),
                actual_date_out = VALUES(actual_date_out),
                total_hours_worked  = VALUES(total_hours_worked),
                scheduled_time_in   = VALUES(scheduled_time_in),
                scheduled_time_out  = VALUES(scheduled_time_out),
                night_shift_hours   = VALUES(night_shift_hours),
                night_shift_overtime_hours  = VALUES(night_shift_overtime_hours),
                night_shift_overtime_excess_hours   = VALUES(night_shift_overtime_excess_hours),
                night_shift_hours_special   = VALUES(night_shift_hours_special),
                night_shift_hours_legal = VALUES(night_shift_hours_legal),
                holiday_hours_special   = VALUES(holiday_hours_special),
                holiday_hours_legal     = VALUES(holiday_hours_legal),
                overtime_hours  = VALUES(overtime_hours),
                overtime_excess_hours   = VALUES(overtime_excess_hours),
                restday_overtime_hours  = VALUES(restday_overtime_hours),
                restday_overtime_excess_hours   = VALUES(restday_overtime_excess_hours),
                restday_overtime_nightshift_hours   = VALUES(restday_overtime_nightshift_hours),
                restday_overtime_nightshift_excess_hours    = VALUES(restday_overtime_nightshift_excess_hours),
                regular_overtime_hours  = VALUES(regular_overtime_hours),
                regular_overtime_excess_hours   = VALUES(regular_overtime_excess_hours),
                regular_overtime_nightshift_hours   = VALUES(regular_overtime_nightshift_hours),
                regular_overtime_nightshift_excess_hours    = VALUES(regular_overtime_nightshift_excess_hours),
                restday_legal_overtime_hours = VALUES(restday_legal_overtime_hours),
                restday_legal_overtime_excess_hours = VALUES(restday_legal_overtime_excess_hours),
                restday_legal_overtime_ns_hours = VALUES(restday_legal_overtime_ns_hours),
                restday_legal_overtime_ns_excess_hours = VALUES(restday_legal_overtime_ns_excess_hours),
                restday_special_overtime_hours = VALUES(restday_special_overtime_hours),
                restday_special_overtime_excess_hours = VALUES(restday_special_overtime_excess_hours),
                restday_special_overtime_ns_hours = VALUES(restday_special_overtime_ns_hours),
                restday_special_overtime_ns_excess_hours = VALUES(restday_special_overtime_ns_excess_hours),
                legal_overtime_hours = VALUES(legal_overtime_hours),
                legal_overtime_excess_hours = VALUES(legal_overtime_excess_hours),
                legal_overtime_ns_hours = VALUES(legal_overtime_ns_hours),
                legal_overtime_ns_excess_hours = VALUES(legal_overtime_ns_excess_hours),
                special_overtime_hours = VALUES(special_overtime_hours),
                special_overtime_excess_hours = VALUES(special_overtime_excess_hours),
                special_overtime_ns_hours = VALUES(special_overtime_ns_hours),
                special_overtime_ns_excess_hours = VALUES(special_overtime_ns_excess_hours),
                late_hours  = VALUES(late_hours),
                undertime_hours = VALUES(undertime_hours),
                overtime_time_in = VALUES(overtime_time_in),
                overtime_time_out = VALUES(overtime_time_out),
                early_overtime_in = VALUES(early_overtime_in),
                early_overtime_out = VALUES(early_overtime_out),
                overtime_date_in = VALUES(overtime_date_in),
                overtime_date_out = VALUES(overtime_date_out),
                scheduled_date_in = VALUES(scheduled_date_in),
                scheduled_date_out = VALUES(scheduled_date_out),
                total_schedule_hours = VALUES(total_schedule_hours),
                total_overtime_hours = VALUES(total_overtime_hours),
                total_breaktime_deductible_hours = VALUES(total_breaktime_deductible_hours)
        ";

        if ($insert_sql_value) {
            Model::runSql($sql_insert);
        }
        if (mysql_errno() > 0) {
            return false;
        } else {
            return true;
        }
    }    

    public static function recordToSingleEmployee(G_Attendance $a) {
        $as[] = $a;
        return self::recordToMultipleEmployees($as);
    }

    /*
     * DEPRECATED - use G_Attendance_Manager::recordToSingleEmployee()
     */
	public static function recordToEmployee(IEmployee $e, G_Attendance $a, $v2_dtr, $logsId, $error_message, G_Employee_Schedule_Type $sched) {
		$h = $a->getHoliday();
		if ($h) {
			$a->setAsHoliday();
			$holiday_id = $h->getId();
			$holiday_title = $h->getTitle();
			$holiday_type = $h->getType();	
		} else {
			$a->setAsNotHoliday();
			$holiday_id = '';
			$holiday_title = '';
			$holiday_type = '';	
		}
		$t = $a->getTimesheet();
		if ($t) {
			$actual_time_in = $t->getTimeIn();
			$actual_time_out = $t->getTimeOut();
			$actual_date_in = $t->getDateIn();
			$actual_date_out = $t->getDateOut();			
			$total_hours_worked = $t->getTotalHoursWorked();
			$scheduled_time_in = $t->getScheduledTimeIn();
			$scheduled_time_out = $t->getScheduledTimeOut();
			$night_shift_hours = $t->getNightShiftHours();
			$night_shift_overtime_hours = $t->getNightShiftOvertimeHours();
			$night_shift_overtime_excess_hours = $t->getNightShiftOvertimeExcessHours();
			$night_shift_hours_special = $t->getNightShiftHoursSpecial();
			$night_shift_hours_legal = $t->getNightShiftHoursLegal();
			$holiday_hours_special = $t->getHolidayHoursSpecial();
			$holiday_hours_legal = $t->getHolidayHoursLegal();
			$overtime_hours = $t->getOvertimeHours();
			$overtime_excess_hours = $t->getOvertimeExcessHours();
			$restday_overtime_hours = $t->getRestDayOvertimeHours();
			$restday_overtime_excess_hours = $t->getRestDayOvertimeExcessHours();
			$restday_overtime_nightshift_hours = $t->getRestDayOvertimeNightShiftHours();
			$restday_overtime_nightshift_excess_hours = $t->getRestDayOvertimeNightShiftExcessHours();
			$regular_overtime_hours = $t->getRegularOvertimeHours();
			$regular_overtime_excess_hours = $t->getRegularOvertimeExcessHours();
			$regular_overtime_nightshift_hours = $t->getRegularOvertimeNightShiftHours();
			$regular_overtime_nightshift_excess_hours = $t->getRegularOvertimeNightShiftExcessHours();
			$late_hours = $t->getLateHours();
			$undertime_hours = $t->getUndertimeHours();
			$overtime_time_in = $t->getOverTimeIn();
			$overtime_time_out = $t->getOverTimeOut();
			$early_over_time_in = $t->getEarlyOverTimeIn();
			$early_over_time_out = $t->getEarlyOverTimeOut();
            $overtime_date_in = $t->getOvertimeDateIn();
            $overtime_date_out = $t->getOvertimeDateOut();
            $scheduled_date_in = $t->getScheduledDateIn();
            $scheduled_date_out = $t->getScheduledDateOut();
            $total_schedule_hours = $t->getTotalScheduleHours();
            $total_overtime_hours = $t->getTotalOvertimeHours();
		}
        if($error_message){
            $has_error = 1;
        }else{
            $has_error = 0;
        }
		if ($logsId) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_ATTENDANCE_V2;
			$sql_end   = " WHERE id = ". Model::safeSql($logsId);	
            $sql = $sql_start ."
                SET
                    actual_time_out	= ". Model::safeSql($v2_dtr->getTime()) .",
                    has_error	= ". Model::safeSql($has_error) .",
                    error_message	= ". Model::safeSql($error_message) ."
                    ". $sql_end ."
                ";	
		} else {
            if($v2_dtr->getType() == 'out'){
                $actual_time_out = $actual_time_in;
                $actual_time_in = "";
            }
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_ATTENDANCE_V2;
			$sql_end   = ", employee_id = ". Model::safeSql($e->getId());
            $sql = $sql_start ."
                SET
                    date_attendance         = ". Model::safeSql($a->getDate()) .",
                    employee_schedule_id    = ". Model::safeSql($sched->id) .",
                    schedule_type           = ". Model::safeSql($sched->schedule_type) .",
                    is_present     	= ". Model::safeSql($a->isPresent()) .",
                    is_paid  		= ". Model::safeSql($a->isPaid()) .",
                    is_restday		= ". Model::safeSql($a->isRestday()) .",
                    is_holiday		= ". Model::safeSql($a->isHoliday()) .",
                    is_leave		= ". Model::safeSql($a->isLeave()) .",
                    leave_id		= ". Model::safeSql($a->getLeaveId()) .",
                    is_suspended	= ". Model::safeSql($a->isSuspended()) .",
                    holiday_id		= ". Model::safeSql($holiday_id) .",
                    holiday_title	= ". Model::safeSql($holiday_title) .",
                    holiday_type	= ". Model::safeSql($holiday_type) .",
                    actual_time_in	= ". Model::safeSql($actual_time_in) .",
                    actual_time_out	= ". Model::safeSql($actual_time_out) .",			
                    total_hours_worked	= ". Model::safeSql($total_hours_worked) .",
                    scheduled_time_in	= ". Model::safeSql($scheduled_time_in) .",
                    scheduled_time_out	= ". Model::safeSql($scheduled_time_out) .",
                    late_hours	= ". Model::safeSql($late_hours) .",
                    undertime_hours	= ". Model::safeSql($undertime_hours) .",
                    project_site_id     = ". Model::safeSql($v2_dtr->getProjectSiteId()) .",
                    activity_id     = ". Model::safeSql($v2_dtr->getActivityName()) .",
                    has_error	= ". Model::safeSql($has_error) .",
                    error_message	= ". Model::safeSql($error_message) ."
                    ". $sql_end ."
                ";
		}
		

        /*$sql = $sql_start ."
            SET
            date = ". Model::safeSql($a->getDate()) .",
            schedule_template_id    = '',
            schedule_in             = '',
            schedule_out            = '',
            schedule_break_out      = '',
            schedule_break_in       = '',
            schedule_snack_out      = '',
            schedule_snack_in       = '',
            schedule_ot_out     = '',
            schedule_ot_in      = '',
            is_auto_sched       = '',
            early_ot_in         = '',
            early_ot_out        = '',
            early_break_in      = '',
            early_break_out     = '',
            in                  = ". Model::safeSql($actual_time_in) .",
            out                 = ". Model::safeSql($actual_time_out) .",
            snack_in            = '',
            snack_out           = '',
            break_in            = '',
            break_out           = '',
            ot_in               = '',
            ot_out              = '',
            ot_break_in         = '',
            ot_break_out        = '',
            is_leave            = '',
            is_leave_with_pay   = '',
            is_ob               = '',
            is_half_day         = '',
            is_absent           = '',
            is_rest_day         = ". Model::safeSql($a->isRestday()) .",
            total_hours_worked  = ". Model::safeSql($total_hours_worked) .", 
            undertime_hours	    = ". Model::safeSql($undertime_hours) .",
            late_hours          = ". Model::safeSql($late_hours) .",
            work_day_type       = '',
            calendar_holiday    = '',
            calendar_holiday_id = '',
            project_site_id     = ". Model::safeSql($a->getProjectSiteId()) .",
            has_error           = '',
            error_message       = '',
            has_approval        = ''
            ". $sql_end ."
		";*/
        
		Model::runSql($sql);
        
		if (mysql_errno() > 0) {
			return false;
		}
        
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}
	}
	
	public static function delete(G_Attendance $a) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE id = ". Model::safeSql($a->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false;
	}

    public static function deleteAllAttendanceByDateRange($date_from = '', $date_to = '') {        
        $date_from = date("Y-m-d",strtotime($date_from));
        $date_to   = date("Y-m-d",strtotime($date_to));
        if( strtotime($date_from) <= strtotime($date_to) ){
            $sql = "
                DELETE FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
                WHERE date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
            ";
            Model::runSql($sql);
            return true;
        }else{
            return false;
        }
    }   
}
?>