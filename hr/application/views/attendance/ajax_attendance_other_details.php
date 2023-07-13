<style>
.tbl-other-details{border:none;}
.tbl-other-details td{border: none;}
.tbl-other-details td.field_label{width:176px;}
.lbl-attendance-other-details{background-color:#198cc9;color:#ffffff;padding-left: 10px;margin:0 0 6px;}
ul.list-attendance-other-details{list-style: none;}
ul.list-attendance-other-details li{display: inline-block; margin-left:24px; padding:6px;width:40%;background-color:#e3e3e3;margin-bottom: 32px}
</style>
<ul class="list-attendance-other-details">
<?php if( !empty($schedule) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Assigned Schedule</h3>
        <table class="tbl-other-details">
        <?php foreach($schedule as $key => $value){ ?>    
            <?php if($value != "0"){ ?>         
                <?php if($key == 'Time Out' && $value == '04:59 AM') { ?>
                        <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo '05:00 AM'; ?></td></tr>  
                <?php }else if($key == 'Total Required Working HRS' && $value == '9.48') { ?>
                        <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo number_format(9.50 - $total_hrs_deductible, 2); ?></td></tr>  
                <?php }else if($key == 'Total Required Working HRS') { ?>
                        <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo number_format($value - $total_hrs_deductible, 2); ?></td></tr>  
                <?php } else { ?>
                        <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>  
                <?php } ?>  
            <?php } ?>
        <?php } ?>
        </table>
    </li>
<?php } ?>

<?php if( !empty($breaktime) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Breaktime Schedule</h3>
        <table class="tbl-other-details">
        <?php foreach($breaktime as $key => $value){ ?>
            <tr>
                <td class="field_label" colspan="1">Sched: </td>
                <td class="field_label" colspan="1"><?php echo $value; ?></td>
            </tr>                  
        <?php } ?>   
        <?php if(!empty($break)) {?>
            <?php foreach($break as $bkey => $bkey_d) { ?> 
                <?php if($bkey == 'time_in') { ?>
                    <tr>
                        <td class="field_label" colspan="1">Time In: </td>
                        <td class="field_label" colspan="1"><?php echo $bkey_d; ?></td>
                    </tr>
                <?php } ?>
                <?php if($bkey == 'time_out') { ?>
                    <tr>
                        <td class="field_label" colspan="1">Time Out: </td>
                        <td class="field_label" colspan="1"><?php echo $bkey_d; ?></td>
                    </tr>
                <?php } ?>            
            <?php } ?>  
        <?php } ?>       
        <tr>
            <td class="field_label" colspan="1">Total Schedule Break HRS: </td>
            <td class="field_label" colspan="1"><?php echo $breaktime_hrs; ?></td>
        </tr>                   
        </table>
    </li>
<?php } ?>
<!-- <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>    -->
<?php if( !empty($attendance) ) { ?>    
    <li>
        <h3 class="lbl-attendance-other-details">Attendance</h3>
        <table class="tbl-other-details">
        <?php foreach($attendance as $key => $value){ ?> 
                       <?php if($value != "0" && $key != 'Total HRS Worked (Less Break Time)'){ ?>             
                <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>        
            <?php }
                elseif($key == 'Total HRS Worked (Less Break Time)'){
                  $break_deduction = 0;
                  $actual_break_deduction = 0;
                  
            $eid = $emp_id;
           
            $e    = G_Employee_Finder::findById($eid);
            $a    = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            $ts = $a->getTimesheet();
            $total_hours_work = $ts->getTotalHoursWorked();
           
            // var_dump($a->getScheduledDateIn());
            // echo "<pre>";
            // var_dump($ts->getTimeOut());
            // echo "</pre>";
            $actual_time_in = $ts->getTimeIn();
            $actual_time_out = $ts->getTimeOut();
            
            $schedules['schedule_in']  = $ts->getScheduledTimeIn();
            $schedules['schedule_out'] = $ts->getScheduledTimeOut();
            $day_type = array();
            $is_holiday = $a->isHoliday();
            
            if( $is_holiday == 1 && !empty($is_holiday) ){
                $h = $a->getHoliday();

                if( $h->getType() == Holiday::LEGAL ){
                    $day_type[] = "applied_to_legal_holiday";
                }else{
                    $day_type[] = "applied_to_special_holiday";
                }
            }elseif( $a->isRestday() == 1 ){

                if( $value['total_schedule_hours'] > 0 ){                   
                    $day_type[] = "applied_to_restday";
                }else{                    
                    $day_type[] = "applied_to_regular_day";
                }
            }else{
                $day_type[] = "applied_to_regular_day";
            }


            $break = G_Employee_Breaktime_Finder::findByEmployeeIdAndDate($eid,$date);
            $break_time_schedules = $e->getEmployeeBreakTimeBySchedule($schedules, $day_type);
           
           $seperate_breaktime_schedule = (explode("to",$break_time_schedules[0]));
           $break_time_in = date('H:i:s',strtotime($seperate_breaktime_schedule[0]));
           $break_time_out = date('H:i:s',strtotime($seperate_breaktime_schedule[1]));

          
          
            if( (strtotime($actual_time_out) > strtotime($break_time_in)) && (strtotime($actual_time_out) < strtotime($break_time_out))){
                
                if(($actual_time_out > $break_time_in && $actual_time_out < $break_time_out)){
                    $time1 = strtotime($actual_time_out);
                    $time2 = strtotime($break_time_out);
                    $difference = round(abs($time2 - $time1) / 3600,2);
                    $break_deduction = $difference;
                }
                else{
                    $break_deduction = 0;
                }
            }else{

                $break_deduction = 0;

                if( ( strtotime($break_time_in) >= strtotime($actual_time_in) && strtotime($break_time_in) <= strtotime($actual_time_out)) && ( strtotime($break_time_out) >= strtotime($actual_time_in) && strtotime($break_time_out) <= strtotime($actual_time_out)) ){
                    $break_deduction = 0;

                }elseif((strtotime($actual_time_in) > strtotime($break_time_in) && strtotime($actual_time_in) < strtotime($break_time_out))){
                    $time1 = strtotime($actual_time_in);
                    $time2 = strtotime($break_time_out);
                    $difference = round(abs($time2 - $time1) / 3600,2);
                    $difference = round(abs($time2 - $time1) / 3600,2);
                    $break_deduction = $difference;

                }                
              }

              if( !empty($break_logs_summary) ) {
                  $actual_break_deduction = $break_logs_summary->getTotalBreakHrs();
              }
              ?>


              <!-- <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo number_format($total_hours_work - $break_deduction,2) ?></td></tr>  -->
              <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo number_format($total_hours_work,2) ?></td></tr> 
<?php

                }

             ?>
        <?php } ?>
        </table>
    </li>
<?php } ?>

<?php if( !empty($break_logs_summary) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Breaktime Logs</h3>
        <table class="tbl-other-details">
            <!-- Break1 -->
            <?php if($break_logs_summary->getRequiredLogBreak1()){ ?>
                <tr>
                    <td class="field_label" colspan="1">Break1 Out:</td>
                    <?php if($break_logs_summary->getLogBreak1Out()){ ?>
                        <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak1Out())); ?></td>
                    <?php } else { ?>
                        <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break1 Out</td>
                    <?php } ?>
                </tr>
                    <tr>
                        <td class="field_label" colspan="1">Break1 In:</td>
                        <?php if($break_logs_summary->getLogBreak1In()){ ?>
                            <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak1In())); ?></td>
                        <?php } else { ?>
                            <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break1 In</td>
                        <?php } ?>
                    </tr>
            <?php } ?>

            <!-- Break2 -->
            <?php if($break_logs_summary->getRequiredLogBreak2()){ ?>
                <tr>
                    <td class="field_label" colspan="1">Break2 Out:</td>
                    <?php if($break_logs_summary->getLogBreak2Out()){ ?>
                        <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak2Out())); ?></td>
                    <?php } else { ?>
                        <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break2 Out</td>
                    <?php } ?>
                </tr>
                    <tr>
                        <td class="field_label" colspan="1">Break2 In:</td>
                        <?php if($break_logs_summary->getLogBreak2In()){ ?>
                            <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak2In())); ?></td>
                        <?php } else { ?>
                            <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break2 In</td>
                        <?php } ?>
                    </tr>
            <?php } ?>

            <!-- Break3 -->
            <?php if($break_logs_summary->getRequiredLogBreak3()){ ?>
                <tr>
                    <td class="field_label" colspan="1">Break3 Out:</td>
                    <?php if($break_logs_summary->getLogBreak3Out()){ ?>
                        <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak3Out())); ?></td>
                    <?php } else { ?>
                        <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break3 Out</td>
                    <?php } ?>
                </tr>
                    <tr>
                        <td class="field_label" colspan="1">Break3 In:</td>
                        <?php if($break_logs_summary->getLogBreak3In()){ ?>
                            <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogBreak3In())); ?></td>
                        <?php } else { ?>
                            <td class="field_label" colspan="1" style="background-color: #dc3545;color: #fff;text-align: center;">No Break3 In</td>
                        <?php } ?>
                    </tr>
            <?php } ?>

            <!-- OT Break1 -->
            <?php if($break_logs_summary->getLogOtBreak1Out()){ ?>
                <tr>
                    <td class="field_label" colspan="1">OT Break1 Out:</td>
                    <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogOtBreak1Out())); ?></td>
                </tr>
            <?php } ?>
            <?php if($break_logs_summary->getLogOtBreak1In()){ ?>
                <tr>
                    <td class="field_label" colspan="1">OT Break1 In:</td>
                    <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogOtBreak1In())); ?></td>
                </tr>
            <?php } ?>

            <!-- OT Break2 -->
            <?php if($break_logs_summary->getLogOtBreak2Out()){ ?>
                <tr>
                    <td class="field_label" colspan="1">OT Break2 Out:</td>
                    <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogOtBreak2Out())); ?></td>
                </tr>
            <?php } ?>
            <?php if($break_logs_summary->getLogOtBreak2In()){ ?>
                <tr>
                    <td class="field_label" colspan="1">OT Break2 In:</td>
                    <td class="field_label" colspan="1"><?php echo date('Y-m-d g:i A', strtotime($break_logs_summary->getLogOtBreak2In())); ?></td>
                </tr>
            <?php } ?>

            <!-- Total Break Hrs -->
            <?php if($break_logs_summary->getTotalBreakHrs()){ ?>
                <tr>
                    <td class="field_label" colspan="1">Total Break HRS:</td>
                    <td class="field_label" colspan="1"><?php echo number_format($break_logs_summary->getTotalBreakHrs(), 2) + 0; ?></td>
                </tr>
            <?php } ?>
        </table>
    </li>
<?php } ?>

<?php if( !empty($holiday) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Holiday</h3>
        <table class="tbl-other-details">
        <?php foreach($holiday as $key => $value){ ?>     
            <?php if($value != "0"){ ?>         
                <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>        
            <?php } ?>
        <?php } ?>
        </table>
    </li>
<?php } ?>

<?php if( !empty($tardiness) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Tardiness</h3>
        <table class="tbl-other-details">
        <?php foreach($tardiness as $key => $value){ ?>  
            <?php if($value != "0"){ ?>                  
                <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>        
            <?php } ?>
        <?php } ?>
        </table>
    </li>
<?php } ?>

<?php if( !empty($overtime) ) { ?>
    <li>
        <h3 class="lbl-attendance-other-details">Overtime</h3>
        <table class="tbl-other-details">
        <?php foreach($overtime as $key => $value){ ?>       
            <?php if($value != "0"){ ?>      
                <tr><td class="field_label"><?php echo $key; ?></td><td>: <?php echo $value; ?></td></tr>    
            <?php } ?>
        <?php } ?>
        </table>
    </li>
<?php } ?>
</ul>
