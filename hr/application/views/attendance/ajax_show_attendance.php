<?php
$path = 'application/views/attendance/_helper.php';
include $path;?>
<div class="additional_info_container">
<div align="right" class="float-right"><a class="blue_button" href="<?php echo url('attendance/download_timesheet_breakdown_by_employee_and_period?employee_id='. $encrypted_employee_id .'&from='. $start_date .'&to='. $end_date);?>"><i class="icon-download-alt icon-white"></i> Download Timesheet</a> 
<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
	<!--<a class="blue_button" onclick="javascript:updateAttendanceByEmployee('<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" href="javascript:void(0)"><i class="icon-repeat icon-white"></i> Update Attendance</a>-->
<?php } ?>
</div>
<h2>Period: <?php echo date('M j', strtotime($start_date));?> - <?php echo date('M j, Y', strtotime($end_date));?></h2></div>

<div class="container_12 npbutton_container">
    <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">
		<?php if ($previous_encrypted_employee_id != ''){ ?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $previous_encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>', '<?php echo $previous_employee_name;?>')" class="tooltip_prev" title="Load previous employee"><span>Previous</span></a>
        <?php }else { ?>
        	<strong class="disabled_prev"><span>Previous</span></strong>
        <?php };?>
        <h4 class="blue">Employee</h4>
        <?php if ($next_encrypted_employee_id != '') {?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $next_encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>', '<?php echo $next_employee_name;?>')" class="tooltip_next" title="Load next employee"><span>Next</span></a>
        <?php }else { ?>
        	<strong class="disabled_next"><span>Next</span></strong>
        <?php };?>
    </div>
    <div class="col_1_2 nextprevious_record nprecord_standalone" align="center">
		<?php if ($previous_start_date != '') {?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container', '<?php echo $encrypted_employee_id;?>', '<?php echo $previous_start_date;?>', '<?php echo $previous_end_date;?>')" class="tooltip_prev" title="Load previous timesheet"><span>Previous</span></a>     
        <?php } else {?>
        	<strong class="disabled_prev"><span>Previous</span></strong>
        <?php };?>
        <h4 class="blue">Timesheet</h4>
        <?php if ($next_start_date != '') {?>
        	<a href="javascript:void(0)" onclick="javascript:showAttendanceFromNavigation('#attendance_container','<?php echo $encrypted_employee_id;?>', '<?php echo $next_start_date;?>', '<?php echo $next_end_date;?>')" class="tooltip_next" title="Load next timesheet"><span>Next</span></a>	
        <?php } else {?>
        	<strong class="disabled_next"><span>Next</span></strong>
        <?php };?>
    </div>
    <div class="clear"></div> 
