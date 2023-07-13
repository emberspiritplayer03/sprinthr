<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date of Attendance</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Scheduled Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Scheduled Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Overtime Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Overtime Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reason</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Overtime Hrs</span></strong></td>
        
    </tr>
	<?php 
        $total_ot = 0;
		foreach($overtime as $a){
            if( $a['scheduled_time_in'] != '' || $a['scheduled_time_out'] != '' ){
            //$total_ot += $a['total_overtime_hours'];
            $e = G_Employee_Finder::findByEmployeeCode($a['employee_code']);
            if($e){
               $ot = G_Overtime_Finder::findByEmployeeAndDateAndStatus($e, $a['date_attendance']);
               if($ot){
                    $reason = $ot->getReason();
               }
            }

            $employee_name = mb_convert_encoding($a['employee_name'] , "HTML-ENTITIES", "UTF-8");
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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_in']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_in']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_time_in']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_time_out']; ?></td>  

            <td align="left" valign="top" style="border-bottom:none;"><?php echo $reason; ?></td>

            <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';" >                
                <?php 
                    $total_overtime_hours = 0;
                    if( $a['total_overtime_hours'] > 0 ){
                        $total_overtime_hours = $a['total_overtime_hours'];
                    }else{
                        $total_overtime_hours = $a['total_hours_worked'];
                    }
                    $total_ot += $total_overtime_hours;

                    echo number_format($total_overtime_hours,2,".",",");
                ?>
            </td>
        </tr>

        <?php if($a['is_present'] == 1 && ($a['is_restday'] == 1 && $a['is_holiday'] == 1) ) {
         ?>
       <!--  <tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['employee_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'],MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_in']; ?></td>   
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_in']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_time_in']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_time_out']; ?></td>  
            <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';" >                
                <?php 
                    $total_rdholiday_overtime_hours = $a['total_hours_worked'] - $a['total_overtime_hours'];
                    $total_ot += round($total_rdholiday_overtime_hours,0);

                    echo number_format(round($total_rdholiday_overtime_hours,0),2,".",",");
                ?>
            </td>
        </tr> -->
        <?php } ?>

    <?php }} ?>
    <tr>
         <td align="left" colspan="14" valign="top" style="border-bottom:none;"><b>Total Overtime</b></td>
         <td align="right" valign="top" style="border-bottom:none;vertical-align:middle;mso-number-format:'\@';"><b>
             <?php echo number_format($total_ot,2,".",","); ?></b>
         </td>
        
    </tr>
</table>