<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>

        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>

        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Attendance</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>OT Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>OT Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Late Minutes</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Undertime Minutes</span></strong></td>
    </tr>
	<?php 
        foreach($timesheet as $a){
            $att = $attendance[$a['employee_id']][$a['date_attendance']];
            $e   = G_Employee_Finder::findById($a['employee_id']);

            if( $e ){
                $at  = G_Attendance_Finder::findByEmployeeAndDate($e, $a['date_attendance']);
                $day_type = $at->getDayTypeString();           
            }else{
                $day_type = '';
            }
            
            $has_regular_ot         = false;
            $has_restday_ot         = false;
            $has_restday_special_ot = false;
            $has_restday_legal_ot   = false;
            $has_special_ot         = false;
            $has_legal_ot           = false;


            if($a['is_present'] == 1) {
               $actual_time_in = date("g:i a", strtotime($a['actual_time_in']));
               $actual_time_out = date("g:i a", strtotime($a['actual_time_out']));
            } else {
                $date_attendance = $a['date_attendance'];
                $employee_id = $a['employee_id'];
                $e = G_Employee_Finder::findById($employee_id);

                $fp_logs = G_Attendance_Log_Finder::findByEmployeeCodeAndDate($e->getEmployeeCode(), $date_attendance);

                if($fp_logs) {

                    if($fp_logs->getType() == 'in') 
                    {
                        $actual_time_in =  date("g:i a", strtotime($fp_logs->getTime()));
                        $actual_time_out = 'No Out';
                    } else {
                        $actual_time_in = 'No In';
                        $actual_time_out  = date("g:i a", strtotime($fp_logs->getTime()));

                         //check previous attendance
                            if($e){
                                $prev_date = date('Y-m-d', strtotime($date_attendance.'-1 day'));
                                $att = G_Attendance_Finder::findByEmployeeAndDate($e, $prev_date);
                                if($att){
                                    $t = $att->getTimesheet();
                                    $prev_timeout = $t->getTimeOut();

                                    $actual_time_out2 = $fp_logs->getTime();

                                    $adjust_timeout = explode(":",$actual_time_out2);
                                    $adjust_timeout[2] = '00';
                                    $actual_time_out2 = implode(":", $adjust_timeout);

                                    if($prev_timeout == $actual_time_out2){
                                         $actual_time_in = '';
                                         $actual_time_out = '';
                                    }

                                }
                            }
                        
                    }

                } else {
                    $actual_time_in = '';
                    $actual_time_out = '';
                }

            }

            if ($a['is_restday'] == 1 && $a['is_holiday'] == 0 ) {
                $has_restday_ot = true;
            } else if ($a['is_restday'] == 1 && $a['is_holiday'] == 1 ) {
                if ($a['holiday_type'] == 1) {
                    $has_restday_legal_ot = true;
                } else if ($a['holiday_type'] == 2) {
                    $has_restday_special_ot = true;
                }
            } else if ($a['is_restday'] == 0 && $a['is_holiday'] == 1) {
                if ($a['holiday_type'] == 1) {
                    $has_legal_ot = true;
                } else if ($a['holiday_0type'] == 2) {
                    $has_special_ot = true;
                }
            } else {
                $has_regular_ot = true;
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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $actual_time_in; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $actual_time_out; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_in'] . ' ' . $a['overtime_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_out'] . ' ' . $a['overtime_time_out']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['late_hours']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['undertime_hours']; ?></td>  
        </tr>
    <?php } ?>
</table>	