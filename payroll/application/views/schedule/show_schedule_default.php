<div id="detailscontainer" class="detailscontainer_blue view_schedule_holder">
    <div id="applicant_details">
    	<div id="applicant_details">    
            <div id="form_main">
                <h2 class="field_title blue" style="font-size:22px;"><i class="icon-list-alt icon-fade vertical-middle"></i> <?php echo $schedule_name; ?></h2>
                <div class="form_separator"></div>
                <div class="ui-state-highlight ui-corner-all" style="font-weight:normal; margin-bottom:10px;">
                    <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                    <i>This is the default schedule. All employees without assigned schedule will be using this default schedule.</i>
                </div>    
                <div id="status_message"></div>
                <div class="view_schedule"><?php echo $schedule_date_time; ?></div>
                <div id="form_default" class="yellow_form_action_section form_action_section yellow_section" align="center">
                    <a class="tooltip edit_button" title="Edit this schedule" onclick="javascript:editWeeklySchedule('<?php echo $public_id;?>')" href="javascript:void(0)"><strong></strong>Edit Schedule</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>	
//$('.tooltip').tipsy({gravity: 's'});
</script>