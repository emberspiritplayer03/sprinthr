<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>
<div style="width:80%">  
<table width="100%" border="1" cellpadding="2" cellspacing="1" style="font-size:8pt; width:836pt; line-height:16pt;">
	<tr>
    	<td style="font-size:10pt; border-bottom:none;" colspan="2"><strong>Salary List According to Seniority</strong></td>        
    </tr>
	<tr>
    	<td align="left" style="border:none; height:10pt;"><i>Date Printed:</i></td>
    	<td align="left" style="border:none; height:10pt;"><?php echo date("Y-m-d"); ?></td>    	
    </tr>	
</table>
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
    	<td align="center" valign="middle" style="width:90pt;"><strong>DEPARTMENT</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>EMPLOYEE CODE</span></strong></td>
        <td align="center" valign="top"><strong>EMPLOYEE NAME</span></strong></td>
        <td align="center" valign="middle" style="width:74pt;"><strong>POSITION</strong></td>
        <td align="center" valign="middle" style="width:74pt;"><strong>HIRED DATE</strong></td>
        <td align="center" valign="middle" style="width:74pt;"><strong>BASIC SALARY</strong></td>
        <td align="center" valign="middle" style="width:74pt;"><strong>TYPE</strong></td>
        <td align="center" valign="middle" style="width:74pt;"><strong>EMPLOYMENT STATUS</strong></td>
    </tr>
<?php foreach ($data as $key => $val) { ?>
    <tr>
    	<td align="center" valign="middle"><?php echo '="' . $val['department'] . '"'; ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['employee_code'] . '"'; ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['employee_name'] . '"'; ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['position'] . '"'; ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['hired_date'] . '"'; ?></td>
        <td align="center" valign="middle" style="mso-number-format:'\@';"><?php echo number_format($val['basic_salary'],2,".",","); ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['type'] . '"'; ?></td>
        <td align="center" valign="middle"><?php echo '="' . $val['employment_status'] . '"'; ?></td>
    </tr>
<?php } ?>
</table>
</div>
<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$file");
header("Pragma: no-cache");
header("Expires: 0");
?>