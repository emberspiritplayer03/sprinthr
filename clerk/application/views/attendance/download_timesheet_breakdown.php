<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="73">ID NO</td>
    <td width="109">Last Name</td>
    <td width="124">First Name</td>
    <td width="65">Date</td>
    <td width="65">Schedule Time In</td>
    <td width="67">Schedule Time Out</td>
    <td width="96">Actual Time In</td>
    <td width="96">Actual Time Out</td>
    <td width="90">OT Time In</td>
    <td width="90">OT Time Out</td>
    <td width="90">Late Minutes</td>
    <td width="90">Undertime Minutes</td>
    <td width="80">Night Diff Hours</td>
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
  </tr>
<?php
foreach ($employees as $e):
	if ($e):
		$employee_code = $e->getEmployeeCode();
		$firstname = $e->getFirstname();
		$lastname = $e->getLastname();		
	?>
    	
    <?php
		$the_attendance = $attendance[$e->getId()];
		foreach ($the_attendance as $a):
			if ($a) {
				$date = $a->getDate();
				$t = $a->getTimesheet();
				if ($t) {
					$schedule_time_in = $t->getScheduledTimeIn();
					$schedule_time_out = $t->getScheduledTimeOut();
					$actual_time_in = $t->getTimeIn();
					$actual_time_out = $t->getTimeOut();
					$ot_time_in = $t->getOverTimeIn();
					$ot_time_out = $t->getOverTimeOut();
				}
				
				$hour = G_Payslip_Hour_Finder::findByAttendanceFinder($a);
					
				if ($hour) {
					$present_days = $hour->getPresentDays();
					$absent_days = $hour->getAbsentDays();
					$hours_worked = $hour->computeTotalHoursWorked();
					$regular_ot_hours = $hour->getRegularOvertime();
					$regular_ot_excess_hours = $hour->getRegularOvertimeExcess();
					$regular_ns_ot_hours = $hour->getNightShiftOvertime();
					$regular_ns_ot_excess_hours = $hour->getNightShiftOvertimeExcess();
					$late_hours = $hour->computeLateMinutes();
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
			}
		?>
          <tr>
            <td width="73"><?php echo $employee_code;?></td>
            <td width="109"><?php echo $lastname;?></td>
            <td width="124"><?php echo $firstname;?></td>
            <td width="65"><?php echo $date;?></td>
            <td width="65"><?php echo $schedule_time_in;?></td>
            <td width="65"><?php echo $schedule_time_out;?></td>
            <td width="67"><?php echo $actual_time_in;?></td>
            <td width="96"><?php echo $actual_time_out;?></td>
            <td width="96"><?php echo $ot_time_in;?></td>
            <td width="90"><?php echo $ot_time_out;?></td>
            <td width="90"><?php echo $late_hours;?></td>
            <td width="90"><?php echo $undertime_hours;?></td>
            <td width="90"><?php echo $total_nightshift_hours;?></td>
            <td width="80"><?php echo $regular_ot_hours;?></td>
             <td width="108"><?php echo $regular_ot_excess_hours;?></td>
            <td width="106"><?php echo $regular_ns_ot_hours;?></td>
            <td width="94"><?php echo $regular_ns_ot_excess_hours;?></td> 
            <td width="84"><?php echo $restday_ot_hrs;?></td>
            <td width="80"><?php echo $restday_ot_excess_hrs;?></td>
            <td width="80"><?php echo $restday_ns_ot_hrs;?></td>
            <td width="80"><?php echo $restday_ns_ot_excess_hrs;?></td>
            <td width="80"><?php echo $special_ot_hrs;?></td>
            <td width="80"><?php echo $special_ot_excess_hrs;?></td>
            <td width="80"><?php echo $special_ns_ot_hrs;?></td>
            <td width="80"><?php echo $special_ns_ot_excess_hrs;?></td>
            <td width="80"><?php echo $legal_ot_hours;?></td>
            <td width="80"><?php echo $legal_ot_excess_hours;?></td>
            <td width="80"><?php echo $legal_ns_ot_hours;?></td>
            <td width="80"><?php echo $legal_ns_ot_excess_hours;?></td>         
          </tr>
        <?php
		endforeach;
	endif;
?>
  <?php
endforeach;
?>
</table>
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=timesheet_breakdown {$from}-{$to}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

