<?php
class G_Attendance_Helper_V2
{
    //original generateAttendance   

    public static function generateAttendance(IEmployee $e, $date, $using_dtr_error = false)
    {

        $salary = $e->getSalary($date); // For rate
        list($year, $month, $day) = explode('-', $date);

        $a = G_Attendance_Finder_V2::findByEmployeeAndDate($e, $date);
        if (!$a) {
            $a = new G_Attendance;
            $a->setDate($date);
        }

        if ($a) {
            $t = $a->getTimesheet();
            if (!$t) {
                $t = new G_Timesheet;
            }
        } elseif (!$a) {
            $t = new G_Timesheet;
        }

        if (!$using_dtr_error) {
            // OFFICIAL BUSINESS
            $ob = $e->getOfficialBusinessRequest($date);
            $a->setAsNotOfficialBusiness();
            $a->setAsAbsent();
            //$a->setAsNotPaid();
            if ($ob) {
                if ($ob->isApproved()) {
                    $a->setAsOfficialBusiness();
                    $a->setAsPresent();
                    //$a->setAsPaid();



                    if($ob->getWholeDay() == G_Employee_Official_Business_Request::NO){
                        $ob_time_start = $ob->getTimeStart();
                        $ob_time_end = $ob->getTimeEnd();

                         $ob_date_in = $date.' '.$ob_time_start;
                         $ob_date_out = $date.' '.$ob_time_end;

                         $ob_total_hrs  = Tools::newComputeHoursDifferenceByDateTime($ob_date_in, $ob_date_out);
                         $t->setOBIn($ob_time_start);
                         $t->setOBOut($ob_time_end);
                         $t->setOBTotalHrs($ob_total_hrs);


                        // if no actual time in and out
                        if (!strtotime($t->getTimeIn()) && !strtotime($t->getTimeOut())) {

                              $t->setDateOut($date);

                                //double check if theres actual in
                                $in = G_Attendance_Log_Finder::FindEmployeeInByDate($e, $date);
                                $out = G_Attendance_Log_Finder::FindEmployeeOutByDate($e, $date);

                                if($in){
                                    $fp_in = $in->getTime();

                                        if(strtotime($fp_in) <= strtotime($ob_time_start)){
                                             $t->setTimeIn($fp_in);
                                        }
                                        else{
                                             $t->setTimeIn($ob_time_start);
                                        }

                                }
                                else{
                                    $t->setTimeIn($ob_time_start);
                                }


                                if($out){
                                    $fp_out = $out->getTime();

                                       if(strtotime($fp_out) >= strtotime($ob_time_end)){
                                             $t->setTimeIn($fp_out);
                                        }
                                        else{
                                             $t->setTimeIn($ob_time_end);
                                        }

                                }
                                else{
                                    $t->setTimeOut($ob_time_end);
                                }
                           
                        }//end if
                       
                    } //end if not whole day

                }
            }
        }

        $has_actual_schedule = false;
        $has_actual_time = false;
        if (strtotime($t->getTimeIn()) && strtotime($t->getTimeOut())) {
            $has_actual_schedule = true;
            $has_actual_time = true;
            $actual_time_in = $t->getTimeIn();
            $actual_time_out = $t->getTimeOut();

            $test_actual_date_time_out = $t->getDateOut().' '.$actual_time_out;




              //for ob time based   
             if($t->getOBIn() != '' && strtotime($t->getOBIn()) <= strtotime($t->getTimeIn())){
                    $actual_time_in = $t->getOBIn();
             } 

             //if($t->getOBOut() != '' && strtotime($t->getOBOut()) >= strtotime($t->getTimeOut())){

             if($t->getOBOut() != '' && strtotime($ob_date_out) >= strtotime($test_actual_date_time_out)){
                  $actual_time_out = $t->getOBOut();
             }

        }


        /*
        if(!empty($actual_time_out)){
            //remove seconds in actual timeout
            $adjust_timeout = explode(':', $actual_time_out);
            $adjust_timeout[2] = "00";
            $new_timeout = implode(":", $adjust_timeout);
            $actual_time_out = date("H:i:s",strtotime($new_timeout));

            $t->setTimeOut($actual_time_out);
         }*/

      

        if ($has_actual_schedule) {
            $t->setDateIn($date);
           // $t->setDateOut($date);
            $a->setAsPresent();
            //$a->setAsPaid();

            $actual_date_time_in = $date . ' ' . $actual_time_in;
            $actual_date_time_out = $date . ' ' . $actual_time_out;

            if (Tools::isTimeAfternoon($actual_time_in) && Tools::isTimeMorning($actual_time_out)) {
                $t->setDateIn($date);
                $tomorrow_date = Tools::getTomorrowDate("{$date} {$actual_time_out}");
                $t->setDateOut($tomorrow_date);

                $actual_date_time_in = $date . ' ' . $actual_time_in;
                $actual_date_time_out = $tomorrow_date . ' ' . $actual_time_out;
            }
             
             /*elseif (Tools::isTimeMorning($actual_time_in) && Tools::isTimeMorning($actual_time_out)) {
                $actual_date_time_in = $date . ' ' . $actual_time_in;
                $actual_date_time_out = $date . ' ' . $actual_time_out;
            } */

            elseif (Tools::isNightShift($actual_time_out)) {
                $t->setDateIn($date);
                $tomorrow_date = Tools::getTomorrowDate("{$date} {$actual_time_out}");
                $t->setDateOut($tomorrow_date);

                $actual_date_time_in = $date . ' ' . $actual_time_in;
                $actual_date_time_out = $tomorrow_date . ' ' . $actual_time_out;
            }
            else{

                 $actual_date_time_in = $date . ' ' . $actual_time_in;
                 $actual_out = $t->getDateOut();
                 $actual_date_time_out = $actual_out . ' ' . $actual_time_out;


            }


            if(strtotime($actual_date_time_in)>= strtotime($actual_date_time_out)){
                $actual_date_time_out = new DateTime($actual_date_time_out);
                $actual_date_time_out->modify('+1 day');
                $actual_date_out = $actual_date_time_out->format('Y-m-d');
                $t->setDateOut($actual_date_out);
                $actual_date_time_out = $actual_date_time_out->format('Y-m-d H:i:s');
            }

            $total_hours_worked       = Tools::newComputeHoursDifferenceByDateTime($actual_date_time_in, $actual_date_time_out);
            $t->setTotalHoursWorked($total_hours_worked);

            //echo "Date time in / out : {$actual_date_time_in} - {$actual_date_time_out}/Total Hrs Worked : {$total_hours_worked} <br />";
        } else {

            $total_hours_worked = 0;
            $t->setTotalHoursWorked(0);
            $t->setDateIn('');
            $t->setDateOut('');
        }

        if (!$using_dtr_error) {
            // HOLIDAY
            $has_holiday = false;
            $h = G_Holiday_Finder::findByMonthDayYear($month, $day, $year);
            if ($h) {
                // echo "<pre>";
                //  var_dump($date);
                //     echo "<pre>";
                $holidays_list = G_Holiday_Finder::findByMonthDayYear($month, $day, $year);
                if ($holidays_list) {
                    $has_holiday = true;
                    $a->setAsHoliday();
                    //$a->setAsPaid();
                    $a->setHoliday($h);
                    $a->setHolidayType($h->getType());
                } else {
                    $a->setAsNotHoliday();
                    //$a->setAsNotPaid();
                    $a->setHoliday('');
                    $a->setHolidayType('');
                }
            } else {

                $a->setAsNotHoliday();
                //$a->setAsNotPaid();
                $a->setHoliday('');
                $a->setHolidayType('');
            }
        }
        // SCHEDULE
        $is_restday          = false;
        $has_schedule        = false;
        $is_default_schedule = false;
        $schedule_id         = 0;

        $ss = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);

        $rd = G_Restday_Finder::findByEmployeeAndDate($e, $date);

        if ($ss) {

            $schedule_id = $ss->getId();
            $has_schedule = true;
            $scheduled_time_in = date("H:i:s", strtotime($ss->getTimeIn()));
            $scheduled_time_out = date("H:i:s", strtotime($ss->getTimeOut()));

            $day_num = date('d', strtotime($date));
            $sched_spec_sched_time_in_custom  = '';
            $sched_spec_sched_time_out_custom = '';
            if ($ss->getTimeIn() == "19:30:00" && $ss->getTimeOut() == "05:00:00" && $day_num == 10) {
                $sched_spec_sched_time_in_custom  = "19:30:00";
                //$sched_spec_sched_time_out_custom = "04:59:59";
                $sched_spec_sched_time_out_custom = "5:00:00";
                $scheduled_time_in         = date("H:i:s", strtotime($sched_spec_sched_time_in_custom));
                $scheduled_time_out        = date("H:i:s", strtotime($sched_spec_sched_time_out_custom));
            }

            $t->setScheduledTimeIn($scheduled_time_in);
            $t->setScheduledTimeOut($scheduled_time_out);

            if ($rd) {
                $is_restday = true;
                $a->setAsRestday();
            } else {
                $is_restday = false;
                $a->setAsNotRestday();
            }
        } else {

            $s = G_Schedule_Finder::findByEmployeeAndDate($e, $date);

            if (!$s) {
                $active_schedule = G_Schedule_Finder::findActiveByEmployee($e, $date);

                if ($active_schedule) {
                    $is_restday = true;
                    $a->setAsRestday();
                    $t->setScheduledTimeIn('');
                    $t->setScheduledTimeOut('');
                } else {
                    $groups = G_Group_Finder::findAllByEmployee($e);
                    $sg = G_Schedule_Finder::findByGroupsAndDate($groups, $date);
                    if (!$sg) {

                        $active_group = G_Schedule_Finder::findActiveByGroups($groups);
                        $default_schedule = G_Schedule_Finder::findDefaultByDate($date);
                        if ($active_group) {

                            $is_restday = true;
                            $a->setAsRestday();
                            //$a->setAsPaid();
                            $t->setScheduledTimeIn('');
                            $t->setScheduledTimeOut('');
                        } else if ($default_schedule) {
                            $has_schedule = true;
                            $is_default_schedule = true; //trigger for using default restday
                            $schedule_id = $default_schedule->getId();

                            $is_restday = false;
                            $a->setAsNotRestday();
                            //$a->setAsPaid();                            
                            $scheduled_time_in  = date("H:i:s", strtotime($default_schedule->getTimeIn()));
                            $scheduled_time_out = date("H:i:s", strtotime($default_schedule->getTimeOut()));
                            $t->setScheduledTimeIn($scheduled_time_in);
                            $t->setScheduledTimeOut($scheduled_time_out);
                        } else {
                            $is_restday = true;
                            $a->setAsRestday();
                            //$a->setAsPaid();
                            $t->setScheduledTimeIn('');
                            $t->setScheduledTimeOut('');
                        }
                    } else {

                        $schedule_id = $sg->getId();
                        $has_schedule = true;
                        $is_restday = false;
                        $a->setAsNotRestday();
                        //$a->setAsPaid();
                        $scheduled_time_in  = date("H:i:s", strtotime($sg->getTimeIn()));
                        $scheduled_time_out = date("H:i:s", strtotime($sg->getTimeOut()));
                        $t->setScheduledTimeIn($scheduled_time_in);
                        $t->setScheduledTimeOut($scheduled_time_out);
                    }
                }
            } else {

                $gsched = G_Schedule_Group_Finder::findById($s->getScheduleGroupId());
                $has_valid_sched = true;

                if ($gsched) {
                    if ($gsched->getEndDate() == '1970-01-01' || $gsched->getEndDate() == "") {
                    } else {
                        if (strtotime($gsched->getEffectivityDate()) <= strtotime($date) && strtotime($gsched->getEndDate()) >= strtotime($date)) {
                            //Covered schedule
                        } else {
                            $has_valid_sched = false;
                        }
                    }
                }
                //echo $has_valid_sched;
                if ($has_valid_sched) {
                    $schedule_id = $s->getId();
                    $has_schedule = true;
                    $is_restday = false;
                    $a->setAsNotRestday();
                    //$a->setAsPaid();
                    $scheduled_time_in  = date("H:i:s", strtotime($s->getTimeIn()));
                    $scheduled_time_out = date("H:i:s", strtotime($s->getTimeOut()));
                    $t->setScheduledTimeIn($scheduled_time_in);
                    $t->setScheduledTimeOut($scheduled_time_out);
                }
            }
        }

        //If empty schedule load default schedule
        if ($scheduled_time_in == "" || $scheduled_time_out == "") {
            $default_schedule = G_Schedule_Finder::findDefaultByDate($date);
            if ($default_schedule) {
                $schedule_id = $default_schedule->getId();
                $has_schedule        = true;
                $is_default_schedule = true; //trigger for using default restday
                $is_restday = false;
                $a->setAsNotRestday();
                //$a->setAsPaid();
                $scheduled_time_in  = date("H:i:s", strtotime($default_schedule->getTimeIn()));
                $scheduled_time_out = date("H:i:s", strtotime($default_schedule->getTimeOut()));;
                $t->setScheduledTimeIn($scheduled_time_in);
                $t->setScheduledTimeOut($scheduled_time_out);
            }
        }

        // REST DAY
        // If restday (from weekly schedule), check again maybe this week has manually inputed restday on that week.
        // if so, this is not rest day anymore
        if ($a->isRestday() && !$rd) {
            $week_dates    = self::findWeekStartDateAndEndDate($date);
            $total_restday = G_Restday_Helper::countRestdayByEmployeeAndDates($e, $week_dates['start_date'], $week_dates['end_date']);
            if ($total_restday >= REST_DAY_PER_WEEK) {
                $is_restday = false;
                $a->setAsNotRestday();
                $a->setAsPaid();
                $t->setScheduledTimeIn($scheduled_time_in);
                $t->setScheduledTimeOut($scheduled_time_out);
            } else {
                $is_restday = true;
                $a->setAsRestday();
                $a->setAsPaid();
            }
        }

        if ($is_default_schedule) {

            $default_group_id   = G_Company_Structure::PARENT_ID;
            $is_default_restday = G_Group_Restday_Helper::sqlIsDateAndGroupIdExists($date, $default_group_id);

            if ($is_default_restday) {

                if ($is_restday == false) {
                    $is_restday = false;
                    $a->setAsNotRestday();
                } else {
                    $is_restday = true;
                    $a->setAsRestday();
                }
            }
        }

        if ($rd && !$ss) {
            $is_restday = true;
            $a->setAsRestday();
            $a->setAsPaid();
            if (!$s) {
                $t->setScheduledTimeIn('');
                $t->setScheduledTimeOut('');
            } else {
                $schedule_id = $s->getId();
                $has_schedule = true;
                if ($is_default_schedule) {
                    $t->setScheduledTimeIn('');
                    $t->setScheduledTimeOut('');
                }
            }
        } else if ($rd && $ss) {
            $schedule_id = $ss->getId();
            $has_schedule = true;
            $is_restday   = true;
            $a->setAsRestday();
            $a->setAsPaid();
            $scheduled_time_in  = date("H:i:s", strtotime($ss->getTimeIn()));
            $scheduled_time_out = date("H:i:s", strtotime($ss->getTimeOut()));
            $t->setScheduledTimeIn($scheduled_time_in);
            $t->setScheduledTimeOut($scheduled_time_out);
        }

        $deductible_breaktime = 0;
        $actual_deductible_breaktime = 0;
        if ($has_schedule) {
            $schedule_date_time_in = $date . ' ' . $scheduled_time_in;
            $schedule_date_time_out = $date . ' ' . $scheduled_time_out;

            if (Tools::isTimeAfternoon($scheduled_time_in) && Tools::isTimeMorning($scheduled_time_out)) {
                $schedule_date_time_in = $date . ' ' . $scheduled_time_in;
                $tomorrow_date = Tools::getTomorrowDate("{$date} {$scheduled_time_out}");

                $schedule_date_time_out = $tomorrow_date . ' ' . $scheduled_time_out;
                //$schedule_date_time_out = $tomorrow_date .' 5:00:00';
                // echo $schedule_date_time_out . "<br>";
                $total_schedule_hours = Tools::newComputeHoursDifferenceByDateTime($schedule_date_time_out, $schedule_date_time_in);
                // echo $schedule_date_time_out . " - " . $schedule_date_time_in . "<br>";

                // echo $total_schedule_hours;

                // echo $total_schedule_hours;

            } elseif (Tools::isTimeMorning($scheduled_time_out)) {
                $schedule_date_time_in = $date . ' ' . $scheduled_time_in;
                $tomorrow_date = Tools::getTomorrowDate("{$date} {$scheduled_time_out}");
                $schedule_date_time_out = $tomorrow_date . ' ' . $scheduled_time_out;
                $total_schedule_hours = Tools::newComputeHoursDifferenceByDateTime($schedule_date_time_out, $schedule_date_time_in);
            } else {
                $total_schedule_hours = Tools::computeHoursDifferenceByDateTime($schedule_date_time_in, $schedule_date_time_out);
            }
            //echo "SchedIn : {$schedule_date_time_in} / SchedOut : {$schedule_date_time_out} / Total Sched Hrs : {$total_schedule_hours}";

            //Subtract deductible breaktime
            $day_type = array();
            /*if( $is_restday ){
                $day_type[] = "applied_to_restday";
            }else{
                $day_type[] = "applied_to_regular_day";
            }*/

            if ($a->isHoliday() && !empty($h)) {
                //$h = $a->holiday;
                if ($h->getType() == Holiday::LEGAL) {
                    $day_type[] = "applied_to_legal_holiday";
                } else {
                    $day_type[] = "applied_to_special_holiday";
                }
            } elseif ($is_restday) {
                if ($has_schedule) {
                    $day_type[] = "applied_to_restday";
                } else {
                    $day_type[] = "applied_to_regular_day";
                }
            } else {
                $day_type[] = "applied_to_regular_day";
            }

            $schedule['schedule_in']  = $schedule_date_time_in;
            $schedule['schedule_out'] = $schedule_date_time_out;
            $schedule['actual_in']    = $actual_time_in;
            $schedule['actual_out']   = $actual_time_out;

            //update employee break logs summary
            $attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());

            if ($attendance_breaks) {
                $break_time_data = $e->getBreakTimeData($schedule, $day_type, $attendance_breaks);
                $attendance_breaks = $break_time_data['attendance_breaks'];

                $attendance_breaks->setScheduleId($schedule_id);
                $attendance_breaks->setTotalBreakHrs($break_time_data['total_actual_break_hrs']);
                $attendance_breaks->setHasEarlyBreakOut($break_time_data['has_early_break_out']);
                $attendance_breaks->setTotalEarlyBreakOutHrs($break_time_data['total_early_break_out_hrs']);
                $attendance_breaks->setHasLateBreakIn($break_time_data['has_late_break_in']);
                $attendance_breaks->setTotalLateBreakInHrs($break_time_data['total_late_break_in_hrs']);
                $attendance_breaks->save();

                if ($attendance_breaks->getHasIncompleteBreakLogs()) {
                    $a->setAsAbsent();
                }

                 $actual_deductible_breaktime = $break_time_data['total_actual_break_hrs'];
                 $deductible_breaktime = $break_time_data['total_hrs_deductible'];
            }
            else{
               
                 $break_time_data = $e->getBreakTimeData($schedule, $day_type, $attendance_breaks);

                 $actual_deductible_breaktime = $break_time_data['total_hrs_deductible'];
                 $deductible_breaktime = $break_time_data['total_hrs_deductible'];
            }
           

            //echo $total_schedule_hours . " - " . $deductible_breaktime;
            //exit();
            // if( $total_schedule_hours >= $deductible_breaktime ){
            //     $total_schedule_hours = $total_schedule_hours - $deductible_breaktime;
            // }

            $t->setScheduledDateIn(date('Y-m-d', strtotime($schedule_date_time_in)));
            $t->setScheduledDateOut(date('Y-m-d', strtotime($schedule_date_time_out)));
            //echo "<hr>";


            $t->setTotalScheduleHours($total_schedule_hours);
            $t->setTotalDeductibleBreaktimeHours($deductible_breaktime);

