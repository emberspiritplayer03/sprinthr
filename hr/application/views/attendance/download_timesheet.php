<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="73">ID NO</td>
    <td width="109">Last Name</td>
    <td width="124">First Name</td>
    <td width="124">Department</td>
    <td width="124">Position</td>
    <td width="124">Date Hired</td>
    <!--<td width="124">Status</td>-->
    <td width="65">Present Days</td>
    <td width="67">Absent Days</td>
    <td width="96">Hours Worked</td>
    <td width="96">Late Minutes</td>
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
foreach ($employees as $e) {
	$hour = $hours[$e->getId()];
	$present_days 				= ''; $absent_days = ''; $regular_ot_hours = ''; $regular_ot_excess_hours = ''; $regular_ns_ot_hours = '';
	$regular_ns_ot_excess_hours = ''; $late_hours = ''; $absent_days_without_pay = ''; $undertime_hours = ''; $total_nightshift_hours = '';
	$restday_ot_hrs 			= ''; $restday_ot_excess_hrs = ''; $restday_ns_ot_hrs = ''; $restday_ns_ot_excess_hrs = ''; $special_ot_hrs = '';
	$special_ot_excess_hrs 		= ''; $special_ns_ot_hrs = ''; $special_ns_ot_excess_hrs = ''; $legal_ot_hours = ''; $legal_ot_excess_hours = '';
	$legal_ns_ot_hours 			= ''; $legal_ns_ot_excess_hours = ''; $hours_worked = '';	
	
	//Department
	$d = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($e);
	if($d){
		$department = $d->getName();
	}else{
		$department = '';
	}
	
	/*//Status
	$s = G_Employee_Job_History_Finder::findByEmployeeAndDate($e,$from);	
	if($s){
		list($m, $d, $y) = explode("/", $s->getEmploymentStatus());		
		if(checkdate($m, $d, $y)){
			$status = '';
		}else{
			$status = $s->getEmploymentStatus();
		}
	}else{
		$status = '';
	}*/
	
	//Position
	$s = G_Employee_Job_History_Finder::findCurrentJob($e);	
	if($s){
		$position = $s->getName();
	}else{
		$position = '';
	}
	
	
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
	
	$employee_code = '="' . $e->getEmployeeCode() . '"';
	
?>
  <tr>
    <td width="73"><?php echo $employee_code;?></td>
    <td width="109"><?php echo $e->getLastname();?></td>
    <td width="124"><?php echo $e->getFirstname();?></td>
    <td width="124"><?php echo $department;?></td>
     <td width="124"><?php echo $position;?></td>
     <td width="124"><?php echo $e->getHiredDate();?></td>
    <!--<td width="124"><?php //echo $status;?></td>-->
    <td width="65"><?php echo $present_days;?></td>
    <td width="67"><?php echo $absent_days;?></td>
    <td width="96"><?php echo $hours_worked;?></td>
    <td width="96"><?php echo $late_hours;?></td>
    <td width="90"><?php echo $undertime_hours;?></td>
    <td width="80"><?php echo $total_nightshift_hours;?></td>
     <td width="108"><?php echo $regular_ot_hours;?></td>
    <td width="106"><?php echo $regular_ot_excess_hours;?></td>
    <td width="94"><?php echo $regular_ns_ot_hours;?></td> 
    <td width="84"><?php echo $regular_ns_ot_excess_hours;?></td>
    <td width="80"><?php echo $restday_ot_hrs;?></td>
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
	//$i++;
}
?>
</table>
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=timesheet_summary_{$from}-{$to}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

