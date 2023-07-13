<?php
/*foreach ($employees as $es){
	
	//if(strtotime($e->getTerminatedDate()) <= strtotime($to)){
		echo $es->getFirstname();
		echo '<br />';
	//}
	
}
exit;*/
?>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="73">ID NO</td>
    <td width="109">Last Name</td>
    <td width="124">First Name</td>
    <td width="65">Date</td>
    <td width="96">Actual Time In</td>
    <td width="96">Actual Time Out</td>
    <td width="90">OT Time In</td>
    <td width="90">OT Time Out</td>
    <td width="90">OT Hours</td>
    <td width="90">Late Minutes</td>
    <td width="90">Undertime Minutes</td>
  </tr>
<?php
foreach ($employees as $e){

	if ($e){
		$terminated_date = $e->getTerminatedDate();
		$employee_code = $e->getEmployeeCode();
		
		$firstname = $e->getFirstname();
		$lastname = $e->getLastname();
		$sub_e    = G_Employee_Finder::findByEmployeeCode($employee_code);				
		if($sub_e){
			$date_hired = $sub_e->getHiredDate();
		}else{
			$date_hired = '';
		}
		
		//position
			$s = G_Employee_Job_History_Finder::findCurrentJob($e);	
			//$s = G_Employee_Job_History_Finder::findByEmployeeAndDate($e, $date);
			if($s){
				$position = $s->getName();
			}else{
				$position = '';
			}
			
	?>
    	
    <?php

     $prev_dateout="";
     $prev_timeout="";

		foreach ($dates as $date){
			//if(strtotime($terminated_date) >= strtotime($from) || strtotime($terminated_date) >= strtotime($to)){	
			//	echo $status = 'active';
			//}
			$a = $attendance[$e->getId()][$date];           
			
			//Department
			$d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
			if($d){
				$department = $d->getName();
			}else{
				$department = '';
			}
			
			//Status
			/*$s = G_Employee_Job_History_Finder::findByEmployeeAndDate($e,$date);	
			if($s){
				list($m, $d, $y) = explode("/", $s->getEmploymentStatus());		
				if(checkdate($m, $d, $y)){
					$status = '';
				}else{
					$status = $s->getEmploymentStatus();
				}
			}else{
				$status = ' ';
			}*/
			
			if ($a){
				$as[] = $a;
				//$date = $a->getDate();
				$day_type = $a->getDayTypeString();
				$t = $a->getTimesheet();
				if ($t) {
                    $schedule_date_in = $t->getScheduledDateIn();
                    $schedule_time_in = $t->getScheduledTimeIn();
                    $schedule_date_out = $t->getScheduledDateOut();
                    $schedule_time_out = $t->getScheduledTimeOut();

					if ($a->isPresent()) {
						$actual_time_in = date("g:i a", strtotime($t->getTimeIn()));
						$actual_time_out =  date("g:i a", strtotime($t->getTimeOut()));
					} else {

 												$date_attendance = $a->getDate();
                        $employee_id = $a->getEmployeeId();
                        $e = G_Employee_Finder::findById($employee_id);

                        $fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($e->getEmployeeCode(), $date_attendance);

                        if($fp_logs) {

                            if($fp_logs->getType() == 'in') 
                            {
                                $actual_time_in =  date("g:i a", strtotime($fp_logs->getTime()));
                                $actual_time_out = 'No Out';
                            } else {
                                
                               
                            

                              if(($a->getTimeSheet()->getTimeOut() !==  $prev_timeout) &&  ($prev_dateout!==$a->getTimeSheet()->getDateOut())  && $time_out !== $prev_timeout){
                              		$actual_time_in = 'No In';
                       			     $actual_time_out = date("g:i a", strtotime($fp_logs->getTime()));

                              }
                              else{
                              	  $actual_time_in = '-';
                          		    $actual_time_out = '-';
                              }

                         	 }


                        } else {
                            $actual_time_in = '';
                            $actual_time_out = '';
                        }
					}
					$ot_time_in = $t->getOverTimeIn();
					$ot_time_out = $t->getOverTimeOut();
					
					if (strtotime($ot_time_in) && strtotime($ot_time_in)) {
						$ot_date_in = $t->getOvertimeDateIn();
						$ot_date_out = $t->getOvertimeDateOut();
					} else {
						$ot_date_in = '';
						$ot_date_out = '';
						$ot_time_in = '';
						$ot_time_in = '';
					}

                    $total_scheduled_hours = $t->getTotalScheduleHours();
					$total_hours_worked = $t->getTotalHoursWorked();
					$total_overtime_hours = $t->getTotalOvertimeHours();

                    // ============================================================

                    //$t = new G_Timesheet;

                    $late_minutes = $t->getLateMinutes();
                    $undertime_minutes = $t->getUndertimeMinutes();

                    $regular_ot_hours = $t->getRegularOvertimeHours();
                    $regular_ns_hours = $t->getNightShiftHours();
                    $regular_ns_ot_hours = $t->getRegularOvertimeNightShiftHours();

                    $restday_hours = G_Attendance_Helper::getTotalRestDayHours($as);
                    $restday_ot_hours = $t->getRestDayOvertimeHours();
                    $restday_ns_hours = G_Attendance_Helper::getTotalRestDayNightShiftHours($as);
                    $restday_ns_ot_hours = $t->getRestDayOvertimeNightShiftHours();

                    $restday_special_hours = G_Attendance_Helper::getTotalHolidaySpecialRestdayHours($as);
                    $restday_special_ot_hours = $t->getRestDaySpecialOvertimeHours();
                    $restday_special_ns_hours = G_Attendance_Helper::getTotalHolidaySpecialRestdayNightShiftHours($as);
                    $restday_special_ns_ot_hours = $t->getRestDaySpecialOvertimeNightShiftHours();

                    $restday_legal_hours = G_Attendance_Helper::getTotalHolidayLegalRestdayHours($as);
                    $restday_legal_ot_hours = $t->getRestDayLegalOvertimeHours();
                    $restday_legal_ns_hours = G_Attendance_Helper::getTotalHolidayLegalRestdayNightShiftHours($as);
                    $restday_legal_ns_ot_hours = $t->getRestDayLegalOvertimeNightShiftHours();

                    $holiday_special_hours = G_Attendance_Helper::getTotalHolidaySpecialHours($as);
                    $holiday_special_ot_hours = $t->getSpecialOvertimeHours();
                    $holiday_special_ns_hours = G_Attendance_Helper::getTotalHolidaySpecialNightShiftHours($as);
                    $holiday_special_ns_ot_hours = $t->getSpecialOvertimeNightShiftHours();

                    $holiday_legal_hours = G_Attendance_Helper::getTotalHolidayLegalHours($as);
                    $holiday_legal_ot_hours = $t->getLegalOvertimeHours();
                    $holiday_legal_ns_hours = G_Attendance_Helper::getTotalHolidayLegalNightShiftHours($as);
                    $holiday_legal_ns_ot_hours = $t->getLegalOvertimeNightShiftHours();

                    unset($as);
				}
		?>        
          <tr>
            <td width="73"><?php echo $employee_code;?></td>
            <td width="109"><?php echo $lastname;?></td>
            <td width="124"><?php echo $firstname;?></td>
            <td width="80"><?php echo $date;?></td>
            <td width="67"><?php echo $actual_time_in;?></td>
            <td width="96"><?php echo $actual_time_out;?></td>
            <td width="96"><?php echo $ot_date_in;?> <?php echo $ot_time_in;?></td>
            <td width="90"><?php echo $ot_date_out;?> <?php echo $ot_time_out;?></td>
            <td width="90"><?php echo $total_overtime_hours; ?></td>
            <td width="90"><?php echo number_format($late_minutes, 2);?></td>
            <td width="90"><?php echo number_format($undertime_minutes, 2);?></td>          
          </tr>
          <?php }else{ ?>
          <tr>
            <td width="73"><?php echo $employee_code;?></td>
            <td width="109"><?php echo $lastname;?></td>
            <td width="124"><?php echo $firstname;?></td>
            <td width="65"><?php echo $date;?></td>
            <td width="67"></td>
            <td width="96"></td>
            <td width="96"></td>
            <td width="90"></td>
            <td width="90"></td>
            <td width="90"></td>          
          </tr>
          <?php 
            $prev_timeout = $a->getTimeSheet()->getTimeOut();
            $prev_dateout = $a->getTimeSheet()->getDateOut();

        } ?>
        <?php
		}
	}
?>
  <?php
}
?>
</table>
<?php
//exit;
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=timesheet_breakdown_{$from}-{$to}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>