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
    <td width="124">Department</td>
    <td width="124">Position</td>
    <td width="124">Date Hired</td>
    <!--<td width="124">Status</td>-->
    <td width="65">Date</td>
    <td width="65">Day Type</td>
    <td width="65">Schedule Time In</td>
    <td width="67">Schedule Time Out</td>
    <td width="96">Required Hrs</td>
    <td width="96">Actual Time In</td>
    <td width="96">Actual Time Out</td>
    <td width="90">Actual Hrs</td>
    <td width="90">OT Time In</td>
    <td width="90">OT Time Out</td>
    <td width="90">Late Minutes</td>
    <td width="90">Undertime Minutes</td>
    <td width="80">Night Diff Hrs</td>
    <td width="108">Reg OT Hrs</td>
    <td width="106">Reg OT Excess Hrs</td> 
    <td width="94">Reg NS OT Hrs</td>
    <td width="84">Reg NS OT Excess Hrs</td>
    <td width="80">Restday OT Hrs</td>
    <td width="80">Restday OT Excess Hrs</td>
    <td width="80">Restday NS OT Hrs</td>
    <td width="80">Restday NS OT Excess Hrs</td>
    <td width="80">Special OT Hrs</td>
    <td width="80">Special OT Excess Hrs</td>
    <td width="80">Special NS OT Hrs</td>
    <td width="80">Special NS OT Excess Hrs</td>
    <td width="80">Legal OT Hrs</td>
    <td width="80">Legal OT Excess Hrs</td>
    <td width="80">Legal OT NS Hrs</td>
    <td width="80">Legal OT NS Excess Hrs</td>
    <td width="80">Total OT Hrs</td>
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
				
				//$date = $a->getDate();
				$day_type = $a->getDayTypeString();
				$t = $a->getTimesheet();
				if ($t) {
					$schedule_time_in = $t->getScheduledTimeIn();
					$schedule_time_out = $t->getScheduledTimeOut();

					if (strtotime($schedule_time_in) && strtotime($schedule_time_out)) {
						$schedule_date_in = $date;
						$schedule_date_out = $date;
						if (Tools::isTimeNightShift($schedule_time_in)) {
							$schedule_date_out = date('Y-m-d', strtotime($date . '+1 day'));	
						}
					} else {
						$schedule_date_in = '';
						$schedule_date_out = '';
					}
					if ($a->isPresent()) {
						$actual_time_in = $t->getTimeIn();
						$actual_time_out = $t->getTimeOut();
						$actual_date_in = $t->getDateIn();
						$actual_date_out = $t->getDateOut();
					} else {
						$actual_time_in = '';
						$actual_time_out = '';
						$actual_date_in = '';
						$actual_date_out = '';
					}
					$ot_time_in = $t->getOverTimeIn();
					$ot_time_out = $t->getOverTimeOut();
					
					if (strtotime($ot_time_in) && strtotime($ot_time_in)) {
						$ot_dates = Tools::getDateInAndOut($ot_time_in, $ot_time_out, $date);
						$ot_date_in = $ot_dates['date_in'];
						$ot_date_out = $ot_dates['date_out'];
					} else {
						$ot_date_in = '';
						$ot_date_out = '';
						$ot_time_in = '';
						$ot_time_in = '';
					}
					
					if (!$a->isHoliday()) {
						$total_scheduled_hours = $t->computeTotalScheduledHours();
					} else {
						$total_scheduled_hours = 0;
					}
					$total_actual_hours = $t->computeTotalActualHours();
				}
				
				$hour = G_Payslip_Hour_Finder::findByAttendanceFinder($a);
					
				if ($hour) {
					$present_days = $hour->getPresentDays();
					$absent_days = $hour->getAbsentDays();
					$hours_worked = $hour->getTotalHoursWorked();
					$regular_ot_hours = $hour->getRegularOvertime();
					$regular_ot_excess_hours = $hour->getRegularOvertimeExcess();
					$regular_ns_ot_hours = $hour->getNightShiftOvertime();
					$regular_ns_ot_excess_hours = $hour->getNightShiftOvertimeExcess();
					$late_minutes = $hour->computeLateMinutes();
					$absent_days_without_pay = $hour->getAbsentDaysWithoutPay();
					$undertime_hours = $hour->computeUndertimeMinutes();
					$total_nightshift_hours = $hour->getTotalNightShift();
					$restday_ot_hrs = $hour->getRestDayOvertime();
					$restday_ot_excess_hrs = $hour->getRestDayOvertimeExcess();
					$restday_ns_ot_hrs = $hour->getRestDayNightShiftOvertime();
					$restday_ns_ot_excess_hrs = $hour->getRestDayNightShiftOvertimeExcess();
					$special_ot_hrs = $hour->getHolidaySpecialOvertime();
					$special_ot_excess_hrs = $hour->getHolidaySpecialOvertimeExcess();
					$special_ns_ot_hrs = $hour->getHolidaySpecialNightShiftOvertime();
					$special_ns_ot_excess_hrs = $hour->getHolidaySpecialNightShiftOvertimeExcess();
					$legal_ot_hours = $hour->getHolidayLegalOvertime();
					$legal_ot_excess_hours = $hour->getHolidayLegalOvertimeExcess();
					$legal_ns_ot_hours = $hour->getHolidayLegalNightShiftOvertime();
					$legal_ns_ot_excess_hours = $hour->getHolidayLegalNightShiftOvertimeExcess();
				}
			$total_ot_hrs = $regular_ot_hours + $regular_ot_excess_hours + $regular_ns_ot_hours + $regular_ns_ot_excess_hours + $restday_ot_hrs + $restday_ot_excess_hrs + $restday_ns_ot_hrs + $restday_ns_ot_excess_hrs + $special_ot_hrs + $special_ot_excess_hrs + $special_ns_ot_hrs + $special_ns_ot_excess_hrs + $legal_ot_hours + $legal_ot_excess_hours + $legal_ns_ot_hours + $legal_ns_ot_excess_hours; 
		?>        
          <tr>
            <td width="73"><?php echo $employee_code;?></td>
            <td width="109"><?php echo $lastname;?></td>
            <td width="124"><?php echo $firstname;?></td>
            <td width="124"><?php echo $department;?></td>
            <td width="124"><?php echo $position;?></td>
            <td width="124"><?php echo $date_hired;?></td>
            <!--<td width="124"><?php //echo $status;?></td>-->
            <td width="65"><?php echo $date;?></td>
            <td width="65"><?php echo $day_type;?></td>
            <td width="65"><?php echo $schedule_date_in;?> <?php echo $schedule_time_in;?></td>
            <td width="65"><?php echo $schedule_date_out;?> <?php echo $schedule_time_out;?></td>
            <td width="67"><?php echo number_format($total_scheduled_hours, 2);?></td>
            <td width="67"><?php echo $actual_date_in;?> <?php echo $actual_time_in;?></td>
            <td width="96"><?php echo $actual_date_out;?> <?php echo $actual_time_out;?></td>
            <td width="96"><?php echo number_format($hours_worked, 2);?></td>
            <td width="96"><?php echo $ot_date_in;?> <?php echo $ot_time_in;?></td>
	        <td width="90"><?php echo $ot_date_out;?> <?php echo $ot_time_out;?></td>
            <td width="90"><?php echo number_format($late_minutes, 2);?></td>
            <td width="90"><?php echo number_format($undertime_hours, 2);?></td>
            <td width="90"><?php echo number_format($total_nightshift_hours, 2);?></td>
            <td width="80"><?php echo number_format($regular_ot_hours, 2);?></td>
            <td width="108"><?php echo number_format($regular_ot_excess_hours, 2);?></td>
            <td width="106"><?php echo number_format($regular_ns_ot_hours, 2);?></td>
            <td width="94"><?php echo number_format($regular_ns_ot_excess_hours, 2);?></td> 
            <td width="84"><?php echo number_format($restday_ot_hrs, 2);?></td>
            <td width="80"><?php echo number_format($restday_ot_excess_hrs, 2);?></td>
            <td width="80"><?php echo number_format($restday_ns_ot_hrs, 2);?></td>
            <td width="80"><?php echo number_format($restday_ns_ot_excess_hrs, 2);?></td>
            <td width="80"><?php echo number_format($special_ot_hrs, 2);?></td>
            <td width="80"><?php echo number_format($special_ot_excess_hrs, 2);?></td>
            <td width="80"><?php echo number_format($special_ns_ot_hrs, 2);?></td>
            <td width="80"><?php echo number_format($special_ns_ot_excess_hrs, 2);?></td>
            <td width="80"><?php echo number_format($legal_ot_hours, 2);?></td>
            <td width="80"><?php echo number_format($legal_ot_excess_hours, 2);?></td>
            <td width="80"><?php echo number_format($legal_ns_ot_hours, 2);?></td>
            <td width="80"><?php echo number_format($legal_ns_ot_excess_hours, 2);?></td>     
            <td width="80"><?php echo number_format($total_ot_hrs, 2);?></td>         
          </tr>
          <?php }else{ ?>
          <tr>
            <td width="73"><?php echo $employee_code;?></td>
            <td width="109"><?php echo $lastname;?></td>
            <td width="124"><?php echo $firstname;?></td>
            <td width="124"><?php echo $department;?></td>
            <td width="124"><?php echo $position;?></td>
            <td width="124"><?php echo $date_hired;?></td>
            <td width="65"><?php echo $date;?></td>
            <td width="65"></td>
            <td width="65"></td>
            <td width="65"></td>
            <td width="67"></td>
            <td width="67"></td>
            <td width="96"></td>
            <td width="96"></td>
            <td width="96"></td>
            <td width="90"></td>
            <td width="90"></td>
            <td width="90"></td>
            <td width="90"></td>
            <td width="80"></td>
            <td width="108"></td>
            <td width="106"></td>
            <td width="94"></td> 
            <td width="84"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>
            <td width="80"></td>   
            <td width="80"></td>         
          </tr>
          <?php } ?>
          
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