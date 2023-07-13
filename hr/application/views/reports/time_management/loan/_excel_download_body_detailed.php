<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Filed</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Start</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date End</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Type</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Paid</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reason</span></strong></td>
    </tr>
	<?php 
		foreach($leave as $a){
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['position']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_applied'] . ' ' . $a['time_applied']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_start']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_end']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['leave_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['is_approved']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['is_paid']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['leave_comments']; ?></td>            
        </tr>
    <?php } ?>

</table>