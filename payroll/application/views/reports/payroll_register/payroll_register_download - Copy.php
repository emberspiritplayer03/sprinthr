
<?php
$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
$pcovered = $period;
$pdate    = $payout_date;
?>
<table border="1" cellpadding="0" cellspacing="0">
  <col width="138" />
  <col width="141" />
  <col width="173" />
  <col width="155" />
  <col width="141" />
  <col width="96" />
  <col width="80" />
  <col width="89" />
  <col width="91" />
  <col width="102" />
  <col width="117" />
  <col width="98" />
  <col width="90" />
  <col width="91" />
  <col width="90" />
  <col width="82" />
  <col width="86" />
  <col width="85" />
  <col width="86" />
  <col width="90" />
  <col width="80" />
  <col width="89" />
  <col width="88" />
  <col width="98" />
  <col width="67" />
  <col width="91" />
  <col width="99" />
  <col width="64" />
  <col width="52" />
  <col width="68" />
  <col width="81" />
  <col width="70" />
  <col width="60" />
  <col width="83" />
  <col width="120" span="8" />
  <tr>
    <td width="138">ID NO</td>
    <td width="141">Surname</td>
    <td width="173">First Name</td>
    <td width="155">Position</td>
    <td width="141"> Semi-Monthly </td>
    <td width="89">Reg OT Hrs</td>
    <td width="91">Reg OT Amt</td>
    <td width="96">NS Hrs</td>
    <td width="80">NS Amt</td>
    <td width="80">NS OT Hrs</td>
    <td width="80">NS OT Amt</td>
    <td width="102">Restday Hrs</td>
    <td width="117">Restday Amt</td>
    <td width="98">Late Hrs</td>
    <td width="90">Late Amt</td>
    <td width="91">Absent Days</td>
    <td width="90">Absent Amt</td>
    <td width="82">Suspension Days</td>
    <td width="86">Suspension Amt</td>
    <td width="85">Holiday Hrs</td>
    <td width="86">Holiday Amt</td>
    <td width="90">Undertime Hrs</td>
    <td width="80">Undertime Amt</td>
    <td width="98">Gross Pay (Basic)</td>
    <td width="64">SSS</td>
    <td width="52">PHIC</td>
    <td width="68">HDMF</td>
    <td width="60">Taxable</td>
    <td width="83">W_Tax</td>
    <td width="120">Net Due</td>
    <td width="120">13th Month</td>
  </tr>
