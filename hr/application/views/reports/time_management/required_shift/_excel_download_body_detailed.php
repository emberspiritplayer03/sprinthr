<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employment Status</span></strong></td>     
    </tr>
	<?php foreach($required_shift as $key => $a){ ?>
        <?php if( $is_all ){ ?>
        <tr>
            <td colspan="6" style="background-color:#D7D7D7;"><strong><?php echo $key; ?></strong></td>
        </tr>
        <?php foreach( $a as $subKey => $subData ){ ?>
            <tr>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $subData['employee_code']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($subData['employee_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($subData['department_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($subData['section_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($subData['position_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($subData['employment_status'],  MB_CASE_TITLE, "UTF-8"); ?></td>          
            </tr>
        <?php } ?>
        <?php  }else{ ?>
            <tr>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['employee_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['employment_status'],  MB_CASE_TITLE, "UTF-8"); ?></td>          
            </tr>
        <?php } ?>        
    <?php } ?>
</table>