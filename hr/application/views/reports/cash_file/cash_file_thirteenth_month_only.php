<?php ob_start();?>
<div style="width:80%">   
	<table width="100%" border="0" cellpadding="2" cellspacing="1" style="width:836pt; line-height:16pt;">	
		<tr><td style="border:none; font-size:12pt;" align="left" colspan="4"><strong><?php echo $header['company_name'] ?></strong></td></tr>
		<tr><td style="border:none; font-size:12pt;" align="left" colspan="4"><?php echo $header['report_name'] ?></td></tr>
		<tr><td style="border:none; font-size:12pt;" align="left" colspan="4"><?php echo $header['payroll_period'] ?></td></tr>
		<tr><td style="border:none; font-size:12pt;" align="left" colspan="4">Rundate : <?php echo $header['run_date'] ?></td></tr>	  
		<tr><td style="border:none; font-size:12pt;" align="left" colspan="4">&nbsp;</td></tr>	  
	</table>	

	<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
	  <tr>
	  	<td style="border-bottom:none;"><strong>LINE</strong></td>
	  	<td style="border-bottom:none;"><strong>CODE</strong></td>
	    <td style="border-bottom:none;"><strong>EMPLOYEE NAME</strong></td>    
	    <td style="border-bottom:none;"><strong>BANK ACCOUNT</strong></td>    
	    <td style="border-bottom:none;"><strong>NET PAY</strong></td>    
	  </tr>
	  <?php 
	  	$line = 1; $total_net_pay = 0; $total_net_pay_bonus_service_award_only = 0;
	  	foreach( $a_cash_file as $data ){ 	  		
	  		$bonus = 0; 
	  		$bonus_witholding_tax = 0;
	  		$bonus_13th_month = 0;
	  		$net_pay_minus_13th_month = 0;
	  		$sub_net_pay_total = 0;
	  		
	  		$deductions = unserialize($data['other_deductions']);
  			$earnings   = unserialize($data['other_earnings']);

  			foreach( $earnings as $earning ){
  				if( trim($earning->getVariable()) == 'Bonus' ){
  					$bonus += $earning->getAmount();
  				}

  				if( $earning->getVariable() == 'Service Award' ){
  					$bonus += $earning->getAmount();
  				}

  				if( $earning->getVariable() == '13th Month Bonus' ){
  					$bonus_13th_month += $earning->getAmount();
  				}  				
  			}

  			foreach( $deductions as $deduction ){
  				if( trim($deduction->getVariable()) == 'tax_bonus_service_award' ){
  					$bonus_witholding_tax += $deduction->getAmount();
  				}
  			}	  	

  			$sub_net_pay_total += $data['net_pay'] - $bonus_witholding_tax;
  			$total_net_pay += $sub_net_pay_total;
	  		
	  ?>
	  	<tr>
	  		<td align="right" valign="middle" style="width:10pt; vertical-align:middle;"><strong><?php echo $line; ?></strong></td>
	  		<td align="left"  valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['employee_code']; ?></strong></td>
	  		<td align="left"  valign="middle" style="width:100pt; vertical-align:middle;"><strong><?php echo mb_convert_case($data['employee_name'], MB_CASE_TITLE, "UTF-8"); ?></strong></td>
	  		<td align="right" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['account']; ?></strong></td>
			<td align="right" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $sub_net_pay_total; ?></strong></td>
	  	</tr>
	  <?php $line++;} ?>
	  <tr>
	  	<td align="right" colspan="4"><strong>Grand Total</strong></td>
	  	<td align="right"><strong><?php echo number_format($total_net_pay,2); ?></strong></td>
	  </tr>
	</table>
</div>

<?php
header("Content-type: application/x-msexcel;charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>