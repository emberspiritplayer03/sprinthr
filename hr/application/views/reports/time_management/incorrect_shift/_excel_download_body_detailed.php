<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee ID</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Remarks</span></strong></td>
    </tr>
	<?php 
        $counter = 0;
		foreach($data as $key => $value){             
            foreach($value as $subKey => $subValue){
                foreach( $subValue as $sValue ){
                    $counter++;
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $counter; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $subKey; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $sValue['employee_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $sValue['department_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $sValue['section_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $sValue['position']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $sValue['remarks']; ?></td>           
        </tr>
    <?php }}} ?>
</table>