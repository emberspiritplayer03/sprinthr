<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong>Employee Code</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Employee Name</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Department Name</span></strong></td>

        <td align="center" valign="top" style="border-bottom:none;"><strong>Project Site</span></strong></td>

        <td align="center" valign="top" style="border-bottom:none;"><strong>Section</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Position</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Hired</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Date Attendance</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Day Type</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Schedule Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Schedule Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Required Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Actual Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>OT Time In</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>OT Time Out</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Late Minutes</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Undertime Minutes</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reg OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reg NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Reg NS OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD NS OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Special Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Special OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Special NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Special NS OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Legal Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Legal OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Legal NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>RD Legal NS OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Special Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Special OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Special NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Special NS OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Legal Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Legal OT Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Legal NS Hrs</span></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Legal NS OT Hrs</span></strong></td>
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
                } else if ($a['holiday_type'] == 2) {
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
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['department_name']; ?></td>
             <td align="left" valign="top" style="border-bottom:none;"><?php echo $project_site_name; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['section_name']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['position']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['hired_date']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $day_type; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_date_in'] . ' ' . $a['scheduled_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_date_out'] . ' ' . $a['scheduled_time_out']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['total_schedule_hours']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $actual_time_in; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $actual_time_out; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo round($a['total_hours_worked'],2); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_in'] . ' ' . $a['overtime_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_out'] . ' ' . $a['overtime_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['late_hours']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['undertime_hours']; ?></td> 
            <!-- REGULAR -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? ($a['regular_overtime_hours'] + $a['regular_overtime_excess_hours']) : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? ($a['regular_overtime_nightshift_hours'] + $a['regular_overtime_nightshift_excess_hours']) : 0 ); ?></td> 
            <?php
                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_restday_hrs = $a['total_schedule_hours'];
                } else {
                    $total_restday_hrs = $a['total_hours_worked'];
                }
            ?>   
            <!-- RESTDAY -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $total_restday_hrs : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['restday_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['restday_overtime_nightshift_hours'] : 0 ); ?></td> 
            <?php
                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_rd_special_hrs = $a['total_schedule_hours'];
                } else {
                    $total_rd_special_hrs = $a['total_hours_worked'];
                }
            ?>             
            <!-- RESTDAY SPECIAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $total_rd_special_hrs : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['restday_special_overtime_hours']: 0); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['restday_special_overtime_ns_hours'] : 0); ?></td>
            <?php
                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_rd_legal_hrs = $a['total_schedule_hours'];
                } else {
                    $total_rd_legal_hrs = $a['total_hours_worked'];
                }
            ?>            
            <!-- RESTDAY LEGAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $total_rd_legal_hrs : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['restday_legal_overtime_hours']: 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['restday_legal_overtime_ns_hours'] : 0 ); ?></td>  
            <!-- SPECIAL -->

            <?php
                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_special_hrs = $a['total_schedule_hours'];
                } else {
                    $total_special_hrs = $a['total_hours_worked'];
                }
            ?>

            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $total_special_hrs : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['special_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['special_overtime_ns_hours'] : 0 ); ?></td> 
            <!-- LEGAL -->
            <?php
                if($a['total_hours_worked'] >= $a['total_schedule_hours']) {
                    $total_legal_hrs = $a['total_schedule_hours'];
                } else {
                    $total_legal_hrs = $a['total_hours_worked'];
                }
            ?>            
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $total_legal_hrs : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['legal_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['legal_overtime_ns_hours'] : 0 ); ?></td>
        </tr>
    <?php } ?>
</table>