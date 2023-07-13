<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department Name</span></strong></td>
         <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Section Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date </span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Time IN</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Time OUT</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Remarks</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Device No.</span></strong></td>   
    </tr>
	<?php 
		foreach($daily_time_record as $emp_id => $value){
            foreach($value as $a) {

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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'], MB_CASE_TITLE, "UTF-8"); ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section'], MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position'], MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_time_out']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['remarks']; ?></td>  
             <td align="left" valign="top" style="border-bottom:none;">
                <?php
                    $employee_id = G_Attendance_Finder::findById($a['employee_attendance_id'])->getEmployeeId();
                    //echo $employee_id ."-". ));
                    $in_device_no = G_Fp_Attendance_Logs_Helper::findDeviceNoByEmployeeIdTypeTimeDate($employee_id, "in", date("H:i", strtotime($a['actual_time_in'])), $a['date_attendance']);
                    $out_device_no = G_Fp_Attendance_Logs_Helper::findDeviceNoByEmployeeIdTypeTimeDate($employee_id, "out", date("H:i", strtotime($a['actual_time_out'])), $a['date_attendance']);
                    if($out_device_no !== NULL){
                        echo $out_device_no;
                    }else if($in_device_no !== NULL){
                        echo $in_device_no;
                    }else{
                        echo "-";
                    }
                ?>
            </td>     
        </tr>
    <?php }} ?>
</table>