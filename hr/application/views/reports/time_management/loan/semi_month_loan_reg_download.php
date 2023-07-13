<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:100%" width:"1000pt"; >   
	<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
		<tr>
	    	<td colspan="5" style="border:none; font-size:16pt;" align="left">
	        	<strong>Laguna Dai-ichi, Inc.</strong>
	        </td>
	    </tr>   
	    <tr>
	    	<td colspan="5" style="border:none; font-size:14pt;" align="left"><strong>LOAN REGISTER REPORT</strong></td>
	    	<td style="border:none;">&nbsp;</td>
	    </tr>
	    <tr>
	    	<td colspan="3" style="border:none; font-size:14pt;" align="left"><strong>Date Generated</strong></td>
	    	<td colspan="2" style="border:none;"><b><?php echo date('Y-m-d'); ?>&nbsp;</b></td>
	    </tr>
	    <tr>
	    	<td colspan="3" style="border:none; font-size:14pt;" align="left"><strong>Period</strong></td>
	    	<td colspan="2" style="border:none;"><b><?php echo $period; ?></b></td>
	    </tr>

	</table>

	<!-- BODY -->	

	<br /><br />
	<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:980pt; line-height:12pt;">
		<tr>
	        <td colspan="2" valign="top" style="border-bottom:none;"><strong>CODE</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>EMPOYEE NAME</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>DEPARTMENT</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>POSITION</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>SECTION</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>EMPLOYMENT STATUS</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>DATE GRANTED</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>LOAN REFERENCE</strong></td>
	        <td valign="top" style="border-bottom:none;"><strong>AMOUNT</strong></td>
	        <!-- <td valign="top" style="border-bottom:none;"><strong>PENALTY</strong></td> -->      
	    </tr>  
	   
	   	<?php $grand_total = array(); ?>
	   	<?php foreach($loans as $loan_key => $loan_data) { ?>

		<tr>
	        <td colspan="2" valign="top" style="border-bottom:none;"><strong>LOAN TYPE :</strong></td>
	        <td valign="top" colspan="8" style="border-bottom:none;"><strong><?php echo $loan_key; ?></strong></td>
	    </tr>  
	    	<?php $sub_total = array(); ?>
	    	<?php $group_by_loan_type = array(); ?>
	    	<?php foreach($loan_data as $ldata) { ?>
					<tr>
				        <td colspan="2" valign="top" style="border-bottom:none;"><?php echo $ldata['employee_code']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['employee_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['department_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['position_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['section_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['employment_status']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['start_date']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $ldata['loan_type']; ?></td>
				        <td valign="top" style="border-bottom:none; mso-number-format:'\@';" ><?php echo number_format(str_replace(',', '', $ldata['monthly_amount_paid']),2, '.' , ',' )?></td>
				        <!-- <td valign="top" style="border-bottom:none; mso-number-format:'\@';">0.00</td> -->
				    </tr>  

				    <?php
				    	$sub_total['loan_amount'] += str_replace(",","",$ldata['monthly_amount_paid']);
				    	//$sub_total['loan_amount'] += $ldata['total_deduction_per_period'];
				    	$grand_total['loan_amount'] += str_replace(",","",$ldata['monthly_amount_paid']);
				    	//$grand_total['loan_amount'] += $ldata['total_deduction_per_period'];
				    ?>

	    	<?php } ?>

			        <tr>
			            <td align="left" colspan="2" valign="top" style="border-bottom:none;"><strong><?php echo "Sub Total"; ?></strong></td>
			            <td align="left" colspan="7" valign="top" style="border-bottom:none;"><strong>&nbsp;</strong></td>
			            <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php echo number_format($sub_total['loan_amount'],2, '.' , ',' ); ?></strong></td>
			            <!-- <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong>0.00</strong></td> -->
			        </tr> 	    	
			        <?php
			        	$sub_total = array();
			        ?>
	    <?php } ?>
	    			<br /><br />
			        <tr>
			            <td align="left" colspan="2" valign="top" style="border-bottom:none;"><strong><?php echo "Grand Total"; ?></strong></td>
			            <td align="left" colspan="7" valign="top" style="border-bottom:none;"><strong>&nbsp;</strong></td>
			            <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php echo number_format($grand_total['loan_amount'],2, '.' , ',' ); ?></strong></td>
			            <!-- <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong>0.00</strong></td> -->
			        </tr> 	    
    
	</table>

	<!-- BODY END -->

	<br /><br />
	<table cellpadding="0" cellspacing="0" style="font-size:10pt; width:836pt; line-height:12pt;">
		<tr>
	    	<td colspan="14" align="left"><strong><i>Prepared By :</i></strong></td>
	    </tr>    
		<tr>
	    	<td colspan="14" align="left"><br /><br /><br /><br /><strong>______________________________</strong></td>
	    </tr>
	    <tr>
	    	<td colspan="14">&nbsp;</td>
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