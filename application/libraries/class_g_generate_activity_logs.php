<?php

class G_Generate_activity_logs{


	public function generateActivityLogs($from, $to){

	  ini_set('max_execution_time', 1000);

	  $deleteActivityAttendance = G_Employee_Activity_Attendance_Manager::deleteAttendanceFromTo($from,$to); //reset laravel attendance
	 
	  $activity_logs = G_Employee_Activities_Finder::findActivityFromTo($from, $to); //get employee_activity logs

	  //utilities::displayArray($activity_logs);exit();

    $saved = 0;

	  foreach($activity_logs as $logs){

	  		$e = G_Employee_Finder::findById($logs->getEmployeeId());
	  		$in = $logs->getDate(). " ".$logs->getTimeIn();
	  		$out = $logs->getDateOut(). " ".$logs->getTimeOut();

        $name = $e->getFirstname(). ' '.$e->getLastname();

	  		$date_in = $logs->getDate();
        $project_site_id = $logs->getProjectSiteId();
        $employee_activity_id = $logs->getId();
        $employee_id = $logs->getEmployeeId();

        $time_out = $logs->getTimeOut();
        $time_in  = $logs->getTimeIn();

            
	  		
	  	    if($out < $in)
            {
                $out = Tools::getTomorrowDate("{$out}");
                $out = $out. " ".$logs->getTimeOut();
            }

           if($e){

           	  $attendance = null;

           	  $attendance = G_Attendance_Finder::findByEmployeeAndDate($e,$date_in);

              $frequency_id = $e->getFrequencyId();

           	 // utilities::displayArray($attendance);exit();

           	  if($attendance){

           	  	  #region Activity Log, Attendance Log, Schedule
           	  	  $t = $attendance->getTimesheet();
                  $time_part_in = explode(":",$t->getTimeIn());

                  if($time_part_in[2] != 00){
                     $time_part_in[2] = 00;
                  }

                  $actual_time_in = implode(":", $time_part_in);

           	  	  $log_in = $t->getDateIn().' '. $actual_time_in;
           	  	  $log_out = $t->getDateOut().' '.$t->getTimeOut();

           	  	  if($log_out < $log_in)
                    {
                        $log_out = Tools::getTomorrowDate("{$log_out}");
               		    $log_out = $log_out. " ".$t->getTimeOut();
                    }

                  $scheduled_date_in = $t->getScheduledDateIn();
                  $scheduled_time_in = $t->getScheduledTimeIn();
                  $scheduled_date_out = $t->getScheduledDateOut();
                  $scheduled_time_out = $t->getScheduledTimeOut();

                  if($scheduled_date_in == "" || $scheduled_time_in == "" || $scheduled_date_out == "" || $scheduled_time_out == "")
                    {
                        $schedule_in = $log_in;
                        $schedule_out =  $log_out;
                    }
                    else
                    {
                        $schedule_in = $scheduled_date_in.' '.$scheduled_time_in;
                        $schedule_out = $scheduled_date_out.' '.$scheduled_time_out;
                    }
                    #endregion

                    #region Get Payslip

                    $payslip = null;

                    if($e->getFrequencyId() == 2 ){

                    	$payslip = G_Weekly_Payslip_Finder::findByEmployeeAndPeriod($e,$from,$to);

                    }
                    else if($e->getFrequencyId() == 3 ){

                        $payslip = G_Monthly_Payslip_Finder::findByEmployeeAndPeriod($e,$from,$to);

                    }

                    else{

                    	$payslip = G_Payslip_Finder::findByEmployeeAndPeriod($e,$from,$to);

                    }

                    if($payslip){

                      $payslip_id = $payslip->getId();

                     // var_dump($in.' - '.$out);exit();
                     
                       if($in >= $log_in && $out <= $log_out){ //check if activity in and out is between actual time in and out

                    	   $raw_hours_worked  = Tools::newComputeHoursDifferenceByDateTime($in, $out);
                    	   //$activity_raw_worked_mins = self::convertTominutes($raw_hours_worked); 
                         $deduct_breaktime_hrs = 0;

                    	   //breaktime region
                    	    $schedule_break = G_Break_Time_Schedule_Header_Finder::findByScheduleTimeInandOut2($scheduled_time_in, $scheduled_time_out, $name ,$date_in);
		                      $to_deduct = 0;

                          if(!$schedule_break){
                             $name = 'All employees';
                              $schedule_break = G_Break_Time_Schedule_Header_Finder::findByScheduleTimeInandOut2($scheduled_time_in, $scheduled_time_out, $name, $date_in);
                          }

		                     if($schedule_break){

		                     	 	 $schedule_breaks = G_Break_Time_Schedule_Details_Finder::findbByHeaderId($schedule_break->getId());
		                     }

		                     if($schedule_breaks){

		                            if($schedule_breaks->getObjId() == 0 || $schedule_breaks->getObjId() == $e->getId()){
		                            	$to_deduct = $schedule_breaks->getToDeduct();
		                            }

		                     }

                            if($to_deduct == 1){ //enabled deduct breaktime

                             $schedule_break_from = date("Y-m-d H:i:s",strtotime($attendance->getDate().' '.$schedule_breaks->getBreakIn() ));
                              $schedule_break_to = date("Y-m-d H:i:s",strtotime($attendance->getDate().' '.$schedule_breaks->getBreakOut() ));

                                 if( $time_in <= $schedule_breaks->getBreakIn()  && $time_out >= $schedule_breaks->getBreakOut()) { //check if yung activty log is umabot ng breaktime

                                 	  $deduct_breaktime_hrs =Tools::newComputeHoursDifferenceByDateTime($schedule_break_from,$schedule_break_to);
                                 	  //$deduct_breaktime_mins = self::convertTominutes($deduct_breaktime_hrs);
                                 	  $total_activity_raw_worked_hrs =$raw_hours_worked - $deduct_breaktime_hrs;

                                 }
                                 else{

                                 	  $total_activity_raw_worked_hrs = $raw_hours_worked;
                                 }

                            }
                            else{

                                $total_activity_raw_worked_hrs = $raw_hours_worked;

                            }

                            //end breaktime region

                              $working_days = $e->getYearWorkingDays();
                              if( $working_days <= 0 ){
                                    $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
                                    $working_days = $sv->getVariableValue();
                               }


                            $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $date_in);
                            $salary_amount = 0;

                            if($s){

                               $salary_amount = $s->getBasicSalary();
                               $salary_type   = $s->getType();
                            }

                             switch ($salary_type):
                              case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                                  $employee_monthly_rate = $salary_amount;
                                  $per_day               = ($salary_amount * 12) / $working_days;
                                  $monthly_rate_daily    = $salary_amount;        
                                  $per_hour = $per_day / 8;
                                  
                                  break;

                              case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:      
                                  $monthly_rate_daily    = ($salary_amount * $working_days) / 12;               
                                  $employee_monthly_rate = $monthly_rate_daily;
                                  $per_day            = ($monthly_rate_daily * 12) / $working_days;
                                  $per_hour           = $per_day / 8;
                                  break;
                              endswitch;

                            
                            $total_worked_amount =  $total_activity_raw_worked_hrs * $per_hour;

                            $a = new G_Employee_Activity_Attendance;
                            $a->setActivityId($employee_activity_id);
                            $a->setEmployeeId($employee_id);
                            $a->setProjectSiteId($project_site_id);
                            $a->setFrequencyId($frequency_id);
                            $a->setPayslipId($payslip_id);
                            $a->setDate($date_in);
                            $a->setActivityIn($in);
                            $a->setActivityOut($out);
                            $a->setActivityRawWorkedHrs($raw_hours_worked);
                            $a->setActivityDeductibleBreakHrs($deduct_breaktime_hrs);
                            $a->setActivityTotalWorkedHrs($total_activity_raw_worked_hrs);
                            $a->setActivityTotalAmountWorked($total_worked_amount);
                            $count_save = $a->save();

                            if($count_save){
                               $saved++;
                            }

                    	}//end if between actual time in and out

                    }//endpayslip

           	  }//end if attendance

           }//end if(e)


	  } //end foreach activity logs

    if($saved != 0){

        $is_generated = true;
       // self::generateActivityPayslips($from,$to);

    }else{

       $is_generated = false;

    }

     return $is_generated;

	}


  public function generateActivityPayslips($from,$to){

      $activity_attendance = G_Employee_Activity_Attendance_Finder::findActivityByDateFromTo($from,$to);



  }






	
	public function convertTominutes($hr){

		$minutes = $hr*60;
		return $minutes;

	}



}


?>