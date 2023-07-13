 <div class="payslip_period_container">
	<div>
    	<h2><b class="blue"><?php echo $schedule_name; ?></b>&nbsp;&nbsp;<span class="smaller normal"><?php echo $schedule_date_time; ?></span></h2>
    </div>
</div>
<div id="show_schedule_page_id">
    <div class="buttons_holder">
    <a class="tooltip edit_button" title="Edit this schedule" onclick="javascript:editWeeklySchedule('<?php echo $public_id;?>')" href="javascript:void(0)"><strong></strong>Edit Schedule</a>
    </div>
    <div class="ui-state-highlight ui-corner-all">
    	<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        This is the default schedule. All employees without assigned schedule will be using this default schedule.
    </div>    
    <div id="status_message"></div>    
</div>

<script>	
//$('.tooltip').tipsy({gravity: 's'});
</script>