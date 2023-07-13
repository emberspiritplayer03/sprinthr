<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date of Attendance</span></strong></td>
        <td align="center" valign="middle" style="width:60pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td> 
        <td align="center" valign="top" style="border-bottom:none;"><strong>Status</span></strong></td> 
        <td align="center" valign="top" style="border-bottom:none;"><strong>Scheduled Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Scheduled Time Out</span></strong></td>            
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Late Hrs.</span></strong></td>
    </tr>
    <?php foreach($absences as $employee => $values) { ?>
        <?php $sub_total_late = 0; ?>
        <?php foreach($values as $a) { ?>
            <?php
                $g_total_late += $a['late_hours'];
                $sub_total_late += $a['late_hours'];

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
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $employee_name; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department'],MB_CASE_TITLE, "UTF-8"); ?></td>

                 <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],MB_CASE_TITLE, "UTF-8"); ?></td>  
                <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['employee_status'],MB_CASE_TITLE, "UTF-8"); ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_in']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_out']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_in']; ?></td>
                <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_out']; ?></td>
                <td align="right" valign="top" style="border-bottom:none;"><?php echo number_format($a['late_hours'],2,".",","); ?></td>           
            </tr>
        <?php } ?>
        <tr>
             <td align="right" colspan="12" valign="top" style="border-bottom:none;"><b>Sub Total</b></td>
             <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
                 <?php echo number_format($sub_total_late,2,".",","); ?></b>
             </td>
        </tr>
    <?php } ?>

    <tr>
    	 <td align="right" colspan="12" valign="top" style="border-bottom:none;"><b>Total Late Hrs</b></td>
         <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
			 <?php echo number_format($g_total_late,2,".",","); ?></b>
         </td>
    </tr>
</table>