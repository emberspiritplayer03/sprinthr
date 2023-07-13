<div class="payslip_period_container">
	<div>
    	<h2><b class="blue"><?php echo $schedule_name; ?></b>&nbsp;&nbsp;<span class="smaller normal"><?php echo $schedule_date_time; ?></span></h2>
    </div>
</div>
<div id="show_schedule_page_id">
<div class="buttons_holder">
<a class="tooltip edit_button" title="Edit this schedule" onclick="javascript:editWeeklySchedule('<?php echo $public_id;?>')" href="javascript:void(0)"><strong></strong>Edit Schedule</a>&nbsp;&nbsp;&nbsp;
<a class="tooltip add_button" title="Add employees to this schedule" onclick="javascript:importEmployeesInSchedule('<?php echo $public_id;?>')" href="javascript:void(0)"><strong><i class="icon-arrow-left"></i></strong>Import Employees</a>&nbsp;&nbsp;&nbsp;
<a class="relative delete_link red" title="Delete this schedule" onclick="javascript:deleteSchedule('<?php echo $public_id;?>')" href="javascript:void(0)"><span class="delete"></span>Delete Schedule</a>
</div>
<div id="status_message"></div>
<div id="schedule_members_list"></div>
</div>

<!--<div style="display:none" id="assign_schedule_groups_id">
<?php //include 'application/views/schedule/forms/ajax_assign_schedule_groups_form.php';?>
</div>-->

<!--<div style="display:none" id="assign_employees_groups_id">
<?php //include 'application/views/schedule/forms/ajax_assign_schedule_employees_form.php';?>
</div>-->

<script>
showScheduleMembersList('#schedule_members_list', '<?php echo $public_id;?>');	
//$('.tooltip').tipsy({gravity: 's'});
</script>