<?php
$i = 1;
foreach ($employees as $e) {
	//if ($i > 5) {
		//break;	
	//}
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
	$regular_ot_hours = $labels['regular_ot_hours']['value'];
	$regular_ot_amount = $labels['regular_ot_amount']['value'];
	
	$regular_ns_hours = $labels['regular_ns_hours']['value'];
	$regular_ns_amount = $labels['regular_ns_amount']['value'];
	
	$regular_ns_ot_hours = $labels['regular_ns_ot_hours']['value'];
	$regular_ns_ot_amount = $labels['regular_ns_ot_amount']['value'];
	
	$present_days = $labels['present_days']['value'];
	$absent_days_with_pay = $labels['absent_days_with_pay']['value'];
	$absent_days_without_pay = $labels['absent_days_without_pay']['value'];
	$suspended_days = $labels['suspended_days']['value'];
	
	$nightshift_ot_hours = $labels['nightshift_overtime_hours']['value'];
	$undertime_hours = $labels['undertime_hours']['value'];
	
	$late_hours = $labels['late_hours']['value'];
	$restday_hours = $labels['restday_hours']['value'];
	$restday_ot_hours = $labels['restday_ot_hours']['value'];
	$restday_nightshift_hours = $labels['restday_nightshift_hours']['value'];
	$holiday_special_hours = $labels['holiday_special_hours']['value'];
	$holiday_special_ot_hours = $labels['holiday_special_ot_hours']['value'];
	$holiday_special_nightshift_hours = $labels['holiday_special_nightshift_hours']['value'];
	$holiday_special_restday_hours = $labels['holiday_special_restday_hours']['value'];
	$holiday_special_restday_ot_hours = $labels['holiday_special_restday_ot_hours']['value'];
	$holiday_special_restday_nightshift_hours = $labels['holiday_special_restday_nightshift_hours']['value'];
	$holiday_legal_hours = $labels['holiday_legal_hours']['value'];
	$holiday_legal_ot_hours = $labels['holiday_legal_ot_hours']['value'];
	$holiday_legal_nightshift_hours = $labels['holiday_legal_nightshift_hours']['value'];
	$holiday_legal_restday_hours = $labels['holiday_legal_restday_hours']['value'];
	$holiday_legal_restday_ot_hours = $labels['holiday_legal_restday_ot_hours']['value'];
	$holiday_legal_restday_nightshift_hours = $labels['holiday_legal_restday_nightshift_hours']['value'];
	$restday_amount = $labels['restday_amount']['value'];
	$restday_ot_amount = $labels['restday_ot_amount']['value'];
	$restday_nightshift_amount = $labels['restday_nightshift_amount']['value'];
	$holiday_special_amount = $labels['holiday_special_amount']['value'];
	$holiday_special_ot_amount = $labels['holiday_special_ot_amount']['value'];
	$holiday_special_nightshift_amount = $labels['holiday_special_nightshift_amount']['value'];
	$holiday_special_restday_amount = $labels['holiday_special_restday_amount']['value'];
	$holiday_special_restday_ot_amount = $labels['holiday_special_restday_ot_amount']['value'];
	$holiday_special_restday_nightshift_amount = $labels['holiday_special_restday_nightshift_amount']['value'];
	$holiday_legal_amount = $labels['holiday_legal_amount']['value'];
	$holiday_legal_ot_amount = $labels['holiday_legal_ot_amount']['value'];
	$holiday_legal_nightshift_amount = $labels['holiday_legal_nightshift_amount']['value'];
	$holiday_legal_restday_amount = $labels['holiday_legal_restday_amount']['value'];
	$holiday_legal_restday_ot_amount = $labels['holiday_legal_restday_ot_amount']['value'];
	$holiday_legal_restday_nightshift_amount = $labels['holiday_legal_restday_nightshift_amount']['value'];
	
	
	
	$total_overtime_amount = $labels['total_overtime_amount']['value'];
	$total_ns_amount = $labels['total_ns_amount']['value'];

	$holiday_amount = $labels['holiday']['value'];
	$late_amount = $labels['late_amount']['value'];
	$undertime_amount = $labels['undertime_amount']['value'];
	$absent_amount = $labels['absent_amount']['value'];
	$suspended_amount = $labels['suspended_amount']['value'];
	$position = $labels['position']['value'];
?>
  <tr>
    <td width="138"><?php echo $employee_code;?></td>
    <td width="141"><?php echo $e->getLastname();?></td>
    <td width="173"><?php echo $e->getFirstname();?></td>
    <td width="155"><?php echo $position;?></td>
    <td width="141"><?php echo $basic_pay;?></td>
    <td width="89"><?php echo $regular_ot_hours;?></td>
    <td width="91"><?php echo $regular_ot_amount;?></td>
    <td width="96"><?php echo $regular_ns_hours;?></td>
    <td width="80"><?php echo $regular_ns_amount;?></td>
    <td width="80"><?php echo $regular_ns_ot_hours;?></td>
    <td width="80"><?php echo $regular_ns_ot_amount;?></td>
    <td width="102"><?php echo $restday_hours;?></td>
    <td width="117"><?php echo $restday_amount;?></td>
    <td width="98"><?php echo $late_hours;?></td>
    <td width="90"><?php echo $late_amount;?></td>
    <td width="91"><?php echo $absent_days_without_pay;?></td>
    <td width="90"><?php echo $absent_amount;?></td>
    <td width="82"><?php echo $suspended_days;?></td>
    <td width="86"><?php echo $suspended_amount;?></td>
    <td width="85"><?php echo ($holiday_legal_hours + $holiday_special_hours);?></td>
    <td width="86"><?php echo $holiday_amount;?></td>
    <td width="90"><?php echo $undertime_hours;?></td>
    <td width="80"><?php echo $undertime_amount;?></td>
    <td width="98"><?php echo $gross_pay;?></td>
    <td width="64"><?php echo $sss;?></td>
    <td width="52"><?php echo $philhealth;?></td>
    <td width="68"><?php echo $pagibig;?></td>
    <td width="60"><?php echo $taxable;?></td>
    <td width="83">&nbsp;</td>
    <td width="120"><?php echo $net_pay;?></td>
    <td width="120"><?php echo $month_13th;?></td>
  </tr>
  <?php
	//$i++;
}
?>
</table>
<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=payroll_register.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

