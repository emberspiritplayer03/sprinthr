<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
            <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td> 
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total Late Hrs.</span></strong></td>  
        <td align="center" valign="top" style="border-bottom:none;"><strong>Total Number of Late</span></strong></td>            
    </tr>
	<?php 
		$total_late = 0;
		foreach($absences as $a){ 
			$total_late += $a['total_late'];
            $total_number_late += $a['total_number_lates'];

             $employee_name = mb_convert_encoding($a['name'] , "HTML-ENTITIES", "UTF-8");
                if(!empty($a['project_site_id']) || $a['project_site_id'] != 0){
                    $project = G_Project_Site_Finder::findById($a['project_site_id']);
                    if($project){
                        $project_site_name = $project->getprojectname();
                    }
                    else{
                        $project_site_name = "";
                    }
                }
                else{
                    $project_site_name = "";
                }
	?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department'],MB_CASE_TITLE, "UTF-8"); ?></td>

             <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['employee_status'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="right" valign="top" style="border-bottom:none;"><?php echo number_format($a['total_late'],2,".",","); ?></td>     
            <td align="right" valign="top" style="border-bottom:none;"><?php echo $a['total_number_lates']; ?></td>           
        </tr>
    <?php } ?>
    <tr>
    	<td colspan="7" align="left" valign="top" style="border-bottom:none;"><b>Total</b></td>
        <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
        	<?php echo number_format($total_late,2,".",","); ?></b>
        </td>
        <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
            <?php echo number_format($total_number_late,2,".",","); ?></b>
        </td>
    </tr>
</table>	