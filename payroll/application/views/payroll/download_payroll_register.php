<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="138">ID NO</td>
    <td width="141">Surname</td>
    <td width="173">First Name</td>
    <td width="155">Position</td>
    <td width="80">Semi-Monthly</td>
    <td width="80">Daily Rate</td>
    <td width="80">Hourly Rate</td>
    <td width="80">Present Days</td>
    <td width="80">Absent Days</td>
    <td width="89">Absent Amount</td>
    <td width="89">Late Hours</td>
    <td width="89">Late Amount</td>
    <td width="89">Undertime Hours</td>
    <td width="89">Undertime Amount</td>
    <td width="89">Nightshift Hours</td>
    <td width="89">Nightshift Amount</td>
    <td width="89">Suspended Days</td>
    <td width="89">Suspended Amount</td>
    
   <!-- <td width="89">Restday Hrs</td>
    <td width="89">Restday Amount</td>-->
    
    <td width="89">Reg OT Hrs</td>
    <td width="91">Reg OT Amt</td>
    <td width="89">Reg OT Excess Hrs</td>
    <td width="91">Reg OT Excess Amt</td>    
    <td width="80">Reg NS OT Hrs</td>
    <td width="80">Reg NS OT Amt</td>
    <td width="80">Reg NS OT Excess Hrs</td>
    <td width="80">Reg NS OT Excess Amt</td>
    <td width="80">Rest Day OT Hrs</td>
    <td width="80">Rest Day OT Amt</td>
    <td width="80">Rest Day OT Excess Hrs</td>
    <td width="80">Rest Day OT Excess Amt</td>
    <td width="80">Rest Day NS OT Hrs</td>
    <td width="80">Rest Day NS OT Amt</td>
    <td width="80">Rest Day NS OT Excess Hrs</td>
    <td width="80">Rest Day NS OT Excess Amt</td>
    <td width="80">Special OT Hrs</td>
    <td width="80">Special OT Amt</td>
    <td width="80">Special OT Excess Hrs</td>
    <td width="80">Special OT Excess Amt</td>
    <td width="80">Special NS OT Hrs</td>
    <td width="80">Special NS OT Amt</td>
    <td width="80">Special NS OT Excess Hrs</td>
    <td width="80">Special NS OT Excess Amt</td>
    <td width="80">Legal OT Hrs</td>
    <td width="80">Legal OT Amt</td>
    <td width="80">Legal OT Excess Hrs</td>
    <td width="80">Legal OT Excess Amt</td>
    <td width="80">Legal NS OT Hrs</td>
    <td width="80">Legal NS OT Amt</td>
    <td width="80">Legal NS OT Excess Hrs</td>
    <td width="80">Legal NS OT Excess Amt</td>
    <td width="80">Gross Pay</td>
    <td width="80">SSS</td>
    <td width="80">Philhealth</td>
    <td width="80">Pagibig</td>
    <td width="80">Taxable</td>
    <td width="80">Witholding Tax</td>
    <td width="80">Net Pay</td>
    <td width="80">13th Month</td>
  </tr>
