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
	    	<td colspan="2" style="border:none;"><b><?php echo date("F", mktime(null, null, null, $month)) . " " . $year; ?>&nbsp;</b></td>
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

	    	<?php
	    		$group_loan_set = array();
	    		foreach($loan_data as $ldata) {
	    			//$group_loan_set[$ldata['employee_code'] . "|" . $ldata['loan_type'] . "|". $ldata['total_amount_to_pay']][] = $ldata;
	    			$group_loan_set[$ldata['employee_code'] . "|" . $ldata['loan_type']][] = $ldata;
	    		}
	    		//Utilities::displayArray($group_loan_set);
				$lsdata_arr = array();	    		
	    	?>

	    	<?php //foreach($loan_data as $ldata) { ?>
	    	<?php foreach($group_loan_set as $loan_set_key => $lsdata) { ?>
	    			<?php
	    				foreach($lsdata as $lskdata) {
	    					//$lsdata_arr[$loan_set_key]['deduction_total'] += $lskdata['total_deduction_per_period'];
	    					//$lsdata_arr[$loan_set_key]['deduction_total'] += str_replace(',', '', $lskdata['total_deduction_per_period']);
	    					//$lsdata_arr[$loan_set_key]['deduction_total'] += str_replace(',', '', $lskdata['total_deduction_per_period']);
	    					$lsdata_arr[$loan_set_key]['deduction_total'] += str_replace(',', '', $lskdata['monthly_amount_paid']);
	    					$lsdata_arr[$loan_set_key]['employee_code']   = $lskdata['employee_code'];
	    					$lsdata_arr[$loan_set_key]['employee_name']   = $lskdata['employee_name'];
	    					$lsdata_arr[$loan_set_key]['loan_type'] 	  = $lskdata['loan_type'];

	    					$lsdata_arr[$loan_set_key]['department_name'] = $lskdata['department_name'];
	    					$lsdata_arr[$loan_set_key]['position_name']   = $lskdata['position_name'];
	    					$lsdata_arr[$loan_set_key]['section_name'] 	  = $lskdata['section_name'];
	    					$lsdata_arr[$loan_set_key]['employment_status'] = $lskdata['employment_status'];
	    					$lsdata_arr[$loan_set_key]['start_date'] 		= $lskdata['start_date'];
	    				}
	    			?>
					<tr>
				        <td colspan="2" valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['employee_code']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['employee_name']; ?></td>

				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['department_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['position_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['section_name']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['employment_status']; ?></td>
				        <td valign="top" style="border-bottom:none;"><?php echo $lsdata_arr[$loan_set_key]['start_date']; ?></td>

				        <td valign="top" style="border-bottom:none; mso-number-format:'\@';"><?php echo $lsdata_arr[$loan_set_key]['loan_type']; ?></td>
				        <td valign="top" style="border-bottom:none; mso-number-format:'\@';"><?php echo $lsdata_arr[$loan_set_key]['deduction_total']; ?></td>
				        <!-- <td valign="top" style="border-bottom:none; mso-number-format:'\@';"><?php //echo number_format(0,2)?></td> -->
				    </tr>  

				    <?php
				    	$sub_total['loan_amount'] += $lsdata_arr[$loan_set_key]['deduction_total'];
				    ?>

	    	<?php } ?>

			        <tr>
			            <td align="left" colspan="2" valign="top" style="border-bottom:none;"><strong><?php echo "Sub Total"; ?></strong></td>
			            <td align="left" colspan="7" valign="top" style="border-bottom:none;"><strong>&nbsp;</strong></td>
			            <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php echo number_format($sub_total['loan_amount'],2); ?></strong></td>
			            <!-- <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php //echo number_format(0,2);?></strong></td> -->
			        </tr> 	    	
			        <?php
			        	$grand_total['loan_amount'] += $sub_total['loan_amount'];
			        ?>
	    <?php } ?>
	    			<br /><br />
			        <tr>
			            <td align="left" colspan="2" valign="top" style="border-bottom:none;"><strong><?php echo "Grand Total"; ?></strong></td>
			            <td align="left" colspan="7" valign="top" style="border-bottom:none;"><strong>&nbsp;</strong></td>
			            <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php echo number_format($grand_total['loan_amount'],2); ?></strong></td>
			            <!-- <td align="left" valign="top" style="border-bottom:none; mso-number-format:'\@';"><strong><?php echo number_format(0,2);?></strong></td> -->
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