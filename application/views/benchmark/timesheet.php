
<?php
    $path = 'timesheet/_helper.php';
    include $path;
?>
<div class="additional_info_container" style="position:relative;">
    <h2>Period: <?php echo date('M j', strtotime($start_date));?> - <?php echo date('M j, Y', strtotime($end_date));?></h2>        
</div>

<table width="100%" border="1">  
  <thead>
  <tr>
    <th>Date</th>
    <th>Day</th>
    <th>&nbsp;</th>
    <th>Attendance</th>
    <th>Time In-Out</th>
    <th>Overtime In-Out</th>
    <th>OT Hours</th>
    <th>Late Hours</th>
    <th>Undertime Hours</th>
  </tr>
  </thead>  
	<?php foreach ($dates as $date):?>
    <?php
	$attendance_string = '';
	$a = $attendance[$date];
	if ($a):        
        $eid = Utilities::encrypt($a->getId());
		$attendance_string = get_attendance_string($a);
        if( $a->isOfficialBusiness() ){
            $attendance_string .= " (OB)";
        }
		$t = $a->getTimesheet();
		$is_present = $a->isPresent();
		$is_paid = $a->isPaid();

        $breaktime_late = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($employee_id, $date, $date);
	?>
		<tr>
        	<td><small><?php echo date('m/d', strtotime($date));?></small></td>
            <td>
			<?php 
				if (date('D', strtotime($date)) == 'Sun' || date('D', strtotime($date)) == 'Sat') {
					?><small style="color:#999999"><?php echo date('D', strtotime($date));?></small><?php
				} else {
					?><small><?php echo date('D', strtotime($date));?></small><?php
				}				
			?>            
            </td>
            <td>

            </td>
            <td>
                <?php
                    if(!$is_present && !$is_paid && $t->getTimeIn() != '' && $t->getTimeOut() != '' ){
                        echo '<span class="absent-font-style">Incorrect Shift</span>';
                    } else {
                        echo $attendance_string;
                    }
                ?>
				<?php //echo $attendance_string; ?>
            </td>
            <td valign="top">
            	<?php if (($t->getTimeIn() == '00:00:00' || $t->getTimeIn() == '') && ($t->getTimeOut() == '00:00:00' || $t->getTimeOut() == '')){?>      
                	       <small>-</small>
            	<?php }else{?>
                        <?php if(!$is_present && !$is_paid && $t->getTimeIn() != '' && $t->getTimeOut() != '' ){ ?>
                                <small><?php echo Tools::timeFormat($t->getTimeIn());?> - <?php echo Tools::timeFormat($t->getTimeOut());?></small>  
                        <?php } else { ?>
                                <?php if ($is_present) {?>
                                <small><?php echo Tools::timeFormat($t->getTimeIn());?> - <?php echo Tools::timeFormat($t->getTimeOut());?></small>                    
                                <?php }elseif($t->getTimeIn()) { ?>
                                <small><?php echo Tools::timeFormat($t->getTimeIn());?> - No Out</small>  
                                <?php }elseif($t->getTimeOut()) { ?>
                                <small>No In - <?php echo Tools::timeFormat($t->getTimeOut());?></small>  
                                <?php } ?>
                        <?php } ?>
                <?php } ?>
            </td>
            <td valign="top">
            	<?php if (($t->getOverTimeIn() == '00:00:00' || $t->getOverTimeIn() == '') && ($t->getOverTimeOut() == '00:00:00' || $t->getOverTimeOut() == '')):?>      
                	 <small>-</small>
            	<?php else:?>
                	<?php if ($is_present):?>
                	<small><?php echo Tools::timeFormat($t->getOverTimeIn());?> - <?php echo Tools::timeFormat($t->getOverTimeOut());?></small>
                    <?php endif;?>
                <?php endif;?>
            </td>
            <td valign="top">
                <?php if ($is_present):?>
                    <small>
                        <?php echo Tools::convertHourToTime($t->getTotalOvertimeHours());?>
                    </small>
                <?php else:?>
                    <small>-</small>
                <?php endif;?>
            </td>
            <td valign="top">                        
            <?php if ($is_present):?>
                 <?php if($breaktime_late) { ?>
                            <?php
                                $total_late_hrs = $t->getLateHours() + $breaktime_late;
                            ?>
                            <small>
                                <?php echo Tools::convertHourToTime($total_late_hrs);?>
                            </small>
                 <?php } else { ?>
                            <small>
                                <?php echo Tools::convertHourToTime($t->getLateHours());?>
                            </small>
                 <?php } ?>

                <?php else:?>
                <small>-</small>
            <?php endif;?>
            </td>
            <td valign="top">
                <?php if ($is_present):?>
                    <small>
                        <?php echo Tools::convertHourToTime($t->getUndertimeHours());?>
                    </small>
                <?php else:?>
                    <small>-</small>
                <?php endif;?>
            </td>
        </tr>
    <?php else:?>
		<tr>
        	<td><small><?php echo date('m/d', strtotime($date)); ?></small></td>
            <td>
			<?php 
				if (date('D', strtotime($date)) == 'Sun' || date('D', strtotime($date)) == 'Sat') {
					?><small style="color:#999999"><?php echo date('D', strtotime($date));?></small><?php
				} else {
					?><small><?php echo date('D', strtotime($date));?></small><?php
				}				
			?>            
            </td>
            <td>&nbsp;</td>
            <td>-</td>
            <td valign="top">-</td>
            <td valign="top">-</td>
            <td valign="top">-</td>
            <td valign="top">-</td>
            <td width="100" valign="top">-</td>
            <td valign="top" data-label="<?php echo strtotime($date); ?>">
                
            </td>
        </tr>
    <?php endif;?>           
	<?php endforeach;?>
</table>