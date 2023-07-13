<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Schedule Time</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date of Attendance</span></strong></td>  
        <td align="center" valign="top" style="border-bottom:none;"><strong>Remarks</span></strong></td>            
    </tr>
	<?php foreach($absences as $a){ ?>
    <?php 
    $employee_name = strtr(utf8_decode($a['name']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    ?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($employee_name,  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time']; ?></td>      
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>      
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['remarks']; ?></td>           
        </tr>
    <?php } ?>
</table>