<?php
ob_start();

//$temp_cutoff_period = G_Cutoff_Period_Finder::findAll();
//$temp_end = $temp_cutoff_period[0];
//$cutoff_end_date = $temp_end->getPayoutDate();
//$temp_start = end($temp_cutoff_period);
//$cutoff_start_date = $temp_start->getPayoutDate();
//$cutoff_period = array_reverse($temp_cutoff_period);
?>
<?php //if (!empty($records)):?>
<style type="text/css">
.font-size {
	font-size: xx-small;
}
</style>

  <?php 
  foreach ($employees as $e):
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
	$total_nightshift_amount = $labels['total_nightshift_amount']['value'];

	$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
	$ph = new G_Payslip_Helper($p);
	$total_deductions = $ph->computeTotalDeductions();			
	$total_earnings   = $ph->computeTotalEarnings();
	//$total_deductions = $labels['total_deductions']['value'];
	
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
<table width="342" border="0" cellpadding="0" cellspacing="0" style="border:1px solid black; font-size: xx-small;">
  <tr>
    <td colspan="6"><h2>Redpill Productions</h2></td>
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
    <td colspan="6"><span class="font-size">Payout Date:<?php echo $payout_date;?></span></td>
  </tr>
  <tr>
    <td colspan="6"><span class="font-size">Position: <?php echo $position;?></span></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td width="3">&nbsp;</td>
    <td width="130"><strong>Earnings</strong></td>
    <td width="58">&nbsp;</td>
    <td colspan="3"><strong>Deductions</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>SM  Pay:</td>
    <td align="left"><span class="font-size"><?php echo $basic_pay?></span></td>
    <td width="95">SSS:</td>
    <td width="51" align="left"><span class="font-size"><?php echo ($sss > 0) ? number_format($sss,2) : '-';?></span></td>
    <td width="3" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Overtime:</td>
    <td align="left"><span class="font-size"><?php echo number_format($total_overtime_amount);?></span></td>
    <td>Philhealth:</td>
    <td align="left"><span class="font-size"><?php echo ($philhealth > 0) ? number_format($philhealth, 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Nightshift</td>
    <td align="left"><?php echo $total_nightshift_amount;?></td>
    <td>HDMF:</td>
    <td align="left"><span class="font-size"><?php echo ($pagibig > 0) ? number_format($pagibig, 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><strong>Others:</strong></td>
    <td align="left">&nbsp;</td>
    <td>Tax:</td>
    <td align="left"><?php echo $witholding_tax;?></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" rowspan="2">
        <table width="176" border="0" cellspacing="0" cellpadding="0">
        <?php foreach($earning_benefits as $earnings){ ?>
          <tr>
            <td width="76" align="left"><span class="font-size"><?php echo $earnings->getBenefitName(); ?></span></td>
            <td width="24" align="left">
            <span class="font-size">
				<?php 
                    foreach($obj_other_earnings as $ear){
                        if($ear->getLabel() == $earnings->getBenefitName()) {
                           echo $val = ($ear->getAmount() > 0) ? number_format($ear->getAmount(), 2) : '-';
                        }
                    }
                ?>
            </span>
            </td>
          </tr>
        <?php } ?>
			  <?php if($earning_list) { ?>
              			<?php foreach($earning_list as $el) { ?>
                              <tr>
                                <td width="76" align="left"><span class="font-size"><?php echo $el->getTitle(); ?></span></td>
                                <td width="24" align="left">
                                <span class="font-size">
                                    <?php 
                                        foreach($obj_other_earnings as $ear){
                                            if($ear->getLabel() == $el->getTitle()) {
                                               echo $val = ($ear->getAmount() > 0) ? number_format($ear->getAmount(), 2) : '-';
                                            }
                                        }
                                    ?>
                                </span>
                                </td>
                              </tr>                        
                        <?php } ?>
              <?php } ?>        
        </table>
    </td>
    <td>Late:</td>
    <td align="left"><span class="font-size"><?php echo ($late_amount > 0) ? number_format($late_amount, 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Undertime:</td>
    <td align="left"><span class="font-size"><?php echo ($undertime_amount > 0) ? number_format($undertime_amount, 2) : '-';?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td colspan="3"><strong>Others:</strong></td>
      </tr>
  <?php foreach($deduction_benefits as $db) { ?>
  
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td><?php echo $db->getBenefitName(); ?>:</td>
        <td align="left">
        <span class="font-size">            
        <?php 
			foreach($obj_other_deductions as $other_deduction){
				if($other_deduction->getLabel() == $db->getBenefitName()) {
					echo $val = ($other_deduction->getAmount() > 0) ? number_format($other_deduction->getAmount(), 2) : '-';
				}
			}
		?>
        </span>
        </td>        
        <td align="left">&nbsp;</td>
      </tr>

  <?php } ?>
  
  <?php if($deduction_list) { ?>
  		<?php foreach($deduction_list as $dl) {?>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td><?php echo $dl->getTitle(); ?>:</td>
            <td align="left">
            <span class="font-size">            
				<?php 
                    foreach($obj_other_deductions as $other_deduction){
                        if($other_deduction->getLabel() == $dl->getTitle()) {
                            echo $val = ($other_deduction->getAmount() > 0) ? number_format($other_deduction->getAmount(), 2) : '-';
                        }
                    }
                ?>
            </span>
            </td>        
            <td align="left">&nbsp;</td>
          </tr>
        
        <?php } ?>
  <?php }?>
  <tr>
    <td>&nbsp;</td>
    <td><strong>Earnings:</strong></td>
    <td align="left"><span class="font-size">
	<?php
    	//echo number_format($gross_pay,2);
		echo number_format($total_earnings,2);
		
	?>
    </span></td>
    <td><strong>Deductions:</strong></td>
    <td align="left"><span class="font-size">
		<?php echo number_format($total_deductions, 2); ?>
    </span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td><strong>Net Pay:</strong></td>
    <td align="left"><span class="font-size">P<?php echo number_format($net_pay, 2); ?></span></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>  
  <tr>
    <td colspan="6" align="right">Received By:__________________________&nbsp;&nbsp;</td>
  </tr> 
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>   
</table>  
  <?php endforeach;?>

<?php
//header('Content-type: application/ms-excel');
//header("Content-Disposition: attachment; filename=$filename");
//header("Pragma: no-cache");
//header("Expires: 0");
?>

<?php //endif;?>