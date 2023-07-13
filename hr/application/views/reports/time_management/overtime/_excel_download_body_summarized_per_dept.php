<br /><br />

<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td align="center" valign="middle" style="vertical-align:middle;"><strong>Department/Section</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Contractual</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Probationary</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Regular</strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><strong>Grand Total</strong></td>
    </tr>
    <?php
        $grand_total      = 0;
        $grand_total_a    = array();
    ?>
    <?php ksort($overtime); ?>
    <?php foreach($overtime as $key_dept => $ot_data) { ?>

    <?php
        /*
         * Employment Status
           1 = Full Time
           2 = Part Time
           3 = Regular
           4 = Probationary
           5 = Contractual
        */

        $regular_total      = 0;
        $probationary_total = 0;
        $contractual_total  = 0;
        $sub_total_ot       = 0;

        $restday_hrs        = 0;        
        $holiday_hrs        = 0;        
        $scheduled_hrs      = 0;
        $undertime_hrs      = 0;
        $late_hrs           = 0;

        $total_ot           = 0;

        foreach($ot_data as $ot_d) {
            $scheduled_hrs = $ot_d['total_schedule_hours'];
            $undertime_hrs = $ot_d['undertime_hours'];
            $late_hrs      = $ot_d['late_hours'];
            $is_restday    = $ot_d['is_restday'];
            $is_holiday    = $ot_d['is_holiday'];

            if( $ot_d['scheduled_time_in'] != '' || $ot_d['scheduled_time_out'] != ''){

                if($ot_d['is_present'] == 1) {

                    if($ot_d['employment_status_id'] == 3) {
                        
                        if($is_restday == 1) {
                            //$restday_hrs = ( $scheduled_hrs - ($undertime_hrs + $late_hrs) );
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_schedule_hours'] + $ot_d['total_overtime_hours'] - ($undertime_hrs + $late_hrs);
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_hours_worked'];
                            }   
                        } elseif($is_holiday == 1) {
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = ($ot_d['total_schedule_hours'] + $ot_d['total_overtime_hours'] - ($undertime_hrs + $late_hrs));
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = ($ot_d['total_hours_worked'] - ($undertime_hrs + $late_hrs));
                            }                       
                        } else {
                            $total_ot = $ot_d['total_overtime_hours'];
                        }              

                        //$regular_total += $ot_d['total_overtime_hours'] + $restday_hrs + $holiday_hrs;
                        $regular_total += $total_ot;
                    }elseif($ot_d['employment_status_id'] == 4) {

                        if($is_restday == 1) {
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_schedule_hours'] + $ot_d['total_overtime_hours'] - ($undertime_hrs + $late_hrs);
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_hours_worked'];
                            }   

                        } elseif($is_holiday == 1) {
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = (($ot_d['total_schedule_hours'] + $ot_d['total_overtime_hours']) - ($undertime_hrs + $late_hrs));
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = ($ot_d['total_hours_worked'] - ($undertime_hrs + $late_hrs));
                            }                             
                        } else {
                            $total_ot = $ot_d['total_overtime_hours'];
                        }  

                        //$probationary_total += $ot_d['total_overtime_hours'];
                        $probationary_total += $total_ot;
                    }elseif($ot_d['employment_status_id'] == 5) {

                        if($is_restday == 1) {
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_schedule_hours'] + $ot_d['total_overtime_hours'];
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = $ot_d['total_hours_worked'];
                            }   

                        } elseif($is_holiday == 1) {
                            if($ot_d['total_hours_worked'] >= $ot_d['total_schedule_hours']) {
                                $total_ot = ($ot_d['total_schedule_hours'] - ($undertime_hrs + $late_hrs));
                            }elseif($ot_d['total_hours_worked'] <= $ot_d['total_schedule_hours']) {
                                $total_ot = ($ot_d['total_hours_worked'] - ($undertime_hrs + $late_hrs));
                            }                             
                        } else {
                            $total_ot = $ot_d['total_overtime_hours'];
                        }

                        //$contractual_total += $ot_d['total_overtime_hours'];   
                        $contractual_total += $total_ot;
                    }

                }

            }

        }

        //$sub_total_ot = ($regular_total + $probationary_total + $contractual_total);
        $sub_total_ot = ($regular_total + $probationary_total + $contractual_total);
    ?>

    <tr>
        <td align="center" valign="middle" style="width:90pt; vertical-align:middle;"><strong><?php echo $key_dept; ?></strong></td>
        <td align="center" valign="top" style="border-bottom:none;"><?php echo number_format($contractual_total,2); ?>&nbsp;</td>
        <td align="center" valign="top" style="border-bottom:none;"><?php echo number_format($probationary_total,2); ?>&nbsp;</td>
        <td align="center" valign="top" style="border-bottom:none;"><?php echo number_format($regular_total,2); ?>&nbsp;</td>
        <td align="center" valign="top" style="border-bottom:none;"><?php echo number_format($sub_total_ot,2); ?>&nbsp;</td>
    </tr>
    <?php
        $grand_total += $sub_total_ot;
    ?>
	<?php } ?>
    <tr>
    	<td colspan="4" align="right" valign="top" style="border-bottom:none;"><b>Grand Total: </b></td>    
        <td align="center" valign="top" style="border-bottom:none;"><b><?php echo number_format($grand_total,2); ?>&nbsp;</b></td>    
    </tr>
</table>	