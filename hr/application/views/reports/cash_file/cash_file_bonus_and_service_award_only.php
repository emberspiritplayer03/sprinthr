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
	  	$line = 1; $total_net_amount = 0; $net_amount = 0; $tax_amount = 0;
		  	foreach( $cash_file as $data ){ 	  
		  		$tax_amount       = $data['tax_amount'];
	  			$net_amount       = $data['net_amount'];

	  			if($net_amount > $tax_amount) {
	  				$net_amount = $data['net_amount'] - $data['tax_amount'];
	  			}

		  		$total_net_amount += $net_amount;
		?>
			  	<tr>
			  		<td align="right" valign="middle" style="width:10pt; vertical-align:middle;"><strong><?php echo $line; ?></strong></td>
			  		<td align="left"  valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['employee_code']; ?></strong></td>
			  		<td align="left"  valign="middle" style="width:100pt; vertical-align:middle;"><strong><?php echo mb_convert_case($data['employee_name'], MB_CASE_TITLE, "UTF-8"); ?></strong></td>
			  		<td align="right" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['account']; ?></strong></td>
					<td align="right" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $net_amount; ?></strong></td>  	
			  	</tr>
	  		<?php $line++;} ?>
		  <tr>
		  	<td align="right" colspan="4"><strong>Grand Total</strong></td>
		  	<td align="right"><strong><?php echo number_format($total_net_amount,2); ?></strong></td>
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