<?php ob_start();?>
<?php
$temp_cutoff_period = G_Cutoff_Period_Finder::findAll();
$temp_end = $temp_cutoff_period[0];
$cutoff_end_date = $temp_end->getPayoutDate();
$temp_start = end($temp_cutoff_period);
$cutoff_start_date = $temp_start->getPayoutDate();
$cutoff_period = array_reverse($temp_cutoff_period);
?>
<?php if (!empty($records)):?>
  <?php 
  foreach ($employees as $e):
	$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	$ph = new G_Payslip_Helper($p);
	$pos = G_Employee_Job_History_Finder::findCurrentJob($e);
	if ($pos) {
		$position = $pos->getName();
	}
	$st = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);
	if ($st) {
		$salary_type = $st->getType();
		$basic_salary = $st->getBasicSalary();
	}
	if ($salary_type == 'daily_rate') {
		$rate = $rate_daily = $basic_salary;
		$employee[$e->getId()]['rate_hourly'] = $rate_daily / 8;
	} else if ($salary_type == 'hourly_rate') {
		$employee[$e->getId()]['rate'] = $rate_hourly = $basic_salary;
		$employee[$e->getId()]['rate_hourly'] = $rate_hourly;	
	}  
  ?>
<table width="342" border="0" cellpadding="0" cellspacing="0" style="border:1px solid black; font-size: xx-small;">
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><span class="font-size"><strong><?php echo $e->getName();?></strong></span><strong><span class="font-size"> (<?php echo strtoupper($e->getEmployeeCode());?>)</span></strong></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><span class="font-size">Period: <?php echo $period;?></span></td>
  </tr>
  <tr>
    <td colspan="6"><span class="font-size">Payout Date:<?php echo $payout;?></span></td>
  </tr>
  <tr>
    <td colspan="6"><span class="font-size">Position: <?php echo $position;?></span></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td width="19">&nbsp;</td>
    <td width="59"><strong>Earnings</strong></td>
    <td width="56">&nbsp;</td>
    <td colspan="3"><strong>Deductions</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Basic Pay:</td>
    <td align="left"><span class="font-size"><?php echo (number_format($p->getBasicPay(),2));?></span></td>
    <td width="69">SSS:</td>
    <td width="67" align="left"><span class="font-size"><?php echo ($ph->getValue('sss') > 0) ? number_format($ph->getValue('sss'),2) : '-';?></span></td>
    <td width="70" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Overtime:</td>
    <td align="left"><span class="font-size"><?php echo (number_format($ph->getValue('overtime'),2));?></span></td>
    <td>Philhealth:</td>
    <td align="left"><span class="font-size"><?php echo ($ph->getValue('philhealth') > 0) ? number_format($ph->getValue('philhealth'), 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td>HDMF:</td>
    <td align="left"><span class="font-size"><?php echo ($ph->getValue('pagibig') > 0) ? number_format($ph->getValue('pagibig'), 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td>Tax:</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td>Late:</td>
    <td align="left"><span class="font-size"><?php echo ($ph->getValue('late_amount') > 0) ? number_format($ph->getValue('late_amount'), 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td>Undertime:</td>
    <td align="left"><span class="font-size"><?php echo ($ph->getValue('undertime_amount') > 0) ? number_format($ph->getValue('undertime_amount'), 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><strong>Gross Pay:</strong></td>
    <td align="left"><span class="font-size"><?php echo number_format($p->getGrossPay(),2);?></span></td>
    <td><strong>Total Dedn:</strong></td>
    <td align="left"><span class="font-size"><?php echo number_format($ph->computeTotalDeductions(), 2);?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td><strong>Net Pay:</strong></td>
    <td align="left"><span class="font-size">P<?php echo number_format($p->getNetPay(), 2);?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
</table>  
  <?php endforeach;?>

<?php
	else:echo 'No record(s) available.';
	
?>
<?php endif;?>

<?php
header("Content-type: application/x-msexcel;charset:UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>