<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>       
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
            $has_regular_ot         = false;
            $has_restday_ot         = false;
            $has_restday_special_ot = false;
            $has_restday_legal_ot   = false;
            $has_special_ot         = false;
            $has_legal_ot           = false;

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
	?>
    	<tr>            
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['date_attendance']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['day_type']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_date_in'] . ' ' . $a['scheduled_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['scheduled_date_out'] . ' ' . $a['scheduled_time_out']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['total_schedule_hours']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_date_in'] . ' ' . $a['actual_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['actual_date_out'] . ' ' . $a['actual_time_out']; ?></td>  
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['total_hours_worked']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_in'] . ' ' . $a['overtime_time_in']; ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['overtime_date_out'] . ' ' . $a['overtime_time_out']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['late_hours']; ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['undertime_hours']; ?></td> 
            <!-- REGULAR -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? $a['regular_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_regular_ot ? $a['regular_overtime_nightshift_hours'] : 0 ); ?></td> 
            <!-- RESTDAY -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['total_hours_worked'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['restday_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_ot ? $a['restday_overtime_nightshift_hours'] : 0 ); ?></td> 
            <!-- RESTDAY SPECIAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['total_hours_worked'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['restday_special_overtime_hours']: 0); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_special_ot ? $a['restday_special_overtime_ns_hours'] : 0); ?></td>
            <!-- RESTDAY LEGAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['total_hours_worked'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['restday_legal_overtime_hours']: 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_restday_legal_ot ? $a['restday_legal_overtime_ns_hours'] : 0 ); ?></td>  
            <!-- SPECIAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['total_hours_worked'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['special_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_special_ot ? $a['special_overtime_ns_hours'] : 0 ); ?></td> 
            <!-- LEGAL -->
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['total_hours_worked'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['legal_overtime_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['night_shift_hours'] : 0 ); ?></td> 
            <td align="left" valign="top" style="border-bottom:none;"><?php echo ($has_legal_ot ? $a['legal_overtime_ns_hours'] : 0 ); ?></td>
        </tr>
    <?php } ?>
</table>