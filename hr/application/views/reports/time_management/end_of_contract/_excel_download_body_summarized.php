<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Department</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total Employee</span></strong></td>            
    </tr>
	<?php 
		$total_employee = 0;
		foreach($end_of_contract as $a){
			$total_employee += $a['employee_count'];
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
            <td align="right" valign="top" style="border-bottom:none;"><?php echo number_format($a['employee_count'],2,".",","); ?></td>           
        </tr>
    <?php } ?>
    <tr>
    	<td align="left" valign="top" style="border-bottom:none;"><b>Total</b></td>
        <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
        	<?php echo $total_employee; ?></b>
        </td>
    </tr>
</table>	