<br /><br />
<table border="1" cellpadding="2" cellspacing="1" style="font-size:9pt; width:836pt; line-height:12pt;">
	<tr>
        <td rowspan="2" align="center" valign="middle" style="width:90pt; "><strong>Employee Code</strong></td>
        <td rowspan="2" align="center" valign="middle" style="border-bottom:none;"><strong>Employee Name</strong></td>        
        <td rowspan="2" align="center" valign="middle" style="border-bottom:none;"><strong>Department</strong></td>
        <td rowspan="2" align="center" valign="middle" style="border-bottom:none;"><strong>Section</strong></td>
        <td rowspan="2" align="center" valign="middle" style="border-bottom:none;"><strong>Position</strong></td>
        <?php foreach($date_range as $value) { ?>
            <td align="center" valign="middle" style="border-bottom:none;"><strong><?php echo date('d-M-y',strtotime($value));?></strong></td>
        <?php } ?>
        <td rowspan="2" align="center" valign="top" style="border-bottom:none;"><strong>Total Hrs. Worked</strong></td>          
    </tr>
    <tr>
        <?php foreach($date_range as $value) { ?>
            <td align="center" valign="middle" style="border-bottom:none;"><strong>HRS</strong></td>
        <?php } ?>
    </tr>
	<?php foreach($actual_hours as $key => $a){ ?>
        <?php $g_total_hrs_worked = 0; ?>
    	<tr>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo $a['employee_code']; ?></td>            
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['lastname'],  MB_CASE_TITLE, "UTF-8") . ", " . mb_convert_case($a['firstname'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['department_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['section_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <td align="left" valign="top" style="border-bottom:none;"><?php echo mb_convert_case($a['position_name'],  MB_CASE_TITLE, "UTF-8"); ?></td>
            <?php foreach($date_range as $value) { ?>
                <?php 
                    if( $a['dates'][$value]['base_on_actual'] > 0 ){
                       
                        if($a['dates'][$value]['is_restday'] == 1 || $a['dates'][$value]['is_holiday'] == 1){
                                                        $get_total_hrs_worked = $a['dates'][$value]['base_on_actual'] - $a['dates'][$value]['total_breaktime_deductible_hours'];
                            
                            $break_total_hours_worked = explode(".",$get_total_hrs_worked);

                                 $hours = $break_total_hours_worked[0];
                                                                            $minutes = $break_total_hours_worked[1];
                          
                              $n = $get_total_hrs_worked;
                               $whole = floor($n);      // 1
                    $get_minutes_dec = $n - $whole;
                                   if(($hours) >= 8){
                     
                        if($get_minutes_dec < .25){
                
                            $get_minutes_dec = 0;
                        }elseif($minutes < .50){              
                            $get_minutes = .25;
                        }elseif($get_minutes_dec < .75){
                            $get_minutes = .50;
                        }else{
                            $get_minutes = .75;               
                        }
                        $total_hrs_worked = $hours + $get_minutes;
                    }else{
                        
                        $total_hrs_worked = $get_total_hrs_worked;
                    }

                         
                        }else{
                            $total_hrs_worked = $a['dates'][$value]['base_on_schedule'];
        
                        }
                        
                    }else{
                        $total_hrs_worked = 0;
                    }
                    $g_total_hrs_worked += number_format($total_hrs_worked,2); 
                ?>
                <td align="center" valign="top" style="border-bottom:none;"><?php echo ($a['dates'][$value] > 0 ? number_format($total_hrs_worked,2) : '0'); ?></td>  
            <?php } ?>      
            <td align="center" valign="top" style="border-bottom:none;"><?php echo $g_total_hrs_worked; ?></td>           
        </tr>
    <?php } ?>
</table>