            if (($t->getTimeIn() != '' && $t->getTimeOut() != '') && (strtotime($t->getTimeIn()) < strtotime($t->getScheduledTimeIn()))) {
                $actual_date_time_in_b = $date . ' ' . $t->getScheduledTimeIn();
                $total_hours_worked = Tools::newComputeHoursDifferenceByDateTime($actual_date_time_in_b, $actual_date_time_out);

            }
        } else {
            $t->setScheduledDateIn('');
            $t->setScheduledDateOut('');
            $t->setTotalScheduleHours(0);
            $t->setTotalDeductibleBreaktimeHours(0);
        }

        /*// BREAK TIME
        if (($has_schedule || $has_actual_schedule) && $t->getTotalScheduleHours() >= 9) {
            if (Tools::isTimeNightShift($scheduled_time_in)) {
                $break_time_in = '00:00:00';
                $break_time_out = '01:00:00';

                $break_date_time_in = Tools::getTomorrowDate("{$date}") .' '. $break_time_in;
                $break_date_time_out = Tools::getTomorrowDate("{$date}") .' '. $break_time_out;
            } else {
                $break_time_in = '12:00:00';
                $break_time_out = '13:00:00';

                $break_date_time_in = $date .' '. $break_time_in;
                $break_date_time_out = $date .' '. $break_time_out;
            }

            $break_time = Tools::computeHoursDifferenceByDateTime($break_date_time_in, $break_date_time_out); // break time hour
        } else {
            $break_time_in = '';
            $break_time_out = '';
            $break_date_time_in = '';
            $break_date_time_out = '';
            $break_time = 0;
        }*/

        // TOTAL HOURS WORKED`
        //$total_hours_worked = G_Attendance_Helper::computeTotalHoursWorked($t->getDateIn(), $t->getDateOut(), $t->getTimeIn(), $t->getTimeOut(), $t->getScheduledDateIn(), $t->getScheduledDateOut(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
        if ($total_hours_worked > 0) {
            $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($e->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());

            //Subtract deductible breaktime
            $schedule['schedule_in']  = $schedule_date_time_in;
            $schedule['schedule_out'] = $schedule_date_time_out;
            // if( $total_hours_worked >= $deductible_breaktime ){
            //     if($total_hours_worked >= 8.75) {
            //         $total_hours_worked = $total_hours_worked - $deductible_breaktime;
            //     }
            // }else{
            //     $total_hours_worked = 0;
            // }

            // if (Tools::isTimeMorning($actual_time_in) && Tools::isTimeBetweenHours($actual_time_out, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out']) ){
            //     if( $total_hours_worked >= 4.50 && $total_hours_worked <= 5.50) {
            //         $total_hours_worked = 4.50;
            //     }
            // }

            if ($total_hours_worked >= $actual_deductible_breaktime) {
                $total_hours_worked = $total_hours_worked - $actual_deductible_breaktime;
            } else {
                $total_hours_worked = 0;
            }

            if (Tools::isTimeMorning($actual_time_in) && Tools::isTimeBetweenHours($actual_time_out, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])) {
                if ($total_hours_worked >= 4.50 && $total_hours_worked <= 5.50) {
                    $total_hours_worked = 4.50;
                }
            }


            //**************
            //ob time based
            //adjustment for total worked hrs if has ob in and out
            if( ($t->getOBIn() != '' && $t->getOBOut() != '') && ($t->getOBIn() != $t->getTimeIn() && $t->getOBOut() != $t->getTimeOut())){

                 if(Tools::isTimeMorning($t->getOBIn()) && Tools::isTimeMorning($t->getOBOut())){
                         $total_hours_worked = $total_hours_worked - $t->getOBTotalHrs();

                         //check difference actual timein and ob out
                          $ob_date_time_in = $date .' '. $t->getOBOut();
                          $ob_date_time_out = $date .' '. $t->getTimeIn();

                          $ob_diff = Tools::computeHoursDifferenceByDateTime($ob_date_time_in, $ob_date_time_out);

                          if(Tools::isTimeBetweenHours($t->getOBOut(), $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])){ 
                           $initial_undertime_hrs = $ob_diff - $actual_deductible_breaktime;
                          }
                          else{
                             $initial_undertime_hrs = $ob_diff;
                          }

                          //if yung actual timein is less than ng ob timeout
                          if( strtotime($t->getTimeIn())  <=  strtotime($t->getOBOut())  ){
                                $initial_undertime_hrs = 0;
                          }


                 }
                 elseif(Tools::isTimeMorning($t->getOBIn()) && Tools::isTimeAfternoon($t->getOBOut())){
                    //check if ob_out is between breaktime schedule
                    if(Tools::isTimeBetweenHours($t->getOBOut(), $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])){
                          //recalculate ob_total_hrs by changing ob_out to Breaktime out
                          $ob_date_time_in = $date .' '. $t->getOBIn();
                          $ob_date_time_out = $date .' '. $breaktime_data[0]['break_out'];
                          $ob_diff = Tools::computeHoursDifferenceByDateTime($ob_date_time_in, $ob_date_time_out);

                          $new_ob_total_hrs = $ob_diff - $actual_deductible_breaktime;
                          $t->setOBTotalHrs($new_ob_total_hrs);
                          $total_hours_worked = $total_hours_worked;

                          //check if may distance yung obout and actual_in
                          if( strtotime($t->getOBOut()) <= strtotime($t->getTimeIn())){
                                 $ob_date_time_in = $date .' '. $breaktime_data[0]['break_out'];
                                 $ob_date_time_out = $date .' '. $t->getTimeIn();
                                 $ob_diff = Tools::computeHoursDifferenceByDateTime($ob_date_time_in, $ob_date_time_out);
                                 $total_hours_worked = $total_hours_worked - $ob_diff;

                                 //for undertime
                                  $initial_undertime_hrs = $ob_diff;

                          }

                    }

                 }
                 elseif(Tools::isTimeAfternoon($t->getOBIn()) && Tools::isTimeAfternoon($t->getOBOut())){

                       //if timeout not reached breaktime hrs
                       if(strtotime($t->getTimeOut()) <=  strtotime($breaktime_data[0]['break_in'])){
                            $diff_date_time_in = $date .' '. $t->getTimeOut();
                            $diff_date_time_out = $date .' '. $t->getOBIn();
                            $ob_diff = Tools::computeHoursDifferenceByDateTime($diff_date_time_in, $diff_date_time_out) - $actual_deductible_breaktime;
                            $total_hours_worked = $total_hours_worked - $ob_diff;
                            //for undertime
                            $initial_undertime_hrs = $ob_diff;
                       }
                       //between breaktime hrs
                       else if(Tools::isTimeBetweenHours($t->getTimeOut(), $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])){
                            $diff_date_time_in = $date .' '.  $breaktime_data[0]['break_out'];
                            $diff_date_time_out = $date .' '. $t->getOBIn();
                            $ob_diff = Tools::computeHoursDifferenceByDateTime($diff_date_time_in, $diff_date_time_out);
                            $total_hours_worked = $total_hours_worked - $ob_diff;
                            //for undertime
                            $initial_undertime_hrs = $ob_diff;
                       }
                       else{
                            
                            if(strtotime($t->getTimeOut()) <=  strtotime($t->getOBIn())){
                                $diff_date_time_in = $date .' '. $t->getTimeOut();
                                $diff_date_time_out = $date .' '. $t->getOBIn();
                                $ob_diff = Tools::computeHoursDifferenceByDateTime($diff_date_time_in, $diff_date_time_out);
                                $total_hours_worked = $total_hours_worked - $ob_diff;
                                //for undertime
                                $initial_undertime_hrs = $ob_diff;
                            }
                           

                       }                       

                 }

                 
            }//end if obin and obout 
            //*************************


            //Tools::isTimeBetweenHours($actual_time_out, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])
            $t->setTotalHoursWorked($total_hours_worked);
            //$t->setTotalHoursWorked($total_hours_worked - $break_time);            
            //echo "Date time in / out : {$actual_date_time_in} - {$actual_date_time_out}Total Hrs Worked 02 : {$total_hours_worked} <br />";
        }
        // if($is_restday && $is_default_schedule){

        //                         $has_schedule = false;
        //                     }else{

        //                          $has_schedule = true;
        //                     }
        //                     echo $has_schedule;
        if (!$using_dtr_error) {
            //Autofile OT   
            if (!empty($a)) {

                if ($is_restday && $is_default_schedule) {
                    $t->setOverTimeIn('');
                    $t->setOverTimeOut('');
                    $t->setOvertimeDateIn('');
                    $t->setOvertimeDateOut('');
                    $t->setTotalOvertimeHours(0);
                } else {
                    $ot  = new G_Overtime();
                    $result = $ot->autoFileRequest($e, $a);
                    $a_ot_details = $result['ot_details'];

                    if (!empty($a_ot_details)) {
                        $t->setOverTimeIn($a_ot_details['overtime_in']);
                        $t->setOverTimeOut($a_ot_details['overtime_out']);
                        $t->setOvertimeDateIn($a_ot_details['overtime_date_in']);
                        $t->setOvertimeDateOut($a_ot_details['overtime_date_out']);
                        $t->setTotalOvertimeHours($a_ot_details['total_ot_hrs']);

                        $t->setRegularOvertimeHours($a_ot_details['total_ot_hrs']);
                        $t->setRegularOvertimeExcessHours($a_ot_details['ot_excess_hours']);
                        $t->setRegularOvertimeNightShiftHours($a_ot_details['ot_nd']);
                        $t->setRegularOvertimeNightShiftExcessHours($a_ot_details['ot_excess_nd']);
                    }
                }
            }
        }
        //If minimum required working hours it will be considered as absent
        if ($total_hours_worked < G_Attendance::MIN_WORKING_HRS_REQ && $has_schedule && $has_actual_schedule && !$a->isHoliday() && !$a->isRestday()) {
            $a->setAsAbsent();
        }


        //check if schedule is nighshift and timein is nightshift
        if(Tools::isTimeNightShift($t->getScheduledTimeIn())){

            //check if actual time in is afternoon and is nightshift
            if(!Tools::isTimeAfternoon($t->getTimeIn()) && !Tools::isTimeNightShift($t->getTimeIn()) ){
                $a->setAsAbsent();
            }

        }


        if (!$using_dtr_error) {
            // OVERTIME
            $has_overtime = false;
            $o = G_Overtime_Finder::findByEmployeeAndDateAndStatus($e, $date, G_Overtime::STATUS_APPROVED);
            if ($o) {

                $has_overtime = true;
                $overtime_in = $o->getTimeIn();
                $overtime_out = $o->getTimeOut();
                $ot_date_in = $o->getDateIn();
                $ot_date_out = $o->getDateOut();

                $t->setOverTimeIn($overtime_in);
                $t->setOverTimeOut($overtime_out);

                $overtime_date_time_in = $date . ' ' . $overtime_in;
                $overtime_date_time_out = $ot_date_out . ' ' . $overtime_out;
                if (Tools::isTimeAfternoon($overtime_in) && Tools::isTimeMorning($overtime_out)) {
                    $overtime_date_time_in = $date . ' ' . $overtime_in;
                    $tomorrow_date = Tools::getTomorrowDate("{$date} {$overtime_out}");
                    $overtime_date_time_out = $tomorrow_date . ' ' . $overtime_out;
                }
                $total_overtime_hours = Tools::computeHoursDifferenceByDateTime($overtime_date_time_in, $overtime_date_time_out);
                $t->setOvertimeDateIn(date('Y-m-d', strtotime($overtime_date_time_in)));
                $t->setOvertimeDateOut(date('Y-m-d', strtotime($overtime_date_time_out)));
                $t->setTotalOvertimeHours($total_overtime_hours);
            } else {
                $t->setOverTimeIn('');
                $t->setOverTimeOut('');
                $t->setOvertimeDateIn('');
                $t->setOvertimeDateOut('');
                $t->setTotalOvertimeHours(0);
            }
        }

        // echo G_Overtime::STATUS_APPROVED;
        // exit();

        // if($is_restday && $is_default_schedule){
        //       $t->setOverTimeIn('');
        //     $t->setOverTimeOut('');
        //     $t->setOvertimeDateIn('');
        //     $t->setOvertimeDateOut('');
        //     $t->setTotalOvertimeHours(0);

        // }else{
        //     $o = G_Overtime_Finder::findByEmployeeAndDateAndStatus($e, $date, G_Overtime::STATUS_APPROVED);

        // if ($o ) {
        //     if($is_restday && $is_default_schedule){
        //     $t->setOverTimeIn('');
        //     $t->setOverTimeOut('');
        //     $t->setOvertimeDateIn('');
        //     $t->setOvertimeDateOut('');
        //     $t->setTotalOvertimeHours(0);
        //     }else{
        //          $has_overtime = true;
        //     $overtime_in = $o->getTimeIn();
        //     $overtime_out = $o->getTimeOut();
        //     $t->setOverTimeIn($overtime_in);
        //     $t->setOverTimeOut($overtime_out);

        //     $overtime_date_time_in = $date .' '. $overtime_in;
        //     $overtime_date_time_out = $date .' '. $overtime_out;
        //     if (Tools::isTimeAfternoon($overtime_in) && Tools::isTimeMorning($overtime_out)) {
        //         $overtime_date_time_in = $date .' '. $overtime_in;
        //         $tomorrow_date = Tools::getTomorrowDate("{$date} {$overtime_out}");
        //         $overtime_date_time_out = $tomorrow_date .' '. $overtime_out;
        //     }
        //     $total_overtime_hours = Tools::computeHoursDifferenceByDateTime($overtime_date_time_in, $overtime_date_time_out);
        //     $t->setOvertimeDateIn(date('Y-m-d', strtotime($overtime_date_time_in)));
        //     $t->setOvertimeDateOut(date('Y-m-d', strtotime($overtime_date_time_out)));
        //     $t->setTotalOvertimeHours($total_overtime_hours);
        //     }

        // } else {
        //   $t->setOverTimeIn('');
        //     $t->setOverTimeOut('');
        //     $t->setOvertimeDateIn('');
        //     $t->setOvertimeDateOut('');
        //     $t->setTotalOvertimeHours(0);
        // }
        //}

        if (!$using_dtr_error) {
            // LATE, UNDERTIME
            if ($has_schedule && $has_actual_schedule && !$a->isHoliday() && !$a->isRestday()) {
                $active_schedule = G_Schedule_Finder::findActiveByEmployee($e);
                if ($active_schedule) {
                    //$grace_period = $active_schedule->getGracePeriod();
                } else {
                    $default_schedule = G_Schedule_Finder::findDefaultByDate($date);
                    if ($default_schedule) {
                        //$grace_period = $default_schedule->getGracePeriod();
                    } else {
                        //$grace_period = 0;
                    }
                }

                $gp = G_Settings_Grace_Period_Finder::findByDefault();
                //check if employee is exempted for grace period
                $gp_exempted = G_Settings_Grace_Period_Exempted_Finder::findByEmployeeId($e->getId());
                if($gp_exempted){
                    $grace_period = 0;
                }
                else{
                   $grace_period = $gp->getNumberMinuteDefault();
                }


                //removing seconds in actual timein
                $time_part = explode(":", $actual_time_in);   
                $time_part[2] = '00';
                $new_actual_timein = implode(":", $time_part);
                $new_actual_timein = date("H:i:s",strtotime($new_actual_timein));



                //LATE
                $l = new Late_Calculator;
                $l->setGracePeriod($grace_period);
                $l->setBreakTimeIn($break_time_in);
                $l->setBreakTimeOut($break_time_out);
                $l->setScheduledTimeIn($scheduled_time_in);
                $l->setScheduledTimeOut($scheduled_time_out);
               // $l->setActualTimeIn($actual_time_in);
                $l->setActualTimeIn($new_actual_timein);
                $l->setActualTimeOut($actual_time_out);
                $late_hours = $l->computeLateHours();

                //BREAKTIME LATE HOURS
                $employee_id    = $e->getId();
                $date_from      = $date;
                $date_to        = $date;
                $breaktime_late = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($employee_id, $date_from, $date_to);

                if ($a->isLeave() || $a->isHoliday() || $a->isRestday()) {
                    $t->setLateHours(0);
                } else {
                    if ($breaktime_late) {
                        $t->setLateHours($late_hours);
                    } else {

                        if (Tools::isTimeAfternoon($new_actual_timein)) {
                            $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($e->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());

                            if (Tools::isTimeBetweenHours($new_actual_timein, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])) {

                                $halftime_late_start = $t->getScheduledTimeIn();
                                $halftime_late_end   = date("H:i:s", strtotime($breaktime_data[0]['break_in']));
                                $late_hours = Tools::newComputeHoursDifferenceByDateTime($halftime_late_start, $halftime_late_end);
                            }
                        }

                        $t->setLateHours($late_hours);
                    }
                }





                if($gp_exempted){
                    $t->setLateHours(0);
                }




                //removing seconds in actual timeout
                $time_part = explode(":", $actual_time_out);   
                $time_part[2] = '00';
                $new_actual_timeout = implode(":", $time_part);
                $new_actual_timeout = date("H:i:s",strtotime($new_actual_timeout));



                // UNDERTIME
                //if ($t->getTotalHoursWorked() < 8) {
                $u = new Undertime_Calculator;
                $u->setScheduledTimeIn($scheduled_time_in);
                $u->setScheduledTimeOut($scheduled_time_out);
                $u->setActualTimeIn($actual_time_in);
                $u->setActualTimeOut($new_actual_timeout);
                $u->setBreakTimeIn($break_time_in);
                $u->setBreakTimeOut($break_time_out);
                $undertime_hours = $u->computeUndertimeHours();

                if ($a->isLeave() || $a->isHoliday() || $a->isRestday()) {

                    $fields = array('apply_half_day_date_start', 'apply_half_day_date_end', 'is_paid');
                    $leave  = G_Employee_Leave_Request_Helper::sqlEmployeeLeaveRequestByDate($a->getEmployeeId(), $a->getDate(), $fields);

                    $t->setUndertimeHours(0);
                    if ($a->isLeave()) {
                        $halfday_undertime_hours = 0;
                        if ($leave['apply_half_day_date_start'] == 'Yes') {
                            $emp_schedule['schedule_in']  = $t->getScheduledDateIn() . " " . $t->getScheduledTimeIn();
                            $emp_schedule['schedule_out'] = $t->getScheduledDateOut() . " " . $t->getScheduledTimeOut();

                            $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($e->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
                            $halfday_scheduled_hours_to_breaktime = 0;
                            $total_breaktime_hours = 0;
                            if ($breaktime_data) {
                                $start_time_sched  = $t->getScheduledTimeIn();
                                $end_time_to_break = $breaktime_data[0]['break_in'];
                                if (Tools::isTimeMorning($actual_time_in)) {
                                    $halfday_scheduled_hours_to_breaktime = Tools::newComputeHoursDifferenceByDateTime($start_time_sched, $end_time_to_break);
                                    $total_breaktime_hours = Tools::newComputeHoursDifferenceByDateTime($breaktime_data[0]['break_in'], $breaktime_data[0]['break_out']);
                                } else {
                                    //if afternoon add code here
                                }
                            }

                            if ($halfday_scheduled_hours_to_breaktime && $halfday_scheduled_hours_to_breaktime > 0) {
                                $halfday_scheduled_hours = $halfday_scheduled_hours_to_breaktime;
                                if ($t->getTotalHoursWorked() < $halfday_scheduled_hours) {
                                    $halfday_undertime_hours = $halfday_scheduled_hours - $t->getTotalHoursWorked();

                                    if (strtotime($actual_time_out) >= strtotime($scheduled_time_out)) {
                                        $halfday_undertime_hours = 0;
                                    }
                                    if (Tools::isTimeMorning($actual_time_in)) {
                                        if (Tools::isTimeBetweenHours($actual_time_out, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])) {
                                            $halfday_undertime_hours = 0;
                                        }
                                    }
                                }
                            } else {
                                if ($t->getTotalScheduleHours() != '') {
                                    $halfday_scheduled_hours = $t->getTotalScheduleHours() / 2;
                                } else {
                                    $halfday_scheduled_hours = 4.75;
                                }

                                if ($t->getTotalHoursWorked() < $halfday_scheduled_hours) {
                                    //$halfday_undertime_hours = ($undertime_hours - $halfday_scheduled_hours);
                                    $halfday_undertime_hours = 0;
                                    //
                                }
                            }

                            $t->setUndertimeHours($halfday_undertime_hours);
                        }
                    }
                } else {

                    if (Tools::isTimeMorning($actual_time_in)) {
                        $breaktime_data = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut($e->getId(), $t->getScheduledTimeIn(), $t->getScheduledTimeOut());
                        if (!empty($breaktime_data) && Tools::isTimeBetweenHours($actual_time_out, $breaktime_data[0]['break_in'], $breaktime_data[0]['break_out'])) {
                            $halftime_undertime_start = date("H:i:s", strtotime($breaktime_data[0]['break_out']));
                            $halftime_undertime_end   = $t->getScheduledTimeOut();
                            $undertime_hours = Tools::newComputeHoursDifferenceByDateTime($halftime_undertime_start, $halftime_undertime_end);
                        }
                    }
                    $t->setUndertimeHours($undertime_hours);
                }
            } else {
                $t->setLateHours(0);
                $t->setUndertimeHours(0);
            }
        }


         //add ob_diff to undertime_hrs
         $total_undertime_hrs = $t->getUndertimeHours() + $initial_undertime_hrs;
         $t->setUndertimeHours($total_undertime_hrs);
         //**************




        if(strtotime($actual_date_time_out) >= strtotime($schedule_date_time_out)){
            $t->setUndertimeHours(0);
        }

        // NIGHT SHIFT       
        if ($has_actual_schedule || $has_schedule) {
            $ns = new Nightshift_Calculator;
            $ns->setScheduledTimeIn($scheduled_time_in);
            $ns->setScheduledTimeOut($scheduled_time_out);
            $ns->setOvertimeIn($overtime_in);
            $ns->setOvertimeOut($overtime_out);
            $ns->setActualTimeIn($actual_time_in);
            $ns->setActualTimeOut($actual_time_out);
            $ns_hours = $ns->compute();
            if ($ns_hours >= $deductible_breaktime) {
                $i_ns_diff  = $ns_hours - $deductible_breaktime;
            } else {
                $i_ns_diff  = $ns_hours;
            }
            $t->setNightShiftHours($i_ns_diff); //B2
            //$t->setNightShiftHours($ns_hours);
        }

        if (!$using_dtr_error) {
            // OVERTIME COMPUTATION
            if ($has_overtime) {
                $o = new G_Overtime_Calculator_New($overtime_date_time_in, $overtime_date_time_out);
                //$o->debugMode();
                $o->setScheduleDateTime($schedule_date_time_in, $schedule_date_time_out);

                $ot_hours = $o->computeHours();
                $ot_excess_hours = $o->computeExcessHours();
                $ot_nd = $o->computeNightDiff();
                $ot_excess_nd = $o->computeExcessNightDiff();
            }

            // SCHEDULE W/ OT
            // AUTO OVERTIME - DEPRE
            $has_auto_overtime = false;
            if ($has_schedule && $has_actual_schedule) {
                //get scheduled working days with OT            
                $regular_working_hours = 8 + $break_time;
                if ($total_schedule_hours > $regular_working_hours) {
                    if ($total_hours_worked > $regular_working_hours) {
                        $scheduled_ot_hours = $total_schedule_hours - $regular_working_hours;
                        $time_in = date('H:i:s', strtotime($scheduled_time_out) - 60 * 60 * $scheduled_ot_hours);
                        if (strtotime($actual_time_out) >= strtotime($scheduled_time_out)) {
                            $time_out = $scheduled_time_out;
                        } else {
                            $time_out = $actual_time_out;
                        }

                        $auto_ot_date_time_in  = $data . ' ' . $time_in;
                        $auto_ot_date_time_out = $data . ' ' . $time_out;

                        $o = new G_Overtime_Calculator_New($auto_ot_date_time_in, $auto_ot_date_time_out);
                        //$o->debugMode();
                        $o->setScheduleDateTime($schedule_date_time_in, $schedule_date_time_out);
                        $auto_ot_hours        = $o->computeHours();
                        $auto_ot_excess_hours = $o->computeExcessHours();
                        $auto_ot_nd           = $o->computeNightDiff();
                        $auto_ot_excess_nd    = $o->computeExcessNightDiff();
                        $has_auto_overtime = true;
                    }
                }
            }
        /*
        Auto Overtime Daiichi
        $has_auto_overtime = false;
        if ($has_schedule && $has_actual_schedule) {
            $data['date_in']  = $t->getDateIn();
            $data['date_out'] = $t->getDateOut();
            $data['time_in']  = $t->getTimeIn();
            $data['time_out'] = $t->getTimeOut();

            $data['scheduled_date_in'] = $t->getScheduledDateIn();
            $data['schedule_date_out'] = $t->getScheduledDateOut();
            $data['schedule_time_in']  = $t->getScheduledTimeIn();
            $data['schedule_time_out'] = $t->getScheduledTimeOut();

            $schedule['schedule_in']  = $t->getScheduledTimeIn();
            $schedule['schedule_out'] = $t->getScheduledTimeOut();

            $break_schedules = $e->getEmployeeBreakTimeBySchedule($schedule);
            $o = new G_Overtime();              
            $data = $o->autoOvertime($data, $break_schedules);

            $has_auto_overtime = $data['has_auto_overtime'];
            if( $has_auto_overtime ){
                $auto_ot_hours        = $data['ot_hours'];
                $auto_ot_excess_hours = $data['ot_excess_hours'];
                $auto_ot_nd           = $data['ot_nd'];
                $auto_ot_excess_nd    = $data['ot_excess_nd'];

                $ot_hours        = $auto_ot_hours;
                $ot_excess_hours = $auto_ot_excess_hours;
                $ot_nd           = $auto_ot_nd;
                $ot_excess_nd    = $auto_ot_excess_nd;
            }
        } END Auto Overtime Daiichi*/

        // COMPUTE AUTO OVERTIME - MEANING IF SCHEDULE IS MORE THAN 8 HOURS, EXCESS HOURS WILL BE AUTO ADDED AS OVERTIME
        /*if ($has_schedule && $has_actual_schedule && ($total_schedule_hours - $break_time_hours)  > 8) {
            $o = new G_Overtime_Calculator;
            $o->setLimitHours(8);
            $o->setScheduleIn($t->getScheduledTimeIn());
            $o->setScheduleOut($t->getScheduledTimeOut());

            $o->setOvertimeIn($t->getScheduledTimeIn());

            if (self::isTime1LessThanTime2($t->getTimeOut(), $t->getScheduledTimeOut())) {
                $o->setOvertimeOut($t->getTimeOut());
            } else {
                $o->setOvertimeOut($t->getScheduledTimeOut());
            }
            $ot_excess_hours = $ot_excess_hours + $o->computeExcessHours();
            $ot_nd = $ot_nd + $o->computeNightDiff();
            $ot_excess_nd = $ot_excess_nd + $o->computeExcessNightDiff();
        }*/

        //new - alex - beta
        //find project site assigned to date attendance
        $project = G_Employee_Project_Site_History_Finder::getProjectSiteByEmployeeAndDate($e, $date);
        if($project){
            $a->setProjectSiteId($project->getProjectId());
        }
        else{
             $a->setProjectSiteId(0);
        }

        //end project site





            $auto_ot = new G_Overtime();

            // SET TO ZERO FIRST
            $t->setRestDayOvertimeHours(0);
            $t->setRestDayOvertimeExcessHours(0);
            $t->setRestDayOvertimeNightShiftHours(0);
            $t->setRestDayOvertimeNightShiftExcessHours(0);

            $t->setRegularOvertimeHours(0);
            $t->setRegularOvertimeExcessHours(0);
            $t->setRegularOvertimeNightShiftHours(0);
            $t->setRegularOvertimeNightShiftExcessHours(0);

            $t->setRestDayLegalOvertimeHours(0);
            $t->setRestDayLegalOvertimeExcessHours(0);
            $t->setRestDayLegalOvertimeNightShiftHours(0);
            $t->setRestDayLegalOvertimeNightShiftExcessHours(0);

            $t->setRestDaySpecialOvertimeHours(0);
            $t->setRestDaySpecialOvertimeExcessHours(0);
            $t->setRestDaySpecialOvertimeNightShiftHours(0);
            $t->setRestDaySpecialOvertimeNightShiftExcessHours(0);

            $t->setLegalOvertimeHours(0);
            $t->setLegalOvertimeExcessHours(0);
            $t->setLegalOvertimeNightShiftHours(0);
            $t->setLegalOvertimeNightShiftExcessHours(0);

            $t->setSpecialOvertimeHours(0);
            $t->setSpecialOvertimeExcessHours(0);
            $t->setSpecialOvertimeNightShiftHours(0);
            $t->setSpecialOvertimeNightShiftExcessHours(0);

            if ($a->isRestday() && !$a->isHoliday()) {
                // Deprecated - Start
                //$t->setOvertimeHours($ot_hours); // value either regular or rest day
                //$t->setOvertimeExcessHours($ot_excess_hours); // value either regular or rest day
                //$t->setOvertimeNightShiftHours($ot_nd);
                //$t->setOvertimeNightShiftExcessHours($ot_excess_nd);

                //$t->setNightShiftOvertimeHours($ot_nd); // value either regular or rest day
                //$t->setNightShiftOvertimeExcessHours($ot_excess_nd); // value either regular or rest day
                // Deprecated - End

                $t->setRestDayOvertimeHours($ot_hours);
                $t->setRestDayOvertimeExcessHours($ot_excess_hours);
                $t->setRestDayOvertimeNightShiftHours($ot_nd);
                $t->setRestDayOvertimeNightShiftExcessHours($ot_excess_nd);
            } else if ($a->isRestday() && $a->isHoliday()) {
                if ($h->isLegal()) {
                    $t->setRestDayLegalOvertimeHours($ot_hours);
                    $t->setRestDayLegalOvertimeExcessHours($ot_excess_hours);
                    $t->setRestDayLegalOvertimeNightShiftHours($ot_nd);
                    $t->setRestDayLegalOvertimeNightShiftExcessHours($ot_excess_nd);
                } else if ($h->isSpecial()) {
                    $t->setRestDaySpecialOvertimeHours($ot_hours);
                    $t->setRestDaySpecialOvertimeExcessHours($ot_excess_hours);
                    $t->setRestDaySpecialOvertimeNightShiftHours($ot_nd);
                    $t->setRestDaySpecialOvertimeNightShiftExcessHours($ot_excess_nd);
                }
            } else if (!$a->isRestday() && $a->isHoliday()) {
                if ($h->isLegal()) {
                    $t->setLegalOvertimeHours($ot_hours);
                    $t->setLegalOvertimeExcessHours($ot_excess_hours);
                    $t->setLegalOvertimeNightShiftHours($ot_nd);
                    $t->setLegalOvertimeNightShiftExcessHours($ot_excess_nd);
                } else if ($h->isSpecial()) {
                    $t->setSpecialOvertimeHours($ot_hours);
                    $t->setSpecialOvertimeExcessHours($ot_excess_hours);
                    $t->setSpecialOvertimeNightShiftHours($ot_nd);
                    $t->setSpecialOvertimeNightShiftExcessHours($ot_excess_nd);
                }
            } else { // Regular
                // Deprecated - Start
                //$t->setOvertimeHours($ot_hours); // value either regular or rest day
                //$t->setOvertimeExcessHours($ot_excess_hours); // value either regular or rest day
                //$t->setOvertimeNightShiftHours($ot_nd);
                //$t->setOvertimeNightShiftExcessHours($ot_excess_nd);

                //$t->setNightShiftOvertimeHours($ot_nd); // value either regular or rest day
                //$t->setNightShiftOvertimeExcessHours($ot_excess_nd); // value either regular or rest day
                // Deprecated - End

                $has_auto_overtime = false; //Remove to trigger auto overtime
                if ($has_auto_overtime) {
                    /*$ot_hours += $auto_ot_hours;
                $ot_excess_hours += $auto_ot_excess_hours;
                $ot_nd += $auto_ot_nd;
                $ot_excess_nd += $auto_ot_excess_nd;*/
                    $t->setTotalOvertimeHours($total_overtime_hours + $auto_ot_hours); //Uncomment to auto render overtime 
                }

                $t->setRegularOvertimeHours($ot_hours);
                $t->setRegularOvertimeExcessHours($ot_excess_hours);
                $t->setRegularOvertimeNightShiftHours($ot_nd);
                $t->setRegularOvertimeNightShiftExcessHours($ot_excess_nd);
            }

            // LEAVE
            //$l = G_Leave_Finder::findApprovedByEmployeeAndDate($e, $date);
            $leave = $e->getLeaveRequest($date);
            /*if( $e->getId() == 31 && $date == '2016-04-18' ){
            Utilities::displayArray($leave);
            exit;
        }*/
            if ($leave && $leave->isApproved() && (!$a->isRestday() && $has_schedule)) {
                $a->setAsLeave();
                $a->setLeaveId($leave->getLeaveId());
            } else {
                $a->setLeaveId(0);
                $a->setAsNotLeave();
            }

            // NO LATE AND UNDERTIME HOURS IF HOLIDAY OR RESTDAY
            if ($a->isHoliday() || $a->isRestday()) {
                $t->setLateHours(0);
                $t->setUndertimeHours(0);
            }
            $a->setTimesheet($t);
            if ($e) {
                $a->setEmployeeId($e->getId()); // set employee id under attendance object
            }

            // PAID OR NOT
            $a->setAsNotPaid();
            if ($a->isPresent()) {
                $a->setAsPaid();
            } else if ($a->isLeave()) {
                if ($leave->getIsPaid() == G_Employee_Leave_Request::IS_PAID_YES) {
                    $a->setAsPaid();
                }
            } else if ($a->isRestday()) {
                $a->setAsPaid();
            } else if ($a->isOfficialBusiness()) {
                $a->setAsPaid();
            } else if ($a->isHoliday()) {
                $a->setAsPaid();
            }

            //CUSTOM OVERTIME
            $is_with_custom_ot  = false;
            $is_valid_custom_ot = true;
            $count_approved_disapproved = G_Custom_Overtime_Helper::sqlCountTotalApprovedAndDisapprovedCustomOvertimeByEmployeeIdAndDate($e->getId(), $date);
         //   if ($count_approved_disapproved == 0) {
                if ($a->isHoliday() && $has_schedule && $total_hours_worked > 0) {
                    $is_with_custom_ot = true;
                    $custom_ot_day_type = G_Custom_Overtime::DAY_TYPE_HOLIDAY;
                } elseif ($is_restday && $has_schedule) {
                    $is_with_custom_ot = true;
                    $custom_ot_day_type = G_Custom_Overtime::DAY_TYPE_RESTDAY;
                }

                if ($is_with_custom_ot) {
                    $co = G_Custom_Overtime_Finder::findByEmployeeIdAndDate($e->getId(), $date);
                    if (empty($co)) {
                        $co = new G_Custom_Overtime();
                    }

                    if ($t->getTimeIn() != '' && $scheduled_time_in != '') {
                        if (strtotime($t->getTimeIn()) < strtotime($scheduled_time_in)) {
                            $custom_overtime_start_time = $scheduled_time_in;
                        } else {
                            $custom_overtime_start_time = $t->getTimeIn();
                        }
                    } else {
                        $is_valid_custom_ot = false;
                    }

                    if ($t->getTimeOut() != '' && $scheduled_time_out != '') {
                        if (strtotime($t->getTimeOut()) > strtotime($scheduled_time_out)) {
                            $custom_overtime_end_time = $scheduled_time_out;
                        } else {
                            $custom_overtime_end_time = $t->getTimeOut();
                        }
                    } else {
                        $is_valid_custom_ot = false;
                    }

                    if ($is_valid_custom_ot) {
                        $co->setEmployeeId($e->getId());
                        $co->setDate($date);
                        $co->setStartTime($custom_overtime_start_time);
                        $co->setEndTime($custom_overtime_end_time);
                        $co->setDayType($custom_ot_day_type);
                        $co->setAsApproved();
                        $co->save();
                    }
                }
         //   }
            // IF EMPLOYEE'S RATE IS DAILY, NO WORK NO PAY      
            if ($salary) {
                if ($salary->isDaily()) {
                if ($a->isPresent()) {
                    $a->setAsPaid();
                }
                elseif(!$a->isPresent() && ($a->isLeave() && $leave->getIsPaid() == G_Employee_Leave_Request::IS_PAID_YES)){
                     $a->setAsPaid();
                     //$a->setAsPresent();

                }
                 else {
                    $a->setAsNotPaid();
                }
                }
            } else {
                $a->setAsNotPaid();
            }

            if ($salary) {
                if ($h) {
                    if ($h->getType() == Holiday::LEGAL) {
                        $a->setAsPaid();
                    }
                }
            }
        }

        if (isset($emp_exemption['no_in_out']) && $emp_exemption['no_in_out'] == 1) {
            if ($actual_time_in == $actual_time_out) {
                if (strtotime($t->getTimeIn()) && strtotime($t->getTimeOut())) {
                    if (($actual_time_in && empty($actual_time_out)) || (empty($actual_time_in) && $actual_time_out) || ($actual_time_in == $actual_time_out)) {
                        $t->setTotalHoursWorked(8);
                        $a->setAsPresent();
                        $a->setAsPaid();
                    } elseif (($actual_time_in && $actual_time_out == '00:00:00') || ($actual_time_in == '00:00:00' && $actual_time_out) || ($actual_time_in == $actual_time_out)) {
                        $t->setTotalHoursWorked(8);
                        $a->setAsPresent();
                        $a->setAsPaid();
                    }
                }
            }
        }

        return $a;
    }

    public static function computeTotalHoursWorked($date_in, $date_out, $time_in, $time_out, $schedule_date_in, $schedule_date_out, $schedule_time_in, $schedule_time_out)
    {
        if ($date_in == '' || $date_out == '' || $time_in == '' || $time_out == '') {
            return 0;
        }

        $actual_in   = "{$date_in} {$time_in}";
        $schedule_in = "{$schedule_date_in} {$schedule_time_in}";

        $actual_out   = "{$date_out} {$time_out}";
        $schedule_out = "{$schedule_date_out} {$schedule_time_out}";

        $in = $actual_in;
        if (Tools::isTime1LessThanTime2($actual_in, $schedule_in)) {
            $in = $schedule_in;
            //$in = $actual_in; 
        }

        $out = $actual_out;
        if (Tools::isTime1LessThanTime2($schedule_out, $actual_out)) {
            $out = $schedule_out;
            //$out = $actual_out; 
        }

        $total_hours_worked = Tools::computeHoursDifferenceByDateTime($in, $out);

        $limit_total_hours_worked = G_Attendance::CEILING_HOURS_WORKED;

        if ($total_hours_worked > $limit_total_hours_worked) {
            $total_hours_worked = G_Attendance::CEILING_HOURS_WORKED;
        }

        return number_format($total_hours_worked, 2);
    }


    /*
     * Insert attendance to those dates that have no attendance for particular employee
     */
    public static function finalizeAttendance($e, $start_date, $end_date)
    {
        $dates = Tools::getBetweenDates($start_date, $end_date);
        $attendance_dates = self::getNoAttendanceDates($e, $start_date, $end_date);
        $empty_dates = array_diff($dates, $attendance_dates);
        foreach ($empty_dates as $empty_date) {
            $attendance_list[] = self::generateAttendance($e, $empty_date);
        }

        return G_Attendance_Manager::recordToMultipleEmployees($attendance_list);
    }

    public static function getNoAttendanceDates($e, $start_date, $end_date)
    {
        $sql = "
            SELECT date_attendance FROM " . G_EMPLOYEE_ATTENDANCE_V2 . "
            WHERE employee_id = " . Model::safeSql($e->getId()) . "
            AND date_attendance
            BETWEEN " . Model::safeSql($start_date) . "
            AND " . Model::safeSql($end_date) . "
            ORDER BY date_attendance ASC
        ";
        $result = Model::runSql($sql);
        while ($row = Model::fetchAssoc($result)) {
            $return[] = $row['date_attendance'];
        }
        return $return;
    }

    /*
     * Update attendance by G_Attendance in array
     * @param $multiple_attendance Array with instance of G_Attendance. It is from G_Attendance_Helper::generateAttendance()
     */
    public static function updateAttendanceByMultipleAttendance($multiple_attendance)
    {
        return G_Attendance_Manager::recordToMultipleEmployees($multiple_attendance);
    }

    /*
     * Update attendance by G_Attendance
     * @param $attendance Instance of G_Attendance. It is from G_Attendance_Helper::generateAttendance()
     */
    public static function updateAttendanceBySingleAttendance($attendance)
    {
        return G_Attendance_Manager::recordToSingleEmployee($attendance);
    }

    public static function updateAttendanceByAllActiveEmployees($date)
    {
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $employees = G_Employee_Finder::findAllActiveByDate($date);
        foreach ($employees as $e) {
            $a[] = self::generateAttendance($e, $date);
            //self::updateAttendance($e, $date);
        }
        self::updateAttendanceByMultipleAttendance($a);
    }

    public static function updateAttendanceByEmployeeIdPeriod($employee_id, $start_date, $end_date)
    {
        $e = G_Employee_Finder::findById($employee_id);
        return self::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
    }

    public static function updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date)
    {
        $is_true    = false;
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
    public static function updateAttendanceByEmployeesAndPeriod($employees, $start_date, $end_date)
    {
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

    public static function updateAttendanceByEmployeeAndPeriodWithCheckAttendanceValidation($e, $start_date, $end_date)
    {
        $is_true = false;
        $is_updated = false;
        $dates = Tools::getBetweenDates($start_date, $end_date);

        if ($e) {
            foreach ($dates as $date) {
                $check   = self::isEmployeeWithAttendance($e, $date);
                if ($check) {
                    $is_true = self::updateAttendance($e, $date);
                    if ($is_true) {
                        $is_updated = true;
                    }
                }
            }
        }
        return $is_updated;
    }

    public static function isEmployeeWithAttendance(G_Employee $e, $date, $order_by, $limit)
    {
        $sql = "
            SELECT COUNT(*) as total                        
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a
            WHERE
                    a.date_attendance = " . Model::safeSql($date) . " AND 
                    a.employee_id=" . Model::safeSql($e->getId()) . "
            " . $order_by . "
            " . $limit . "
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function updateAttendanceByPeriod($start_date, $end_date)
    {
        $is_true = false;
        $is_updated = false;

        $employees = G_Employee_Finder::findAllEmployeesActiveByDateAndIsNotResignedAndIsNotTerminated($end_date);

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

    public static function updateAttendanceByPeriodAndFrequency($start_date, $end_date, $frequency_id)
    {
        $is_true = false;
        $is_updated = false;

        $employee_ids_qry = " e.id IN () AND ";

        $s = G_Employee_Basic_Salary_History_Finder::findByDateAndFrequency($end_date, $frequency_id);

        foreach ($s as $key => $data) {
            $employee_ids[] = $data->employee_id;
        }

        if (count($employee_ids) > 0) {
            $employee_ids_qry = " e.id IN (" . implode(",", $employee_ids) . ") AND ";
        }

        $employees = G_Employee_Finder::findAllEmployeesActiveByDateAndIsNotResignedAndIsNotTerminated($end_date, "", $employee_ids_qry);

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

    public static function test_curl()
    {
        $parts = parse_url(url('attendance/show_curl'));
        $fp = fsockopen(
            $parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno,
            $errstr,
            30
        );

        if (!$fp) {
            return false;
        } else {
            $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
            $out .= "Host: " . $parts['host'] . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: " . strlen($parts['query']) . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            if (isset($parts['query'])) $out .= $parts['query'];

            fwrite($fp, $out);
            fclose($fp);
        }
    }

    public static function updateAllNoAttendanceDateByPeriod($date_start, $date_end)
    {
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

    public static function isTime1LessThanTime2($time1, $time2)
    {
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        if ($time1 < $time2) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Gets the start date and end date of the week (Sunday is the first day of the week)
    *
    *   Returns array - $date['start_date'] and $date['end_date'];
    */
    public static function findWeekStartDateAndEndDate($date)
    {
        $current_day = (int) date("w", strtotime($date));
        $d = new DateTime($date);
        $d->modify("-{$current_day} day");
        $return['start_date'] = $d->format('Y-m-d');
        $d->modify("+6 days");
        $return['end_date'] = $d->format('Y-m-d');

        return $return;
    }

    public static function isDateOutGreaterThanDateIn($date_in, $date_out)
    {
        if ($date_in == "" && $date_out == "") {
            return false;
        } else if (strtotime($date_out) > strtotime($date_in)) {
            return true;
        } else {
            return false;
        }
    }


    //this is for early ot
    public static function generateAttendanceFOREARLYOT(IEmployee $e, $date)
    {
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

            $scheduled_time_in = date("H:i:s", strtotime($ss->getTimeIn()));
            $scheduled_time_out = date("H:i:s", strtotime($ss->getTimeOut()));

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
            if ($total_ot_hours > 8) {
                $total_excess_hours = $total_ot_hours - 8;
                $total_ot_hours = 8;
            }

            $total_ot_nd_hours = $ot_nd + $eot_excess_nd;
            if ($total_ot_nd_hours > 8) {
                $total_excess_nd_hours = $total_ot_nd_hours - 8;
                $total_ot_nd_hours = 8;
            }
            /*echo "total ot hours : {$total_ot_hours} <br/>";
            echo "total excess  : {$total_excess_hours} <br/>";
            echo "total otnd hours : {$total_ot_nd_hours} <br/>";
            echo "total excess nd   : {$total_excess_nd_hours} <br/>";*/


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
    public static function getUpdatedAttendance(IEmployee $e, $date)
    {
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

    public static function updateAttendance(IEmployee $e, $date)
    {
        if ($e) {

            $a = self::generateAttendance($e, $date);
            $is_true = self::updateAttendanceBySingleAttendance($a);
            if ($is_true) {
                $is_updated = true;
            }
        }
        return $is_updated;
    }

    public static function recordTimecardTimeIn(IEmployee $e, $date, $time, $v2_dtr, $error_message, G_Employee_Schedule_Type $sched)
    {
        $a = G_Attendance_Finder_V2::findByEmployeeAndDate($e, $date);
        if (!$a) {
            $a = new G_Attendance;
        }
        $a->setDate($date);
        $a->setAsPaid();
        $a->setAsPresent();
        $t = new G_Timesheet;
        $t->setTimeIn($time);
        $a->setTimesheet($t);
        
        $employee_attendance_id = $a->recordToEmployeeV2($e, $v2_dtr, null, $error_message, $sched);


        return $employee_attendance_id;
    }

    public static function recordTimecardTimeOut(IEmployee $e, $logsId, $time, $v2_dtr, $error_message)
    {
        $a = new G_Attendance;
        $a->setAsPresent();
        $t = new G_Timesheet;
        $t->setTimeOut($time);
        $a->setTimesheet($t);
        
        $employee_attendance_id = $a->recordToEmployeeV2($e, $v2_dtr, $logsId, $error_message);

        return $employee_attendance_id;
    }

    public static function recordTimecardTimeOutWithError(IEmployee $e, $time, $v2_dtr, $error_message)
    {
        $a = new G_Attendance;
        $a->setAsPresent();
        $t = new G_Timesheet;
        $t->setTimeOut($time);
        $a->setTimesheet($t);
        
        $employee_attendance_id = $a->recordToEmployeeV2($e, $v2_dtr, null, $error_message);


        return $employee_attendance_id;
    }

    public static function recordTimecard(IEmployee $e, $date, $time_in, $time_out, $date_in, $date_out, $overtime_in, $overtime_out, $grace_period = 0, $breaks = array(), $v2_dtr)
    {
        $a = G_Attendance_Finder_V2::findByEmployeeAndDate($e, $date);
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
        
        $employee_attendance_id = $a->recordToEmployeeV2($e, $v2_dtr);
        
        //break logs summary
        $attendance_breaks = null;
        if (count($breaks) > 0) {

            $attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAndDate($e, $date);

            if (!$attendance_breaks) {
                $attendance_breaks = new G_Employee_Break_logs_Summary;
            }

            //break1 out
            $break1_log_out_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['id'] : 0;
            $break1_log_out = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['time'])) {
                $break1_log_out = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_OUT)]['time']));
            }

            //break1 in
            $break1_log_in_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['id'] : 0;
            $break1_log_in = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['time'])) {
                $break1_log_in = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B1_IN)]['time']));
            }

            //break2 out
            $break2_log_out_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['id'] : 0;
            $break2_log_out = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['time'])) {
                $break2_log_out = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_OUT)]['time']));
            }

            //break2 in
            $break2_log_in_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['id'] : 0;
            $break2_log_in = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['time'])) {
                $break2_log_in = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B2_IN)]['time']));
            }

            //break3 out
            $break3_log_out_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['id'] : 0;
            $break3_log_out = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['time'])) {
                $break3_log_out = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_OUT)]['time']));
            }

            //break3 in
            $break3_log_in_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['id'] : 0;
            $break3_log_in = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['time'])) {
                $break3_log_in = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_B3_IN)]['time']));
            }

            //ot_break1 out
            $ot_break1_log_out_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['id'] : 0;
            $ot_break1_log_out = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['time'])) {
                $ot_break1_log_out = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_OUT)]['time']));
            }

            //ot_break1 in
            $ot_break1_log_in_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['id'] : 0;
            $ot_break1_log_in = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['time'])) {
                $ot_break1_log_in = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B1_IN)]['time']));
            }

            //ot_break1 out
            $ot_break2_log_out_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['id'] : 0;
            $ot_break2_log_out = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['time'])) {
                $ot_break2_log_out = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_OUT)]['time']));
            }

            //ot_break2 in
            $ot_break2_log_in_id = isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['id']) ? $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['id'] : 0;
            $ot_break2_log_in = null;
            if (isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['date']) && isset($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['time'])) {
                $ot_break2_log_in = date("Y-m-d H:i:s", strtotime($breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['date'] . ' ' . $breaks[strtolower(G_Employee_Break_Logs::TYPE_OT_B2_IN)]['time']));
            }

            $attendance_breaks->setAttendanceDate($date);
            $attendance_breaks->setEmployeeAttendanceId($employee_attendance_id);
            $attendance_breaks->setEmployeeId($e->getId());
            $attendance_breaks->setLogBreak1OutId($break1_log_out_id);
            $attendance_breaks->setLogBreak1Out($break1_log_out);
            $attendance_breaks->setLogBreak1InId($break1_log_in_id);
            $attendance_breaks->setLogBreak1In($break1_log_in);
            $attendance_breaks->setLogBreak2OutId($break2_log_out_id);
            $attendance_breaks->setLogBreak2Out($break2_log_out);
            $attendance_breaks->setLogBreak2InId($break2_log_in_id);
            $attendance_breaks->setLogBreak2In($break2_log_in);
            $attendance_breaks->setLogBreak3OutId($break3_log_out_id);
            $attendance_breaks->setLogBreak3Out($break3_log_out);
            $attendance_breaks->setLogBreak3InId($break3_log_in_id);
            $attendance_breaks->setLogBreak3In($break3_log_in);
            $attendance_breaks->setLogOtBreak1OutId($ot_break1_log_out_id);
            $attendance_breaks->setLogOtBreak1Out($ot_break1_log_out);
            $attendance_breaks->setLogOtBreak1InId($ot_break1_log_in_id);
            $attendance_breaks->setLogOtBreak1In($ot_break1_log_in);
            $attendance_breaks->setLogOtBreak2OutId($ot_break2_log_out_id);
            $attendance_breaks->setLogOtBreak2Out($ot_break2_log_out);
            $attendance_breaks->setLogOtBreak2InId($ot_break2_log_in_id);
            $attendance_breaks->setLogOtBreak2In($ot_break2_log_in);
            $attendance_breaks->setCreatedAt(date('Y-m-d H:i:s'));
            $attendance_breaks->save();
        }

        return $employee_attendance_id;
    }

    public static function recordTimecardWithIncomplete(IEmployee $e, $date, $time_in, $time_out, $date_in, $date_out, $overtime_in, $overtime_out, $grace_period = 0)
    {

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        if (!$a) {
            $a = new G_Attendance;
        }
        $a->setDate($date);
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

    public static function goToWork($e, $date, $time_in, $time_out)
    {
        $is_recorded = G_Attendance_Helper::recordTimeInOut($e, $date, $time_in, $time_out);
        G_Attendance_Helper::updateAttendance($e, $date);

        return $is_recorded;
    }

    public static function recordTimeInOut($e, $date, $time_in, $time_out)
    {
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        if (!$a) {
            $a = new G_Attendance;
        }
        $a->setDate($date);
        $a->setEmployeeId($e->getId());
        $a->setAsPresent();
        $t = $a->getTimesheet();
        if (!$t) {
            $t = new G_Timesheet;
        }
        $t->setTimeIn($time_in);
        $t->setTimeOut($time_out);
        $a->setTimesheet($t);
        return G_Attendance_Manager::recordToSingleEmployee($a);
    }

    public static function clearActualTimeAndDateIn($e, $date)
    {
        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        if (!$a) {
            $a = new G_Attendance;
        }
        $a->setDate($date);
        $a->setEmployeeId($e->getId());
        $t = $a->getTimesheet();
        if (!$t) {
            $t = new G_Timesheet;
        }
        $t->setTimeIn('');
        $t->setTimeOut('');
        $t->setDateIn('');
        $t->setDateOut('');
        $a->setTimesheet($t);
        return G_Attendance_Manager::recordToSingleEmployee($a);
    }

    public static function getAllAttendanceByEmployeesAndPeriod($employees, $start_date, $end_date)
    {
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

    public static function getAllAttendanceGroupByEmployeeAndDate($employees, $start_date, $end_date)
    {
        foreach ($employees as $e) {
            if ($e) {
                if ($e->getTerminatedDate() != '0000-00-00') {
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





    /*
    * Attendance Log In. This is used for manual adding of DTR
    * See wrapper function in G_Employee::punchIn();
    *
    * @return void
    */
    public static function punchIn(IEmployee $e, $date, $time)
    {
        $a = new G_Attendance_Log;
        $a->setEmployeeId($e->getId());
        $a->setEmployeeCode($e->getEmployeeCode());
        $a->setEmployeeName($e->getName());
        $a->setDate($date);
        $a->setTime($time);
        $a->setType(G_Attendance_Log::TYPE_IN);
        $a->save();

        $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
        if ($a) {
            // Override timeIN temporarily
            $t = $a->getTimesheet();
            $t->setTimeIn($time);
            $eb = new G_Employee_Breaktime();
            $is_e_breaktime = $eb->validateBreaktime($a);
        }
    }

    /*
    * Attendance Log Out. This is used for manual adding of DTR
    * See wrapper function in G_Employee::punchOut();
    *
    * @return void
    */
    public static function punchOut(IEmployee $e, $date, $time)
    {
        $a = new G_Attendance_Log;
        $a->setEmployeeId($e->getId());
        $a->setEmployeeCode($e->getEmployeeCode());
        $a->setEmployeeName($e->getName());
        $a->setDate($date);
        $a->setTime($time);
        $a->setType(G_Attendance_Log::TYPE_OUT);
        $a->save();

        // UPDATE THE ATTENDANCE
        $yesterday = strtotime($date . ' -1 day');
        $yesterday = date('Y-m-d', $yesterday);
        $logs = G_Attendance_Log_Finder::findAllByPeriod($yesterday, $date);
        self::updateAttendanceByLogs($logs);
    }

    /*
    * Attendance Log In. This is used for manual adding of staggered schedule
    * See wrapper function in G_Employee::punchIn();
    *
    * @return void
    */
    public static function punchInWithProjectSiteAndActivity(IEmployee $e, $date, $time, $project_site, $activity_name)
    {
        $a = new G_Attendance_Log_V2;
        $a->setEmployeeId($e->getId());
        $a->setDate($date);
        $a->setTime($time);
        $a->setType(G_Attendance_Log::TYPE_IN);
        $a->setProjectSiteId($project_site);
        $a->setActivityName($activity_name);
        $a->save();

        /*$a = G_Attendance_Finder_V2::findByEmployeeAndDate($e, $date);
        if ($a) {
            // Override timeIN temporarily
            $t = $a->getTimesheet();
            $t->setTimeIn($time);
        }*/

        // UPDATE THE ATTENDANCE
        $yesterday = strtotime($date . ' -1 day');
        $yesterday = date('Y-m-d', $yesterday);
        $logs = G_Employee_Attendance_Finder_V2::findAllNoTimeOutByToday($date, $date);
        
        self::updateAttendanceByLogsTimeInStaggerV2($logs, $a);
        
    }
/*
    * Attendance Log Out. This is used for manual adding of staggered schedule
    * See wrapper function in G_Employee::punchOut();
    *
    * @return void
    */
    public static function punchOutWithProjectSiteAndActivity(IEmployee $e, $date, $time, $project_site, $activity_name)
    {
        $a = new G_Attendance_Log_V2;
        $a->setEmployeeId($e->getId());
        $a->setDate($date);
        $a->setTime($time);
        $a->setType(G_Attendance_Log::TYPE_OUT);
        $a->setProjectSiteId($project_site);
        $a->setActivityName($activity_name);
        $a->save();

        // UPDATE THE ATTENDANCE
        $yesterday = strtotime($date . ' -1 day');
        $yesterday = date('Y-m-d', $yesterday);
        $logs = G_Employee_Attendance_Finder_V2::findAllNoTimeOutByToday($date, $date, $device_id, $a->getProjectSiteId());

        self::updateAttendanceByLogsTimeOutStaggerV2($logs, $a);
    }

    /*
    * Updates the attendance via Logs
    * Usage: 
    *   $logs = G_Attendance_Log_Finder::findAllByPeriod('2014-10-01', '2014-10-10');
    *   G_Attendance_Helper::updateAttendanceByLogs($logs);
    *
    * @param $logs Returned values from G_Attendance_Log_Finder
    * @return void
    */
    public static function updateAttendanceByLogs($logs)
    {
        foreach ($logs as $l) {
            $timesheets[$l->getEmployeeId()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
        }
        
        $tr = new G_Timesheet_Raw_Filter($timesheets);
        
        $tr->filterAndUpdateAttendance();
    }

    public static function updateAttendanceByLogsTimeInStaggerV2($logs, $a)
    {
        foreach ($logs as $l) {
            $timesheets[$l->getEmployeeId()][$l->getTimeIn()][$l->getTimeOut()] = $l->getDate();
        }
        
        $tr = new G_Timesheet_Raw_Filter_V2($timesheets);
        $error_message = "multiple in";
        $tr->filterAndUpdateAttendanceTimeInStagger($a, $error_message);
    }

    public static function updateAttendanceByLogsTimeOutStaggerV2($logs, $a)
    {
        foreach($logs as $timesheet){
            $logsProjectSiteId = $timesheet->getProjectSiteId();
            $logsId = $timesheet->getId();
        }
        if($logs){
            if($logsProjectSiteId == $a->getProjectSiteId()){
                $tr = new G_Timesheet_Raw_Filter_V2($logs);
                $tr->filterAndUpdateAttendanceTimeOutStagger($a, $logsId, null);
            }else if($logsProjectSiteId != $a->getProjectSiteId()){
                $error_message = "incomplete logs / projectsite: " . $a->getProjectSiteId();
                $tr = new G_Timesheet_Raw_Filter_V2($logs);
                $tr->filterAndUpdateAttendanceTimeOutStagger($a, $logsId, $error_message);
            }
        }else{
            foreach ($logs as $l) {
                $timesheets[$l->getEmployeeId()][$l->getType()][$l->getDate()][$l->getTime()] = $l->getDate();
            }
            
            $tr = new G_Timesheet_Raw_Filter_V2($timesheets);
            $error_message = "multiple out";
            $tr->filterAndUpdateAttendanceTimeInStagger($a, $error_message);
        }
        

    }

    public static function updateAttendanceByLogsV2($logs, $a)
    {
        foreach ($logs as $l) {
            $timesheets[$l->getEmployeeId()][$l->getTimeIn()][$l->getTimeOut()][$l->getProjectSiteId()] = $l->getDate();
        }

        $tr = new G_Timesheet_Raw_Filter_V2($timesheets);
        $tr->filterAndUpdateAttendanceTimeOutStagger($a);
    }

    public static function importTimesheet($file)
    {
        error_reporting(1);

        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);

        $time = new Timesheet_Raw_Converter_IM($file);
        $raw_timesheet = $time->convert();

        /* $logs = new G_Attendance_Log();
        $logs->importData($file);           */

        $r = new G_Timesheet_Raw_Logger($raw_timesheet);
        $r->logTimesheet();

        $tr = new G_Timesheet_Raw_Filter($raw_timesheet);
        $tr->filterAndUpdateAttendance();


        /*$dates = $time->getInvolvedDates();
        $cutoffs = G_Cutoff_Period_Helper::getAllByDates($dates);
        foreach ($cutoffs as $c) {
            if ($c) {
                G_Attendance_Helper::updateAllNoAttendanceDateByPeriod($c->getStartDate(), $c->getEndDate());   
            }
        }*/
        //return $is_imported;
        return true;
    }

    public static function getAllEmployeeIdAndAttendanceDateByPeriod($date_start, $date_end)
    {
        $sql = "
            SELECT employee_id, date_attendance
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . "
            WHERE date_attendance >= " . Model::safeSql($date_start) . "
            AND date_attendance <= " . Model::safeSql($date_end) . "
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
    public static function getTotalHolidayLegalRestdayNightShiftHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getNightShiftHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialRestdayNightShiftHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isSpecial()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getNightShiftHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialRestdayOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

                $t = $a->getTimesheet();
                if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
                    $h = $a->getHoliday();
                    if ($h && $h->isSpecial()) {

                        $temp_total = $t->getRestDaySpecialOvertimeHours() + $t->getRestDaySpecialOvertimeExcessHours();
                        $total += $temp_total;
                    }
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidayLegalRestdayOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getRestDayLegalOvertimeHours() + $t->getRestDayLegalOvertimeExcessHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }


    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialRestdayHoursDepre122816($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

                $h = $a->getHoliday();
                if ($h && $h->isSpecial()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getTotalHoursWorked();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    public static function getTotalHolidaySpecialRestdayHours($attendance, $custom_ot = array())
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isSpecial()) {
                    $t = $a->getTimesheet();
                    if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

                        if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
                            $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
                            $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
                            $e = G_Employee_Finder::findById($a->getEmployeeId());
                            $day_type[] = "applied_to_restday";

                            $schedule['schedule_in']  = $timestamp_start;
                            $schedule['schedule_out'] = $timestamp_end;
                            $schedule['actual_in']    = $t->getTimeIn();
                            $schedule['actual_out']   = $t->getTimeOut();
                            $deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

                            if ($timestamp_start > $timestamp_end) {
                                $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                                $date_end   = date("Y-m-d", strtotime("+1 day", strtotime($a->getDate()))) . " " . $custom_ot[$a->getDate()]['end_time'];
                            } else {
                                $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                                $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
                            }
                            $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
                            if ($temp_total >= $t->totalHrsWorkedBaseOnSchedule()) {
                                $temp_total = $t->totalHrsWorkedBaseOnSchedule();
                            }

                            $total += $temp_total;
                        }
                    } else {

                        if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
                            $t = $a->getTimesheet();
                            $temp_total = $t->getTotalHoursWorked();
                            $total += $temp_total;
                        }
                    }
                }
            }
        }
        return $total;
    }

    public static function getTotalHolidayLegalRestdayHours($attendance, $custom_ot = array())
    {

        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                      if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

                        if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
                            $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
                            $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
                            $e = G_Employee_Finder::findById($a->getEmployeeId());
                            $day_type[] = "applied_to_restday";

                            $schedule['schedule_in']  = $timestamp_start;
                            $schedule['schedule_out'] = $timestamp_end;
                            $schedule['actual_in']    = $t->getTimeIn();
                            $schedule['actual_out']   = $t->getTimeOut();
                            $deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

                            if ($timestamp_start > $timestamp_end) {
                                $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                                $date_end   = date("Y-m-d", strtotime("+1 day", strtotime($a->getDate()))) . " " . $custom_ot[$a->getDate()]['end_time'];
                            } else {
                                $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                                $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
                            }
                            $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
                            if ($temp_total >= $t->totalHrsWorkedBaseOnSchedule()) {
                                $temp_total = $t->totalHrsWorkedBaseOnSchedule();
                            }

                            $total += $temp_total;
                        }
                    } else {

                        if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {
                            $t = $a->getTimesheet();
                            $temp_total = $t->getTotalHoursWorked();
                            $total += $temp_total;
                        }
                    }
                }
            }
        }
        return $total;
    }

    public static function getTotalValidCetaSeaNumberOfDaysDailyEmployee($attendance)
    {
        $total = 0;
        $offset_hrs_worked = false;
        foreach ($attendance as $a) {
            $is_valid = false;
            $date = $a->getDate();

            //if ( $a->isPresent() || ($a->isRestday() && $a->isPresent()) || $a->isLeave() ) {                            

            /*if ( $a->isPresent() && !$a->isRestday() && !$a->isLeave() && !$a->isHoliday() ) {
                //echo "Date : {$date}<br>";
                $is_valid = true;
            }*/

            if ($a->isPresent() && $a->isPaid() && !$a->isLeave() && !$a->isRestday()) {
                $is_valid = true;
            }

            $holiday_type = $a->getHolidayType();

            if (!empty($holiday_type) && $holiday_type == 2) {
                $is_valid = false;
            }

            if ($is_valid) {
                if ($a->isOfficialBusiness()) {
                    $total++;
                } else {
                    $t = $a->getTimesheet();
                    if ($t) {
                        //$required_hrs_work = $t->getTotalScheduleHours();                    
                        $required_hrs_work = 8;
                        if ($offset_hrs_worked) {
                            $total_hrs_worked  = $required_hrs_work;
                        } else {
                            $total_hrs_worked  = $t->getTotalHoursWorked() + $t->getTotalOvertimeHours();
                        }
                        $date_in  = $t->getDateIn();
                        $date_out = $t->getDateOut();

                        //echo "Req Hrs Wrk : {$required_hrs_work} / Total Hrs Wrk : {$total_hrs_worked} Date In : {$date_in} / Date Out: {$date_out}<br>";

                        if ($total_hrs_worked >= $required_hrs_work) {
                            $total++;
                        }
                    }
                }
            }
        }

        return $total;
    }

    public static function getTotalValidCetaSeaNumberOfDays($attendance)
    {
        $total = 0;
        $offset_hrs_worked = false;
        foreach ($attendance as $a) {
            $is_valid = false;
            $date = $a->getDate();
            //if ( $a->isPresent() || ($a->isRestday() && $a->isPresent()) || $a->isLeave() ) {                            

            /*if ( $a->isPresent() && !$a->isRestday() && !$a->isLeave() && !$a->isHoliday() ) {
                //echo "Date : {$date}<br>";
                $is_valid = true;
            }*/

            if ($a->isPresent() && $a->isPaid() && !$a->isLeave()) {
                //echo "Date : {$date}<br>";
                $is_valid = true;
            }

            $holiday_type = $a->getHolidayType();

            if (!empty($holiday_type) && $holiday_type == 2) {
                $is_valid = false;
            }

            //$h = $a->getHoliday();      
            //if ($h && $h->isLegal() && $a->isPresent()) {                    
            //    $is_valid = true;
            //    $offset_hrs_worked = true;
            //}

            if ($is_valid) {
                if ($a->isOfficialBusiness()) {
                    $total++;
                } else {
                    $t = $a->getTimesheet();
                    if ($t) {
                        //$required_hrs_work = $t->getTotalScheduleHours();                    
                        $required_hrs_work = 8;
                        if ($offset_hrs_worked) {
                            $total_hrs_worked  = $required_hrs_work;
                        } else {
                            $total_hrs_worked  = $t->getTotalHoursWorked() + $t->getTotalOvertimeHours();
                        }
                        $date_in  = $t->getDateIn();
                        $date_out = $t->getDateOut();

                        //echo "Req Hrs Wrk : {$required_hrs_work} / Total Hrs Wrk : {$total_hrs_worked} Date In : {$date_in} / Date Out: {$date_out}<br>";

                        if ($total_hrs_worked >= $required_hrs_work) {
                            $total++;
                        }
                    }
                }
            }
        }

        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidayLegalNightShiftHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getNightShiftHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialNightShiftHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isSpecial()) {
                    $t = $a->getTimesheet();
                    $temp_total = $t->getNightShiftHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidayLegalOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                //$holiday = $a->getHoliday();
                //if ($holiday) {
                if ($a->getHolidayType() == G_Holiday::LEGAL) {
                    $t = $a->getTimesheet();
                    //$temp_total = $t->getOvertimeHours();
                    $temp_total = $t->getLegalOvertimeHours() + $t->getLegalOvertimeExcessHours();
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
    public static function getTotalHolidaySpecialOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                //$holiday = $a->getHoliday();
                //if ($holiday) {
                if ($a->getHolidayType() == G_Holiday::SPECIAL) {
                    $t = $a->getTimesheet();
                    //$temp_total = $t->getOvertimeHours();
                    $temp_total = $t->getSpecialOvertimeHours() + $t->getSpecialOvertimeExcessHours();
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
    public static function getTotalHolidaySpecialOvertimeExcessHours($attendance)
    {
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

    public static function getTotalHolidayLegalHours($attendance, $custom_ot = array())
    {
        $total = 0;
        $temp_total = 0;
        $deductible_breaktime = 0;
        foreach ($attendance as $a) {

            if ($a->isPresent() && $a->isHoliday() && !$a->isOfficialBusiness()) {

                if ($a->getHolidayType() == G_Holiday::LEGAL && !$a->isRestday()) {

                    $t = $a->getTimesheet();
                    if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

                        $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
                        $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

                        $e = G_Employee_Finder::findById($a->getEmployeeId());
                        $day_type[]               = "applied_to_legal_holiday";

                        //$schedule['schedule_in']  = $timestamp_start; // $t->getScheduledDateIn();
                        //$schedule['schedule_out'] = $timestamp_end; // $t->getScheduledDateOut();

                        $schedule['schedule_in']  = $t->getScheduledDateIn() . " " .  $t->getScheduledTimeIn();
                        $schedule['schedule_out'] = $t->getScheduledDateOut() . " " .  $t->getScheduledTimeOut();

                        $schedule['actual_in']    = $t->getTimeIn();
                        $schedule['actual_out']   = $t->getTimeOut();
                        $deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);


                        if ($timestamp_start > $timestamp_end) {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                            $date_end   = date("Y-m-d", strtotime("+1 day", strtotime($a->getDate()))) . " " . $custom_ot[$a->getDate()]['end_time'];
                        } else {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                            $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
                        }

                        $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
                        //$temp_total = Tools::newComputeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;

                        if ($temp_total >= $t->totalHrsWorkedBaseOnSchedule()) {
                            $temp_total = $t->totalHrsWorkedBaseOnSchedule();
                        }
                    } else {
                        $temp_total = $t->getTotalHoursWorked();
                        //$temp_total = $t->totalHrsWorkedBaseOnSchedule();

                    }
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialHours($attendance, $custom_ot = array())
    {
        $total = 0;
        foreach ($attendance as $a) {
            //if ($a->isPresent() && $a->isHoliday()) {
            //if ($a->isPresent() && $a->isHoliday() && !$a->isRestday() && !$a->isOfficialBusiness()) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                //$holiday = $a->getHoliday();
                //if ($holiday) {
                if ($a->getHolidayType() == G_Holiday::SPECIAL) {
                    $attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());
                    $breaks = $a->groupTimesheetData()['breaktime'];
                    $breakhours = 0;
                    if (!$attendance_breaks) {
                        foreach ($breaks as $break) {
                            $tarray = explode(" to ", $break);
                            $breakhours += Tools::computeHoursDifferenceByDateTime(date("H:i:s", strtotime($tarray[0])), date("H:i:s", strtotime($tarray[1])));
                        }
                    } else {
                        $breakhours = $t->getTotalDeductibleBreaktimeHours();
                    }
                    if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
                        $t = $a->getTimesheet();

                        //if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

                        $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
                        $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
                        if ($timestamp_start > $timestamp_end) {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                            $date_end   = date("Y-m-d", strtotime("+1 day " . $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
                        } else {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                            $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
                        }

                        $ceiling_hours_worked_holiday_special_amount = 0;
                        $ceiling_hours_worked_holiday_special_amount = ($t->totalHrsWorkedBaseOnSchedule() / 2) + $t->getTotalDeductibleBreaktimeHours();

                        $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

                        if ($temp_total > $t->totalHrsWorkedBaseOnSchedule()) {
                            $temp_total = $t->totalHrsWorkedBaseOnSchedule() - $breakhours;
                        } elseif (($temp_total >= $ceiling_hours_worked_holiday_special_amount) && ($temp_total <= $t->totalHrsWorkedBaseOnSchedule())) {
                            $temp_total = $temp_total - $breakhours;
                        } else {
                            $temp_total = $temp_total;
                        }

                        //}            

                    } else {
                        $t = $a->getTimesheet();

                        //if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

                        //$temp_total = $t->getTotalHoursWorked();
                        $temp_total = $t->totalHrsWorkedBaseOnSchedule() - $breakhours; //Return deducted breaktime                            

                        /*if($temp_total > 8) {
                                    $temp_total = 8;
                                }*/


                        if ($temp_total > $t->totalHrsWorkedBaseOnSchedule()) {
                            $temp_total = $t->totalHrsWorkedBaseOnSchedule() - $breakhours;;
                        }

                        //}

                    }

                    $total += $temp_total;
                }
                //}
            }
        }
        return $total;
    }

    // public static function getTotalHolidaySpecialHours($attendance, $custom_ot = array()) {
    //     $total = 0;
    //     foreach ($attendance as $a) {
    //         //if ($a->isPresent() && $a->isHoliday()) {
    //         //if ($a->isPresent() && $a->isHoliday() && !$a->isRestday() && !$a->isOfficialBusiness()) {
    //         if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {    
    //             //$holiday = $a->getHoliday();
    //             //if ($holiday) {
    //                 if ($a->getHolidayType() == G_Holiday::SPECIAL) {
    //                     if( !empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time']) ){
    //                         $t = $a->getTimesheet();

    //                         //if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

    //                             $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
    //                             $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
    //                             if( $timestamp_start > $timestamp_end ){
    //                                 $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
    //                                 $date_end   = date("Y-m-d",strtotime("+1 day " . $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
    //                             }else{
    //                                 $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
    //                                 $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
    //                             }       

    //                             $ceiling_hours_worked_holiday_special_amount = 0;
    //                             $ceiling_hours_worked_holiday_special_amount = ( $t->totalHrsWorkedBaseOnSchedule() / 2 ) + $t->getTotalDeductibleBreaktimeHours();

    //                             $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);                            

    //                             if($temp_total > $t->totalHrsWorkedBaseOnSchedule()) {
    //                                 $temp_total = $t->totalHrsWorkedBaseOnSchedule();
    //                             }elseif( ($temp_total >= $ceiling_hours_worked_holiday_special_amount) && ($temp_total <= $t->totalHrsWorkedBaseOnSchedule() ) ) {
    //                                 $temp_total = $temp_total - $t->getTotalDeductibleBreaktimeHours();
    //                             } else {
    //                                 $temp_total = $temp_total;
    //                             }                

    //                         //}            

    //                     }else{
    //                         $t = $a->getTimesheet();

    //                         //if($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

    //                             //$temp_total = $t->getTotalHoursWorked();
    //                             $temp_total = $t->totalHrsWorkedBaseOnSchedule() + $t->getTotalDeductibleBreaktimeHours(); //Return deducted breaktime                            

    //                             /*if($temp_total > 8) {
    //                                 $temp_total = 8;
    //                             }*/  

    //                             if($temp_total > $t->totalHrsWorkedBaseOnSchedule()) {
    //                                 $temp_total = $t->totalHrsWorkedBaseOnSchedule();
    //                             } 

    //                         //}

    //                     }

    //                     $total += $temp_total;
    //                 }
    //             //}
    //         }
    //     }
    //     return $total;
    // }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidayLegalOvertimeExcessHours($attendance)
    {
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

    public static function getTotalHolidayLegalNightShiftOvertimeExcessHours($attendance)
    {
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
    public static function getTotalHolidayLegalNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                    $temp_total = (float) $t->getLegalOvertimeNightShiftHours() + $t->getLegalOvertimeNightShiftExcessHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalHolidaySpecialNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isSpecial()) {
                    $t = $a->getTimesheet();
                    $temp_total = (float) $t->getSpecialOvertimeNightShiftHours() + $t->getSpecialOvertimeNightShiftExcessHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    public static function getTotalHolidaySpecialRestDayNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

                $t = $a->getTimesheet();
                if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

                    $h = $a->getHoliday();
                    if ($h && $h->isSpecial()) {

                        $temp_total = (float) $t->getRestDaySpecialOvertimeNightShiftHours() + $t->getRestDaySpecialOvertimeNightShiftExcessHours();
                        $total += $temp_total;
                    }
                }
            }
        }
        return $total;
    }

    public static function getTotalHolidayLegalRestDayNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
                $h = $a->getHoliday();
                if ($h && $h->isLegal()) {
                    $t = $a->getTimesheet();
                    $temp_total = (float) $t->getRestDayLegalOvertimeNightShiftHours() + $t->getRestDayLegalOvertimeNightShiftExcessHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    public static function getTotalHolidaySpecialNightShiftOvertimeExcessHours($attendance)
    {
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
    public static function getTotalRestDayNightShiftHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
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
    public static function getTotalRestDayNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
                if ($t) {
                    $temp_total = (float) $t->getRestDayOvertimeNightShiftHours() + $t->getRestDayOvertimeNightShiftExcessHours();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalRestDayNightShiftOvertimeExcessHours($attendance)
    {
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

    public static function countPresentRegularDaysWithPayWithHolidayPresent($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isPaid()) {
                $count++;
            }
        }
        return $count;
    }

    public static function countPresentRegularDaysOnlyWithPay($attendance)
    {
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
    public static function getTotalRestDayOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness() && $t) {
                $temp_total = (float) $t->getRestDayOvertimeHours() + $t->getRestDayOvertimeExcessHours();
                $total = $total + $temp_total;
            }
        }
        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalRestDayOvertimeExcessHours($attendance)
    {
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
    public static function getTotalRestDayHours($attendance, $custom_ot = array())
    {
        $total      = 0;
        $temp_total = 0;

        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '')) {
                $t = $a->getTimesheet();
                $temp_total = 0;

                if (!empty($t) && $t->getTotalHoursWorked() > 0) {

                    // $temp_total = $t->getTotalHoursWorked();    
                    if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_RESTDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

                        $timestamp_start = $custom_ot[$a->getDate()]['start_time'];
                        $timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
                        if ($timestamp_start > $timestamp_end) {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];

                            $date = new DateTime($a->getDate());
                            $date->modify('+1 day');
                            $date_end = $date->format('Y-m-d');
                            $date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
                        } else {
                            $date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
                            $date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
                        }
                        $temp_total = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

                        $day_type = array();
                        if ($a->isRestday()) {
                            $day_type[] = "applied_to_restday";
                        } else {
                            $day_type[] = "applied_to_regular_day";
                        }

                        $schedule['schedule_in']  = $t->getScheduledDateIn() . " " . $t->getScheduledTimeIn();
                        $schedule['schedule_out'] = $t->getScheduledDateOut() . " " . $t->getScheduledTimeOut();
                        $schedule['actual_in']    = $t->getTimeIn();
                        $schedule['actual_out']   = $t->getTimeOut();
                        $e = new G_Employee();
                        $e->setId($a->getEmployeeId());
                        $deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);
                        $breaktime_details        = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);
                        if ($temp_total > $deductible_breaktime) {

                            $break_details = explode(" to ", $breaktime_details[0]);

                            $actual_timein  = $t->getTimeIn();
                            $breaktime_in   = substr($break_details[0], 0, -3);
                            $breaktime_out  = substr($break_details[1], 0, -3);

                            $breaktime_in_24_format  = '';
                            $breaktime_out_24_format = '';

                            if (strtotime($actual_timein) <= strtotime($breaktime_in)) {
                                $breaktime_in_24_format  = date("H:i:s", strtotime($break_details[0]));
                                $breaktime_out_24_format = date("H:i:s", strtotime($break_details[1]));

                                if (Tools::isTimeMorning($t->getTimeIn()) && Tools::isTimeBetweenHours($t->getTimeOut(), $breaktime_in_24_format, $breaktime_out_24_format)) {
                                    $temp_total = 4.50;
                                } else {
                                    $temp_total = $temp_total - $deductible_breaktime;
                                }
                            } else {
                                $total_schedule_hours_plus_break = $t->getTotalScheduleHours() + $t->getTotalDeductibleBreaktimeHours();
                                if ($temp_total >= $t->getTotalScheduleHours()) {
                                    $temp_total = $temp_total - $deductible_breaktime;
                                } else {
                                    $temp_total = $temp_total;
                                }
                                //$temp_total = $temp_total - $deductible_breaktime;
                            }

                            $total += $temp_total;
                            //$temp_total = $temp_total - $deductible_breaktime;
                        }
                    } else {


                        $temp_total = $t->totalHrsWorkedBaseOnSchedule();

                        if (strtotime($t->getTimeIn()) > strtotime($t->getScheduledTimeIn())) {
                            $in_diff = Tools::computeHoursDifferenceByDateTime($t->getScheduledTimeIn(), $t->getTimeIn());
                            $temp_total -= $in_diff;

                            $total += $temp_total;
                        }
                    }
                    //echo "Date : " . $a->getDate() . "/ Total : {$temp_total} <Br />";                   
                }
            }
        }

        return $total;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function getTotalRegularHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            //if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) { 
            if ($a->isPresent() && !$a->isRestday()) { // March 4, 2015
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
    public static function getTotalLateHours($attendance)
    {
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
    public static function getTotalOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
                $t = $a->getTimesheet();
                $temp_total = (float) $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours();
                $total = $total + $temp_total;
            }
        }
        return $total;
    }

    //new

    public static function getCutTotalOvertimeHours($attendance)
    {
        $total = 0;

        foreach ($attendance as $a) {
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 01", strtotime($date));
            $date_end = date("M 10", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {

                if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
                    $t = $a->getTimesheet();
                    $temp_total = (float) $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours() - $t->getRegularOvertimeNightShiftHours();
                    $total = $total + $temp_total;
                }
            }
        }
        return $total;
    }

    public static function getPrevTotalOvertimeHours($attendance)
    {
        $total = 0;

        foreach ($attendance as $a) {

            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 25", strtotime($date));
            $date_end = date("M t", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {

                if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
                    $t = $a->getTimesheet();
                    $temp_total = (float) $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours() - $t->getRegularOvertimeNightShiftHours();
                    $total = $total + $temp_total;
                }
            }
        }
        return $total;
    }
    //new

    public static function getTotalOvertimeExcessHours($attendance)
    {
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
    public static function getTotalUndertimeHours($attendance)
    {
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
    public static function getTotalHoursWorked($attendance)
    {
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
    public static function getTotalNightShiftHours($attendance)
    {
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
    public static function getTotalRegularNightShiftHours($attendance)
    {
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
    public static function getTotalNightShiftOvertimeHours($attendance)
    {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
                $t = $a->getTimesheet();
                $temp_total = (float) $t->getRegularOvertimeNightShiftHours() + $t->getRegularOvertimeNightShiftExcessHours();
                $total += $temp_total;
            }
        }
        return $total;
    }

    public static function getTotalNightShiftOvertimeExcessHours($attendance)
    {
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
    public static function countPresentDays($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent()) {
                $count++;
            }elseif(!$a->isPresent() && $a->isPaid()){
                $count++;
            }
        }
        return $count;
    }

    // public static function countActualPresentDays($attendance) {
    //     $count = 0;
    //     foreach ($attendance as $a) {
    //         if ($a->isPresent()) {
    //             $count++;   
    //         }
    //     }
    //     return $count;
    // }

    public static function countPresentRegularDaysWithPay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isPaid() && !$a->isHoliday()) {
                $count++;
            }
        }
        return $count;
    }

    public static function countPresentRegularDaysWithPayNoRestDay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isPaid() && !$a->isHoliday() && !$a->isRestday()) {
                $count++;
            }
        }
        return $count;
    }

    public static function countPresentRegularHolidayDaysWithPay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            //if ( $a->isPresent() && $a->isPaid() ) {
            if ($a->isPresent() && $a->isPaid() && !$a->isRestday() && !$a->isHoliday()) {
                $count++;
            } else {
                if ($a->getHolidayType() == G_Holiday::LEGAL) {
                    $employee_id   = $a->getEmployeeId();
                    $date          = $a->getDate();
                    $previous_date = date("Y-m-d", strtotime("-1 days", strtotime($date)));
                    $fields        = array("ea.is_present", "ea.is_paid", "ea.is_leave", "ea.is_ob");
                    $yesterday     = G_Attendance_Helper::sqlGetNearestRegularDay($employee_id, $date); //Get previous date attendance 
                    if ($yesterday['is_present']) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countPresentDaysWithPay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isPaid()) {
                $count++;
            }
        }
        return $count;
    }


    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countDaysLeaveWithPay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isLeave() && $a->isPaid()) {
                $count++;
            }
        }
        return $count;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countDaysPresentRestDay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isRestday()) {
                $count++;
            }
        }
        return $count;
    }

    public static function countDaysPresentHoliday($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent() && $a->isHoliday() || $a->isPaid() && $a->isHoliday()) {
                $count++;
            }
        }
        return $count;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countAbsentDaysWithPay($attendance)
    {
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
    public static function countAbsentDaysWithoutPay($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if (!$a->isPresent() && !$a->isPaid() && !$a->isRestday() && !$a->isLeave() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
                $count++;
            }
        }
        return $count;
    }

    public static function sqlCountAbsentDaysByEmployeeIdAndFromAndToDate($employee_id = 0, $from = '', $to = '')
    {
        $sql = "
            SELECT COALESCE(COUNT(id),0)AS total_absent
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " 
            WHERE  employee_id =" . Model::safeSql($employee_id) . "
                AND date_attendance BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
                AND (is_present = 0 AND is_restday = 0 AND is_holiday = 0 AND is_ob = 0 OR is_present = 0 AND is_restday = 1 AND is_leave = 1 AND is_holiday = 0 AND is_ob = 0)
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total_absent'];
    }

    public static function sqlCountAbsentDaysByDateRange($from = '', $to = '')
    {
        $sql = "
            SELECT COALESCE(COUNT(ea.id),0)AS total_absent
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
            WHERE ea.date_attendance BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
                AND (ea.is_present = 0 AND ea.is_restday = 0 AND ea.is_holiday = 0 AND ea.is_ob = 0 AND ea.is_leave = 0)
                AND e.employee_status_id = 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total_absent'];
    }

    public static function sqlSumTotalHrsWorkedByEmployeeIdAndDateRange($employee_id = 0, $from = '', $to = '')
    {
        $sql = "
            SELECT COALESCE(SUM(total_hours_worked),0)AS total
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " 
            WHERE  employee_id =" . Model::safeSql($employee_id) . "
                AND date_attendance BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "   
                AND is_holiday = 0             
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function sqlSumRestDayAndHolidayTotalHrsWorkedByEmployeeIdAndDateRange($employee_id = 0, $from = '', $to = '')
    {
        $sql = "
            SELECT COALESCE(SUM(total_hours_worked),0)AS total
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " 
            WHERE  employee_id =" . Model::safeSql($employee_id) . "
                AND date_attendance BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "   
                AND (is_holiday = 1 OR is_restday = 1)             
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function sqlCountLeaveDaysByEmployeeIdAndFromAndToDate($employee_id = 0, $from = '', $to = '')
    {
        $sql = "
            SELECT COALESCE(COUNT(id),0)AS total_absent
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " 
            WHERE  employee_id =" . Model::safeSql($employee_id) . "
                AND date_attendance BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
                AND is_leave = 1
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total_leave'];
    }
    //new
    public static function countPrevUndertimeHours($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 25", strtotime($date));
            $date_end = date("M t", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {

                $count += $t->getUndertimeHours();
            }
        }

        return $count;
    }
    public static function countCutUndertimeHours($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 01", strtotime($date));
            $date_end = date("M 10", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {
                $count += $t->getUndertimeHours();
            }
        }

        return $count;
    }

    public static function countPrevRegularLateHours($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 25", strtotime($date));
            $date_end = date("M t", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {
                $count += $t->getLateHours();
            }
        }

        return $count;
    }
    public static function countCutRegularLateHours($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $t = $a->getTimesheet();
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 01", strtotime($date));
            $date_end = date("M 10", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {
                $count += $t->getLateHours();
            }
        }

        return $count;
    }
    //new
    public static function countDaysWithoutPay($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            //$t = $a->getTimesheet();
            /*if (!$a->isPaid()) {
                $count++;
            }*/
            //if (!$a->isPaid() && !$a->isHoliday() && !$a->isRestday() ) {
            if (!$a->isHoliday() && !$a->isRestday()) {
                if ($a->isLeave()) {
                    $fields = array('apply_half_day_date_start', 'apply_half_day_date_end', 'is_paid');
                    $leave  = G_Employee_Leave_Request_Helper::sqlEmployeeLeaveRequestByDate($a->getEmployeeId(), $a->getDate(), $fields);

                    if (!empty($leave)) {
                        if (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'No') {
                            $count = $count + 0.5;
                        } elseif (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'Yes') {
                            if (!$a->isPresent() && $a->isPaid()) {
                                $count = $count + 0.5;
                            }
                        } else {
                            if (!$a->isPaid()) {
                                $count++;
                            }
                        }
                    }
                } else {
                    if (!$a->isPaid()) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
    //new

    public static function countPrevDaysWithoutPay($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 25", strtotime($date));
            $date_end = date("M t", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {
                if (!$a->isHoliday() && !$a->isRestday()) {
                    if ($a->isLeave()) {
                        $fields = array('apply_half_day_date_start', 'apply_half_day_date_end', 'is_paid');
                        $leave  = G_Employee_Leave_Request_Helper::sqlEmployeeLeaveRequestByDate($a->getEmployeeId(), $a->getDate(), $fields);

                        if (!empty($leave)) {
                            if (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'No') {
                                $count = $count + 0.5;
                            } elseif (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'Yes') {
                                if (!$a->isPresent() && $a->isPaid()) {
                                    $count = $count + 0.5;
                                }
                            } else {
                                if (!$a->isPaid()) {
                                    $count++;
                                }
                            }
                        }
                    } else {
                        if (!$a->isPaid()) {
                            $count++;
                        }
                    }
                }
            }
            //$t = $a->getTimesheet();
            /*if (!$a->isPaid()) {
                $count++;
            }*/
            //if (!$a->isPaid() && !$a->isHoliday() && !$a->isRestday() ) {

        }

        return $count;
    }

    public static function countCutDaysWithoutPay($attendance)
    {
        $count = 0;
        //Utilities::displayArray($attendance);
        foreach ($attendance as $a) {
            $date = date("M d", strtotime($a->getDate()));
            $date_start = date("M 1", strtotime($date));
            $date_end = date("M 10", strtotime($date));
            if (($date >= $date_start) && ($date <= $date_end)) {
                if (!$a->isHoliday() && !$a->isRestday()) {
                    if ($a->isLeave()) {
                        $fields = array('apply_half_day_date_start', 'apply_half_day_date_end', 'is_paid');
                        $leave  = G_Employee_Leave_Request_Helper::sqlEmployeeLeaveRequestByDate($a->getEmployeeId(), $a->getDate(), $fields);

                        if (!empty($leave)) {
                            if (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'No') {
                                $count = $count + 0.5;
                            } elseif (($leave['apply_half_day_date_start'] == 'Yes' || $leave['apply_half_day_date_end'] == 'Yes') && $leave['is_paid'] == 'Yes') {
                                if (!$a->isPresent() && $a->isPaid()) {
                                    $count = $count + 0.5;
                                }
                            } else {
                                if (!$a->isPaid()) {
                                    $count++;
                                }
                            }
                        }
                    } else {
                        if (!$a->isPaid()) {
                            $count++;
                        }
                    }
                }
            }
            //$t = $a->getTimesheet();
            /*if (!$a->isPaid()) {
                $count++;
            }*/
            //if (!$a->isPaid() && !$a->isHoliday() && !$a->isRestday() ) {

        }

        return $count;
    }
    //new
    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countOBDays($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isOfficialBusiness()) {
                $count++;
            }
        }
        return $count;
    }

    /*
        $attendance - array produced by G_Attendance_Finder
    */
    public static function countAbsentDays($attendance)
    {
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
    public static function countSuspendedDays($attendance)
    {
        $count = 0;
        foreach ($attendance as $a) {
            if ($a->isSuspended()) {
                $count++;
            }
        }
        return $count;
    }

    public static function computeNDHRS($arr)
    {
        $start_nd = '22:00:00';
        $end_nd   = '24:00:00';

        //echo '<pre>';
        //print_r($arr);

        if ($arr['s_date_in'] != $arr['s_date_out']) {
            if ($arr['a_time_in'] <= $start_nd) {
                $start_var = $start_nd;
            } else {
                $start_var = $arr['a_time_in'];
            }

            if ($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd) {
                $total_nd_hrs = Tools::computeHoursDifference($start_var, $arr['a_time_out']);
                //$total_nd_hrs = $arr['a_time_out'] - $start_var;
            } else {
                if (($arr['a_date_out'] == $arr['a_date_in']) && ($arr['a_time_out'] < $start_nd)) {
                    $total_nd_hrs = 0;
                } else {
                    $total_nd_hrs = Tools::computeHoursDifference($start_var, $end_nd);
                }
                //$total_nd_hrs = $end_nd - $start_var;
            }
        } else {
            if ($arr['s_time_out'] <= $end_nd && $arr['s_time_out'] >= $start_nd) {
                if ($arr['a_time_in'] <= $start_nd) {
                    $start_var = $start_nd;
                } else {
                    $start_var = $arr['a_time_in'];
                }

                if ($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd) {
                    $total_nd_hrs = Tools::computeHoursDifference($start_var, $arr['a_time_out']);
                    //$total_nd_hrs = $start_var - $arr['a_time_out'];
                } else {
                    $total_nd_hrs = Tools::computeHoursDifference($start_var, $end_nd);
                    //$total_nd_hrs = $start_var - $end_nd;
                }
            } else {
                if ($arr['a_time_out'] <= $end_nd && $arr['a_time_out'] >= $start_nd) {
                    if ($arr['a_time_in'] <= $start_nd) {
                        $start_var = $start_nd;
                    } else {
                        $start_var = $arr['a_time_in'];
                    }
                    $total_nd_hrs = Tools::computeHoursDifference($start_var, $arr['a_time_out']);
                    //$total_nd_hrs = $arr['a_time_out'] - $start_var;
                } else {
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
    public static function changeArrayKeyToDate($attendance)
    {
        $records = array();
        foreach ($attendance as $a) {
            $records[$a->getDate()] = $a;
        }
        return $records;
    }

    public static function findByDate($date, $order_by, $limit)
    {
        $sql = "
            SELECT 
                a.*,
                CONCAT(e.firstname, ' ', e.lastname) as employee_name,
                e.employee_code             
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a,g_employee e
            WHERE
                    a.date_attendance = " . Model::safeSql($date) . " AND 
                    e.id=a.employee_id
            " . $order_by . "
            " . $limit . "
        ";

        return Model::runSql($sql, true);
    }

    public static function findByDateAndEmployee(G_Employee $e, $date, $order_by, $limit)
    {
        $sql = "
            SELECT 
                a.*                             
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a
            WHERE
                    a.date_attendance = " . Model::safeSql($date) . " AND 
                    a.employee_id=" . Model::safeSql($e->getId()) . "
            " . $order_by . "
            " . $limit . "
        ";

        return Model::runSql($sql, true);
    }

    public static function sqlFindByDateAndEmployeeId($employee_id = 0, $date, $order_by, $limit)
    {
        $sql = "
            SELECT 
                a.*                             
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a
            WHERE
                    a.date_attendance = " . Model::safeSql($date) . " AND 
                    a.employee_id=" . Model::safeSql($employee_id) . "
            " . $order_by . "
            " . $limit . "
        ";

        return Model::runSql($sql, true);
    }

    public static function countTotalRecordsByDate($date)
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a, g_employee e
            WHERE
                    a.date_attendance = " . Model::safeSql($date) . " AND 
                    e.id=a.employee_id
        
        ";

        $total = Model::runSql($sql, true);
        return $total[0]['total'];
    }

    public static function sqlCountTotalWithUndertimeByFromAndToDate($from = '', $to = '')
    {
        $sql_from = date("Y-m-d", strtotime($from));
        $sql_to   = date("Y-m-d", strtotime($to));

        $sql = "
            SELECT COUNT(a.id) as total
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a, g_employee e
            WHERE
                    a.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
                    AND e.id=a.employee_id
                    AND ((a.undertime_hours <> 0 OR a.undertime_hours <> '' )
                        AND (STR_TO_DATE(CONCAT(a.scheduled_date_out, ' ', a.scheduled_time_out), '%Y-%m-%d %H:%i:%s') >
                        STR_TO_DATE(CONCAT(a.actual_date_out, ' ', a.actual_time_out), '%Y-%m-%d %H:%i:%s')))                    
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function sqlEmployeesWithUndertimeByFromAndToDate($from = '', $to = '')
    {
        $sql_from = date("Y-m-d", strtotime($from));
        $sql_to   = date("Y-m-d", strtotime($to));

        $sql = "
            SELECT CONCAT(e.firstname, ' ', e.lastname)AS employee_name, DATE_FORMAT(a.date_attendance,'%M %d, %Y') date_attendance, a.scheduled_time_in, a.scheduled_time_out,a.actual_time_in, a.actual_time_out,a.undertime_hours
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a, g_employee e
            WHERE
                    a.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
                    AND e.id=a.employee_id
                    AND ((a.undertime_hours <> 0 OR a.undertime_hours <> '' )
                        AND (STR_TO_DATE(CONCAT(a.scheduled_date_out, ' ', a.scheduled_time_out), '%Y-%m-%d %H:%i:%s') >
                        STR_TO_DATE(CONCAT(a.actual_date_out, ' ', a.actual_time_out), '%Y-%m-%d %H:%i:%s')))                    
        ";
        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlEmployeesWithEarlyInByFromAndToDate($from = '', $to = '')
    {
        $sql_from = date("Y-m-d", strtotime($from));
        $sql_to   = date("Y-m-d", strtotime($to));

        $sql = "
            SELECT CONCAT(e.firstname, ' ', e.lastname)AS employee_name, DATE_FORMAT(a.date_attendance,'%M %d, %Y') date_attendance, a.scheduled_time_in, a.scheduled_time_out,a.actual_time_in, a.actual_time_out
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a, g_employee e
            WHERE
                    a.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
                    AND e.id=a.employee_id
                    AND ((a.undertime_hours <> 0 OR a.undertime_hours <> '' )
                        AND (STR_TO_DATE(CONCAT(a.scheduled_date_in, ' ', a.scheduled_time_in), '%Y-%m-%d %H:%i:%s') >
                        STR_TO_DATE(CONCAT(a.actual_date_in, ' ', a.actual_time_in), '%Y-%m-%d %H:%i:%s')))                    
        ";
        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlEmployeesAttendanceWithIncorrectShiftByEmployeeIdAndDateRange($employee_ids = '', $date_from = '', $date_to = '', $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
            WHERE ea.employee_id IN({$employee_ids}) 
                AND ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
                AND ea.scheduled_date_out <> '' AND ea.actual_date_in <> ''
                AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s')
            ORDER BY ea.date_attendance ASC 
        ";

        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlEmployeesAttendanceWithIncorrectShiftByEmployeeIdAndDateRangeWithAddQuery($employee_ids = '', $date_from = '', $date_to = '', $fields = array(), $add_query = '')
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        $sql = "
            SELECT {$sql_fields},
            COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name, 
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name             
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''            
            WHERE ea.employee_id IN({$employee_ids}) 
                AND ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " {$sql_add_query} 
                AND ea.scheduled_date_out <> '' AND ea.actual_date_in <> ''
                AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s')
            ORDER BY ea.date_attendance ASC 
        ";

        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlAllWithIncorrectShiftByEmployeeIdAndDateRange($date_from = '', $date_to = '', $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
            WHERE (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND ea.scheduled_time_in != '' AND ea.scheduled_time_out != '' ) AND 
                (ea.is_present = 1 AND ea.is_restday = 1 AND (ea.scheduled_date_in = '' OR ea.scheduled_date_out = '' ) ) 
                OR
                (
                    (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . ") AND 
                    ea.scheduled_date_out <> '' AND ea.actual_date_in <> '' AND ea.scheduled_time_in != '' AND ea.scheduled_time_out != '' 
                    AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s')
                )
            ORDER BY ea.date_attendance ASC 
        ";

        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlAllWithIncorrectShiftByEmployeeIdAndDateRangeWithAddQuery($date_from = '', $date_to = '', $fields = array(), $add_query = '')
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        $sql = "
            SELECT {$sql_fields}, 
            (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
            COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
            COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position                             
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea LEFT JOIN " . EMPLOYEE . " e ON ea.employee_id = e.id 
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " {$sql_add_query} ) AND 
                (ea.is_present = 1 AND ea.is_restday = 1 AND (ea.scheduled_date_in = '' OR ea.scheduled_date_out = '' ) AND ea.is_ob != 1 ) 
                OR
                (
                    (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . ") AND 
                    ea.scheduled_date_out <> '' AND ea.actual_date_in <> ''
                    AND STR_TO_DATE(CONCAT(ea.scheduled_date_out, ' ', ea.scheduled_time_out), '%Y-%m-%d %H:%i:%s') <= STR_TO_DATE(CONCAT(ea.actual_date_in, ' ', ea.actual_time_in), '%Y-%m-%d %H:%i:%s')             
                )
                OR
                (
                    ea.is_present = 0 AND 
                    (ea.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . ") 
                    AND (ea.actual_time_in != '' AND ea.actual_time_out != '')
                )
            ORDER BY ea.date_attendance ASC 
        ";

        //echo $sql;

        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function sqlCountTotalEarlyInByFromAndToDate($from = '', $to = '')
    {
        $sql_from = date("Y-m-d", strtotime($from));
        $sql_to   = date("Y-m-d", strtotime($to));

        $sql = "
            SELECT COUNT(a.id) as total
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a, g_employee e
            WHERE
                    a.date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
                    AND e.id=a.employee_id
                    AND STR_TO_DATE(CONCAT(a.scheduled_date_in, ' ', a.scheduled_time_in), '%Y-%m-%d %H:%i:%s') >
                        STR_TO_DATE(CONCAT(a.actual_date_in, ' ', a.actual_time_in), '%Y-%m-%d %H:%i:%s')
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function getDatesByEmployeeNumberAndWeekNumber(G_Employee $e, $week_number)
    {
        $sql = "
            SELECT id, employee_id, date_attendance, WEEK( a.date_attendance ) AS week_number,is_present,is_paid,is_restday
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " a
            WHERE WEEK(a.date_attendance) = " . Model::safeSql($week_number) . " AND a.employee_id = " . Model::safeSql($e->getId()) . "
        ";
        //echo $sql;    
        $result = Model::runSql($sql, true);
        return $result;
    }

    public static function countAttendanceAbsenceData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }


        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }


        /*$sql = "
            SELECT e.employee_code, e.lastname, e.firstname,    
                ejh.name AS position_name,  
                esh.name AS department_name,        
                ea.date_attendance, COUNT(ea.is_present) AS total_absent 
                
            FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE_V2 . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh        
            WHERE (ea.employee_id = e.id) 
                AND (e.id = esh.employee_id AND esh.end_date = '')
                AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
                AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
                AND(ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND is_leave = 0 AND ea.is_restday = 0)              
                " . $search . "
            GROUP BY e.employee_code
        ";  */

        // OR ea.leave_id = 10  -> GENERAL LEAVE counted as absent
        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, 
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,   
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,                    
                ea.date_attendance, COUNT(ea.is_present) AS total_absent, ea.project_site_id,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND (ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND ea.is_leave = 0 AND ea.is_restday = 0 AND ea.is_ob = 0 OR ea.leave_id = 10)
                " . $search . " 
            GROUP BY e.employee_code
        ";

        //var_dump($sql);exit;

        $result = Model::runSql($sql);
        $counter = 0;
        while ($row = Model::fetchAssoc($result)) {
            $return[$counter]['employee_code']  = $row['employee_code'];
            $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
            $return[$counter]['position']       = $row['position_name'];
            $return[$counter]['department']     = $row['department_name'];
            $return[$counter]['total_absent']   = $row['total_absent'];
            $return[$counter]['section_name']   = $row['section_name'];
            $return[$counter]['project_site_id']   = $row['project_site_id'];
            $counter++;
        }
        return $return;
    }

    public static function getAttendanceAbsenceData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }



        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        /*$sql = "
            SELECT e.employee_code, e.lastname, e.firstname,    
                ejh.name AS position_name,      
                esh.name AS department_name,    
                ea.date_attendance
                
            FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE_V2 . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh    
            WHERE (ea.employee_id = e.id) 
                AND (e.id = esh.employee_id AND esh.end_date = '')
                AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
                AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
                AND(ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND is_leave = 0 AND ea.is_restday = 0)              
                " . $search . "
            ORDER BY ea.date_attendance DESC
        ";  */

        $sql = "
            SELECT e.id AS emp_id, e.employee_code, e.lastname, e.firstname, ea.project_site_id, 
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,                
                ea.date_attendance, IF(ea.scheduled_time_in <> '' AND ea.scheduled_time_out <> '',CONCAT(ea.scheduled_time_in, ' to ', ea.scheduled_time_out), 'No schedule')AS scheduled_time,ea.leave_id, 
                (SELECT name FROM g_leave WHERE id = ea.leave_id)AS leave_type,                
                is_ob, is_paid,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND ea.is_present = 0 AND ea.is_holiday = 0 AND ea.is_restday = 0 AND ea.is_ob = 0
                " . $search . "
            ORDER BY ea.date_attendance DESC 
        ";

        $result = Model::runSql($sql);

        $counter = 0;
        while ($row = Model::fetchAssoc($result)) {

            $return[$counter]['employee_code']  = $row['employee_code'];
            $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
            $return[$counter]['department']     = $row['department_name'];
            $return[$counter]['section_name']   = $row['section_name'];
            $return[$counter]['position']       = $row['position_name'];
            $return[$counter]['date_attendance'] = $row['date_attendance'];
            $return[$counter]['scheduled_time'] = $row['scheduled_time'];
            $return[$counter]['emp_id']         = $row['emp_id'];
             $return[$counter]['project_site_id']         = $row['project_site_id'];

            $_leave = G_Employee_Leave_Request_Finder::findByEmployeeIdAndLeaveDate($row['emp_id'], $row['date_attendance']);

            if ($row['is_ob']) {
                $return[$counter]['remarks'] = 'OB';
            } elseif ($row['leave_id'] > 0) {
                if ($row['is_paid'] == 1) {
                    $return[$counter]['remarks'] = $row['leave_type'] . " (With Pay)";
                } else {
                    $return[$counter]['remarks'] = $row['leave_type'] . " (Without Pay)";
                }
            } elseif ($_leave) {
                $l = G_leave_Finder::findById($_leave->getLeaveId());

                if ($_leave->getIsApproved() != "Approved") {
                    $return[$counter]['remarks'] = 'Absent (Wholeday)';
                } else {

                    if ($l->getName()) {
                        $return[$counter]['remarks'] = $l->getName();
                    } else {
                        $return[$counter]['remarks'] = 'Leave';
                    }
                }
            } else {
                //Check fp logs
                $fields = array('time', 'type');
                $fp = G_Fp_Attendance_Logs_Helper::sqlGetEmployeeLogsByUserIdAndDate($row['emp_id'], $row['date_attendance']);
                $is_with_in  = false;
                $is_with_out = false;
                if (!empty($fp)) {
                    foreach ($fp as $log) {
                        if ($log['type'] == 'in' && $log['time'] != '') {
                            $is_with_in = true;
                        } elseif ($log['type'] == 'out' && $log['time'] != '') {
                            $is_with_out = true;
                        }
                    }

                    if (!$is_with_in) {
                        $return[$counter]['remarks'] = 'No In';
                    } elseif (!$is_with_out) {
                        $return[$counter]['remarks'] = 'No Out';
                    } else {
                        //$return[$counter]['remarks'] = 'No In and Out';
                        $return[$counter]['remarks'] = 'Absent (Wholeday)';
                    }
                } else {
                    //$return[$counter]['remarks'] = 'No In and Out';
                    $return[$counter]['remarks'] = 'Absent (Wholeday)';
                }
            }
            $counter++;
        }

        return $return;
    }

    public static function getAttendanceHalfdayData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT  e.id AS emp_id, e.employee_code, e.lastname, e.firstname, 
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,                
                ea.date_attendance, IF(ea.scheduled_time_in <> '' AND ea.scheduled_time_out <> '',CONCAT(ea.scheduled_time_in, ' to ', ea.scheduled_time_out), 'No schedule')AS scheduled_time,ea.leave_id, 
                (SELECT name FROM g_leave WHERE id = ea.leave_id)AS leave_type,                
                is_ob, ea.is_paid AS employee_is_paid,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN g_employee_leave_request gelr on gelr.date_start = ea.date_attendance
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_TAGS . " et ON e.id = et.employee_id
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                AND (ea.total_hours_worked >= 3 AND ea.total_hours_worked <= 5.60)
                {$sql_add_query}
                AND ea.is_holiday = 0 AND ea.is_restday = 0 AND ea.is_ob = 0
                " . $search . "
                AND gelr.apply_half_day_date_start = 'Yes' 
            ORDER BY ea.date_attendance DESC
        ";
        echo $sql;
        $result = Model::runSql($sql);
        $counter = 0;

        while ($row = Model::fetchAssoc($result)) {

            $_leave = G_Employee_Leave_Request_Finder::findByEmployeeIdAndLeaveDateHalfday($row['emp_id'], $row['date_attendance']);
            if ($_leave) {
                $return[$counter]['employee_code']  = $row['employee_code'];
                $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
                $return[$counter]['department']     = $row['department_name'];
                $return[$counter]['section_name']   = $row['section_name'];
                $return[$counter]['position']       = $row['position_name'];
                $return[$counter]['date_attendance'] = $row['date_attendance'];
                $return[$counter]['scheduled_time'] = $row['scheduled_time'];
                $return[$counter]['emp_id']         = $row['emp_id'];

                $l = G_leave_Finder::findById($_leave->getLeaveId());

                if ($_leave->getIsApproved() != "Approved") {
                    $return[$counter]['remarks'] = 'Absent (Halfday)';
                } elseif ($_leave->getIsApproved() == "Approved" && $_leave->getIsPaid() == 'Yes') {
                    $return[$counter]['remarks'] = $l->getName() . ' (Halfday With Pay)';
                } elseif ($_leave->getIsApproved() == "Approved" && $_leave->getIsPaid() == 'No') {
                    $return[$counter]['remarks'] = $l->getName() . ' (Halfday Without Pay)';
                } else {
                    if ($l->getName()) {
                        $return[$counter]['remarks'] = $l->getName() . '(Halfday)';
                    } else {
                        $return[$counter]['remarks'] = 'Halfday';
                    }
                }
            } else {
                //if halfday and not filing leave
                $return[$counter]['employee_code']  = $row['employee_code'];
                $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
                $return[$counter]['department']     = $row['department_name'];
                $return[$counter]['section_name']   = $row['section_name'];
                $return[$counter]['position']       = $row['position_name'];
                $return[$counter]['date_attendance'] = $row['date_attendance'];
                $return[$counter]['scheduled_time'] = $row['scheduled_time'];
                $return[$counter]['emp_id']         = $row['emp_id'];
                $return[$counter]['remarks']        = 'Absent (Halfday)';
            }

            $counter++;
        }

        return $return;
    }
    public static function getAttendanceHalfdayDataDistinct($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT DISTINCT  e.id AS emp_id, e.employee_code, e.lastname, e.firstname, 
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,                
                ea.date_attendance, IF(ea.scheduled_time_in <> '' AND ea.scheduled_time_out <> '',CONCAT(ea.scheduled_time_in, ' to ', ea.scheduled_time_out), 'No schedule')AS scheduled_time,ea.leave_id, 
                (SELECT name FROM g_leave WHERE id = ea.leave_id)AS leave_type,                
                is_ob, ea.is_paid AS employee_is_paid,
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN g_employee_leave_request gelr on gelr.date_start = ea.date_attendance
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_TAGS . " et ON e.id = et.employee_id
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                AND (ea.total_hours_worked >= 3 AND ea.total_hours_worked <= 5.60)
                {$sql_add_query}
                AND ea.is_holiday = 0 AND ea.is_restday = 0 AND ea.is_ob = 0
                " . $search . "
                AND gelr.apply_half_day_date_start = 'Yes' 
            ORDER BY ea.date_attendance DESC
        ";

        $result = Model::runSql($sql);
        $counter = 0;

        while ($row = Model::fetchAssoc($result)) {

            $_leave = G_Employee_Leave_Request_Finder::findByEmployeeIdAndLeaveDateHalfday($row['emp_id'], $row['date_attendance']);
            if ($_leave) {
                $return[$counter]['employee_code']  = $row['employee_code'];
                $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
                $return[$counter]['department']     = $row['department_name'];
                $return[$counter]['section_name']   = $row['section_name'];
                $return[$counter]['position']       = $row['position_name'];
                $return[$counter]['date_attendance'] = $row['date_attendance'];
                $return[$counter]['scheduled_time'] = $row['scheduled_time'];
                $return[$counter]['emp_id']         = $row['emp_id'];

                $l = G_leave_Finder::findById($_leave->getLeaveId());

                if ($_leave->getIsApproved() != "Approved") {
                    $return[$counter]['remarks'] = 'Absent (Halfday)';
                } elseif ($_leave->getIsApproved() == "Approved" && $_leave->getIsPaid() == 'Yes') {
                    $return[$counter]['remarks'] = $l->getName() . ' (Halfday With Pay)';
                } elseif ($_leave->getIsApproved() == "Approved" && $_leave->getIsPaid() == 'No') {
                    $return[$counter]['remarks'] = $l->getName() . ' (Halfday Without Pay)';
                } else {
                    if ($l->getName()) {
                        $return[$counter]['remarks'] = $l->getName() . '(Halfday)';
                    } else {
                        $return[$counter]['remarks'] = 'Halfday';
                    }
                }
            } else {
                //if halfday and not filing leave
                $return[$counter]['employee_code']  = $row['employee_code'];
                $return[$counter]['name']           = $row['lastname'] . ", " . $row['firstname'];
                $return[$counter]['department']     = $row['department_name'];
                $return[$counter]['section_name']   = $row['section_name'];
                $return[$counter]['position']       = $row['position_name'];
                $return[$counter]['date_attendance'] = $row['date_attendance'];
                $return[$counter]['scheduled_time'] = $row['scheduled_time'];
                $return[$counter]['emp_id']         = $row['emp_id'];
                $return[$counter]['remarks']        = 'Absent (Halfday)';
            }

            $counter++;
        }

        return $return;
    }

    public static function getTardinessData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        /* $sql = "
            SELECT e.employee_code, e.lastname, e.firstname,    
                ejh.name AS position_name,      
                esh.name AS department_name,    
                ea.date_attendance, ea.scheduled_time_in, ea.scheduled_time_out, ea.actual_time_in, ea.actual_time_out, ea.late_hours 
                
            FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE_V2 . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh    
            WHERE (ea.employee_id = e.id) 
                AND (e.id = esh.employee_id AND esh.end_date = '')
                AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
                AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
                AND(ea.late_hours <> '')                
                " . $search . "
            ORDER BY ea.date_attendance DESC
        ";      */

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, ea.project_site_id,
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, 
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,                
                ea.date_attendance, ea.scheduled_time_in, ea.scheduled_time_out, 
                ea.actual_time_in, ea.actual_time_out, ea.late_hours
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND ea.late_hours <> '' 
                " . $search . "
            ORDER BY ea.date_attendance DESC 
        ";

        $result = Model::runSql($sql);
        $counter = 0;
        while ($row = Model::fetchAssoc($result)) {
            $d = $row['date_attendance'];
            $return[$d][]     = array(
                'employee_code'     => $row['employee_code'],
                'name'              => $row['lastname'] . ", " . $row['firstname'],
                'department'        => $row['department_name'],
                'section_name'      => $row['section_name'],
                'position'          => $row['position'],
                'employee_status'   => $row['employee_status'],
                'date_attendance'   => $row['date_attendance'],
                'scheduled_time_in' => $row['scheduled_time_in'],
                'scheduled_time_out' => $row['scheduled_time_out'],
                'actual_time_in'    => $row['actual_time_in'],
                'actual_time_out'   => $row['actual_time_out'],
                'late_hours'        => $row['late_hours'],
                 'project_site_id'        => $row['project_site_id']
            );
            $counter++;
        }

        return $return;
    }

    public static function getTardinessWithBreakLogsData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, 
                COALESCE(ejh.name,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS position, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, 
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,                
                ea.date_attendance, ea.scheduled_time_in, ea.scheduled_time_out, 
                ea.actual_time_in, ea.actual_time_out, ea.late_hours,
                ebl.total_late_break_in_hrs, ebl.log_break1_out, ebl.log_break1_in, ebl.log_break2_out, ebl.log_break2_in, ebl.log_break3_out, ebl.log_break3_in,
                ea.id as employee_attendance_id
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_BREAK_LOGS_SUMMARY . " ebl ON ea.id = ebl.employee_attendance_id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND (ea.late_hours <> '' || ebl.total_late_break_in_hrs > 0)
                " . $search . "
            ORDER BY ea.date_attendance DESC 
        ";

        $result = Model::runSql($sql);
        $counter = 0;

        $default_break_logs_headers = array(
            G_Employee_Break_Logs::TYPE_B1_OUT => 'Break1 OUT',
            G_Employee_Break_Logs::TYPE_B1_IN => 'Break1 IN',
            G_Employee_Break_Logs::TYPE_B2_OUT => 'Break2 OUT',
            G_Employee_Break_Logs::TYPE_B2_IN => 'Break2 IN',
            G_Employee_Break_Logs::TYPE_B3_OUT => 'Break3 OUT',
            G_Employee_Break_Logs::TYPE_B3_IN => 'Break3 IN'
        );
        $display_break_logs_headers = array();

        while ($row = Model::fetchAssoc($result)) {
            $d = $row['date_attendance'];
            $late_hours = $row['late_hours'] + $row['total_late_break_in_hrs'];
            $return[$d][]     = array(
                'employee_code'         => $row['employee_code'],
                'name'                  => $row['lastname'] . ", " . $row['firstname'],
                'department'            => $row['department_name'],
                'section_name'          => $row['section_name'],
                'position'              => $row['position'],
                'employee_status'       => $row['employee_status'],
                'date_attendance'       => $row['date_attendance'],
                'scheduled_time_in'     => $row['scheduled_time_in'],
                'scheduled_time_out'    => $row['scheduled_time_out'],
                'actual_time_in'        => $row['actual_time_in'],
                'actual_time_out'       => $row['actual_time_out'],
                'late_hours'            => $late_hours,
                'employee_break_logs'   => array(
                    G_Employee_Break_Logs::TYPE_B1_OUT  => $row['log_break1_out'],
                    G_Employee_Break_Logs::TYPE_B1_IN  => $row['log_break1_in'],
                    G_Employee_Break_Logs::TYPE_B2_OUT  => $row['log_break2_out'],
                    G_Employee_Break_Logs::TYPE_B2_IN  => $row['log_break2_in'],
                    G_Employee_Break_Logs::TYPE_B3_OUT  => $row['log_break3_out'],
                    G_Employee_Break_Logs::TYPE_B3_IN  => $row['log_break3_in']
                )
            );

            $employee_break_logs = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($row['employee_attendance_id']);

            if ($employee_break_logs) {
                $available_break_types = G_Employee_Break_Logs_Summary_Helper::getAvailableBreakTypes($employee_break_logs);

                foreach ($available_break_types as $key => $available_break_type) {
                    if ($default_break_logs_headers[$available_break_type]) {
                        $display_break_logs_headers[$available_break_type] = $default_break_logs_headers[$available_break_type];
                    }
                }
            }

            $counter++;
        }

        $total_late = $row['total_late'] + $row['total_late_break_in_hrs'];

        return array(
            'records'                         => $return,
            'display_break_logs_headers'     => $display_break_logs_headers
        );
    }

    public static function countTardinessData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

         if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        /*$sql = "
            SELECT e.employee_code, e.lastname, e.firstname,    
                ejh.name AS position_name,  
                esh.name AS department_name,        
                ea.date_attendance, CAST(SUM(ea.late_hours) AS DECIMAL(7,2)) AS total_late 
                
            FROM " . EMPLOYEE . " e, " . G_EMPLOYEE_ATTENDANCE_V2 . " ea, " . G_EMPLOYEE_JOB_HISTORY . " ejh, " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh        
            WHERE (ea.employee_id = e.id) 
                AND (e.id = esh.employee_id AND esh.end_date = '')
                AND (ea.employee_id = ejh.employee_id AND ejh.end_date ='')
                AND (ea.date_attendance >= " . Model::safeSql($query['date_from']) . " AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . ")
                AND(ea.late_hours <> '')                
                " . $search . "
            GROUP BY e.employee_code
        ";*/

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, ea.project_site_id,   
                COALESCE(ejh.name,(
                    SELECT name FROM `g_employee_job_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC 
                    LIMIT 1
                ))AS position_name,
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,                       
                ea.date_attendance, COUNT(ea.id) AS total_number_lates, CAST(SUM(ea.late_hours) AS DECIMAL(7,2)) AS total_late 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND ea.late_hours <> '' 
                " . $search . "
            GROUP BY e.employee_code
        ";


        $result = Model::runSql($sql);
        $counter = 0;
        while ($row = Model::fetchAssoc($result)) {
            $return[$counter]['employee_code']     = $row['employee_code'];
            $return[$counter]['name']              = $row['lastname'] . ", " . $row['firstname'];
            $return[$counter]['department']        = $row['department_name'];
            $return[$counter]['section_name']      = $row['section_name'];
            $return[$counter]['position']          = $row['position_name'];
            $return[$counter]['employee_status']   = $row['employee_status'];
            $return[$counter]['total_late']        = number_format($row['total_late'], 2);
            $return[$counter]['total_number_lates'] = $row['total_number_lates'];
            $return[$counter]['project_site_id'] = $row['project_site_id'];
            $counter++;
        }
        return $return;
    }

    public static function countTardinessWithBreakLogsData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname,    
                COALESCE(ejh.name,(
                    SELECT name FROM `g_employee_job_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC 
                    LIMIT 1
                ))AS position_name,
                (SELECT es.name FROM `g_settings_employee_status` es WHERE es.id = e.employee_status_id ORDER BY es.id DESC LIMIT 1 )AS employee_status, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,                       
                ea.date_attendance, COUNT(ea.id) AS total_number_lates, CAST(SUM(ea.late_hours) AS DECIMAL(7,2)) AS total_late,
                CAST(SUM(ea.total_late_break_in_hrs) AS DECIMAL(7,2)) AS total_late_break 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_BREAK_LOGS_SUMMARY . " ebl ON ea.id = ebl.employee_attendance_id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON e.id = ejh.employee_id AND ejh.end_date = ''
            WHERE ea.date_attendance >= " . Model::safeSql($query['date_from']) . "
                AND ea.date_attendance <= " . Model::safeSql($query['date_to']) . "
                {$sql_add_query}
                AND (ea.late_hours <> '' || ebl.total_late_break_in_hrs > 0)
                " . $search . "
            GROUP BY e.employee_code
        ";


        $result = Model::runSql($sql);
        $counter = 0;
        while ($row = Model::fetchAssoc($result)) {
            $total_late = $row['total_late'] + $row['total_late_break'];
            $return[$counter]['employee_code']     = $row['employee_code'];
            $return[$counter]['name']              = $row['lastname'] . ", " . $row['firstname'];
            $return[$counter]['department']        = $row['department_name'];
            $return[$counter]['section_name']      = $row['section_name'];
            $return[$counter]['position']          = $row['position_name'];
            $return[$counter]['employee_status']   = $row['employee_status'];
            $return[$counter]['total_late']        = number_format($total_late, 2);
            $return[$counter]['total_number_lates'] = $row['total_number_lates'];
            $counter++;
        }
        return $return;
    }

    public static function sqlEmployeeAttendanceByDate($employee_id = 0, $date = '', $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea 
            WHERE ea.date_attendance =" . Model::safeSql($date) . " AND ea.employee_id =" . Model::safeSql($employee_id) . " AND ea.is_restday = 0
            ORDER BY ea.id DESC
            LIMIT 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
    }

    public static function sqlEmployeeAttendanceByDateAndEmployeeId($employee_id = 0, $date = '', $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea 
            WHERE ea.date_attendance =" . Model::safeSql($date) . " AND ea.employee_id =" . Model::safeSql($employee_id) . "
            ORDER BY ea.id DESC
            LIMIT 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
    }

    public static function sqlGetNearestRegularDay($employee_id = 0, $date = '', $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = "*";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea 
            WHERE ea.date_attendance < " . Model::safeSql($date) . " AND ea.employee_id =" . Model::safeSql($employee_id) . " 
                AND ea.is_restday = 0
                AND ea.is_holiday = 0
            ORDER BY ea.date_attendance DESC
            LIMIT 1
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
    }

    public static function sqlIsDateRdByEmployeeIdAndDate($employee_id = 0, $date = '')
    {
        $is_restday = false;
        $sql = "
            SELECT ea.is_restday
            FROM " . G_EMPLOYEE_ATTENDANCE_V2 . " ea 
            WHERE ea.date_attendance =" . Model::safeSql($date) . " AND ea.employee_id =" . Model::safeSql($employee_id) . "             
            ORDER BY ea.id DESC
            LIMIT 1
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if ($row['is_restday'] == 1) {
            $is_restday = true;
        }

        return $is_restday;
    }

    public static function changeArrayKeyToDateConstructed($attendance)
    {
        $records = array();
        $prev_dateout="";
        $prev_timeout="";
        foreach ($attendance as $a) {
            if ($a->isPresent() == 1) {
                $records[$a->getDate()] = $a;
            } else {
                $date_attendance = $a->getDate();
                $employee_id = $a->getEmployeeId();
                $e = G_Employee_Finder::findById($employee_id);
                if ($e) {
                    $fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate2($e->getEmployeeCode(), $date_attendance);
                    if ($fp_logs) {
                        if ($fp_logs->getType() == 'in') {
                            $time_in = $fp_logs->getTime();
                            $t = $a->getTimesheet();
                            $t->setTimeIn($time_in);
                            $a->setTimesheet($t);
                        } else { 

                            $time_out = $fp_logs->getTime();

                            $time_out2 = explode(':',$time_out);
                            $time_out2[2] = '00';
                            $time_out = implode(":", $time_out2);

                            $t = $a->getTimesheet();
                            //Prevent from viewing previos timeout
                            if(($a->getTimeSheet()->getTimeOut() !==  $prev_timeout) &&  ($prev_dateout!==$a->getTimeSheet()->getDateOut())  && $time_out !== $prev_timeout){
                                $t->setTimeOut($time_out);
                                $a->setTimesheet($t);
                            }

                            
                        }
                    }
                }
            }

            $records[$a->getDate()] = $a;

            $prev_timeout = $a->getTimeSheet()->getTimeOut();
            $prev_dateout = $a->getTimeSheet()->getDateOut();
        }
        return $records;
    }

    public static function countAttendancePerfectDataDepre($query, $add_query = '')
    {
        $query_late_minutes = $query['late_minutes'];
        //Convert minutes to numeric
        $numeric_minutes = number_format($query_late_minutes / 60, 2);

        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
                ejh.employment_status AS employment_status,
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,   
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,
                ea.late_hours as late_hours,
                SUM(ea.is_present = 0) as total_absent,
                SUM(ea.undertime_hours) as undertime_hours, 
                SUM(ea.late_hours) as late_hours 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''                
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
            WHERE ea.date_attendance >= " . Model::safeSql($query['perfect_date_from']) . " 
                AND ea.date_attendance <= " . Model::safeSql($query['perfect_date_to']) . "
                {$sql_add_query}
                AND ea.is_restday = 0
                AND ea.is_holiday = 0 
                AND ea.total_schedule_hours > 0
                " . $search . " 
                GROUP BY ea.employee_id
        ";

        $result = Model::runSql($sql, true);

        $counter = 0;
        foreach ($result as $result_key => $result_data) {
            $total_late_undertime_minutes = $result_data['late_hours'] + $result_data['undertime_hours'];
            if (($result_data['total_absent']) == 0 && $total_late_undertime_minutes <= $numeric_minutes) {
                $return[$counter]['total_tardi']        = $total_late_undertime_minutes;
                $return[$counter]['employee_code']      = $result_data['employee_code'];
                $return[$counter]['lastname']           = $result_data['lastname'];
                $return[$counter]['firstname']          = $result_data['firstname'];
                $return[$counter]['section_name']       = $result_data['section_name'];
                $return[$counter]['employment_status']  = $result_data['employment_status'];
                $return[$counter]['position_name']      = $result_data['position_name'];
                $return[$counter]['department_name']    = $result_data['department_name'];
                $counter++;
            }
        }

        return $return;
    }

    /**
     * List of employees with perfect attendance
     *
     * @param string from
     * @param string to
     * @return array
     */
    public static function perfectAttendanceDataByDateRange($from = '', $to = '')
    {
        $sql = "

            SELECT e.id, e.employee_code, e.lastname, e.firstname,e.hired_date, 
                            COALESCE(`ejh`.`name`,(
                            SELECT name FROM `g_employee_job_history`
                            WHERE employee_id = e.id

                                AND end_date <> ''
                            ORDER BY end_date DESC 
                            LIMIT 1
                            ))AS `position_name`,   
                            COALESCE(`esh`.`name`,(
                                SELECT name FROM `g_employee_subdivision_history`
                                WHERE employee_id = e.id 
                                    AND end_date <> ''
                                ORDER BY end_date DESC
                                LIMIT 1
                            ))AS `department_name`,
                            ea.late_hours as late_hours,
                            SUM(ea.is_present = 0) as total_absent,
                            SUM(ea.undertime_hours) as undertime_hours, 
                            SUM(ea.is_leave) as is_leave, 
                            SUM(ea.late_hours) as late_hours 
                         FROM " . EMPLOYEE . " e
                            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
                        WHERE 
                              
                                e.employment_status_id IN ( 1, 3 )
                                AND (ea.date_attendance >= " . Model::safeSql($from) . " AND ea.date_attendance <= " . Model::safeSql($to) . ")
                                AND ea.is_restday = 0
                                AND ea.is_holiday = 0 
                                AND (ea.leave_id <> 8 AND ea.leave_id >= 0) 
                            GROUP BY ea.employee_id
            ORDER BY e.lastname ASC
        ";

        $result = Model::runSql($sql, true);

        $counter = 0;
        foreach ($result as $result_key => $result_data) {
            if (($result_data['total_absent']) == 0 && ($result_data['late_hours'] == 0) && ($result_data['undertime_hours'] == 0) && ($result_data['is_leave'] == 0)) {

                $date_hired = $result_data['hired_date'];
                $date_hired = strtotime($date_hired);
                $date_hired_plus1year = strtotime('+ 1 year', $date_hired);
                if ($date_hired_plus1year <= strtotime(date("Y-m-d"))) { // 

                    $return[$counter]['id']                 = $result_data['id'];
                    $return[$counter]['employee_code']      = $result_data['employee_code'];
                    $return[$counter]['hired_date']     = $result_data['hired_date'];
                    $return[$counter]['lastname']           = $result_data['lastname'];
                    $return[$counter]['firstname']          = $result_data['firstname'];
                    $return[$counter]['position_name']      = $result_data['position_name'];
                    $return[$counter]['department_name']    = $result_data['department_name'];
                    $counter++;
                }
            }
        }

        return $return;
    }

    public static function perfectAttendanceDataByDateRangeAndEmployeeId($from = '', $to = '', $eid)
    {
        $employee_id = $eid;
        $sql = "

            SELECT e.id, e.employee_code, e.lastname, e.firstname, 
                            COALESCE(`ejh`.`name`,(
                            SELECT name FROM `g_employee_job_history`
                            WHERE employee_id = e.id 
                                AND end_date <> ''
                            ORDER BY end_date DESC 
                            LIMIT 1
                            ))AS `position_name`,   
                            COALESCE(`esh`.`name`,(
                                SELECT name FROM `g_employee_subdivision_history`
                                WHERE employee_id = e.id 
                                    AND end_date <> ''
                                ORDER BY end_date DESC
                                LIMIT 1
                            ))AS `department_name`,
                            ea.late_hours as late_hours,
                            SUM(ea.is_present = 0) as total_absent,
                            SUM(ea.undertime_hours) as undertime_hours, 
                            SUM(ea.is_leave) as is_leave, 
                            SUM(ea.late_hours) as late_hours 
                         FROM " . EMPLOYEE . " e
                            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
                        WHERE 
                                e.employment_status_id IN ( 1, 3 )
                                AND (ea.date_attendance >= " . Model::safeSql($from) . " AND ea.date_attendance <= " . Model::safeSql($to) . ")
                                AND ea.is_restday = 0
                                AND ea.is_holiday = 0 
                                AND (ea.leave_id <> 8 AND ea.leave_id >= 0) 
                                AND e.id = " . Model::safeSql($employee_id) . " 
                            GROUP BY ea.employee_id
            ORDER BY e.lastname ASC
        ";

        $result = Model::runSql($sql, true);

        $counter = 0;
        foreach ($result as $result_key => $result_data) {
            if (($result_data['total_absent']) == 0 && ($result_data['late_hours'] == 0) && ($result_data['undertime_hours'] == 0) && ($result_data['is_leave'] == 0)) {
                $return[$counter]['id']                 = $result_data['id'];
                $return[$counter]['employee_code']      = $result_data['employee_code'];
                $return[$counter]['lastname']           = $result_data['lastname'];
                $return[$counter]['firstname']          = $result_data['firstname'];
                $return[$counter]['position_name']      = $result_data['position_name'];
                $return[$counter]['department_name']    = $result_data['department_name'];
                $counter++;
            }
        }

        return $return;
    }

    public static function perfectAttendanceDataByMonthAndEmployeeId($from = '', $to = '', $eid)
    {
        $employee_id = $eid;
        $sql = "

            SELECT e.id, e.employee_code, e.lastname, e.firstname, 
                            COALESCE(`ejh`.`name`,(
                            SELECT name FROM `g_employee_job_history`
                            WHERE employee_id = e.id 
                                AND end_date <> ''
                            ORDER BY end_date DESC 
                            LIMIT 1
                            ))AS `position_name`,   
                            COALESCE(`esh`.`name`,(
                                SELECT name FROM `g_employee_subdivision_history`
                                WHERE employee_id = e.id 
                                    AND end_date <> ''
                                ORDER BY end_date DESC
                                LIMIT 1
                            ))AS `department_name`,
                            ea.late_hours as late_hours,
                            SUM(ea.is_present = 0) as total_absent,
                            SUM(ea.undertime_hours) as undertime_hours, 
                            SUM(ea.is_leave) as is_leave, 
                            SUM(ea.late_hours) as late_hours 
                         FROM " . EMPLOYEE . " e
                            LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''
                            LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON ea.employee_id = ejh.employee_id AND ejh.end_date = ''
                        WHERE 
                                e.employment_status_id IN ( 1, 3 )
                                AND (ea.date_attendance >= " . Model::safeSql($from) . " AND ea.date_attendance <= " . Model::safeSql($to) . ")
                                AND ea.is_restday = 0
                                AND ea.is_holiday = 0 
                                AND (ea.leave_id <> 8 AND ea.leave_id >= 0) 
                                AND e.id = " . Model::safeSql($employee_id) . " 
                            GROUP BY ea.employee_id
            ORDER BY e.lastname ASC
        ";

        $result = Model::runSql($sql, true);

        $counter = 0;
        foreach ($result as $result_key => $result_data) {
            if (($result_data['total_absent']) == 0 && ($result_data['late_hours'] == 0) && ($result_data['undertime_hours'] == 0) && ($result_data['is_leave'] == 0)) {
                /*
                $return[$counter]['id']                 = $result_data['id'];
                $return[$counter]['employee_code']      = $result_data['employee_code'];    
                $return[$counter]['lastname']           = $result_data['lastname'];
                $return[$counter]['firstname']          = $result_data['firstname'];
                $return[$counter]['position_name']      = $result_data['position_name'];
                $return[$counter]['department_name']    = $result_data['department_name'];*/
                $counter++;
            }
        }

        return $return = $counter;
    }


    public static function countAttendancePerfectData($query, $add_query = '')
    {
        $query_late_minutes = $query['late_minutes'];
        //Convert minutes to numeric
        $numeric_minutes = number_format($query_late_minutes / 60, 2);

        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        if ($query['project_site_id'] != '' && $query['project_site_id'] != 'all') {
            $search .= " AND ea.project_site_id =" . Model::safeSql($query['project_site_id']);
        }

        /*$sql = "
            SELECT e.employee_code, e.lastname, e.firstname, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
                ejh.employment_status AS employment_status,
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,   
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,
                ea.late_hours as late_hours,
                SUM(ea.is_present = 0 AND ea.is_paid = 0) as total_absent,
                SUM(ea.undertime_hours) as undertime_hours, 
                SUM(ea.late_hours) as late_hours,
                SUM(ea.leave_id = 8) as birthday_leave  
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''                
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
            WHERE ea.date_attendance >= " . Model::safeSql($query['perfect_date_from']) . " 
                AND ea.date_attendance <= " . Model::safeSql($query['perfect_date_to']) . "
                {$sql_add_query}
                AND ea.is_restday = 0
                AND ea.is_holiday = 0
                AND ea.is_paid    = 1 
                AND ea.total_schedule_hours > 0
                " . $search . " 
                GROUP BY ea.employee_id
        ";*/

        $sql = "
            SELECT e.employee_code, e.lastname, e.firstname, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
                ejh.employment_status AS employment_status,ea.project_site_id,
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,   
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,
                ea.late_hours as late_hours,
                SUM(ea.is_present = 0 AND ea.is_paid = 0 AND ea.is_holiday = 0 AND ea.is_restday = 0) as total_absent,
                SUM(ea.undertime_hours) as undertime_hours, 
                SUM(ea.late_hours) as late_hours,
                SUM(ea.leave_id = 8) as birthday_leave,
                SUM(ea.leave_id <> 8 AND ea.leave_id <> 0) as sum_leave 
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''                
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
            WHERE ea.date_attendance >= " . Model::safeSql($query['perfect_date_from']) . " 
                AND ea.date_attendance <= " . Model::safeSql($query['perfect_date_to']) . "
                {$sql_add_query}               
                AND ea.total_schedule_hours > 0
                " . $search . " 
                GROUP BY ea.employee_id
        ";

        $result = Model::runSql($sql, true);

        $counter      = 0;
        $total_absent = 0;
        foreach ($result as $result_key => $result_data) {
            $total_late_undertime_minutes = $result_data['late_hours'] + $result_data['undertime_hours'];
            $total_absent = ($result_data['total_absent'] + $result_data['sum_leave']);

            if (($total_absent) <= 0 && $total_late_undertime_minutes <= $numeric_minutes) {
                $return[$counter]['total_tardi']        = $total_late_undertime_minutes;
                $return[$counter]['employee_code']      = $result_data['employee_code'];
                $return[$counter]['lastname']           = $result_data['lastname'];
                $return[$counter]['firstname']          = $result_data['firstname'];
                $return[$counter]['section_name']       = $result_data['section_name'];
                $return[$counter]['employment_status']  = $result_data['employment_status'];
                $return[$counter]['position_name']      = $result_data['position_name'];
                $return[$counter]['department_name']    = $result_data['department_name'];
                $return[$counter]['project_site_id']    = $result_data['project_site_id'];
                $counter++;
            }
        }

        return $return;
    }

    public static function getActualHoursData($query, $add_query = '')
    {
        $sql_add_query = '';
        if ($add_query != '') {
            $sql_add_query = $add_query;
        }

        if ($query['search_field'] != '' && $query['search_field'] != 'all') {
            $search = "AND e." . $query['search_field'] . "=" . Model::safeSql($query['search']);
        }

        if ($query['position_applied'] != '' && $query['position_applied'] != 'all') {
            $search .= " AND ejh.job_id =" . Model::safeSql($query['position_applied']);
        }

        if ($query['department_applied'] != '' && $query['department_applied'] != 'all') {
            $search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);
        }

        $sql = "
            SELECT e.id,e.employee_code, e.lastname, e.firstname, 
                (SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
                ejh.employment_status AS employment_status,
                COALESCE(`ejh`.`name`,(
                SELECT name FROM `g_employee_job_history`
                WHERE employee_id = e.id 
                    AND end_date <> ''
                ORDER BY end_date DESC 
                LIMIT 1
                ))AS `position_name`,   
                COALESCE(`esh`.`name`,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS `department_name`,
                ea.date_attendance,
                ea.total_schedule_hours,
                ea.total_hours_worked,
                ea.total_overtime_hours,
                ea.undertime_hours,
                ea.late_hours,
                ea.is_holiday,
                ea.is_restday,
                ea.scheduled_time_in,
                ea.scheduled_time_out,
                ea.actual_time_in,
                ea.actual_time_out,
                ea.total_breaktime_deductible_hours
            FROM " . EMPLOYEE . " e
                LEFT JOIN " . G_EMPLOYEE_ATTENDANCE_V2 . " ea ON ea.employee_id = e.id
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''                
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
            WHERE ea.date_attendance >= " . Model::safeSql($query['actual_hours_date_from']) . " 
                AND ea.date_attendance <= " . Model::safeSql($query['actual_hours_date_to']) . "
                {$sql_add_query}
                AND ea.total_schedule_hours > 0
                " . $search . " 
              
                ORDER by ea.date_attendance, e.firstname
        ";
        // echo $sql;
        // echo $sql   AND employee_code = 6470  AND ea.employee_id = 141;

        $result = Model::runSql($sql, true);

        $data = array();
        foreach ($result as $key => $value) {
            $break_deduction = 0;

            $eid = $value['id'];

            $e    = G_Employee_Finder::findById($eid);
            $a    = G_Attendance_Finder::findByEmployeeAndDate($e, $value['date_attendance']);
            // var_dump($a);
            $schedule['schedule_in']  = $value['scheduled_time_in'];
            $schedule['schedule_out'] = $value['scheduled_time_out'];
            $day_type = array();
            $is_holiday = $a->isHoliday();

            if ($is_holiday == 1 && !empty($is_holiday)) {
                $h = $a->holiday;

                if ($h->getType() == Holiday::LEGAL) {
                    $day_type[] = "applied_to_legal_holiday";
                } else {
                    $day_type[] = "applied_to_special_holiday";
                }
            } elseif ($a->isRestday() == 1) {

                if ($value['total_schedule_hours'] > 0) {
                    $day_type[] = "applied_to_restday";
                } else {
                    $day_type[] = "applied_to_regular_day";
                }
            } else {
                $day_type[] = "applied_to_regular_day";
            }


            $break = G_Employee_Breaktime_Finder::findByEmployeeIdAndDate($eid, $value['date_attendance']);
            $break_time_schedules = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);

            //echo $break_time_schedules;
            //14:20:00 IN
            //17:09:00
            $seperate_breaktime_schedule = (explode("to", $break_time_schedules[0]));
            $break_time_in = date('H:i:s', strtotime($seperate_breaktime_schedule[0]));
            $break_time_out = date('H:i:s', strtotime($seperate_breaktime_schedule[1]));
            // $value['actual_time_in'] = "8:"
            // $value['actual_time_out'] =

            if (($value['actual_time_out'] > $break_time_in && $value['actual_time_out'] < $break_time_out)) {

                if (($value['actual_time_out'] > $break_time_in && $value['actual_time_out'] < $break_time_out)) {
                    $time1 = strtotime($value['actual_time_out']);
                    $time2 = strtotime($break_time_out);
                    $difference = round(abs($time2 - $time1) / 3600, 2);
                    $break_deduction = $difference;
                } else {
                    $break_deduction = 0;
                }
            } else {
                $break_deduction = 0;
                if (($break_time_in >= $value['actual_time_in'] && $break_time_in <= $value['actual_time_out']) && ($break_time_out >= $value['actual_time_in'] && $break_time_out <= $value['actual_time_out'])) {
                    $break_deduction = 0;
                } elseif (($value['actual_time_in'] > $break_time_in && $value['actual_time_in'] < $break_time_out)) {
                    $time1 = strtotime($value['actual_time_in']);
                    $time2 = strtotime($break_time_out);
                    $difference = round(abs($time2 - $time1) / 3600, 2);
                    $difference = round(abs($time2 - $time1) / 3600, 2);
                    $break_deduction = $difference;
                }
            }

            $total_hours_worked = explode(".", $value['total_hours_worked']);

            $hours = $total_hours_worked[0];
            $minutes = $total_hours_worked[1];


            $n = $value['total_hours_worked'];
            $whole = floor($n);      // 1
            $get_minutes_dec = $n - $whole;
            if (($hours) >= 8) {

                if ($get_minutes_dec < .25) {

                    $get_minutes_dec = 0;
                } elseif ($minutes < .50) {
                    $get_minutes = .25;
                } elseif ($get_minutes_dec < .75) {
                    $get_minutes = .50;
                } else {
                    $get_minutes = .75;
                }
                $get_total_hours_worked = $hours + $get_minutes;
            } else {

                $get_total_hours_worked = $value['total_hours_worked'];
            }


            // echo $total_hours_worked;
            // echo $hours = date('H:i',strtotime($thw));

            // echo $hours;


            // if( ($break_time_in >= $value['actual_time_in'] && $break_time_in <= $value['actual_time_out']) && ($break_time_out >= $value['actual_time_in'] && $break_time_out <= $value['actual_time_out'])  ){
            //          // echo $break_hours_deduction = $value['total_breaktime_deductible_hours'];
            //  echo "full";
            // }
            // elseif (( $value['actual_time_in'] > $break_time_in && $value['actual_time_in'] < $break_time_out )) {
            //     echo "betweend breaktime in";
            //  }
            //  elseif( ($value['actual_time_out'] > $break_time_in && $value['actual_time_out'] < $break_time_out)  ){
            //      // time out - breakout
            //       echo "betweend breaktime out";   
            //  }

            //  else{
            //     echo  $break_hours_deduction = 0;
            //  }




            //  echo "<pre>";
            // var_dump($break);
            // echo "</pre>";
            if (array_key_exists($value['employee_code'], $data)) {
                // $data[$value['employee_code']]['dates'][$value['date_attendance']]['base_on_schedule'] = ($value['total_schedule_hours'] + $value['total_overtime_hours']) - ($value['undertime_hours'] + $value['late_hours']);

                $data[$value['employee_code']]['dates'][$value['date_attendance']]['base_on_schedule'] = (($value['total_schedule_hours'] - G_Attendance_Helper::getDeduction($eid, $value['date_attendance'])) + $value['total_overtime_hours']) - ($value['undertime_hours'] + $value['late_hours']);
                //$data[$value['employee_code']]['dates'][$value['date_attendance']]['base_on_actual'] = ($value['total_hours_worked'] + $value['total_overtime_hours']) - ($value['late_hours']);
                $data[$value['employee_code']]['dates'][$value['date_attendance']]['base_on_actual'] = $value['total_hours_worked'];
                $data[$value['employee_code']]['dates'][$value['date_attendance']]['deduction'] = $value['total_schedule_hours'] - G_Attendance_Helper::getDeduction($eid, $value['date_attendance']);
                $data[$value['employee_code']]['dates'][$value['date_attendance']]['is_restday'] = ($value['is_restday']);
                $data[$value['employee_code']]['dates'][$value['date_attendance']]['is_holiday'] = ($value['is_holiday']);
                $data[$value['employee_code']]['dates'][$value['date_attendance']]['total_breaktime_deductible_hours'] = ($break_deduction);
            } else {
                // var_dump($value['total_hours_worked']);
                $data[$value['employee_code']] = array(
                    'employee_code' => $value['employee_code'],
                    'lastname' => $value['lastname'],
                    'firstname' => $value['firstname'],
                    'section_name' => $value['section_name'],
                    'employment_status' => $value['employment_status'],
                    'position_name' => $value['position_name'],
                    'department_name' => $value['department_name'],
                    'is_restday' => $value['is_restday'],
                    'is_holiday' => $value['is_holiday'],
                    'dates' => array(
                        $value['date_attendance'] => array(
                            // 'base_on_schedule' => $value['total_schedule_hours'] + $value['total_overtime_hours'] - ($value['undertime_hours'] + $value['late_hours']),
                            'base_on_schedule' => ($value['total_schedule_hours'] - G_Attendance_Helper::getDeduction($eid, $value['date_attendance'])) + $value['total_overtime_hours'] - ($value['undertime_hours'] + $value['late_hours']),
                            //'base_on_actual' => $value['total_hours_worked'] + $value['total_overtime_hours'] - ($value['late_hours'])
                            'base_on_actual' => $value['total_hours_worked'],
                            'schedule_total_working_hours' => $value['total_schedule_hours'],
                            'deduction' => $value['total_schedule_hours'] - G_Attendance_Helper::getDeduction($eid, $value['date_attendance']),
                            'is_restday' => $value['is_restday'],
                            'is_holiday' => $value['is_holiday'],
                            'total_breaktime_deductible_hours' => $break_deduction
                        )
                    )
                );
            }
        }

        return $data;
    }
    public function getDeduction($eid, $date_att)
    {

        $e    = G_Employee_Finder::findById($eid);

        if (!empty($e)) {
            $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date_att);

            $data_deduc = $a->groupTimesheetData();
            $deduction = $data_deduc['total_hrs_deductible'];
        } else {
            $deduction = 0;
        }
        return $deduction;
    }
}