</div>
<!--<div>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importTimesheet()">Import Timesheet</a>
<a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()">Import Changed Schedule</a>
<br /><br /></div>-->
<table width="100%" class="formtable manydetails">  
  <thead>
  <tr>
    <th>Date</th>
    <th>Day</th>
    <th>&nbsp;</th>
    <th>Attendance</th>
    <th>Time In-Out</th>
    <th>Overtime In-Out</th>
    <th>Late / Overtime</th>
    <!--<td width="34%" bgcolor="#efefef"><strong>Time In - Out</strong></td>-->
    <th width="100">&nbsp;</th>
  </tr>
  </thead>  
	<?php foreach ($dates as $date):?>
    <?php
	$attendance_string = '';
	$a = $attendance[$date];
	if ($a):
		$attendance_string = get_attendance_string($a);
		$t = $a->getTimesheet();
		$is_present = $a->isPresent();
		$is_paid = $a->isPaid();
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
            	<?php if (!$is_paid):?>
                	<span style="float:left" title="This attendance is 'without pay'" class="ui-icon ui-icon-alert edit"></span>
                <?php endif;?>            
            </td>
            <td>
				<?php echo $attendance_string;?>
                <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                	<a title="Edit Attendance" href="javascript:void(0)" onclick="javascript:editAttendance('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" class="link_option edit"><i class="icon-edit"></i> Edit</a>
                <?php } ?>
            </td>
            <td valign="top">
            	<?php if (($t->getTimeIn() == '00:00:00' || $t->getTimeIn() == '') && ($t->getTimeOut() == '00:00:00' || $t->getTimeOut() == '')):?>      
                	 <small>-</small>
            	<?php else:?>
                	<?php if ($is_present):?>
                	<small><?php echo Tools::timeFormat($t->getTimeIn());?> - <?php echo Tools::timeFormat($t->getTimeOut());?></small>                    
                    <?php endif;?>
                <?php endif;?>
                <?php if ($is_present):?>
                	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                	<a title="Edit Schedule and Actual Time" href="javascript:void(0)" onclick="javascript:editTimeInOut('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" class="link_option edit"><i class="icon-edit"></i> Edit</a>
                    <?php } ?>
                <?php endif;?>
            </td>
            <td valign="top">
            	<?php if (($t->getOverTimeIn() == '00:00:00' || $t->getOverTimeIn() == '') && ($t->getOverTimeOut() == '00:00:00' || $t->getOverTimeOut() == '')):?>      
                	 <small>-</small>
            	<?php else:?>
                	<?php if ($is_present):?>
                	<small><?php echo Tools::timeFormat($t->getOverTimeIn());?> - <?php echo Tools::timeFormat($t->getOverTimeOut());?></small>
                    <?php endif;?>
                <?php endif;?>            
                <?php if ($is_present):?>
                	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
                		<a title="Edit Overtime" href="javascript:void(0)" onclick="javascript:editOvertimeInOut('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" class="link_option edit"><i class="icon-edit"></i> Edit</a>
                    <?php } ?>
                <?php endif;?>
                <?php if (($t->getOverTimeIn() != '00:00:00' && $t->getOverTimeIn() != '') && ($t->getOverTimeOut() != '00:00:00' && $t->getOverTimeOut() != '')):?> 
                	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
	                	<a title="Delete Overtime" href="javascript:void(0)" onclick="javascript:deleteOvertimeByEmployeeAndDate('<?php echo $encrypted_employee_id;?>', '<?php echo $date;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>');" class="link_option edit"><i class="icon-trash"></i> Delete</a>
                    <?php } ?>
                <?php endif;?>        
            </td>
            <td valign="top">                        
            <?php if ($is_present):?>
            	<small><?php echo Tools::convertHourToTime($t->getLateHours());?> / <?php echo Tools::convertHourToTime($t->getOvertimeHours());?>
                <?php //echo Tools::convertHourToTime($t->getUndertimeHours());?>
                </small>
            <?php endif;?>
            </td>
        <td width="100" valign="top">
        	<?php if ($is_present):?>
            	<a title="Show Details" href="javascript:void(0)" onclick="javascript:editTimesheet('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>','<?php echo Utilities::encrypt($is_period_lock) ?>')" class="link_option edit"><i class="icon-align-justify"></i> Show Details</a><!--<a title="Show Details" href="javascript:void(0)" onclick="javascript:showTimesheet('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>')" class="link_option">Details</a>-->
            <?php endif;?>
        </td>
        </tr>
    <?php else:?>
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
            <td>&nbsp;</td>
            <td>-
            <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>
	            <a title="Edit Attendance" href="javascript:void(0)" onclick="javascript:editAttendance('<?php echo $date;?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $start_date;?>', '<?php echo $end_date;?>')" class="link_option edit"><i class="icon-edit"></i> Edit</a></td>
           	<?php } ?>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
            <td valign="top">&nbsp;</td>
        <td width="100" valign="top">&nbsp;</td>
        </tr>
    <?php endif;?>           
	<?php endforeach;?>
</table>

<script language="javascript">		
$('.edit').tipsy({gravity: 's'});
</script>