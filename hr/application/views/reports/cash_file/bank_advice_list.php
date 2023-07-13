<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">   
	<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">	
		<tr>
	    	<td style="border:none; font-size:16pt;" align="left"><strong>CASH FILE REPORT</strong></td>
	    	<td style="border:none;">&nbsp;</td>
	    </tr>
	    <tr>
	    	<td style="border:none; font-size:14pt;" align="left"><strong>Date Printed</strong></td>
	    	<td style="border:none;"><?php echo date('Y-m-d'); ?></td>
	    </tr>
	</table>	

	<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
	  <tr>
	  	<td style="border-bottom:none;"><strong>Employee Code</strong></td>
	    <td style="border-bottom:none;"><strong>Employee Name</strong></td>    
	    <td style="border-bottom:none;"><strong>Account Number</strong></td>    
	    <td style="border-bottom:none;"><strong>NET Pay</strong></td>    
	  </tr>
	  <?php foreach( $a_cash_file as $data ){ ?>
	  	<tr>
	  		<td align="left" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['employee_code']; ?></strong></td>
	  		<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['employee_name']; ?></strong></td>
	  		<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['account']; ?></strong></td>
	  		<td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $data['net_pay']; ?></strong></td>
	  	</tr>
	  <?php } ?>
	</table>

	<br /><br />
	<table cellpadding="0" cellspacing="0" style="font-size:10pt; width:836pt; line-height:12pt;">
		<tr>
	    	<td colspan="14" align="left"><strong><i>Printed By :</i></strong></td>
	    </tr>    
		<tr>
	    	<td colspan="14" align="center"><strong>______________________________</strong></td>
	    </tr>
	    <tr>
	    	<td colspan="14">&nbsp;</td>
	    </tr>
	</table>
</div>




<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>