<?php
foreach ($employees as $e) {
	/*echo '<pre>';
	print_r($labels);
	*/
	$employee_id = $e->getId();
	$employee_code = $e->getEmployeeCode();
	$basic_pay = $payslips[$employee_id]['basic_pay'];	
	$month_13th = $payslips[$employee_id]['month_13th'];
	$taxable = $payslips[$employee_id]['taxable'];
	$gross_pay = $payslips[$employee_id]['gross_pay'];
	$net_pay = $payslips[$employee_id]['net_pay'];
	$sss = $payslips[$employee_id]['sss'];
	$philhealth = $payslips[$employee_id]['philhealth'];
	$pagibig = $payslips[$employee_id]['pagibig'];
	$witholding_tax = $payslips[$employee_id]['withheld_tax'];
	$month_13th = $payslips[$employee_id]['month_13th'];
	
	$obj_labels = unserialize($payslips[$employee_id]['labels']);
	foreach ($obj_labels as $label) {
		$variable = strtolower($label->getVariable());
		$labels[$variable]['label'] = $label->getLabel();
		$labels[$variable]['value'] = $label->getValue();			
	}	
	$obj_earnings = unserialize($payslips[$employee_id]['earnings']);
	foreach ($obj_earnings as $earning) {
		$variable = strtolower($earning->getVariable());
		$labels[$variable]['label'] = $earning->getLabel();
		$labels[$variable]['value'] = $earning->getAmount();		
	}
	$obj_other_earnings = unserialize($payslips[$employee_id]['other_earnings']);
	foreach ($obj_other_earnings as $other_earning) {
		$variable = strtolower($other_earning->getVariable());
		$labels[$variable]['label'] = $other_earning->getLabel();
		$labels[$variable]['value'] = $other_earning->getAmount();		
	}
	$obj_deductions = unserialize($payslips[$employee_id]['deductions']);
	foreach ($obj_deductions as $deduction) {
		$variable = strtolower($deduction->getVariable());
		$labels[$variable]['label'] = $deduction->getLabel();
		$labels[$variable]['value'] = $deduction->getAmount();		
	}
	$obj_other_deductions = unserialize($payslips[$employee_id]['other_deductions']);
	foreach ($obj_other_deductions as $other_deduction) {
		$variable = strtolower($other_deduction->getVariable());
		$labels[$variable]['label'] = $other_deduction->getLabel();
		$labels[$variable]['value'] = $other_deduction->getAmount();		
	}
	
	$position = $labels['position']['value'];
	$daily_rate = $labels['daily_rate']['value'];
	$hourly_rate = $labels['hourly_rate']['value'];
	
	$present_days = $labels['present_days']['value'];	
	$absent_days = $labels['absent_days']['value'];
		$absent_amount = $labels['absent_amount']['value'];
	$late_hours = $labels['late_hours']['value'];
		$late_amount = $labels['late_amount']['value'];
	$undertime_hours = $labels['undertime_hours']['value'];
		$undertime_amount = $labels['undertime_amount']['value'];
	$total_nightshift_hours = $labels['total_nightshift_hours']['value'];
		$total_nightshift_amount = $labels['total_nightshift_amount']['value'];
	$suspended_days = $labels['suspended_days']['value'];
		$suspended_amount = $labels['suspended_amount']['value'];
	$total_overtime_amount = $labels['total_overtime_amount']['value'];	
	
	/*$restday_hrs = $labels['restday_hours']['value'];
		$restday_amount = $labels['restday_amount']['value'];*/
	
	$regular_ot_hours = $labels['regular_ot_hours']['value'];
		$regular_ot_amount = $labels['regular_ot_amount']['value'];
	$regular_ot_hours_excess = $labels['regular_ot_hours_excess']['value'];
		$regular_ot_amount_excess = $labels['regular_ot_amount_excess']['value'];	
	$regular_ns_ot_hours = $labels['regular_ns_ot_hours']['value'];
		$regular_ns_ot_amount = $labels['regular_ns_ot_amount']['value'];		
	$regular_ns_ot_hours_excess = $labels['regular_ns_ot_hours_excess']['value'];
		$regular_ns_ot_amount_excess = $labels['regular_ns_ot_amount_excess']['value'];
		
	$restday_ot_hours = $labels['restday_ot_hours']['value'];
		$restday_ot_amount = $labels['restday_ot_amount']['value'];
	$restday_ot_hours_excess = $labels['restday_ot_hours_excess']['value'];
		$restday_ot_amount_excess = $labels['restday_ot_amount_excess']['value'];
	$restday_ns_ot_hours = $labels['restday_ns_ot_hours']['value'];
		$restday_ns_ot_amount = $labels['restday_ns_ot_amount']['value'];
	$restday_ns_ot_hours_excess = $labels['restday_ns_ot_hours_excess']['value'];
		$restday_ns_ot_amount_excess = $labels['restday_ns_ot_amount_excess']['value'];
		
	$holiday_special_ot_hours = $labels['holiday_special_ot_hours']['value'];
		$holiday_special_ot_amount = $labels['holiday_special_ot_amount']['value'];
	$holiday_special_ot_hours_excess = $labels['holiday_special_ot_hours_excess']['value'];
		$holiday_special_ot_amount_excess = $labels['holiday_special_ot_amount_excess']['value'];
	$holiday_special_ns_ot_hours = $labels['holiday_special_ns_ot_hours']['value'];
		$holiday_special_ns_ot_amount = $labels['holiday_special_ns_ot_amount']['value'];
	$holiday_special_ns_ot_hours_excess = $labels['holiday_special_ns_ot_hours_excess']['value'];
		$holiday_special_ns_ot_amount_excess = $labels['holiday_special_ns_ot_amount_excess']['value'];
		
	$holiday_legal_ot_hours = $labels['holiday_legal_ot_hours']['value'];
		$holiday_legal_ot_amount = $labels['holiday_legal_ot_amount']['value'];
	$holiday_legal_ot_hours_excess = $labels['holiday_legal_ot_hours_excess']['value'];
		$holiday_legal_ot_amount_excess = $labels['holiday_legal_ot_amount_excess']['value'];
	$holiday_legal_ns_ot_hours = $labels['holiday_legal_ns_ot_hours']['value'];
		$holiday_legal_ns_ot_amount = $labels['holiday_legal_ns_ot_amount']['value'];
	$holiday_legal_ns_ot_hours_excess = $labels['holiday_legal_ns_ot_hours_excess']['value'];
		$holiday_legal_ns_ot_amount_excess = $labels['holiday_legal_ns_ot_amount_excess']['value'];					
?>
  <tr>
    <td><?php echo $employee_code;?></td>
    <td><?php echo $e->getLastname();?></td>
    <td><?php echo $e->getFirstname();?></td>
    <td><?php echo $position;?></td>
    <td><?php echo $basic_pay;?></td>
    <td><?php echo $daily_rate;?></td>
    <td><?php echo $hourly_rate;?></td>
    <td><?php echo $present_days;?></td>
    <td><?php echo $absent_days;?></td>
    <td><?php echo $absent_amount;?></td>
    <td><?php echo $late_hours;?></td>
    <td><?php echo $late_amount;?></td>
    <td><?php echo $undertime_hours;?></td>
    <td><?php echo $undertime_amount;?></td>
    <td><?php echo $total_nightshift_hours;?></td>
    <td><?php echo $total_nightshift_amount;?></td>
    <td><?php echo $suspended_days;?></td>
    <td><?php echo $suspended_amount;?></td>
    
    <!--<td><?php //echo $restday_hrs;?></td>
    <td><?php //echo $restday_amount;?></td>-->
    
    <td><?php echo $regular_ot_hours;?></td>
    <td><?php echo $regular_ot_amount;?></td>
    <td><?php echo $restday_ot_hours_excess;?></td>
    <td><?php echo $restday_ot_amount_excess;?></td>    
    <td><?php echo $regular_ns_ot_hours;?></td>
    <td><?php echo $regular_ns_ot_amount;?></td>
    <td><?php echo $regular_ns_ot_hours_excess;?></td>
    <td><?php echo $regular_ns_ot_amount_excess;?></td>
    <td><?php echo $restday_ot_hours;?></td>
    <td><?php echo $restday_ot_amount;?></td>
    <td><?php echo $restday_ot_hours_excess;?></td>
    <td><?php echo $restday_ot_amount_excess;?></td>
    <td><?php echo $restday_ns_ot_hours;?></td>
    <td><?php echo $restday_ns_ot_amount;?></td>
    <td width="80"><?php echo $restday_ns_ot_hours_excess;?></td>
    <td width="80"><?php echo $restday_ns_ot_amount_excess;?></td>
    <td width="80"><?php echo $holiday_special_ot_hours;?></td>
    <td width="80"><?php echo $holiday_special_ot_amount;?></td>
    <td width="80"><?php echo $holiday_special_ot_hours_excess;?></td>
    <td width="80"><?php echo $holiday_special_ot_amount_excess;?></td>
    <td width="80"><?php echo $holiday_special_ns_ot_hours;?></td>
    <td width="80"><?php echo $holiday_special_ns_ot_amount;?></td>
    <td width="80"><?php echo $holiday_special_ns_ot_hours_excess;?></td>
    <td width="80"><?php echo $holiday_special_ns_ot_amount_excess;?></td>
    <td width="80"><?php echo $holiday_legal_ot_hours;?></td>
    <td width="80"><?php echo $holiday_legal_ot_amount;?></td>
    <td width="80"><?php echo $holiday_legal_ot_hours_excess;?></td>
    <td width="80"><?php echo $holiday_legal_ot_amount_excess;?></td>
    <td width="80"><?php echo $holiday_legal_ns_ot_hours;?></td>
    <td width="80"><?php echo $holiday_legal_ns_ot_amount;?></td>
    <td width="80"><?php echo $holiday_legal_ns_ot_hours_excess;?></td>
    <td width="80"><?php echo $holiday_legal_ns_ot_amount_excess;?></td>
    <td width="80"><?php echo $gross_pay;?></td>
    <td width="80"><?php echo $sss;?></td>
    <td width="80"><?php echo $philhealth;?></td>
    <td width="80"><?php echo $pagibig;?></td>
    <td width="80"><?php echo $taxable;?></td>
    <td width="80"><?php echo $witholding_tax;?></td>
    <td width="80"><?php echo $net_pay;?></td>
    <td width="80"><?php echo $month_13th;?></td>
  </tr>
  <?php
	//$i++;
}
?>
</table>
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payroll_register_{$from}-{$to}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

