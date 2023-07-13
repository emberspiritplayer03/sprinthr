<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total Overtime Hours</span></strong></td>            
    </tr>
	<?php 
		$g_total_ot = 0;
		foreach($overtime as $a){
			$g_total_ot += $a['total_ot_hrs'];
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['position']; ?></td>
            <td align="right" valign="top" style="border-bottom:none;"><?php echo number_format($a['total_ot_hrs'],2,".",","); ?></td>           
        </tr>
    <?php } ?>
    <tr>
    	<td colspan="4" align="left" valign="top" style="border-bottom:none;"><b>Grand Total</b></td>
        <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
        	<?php echo number_format($g_total_ot,2,".",",");  ?></b>
        </td>
    </tr>
</table>	