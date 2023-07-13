<div id="detailscontainer" class="detailscontainer_blue view_schedule_holder">
    <div id="applicant_details">
    	<div id="applicant_details">    
            <div id="form_main">
                <h2 class="field_title blue" style="font-size:22px;"><i class="icon-list-alt icon-fade vertical-middle"></i> <?php echo $schedule_name; ?></h2>
                Start Date : <strong><?php echo Tools::convertDateFormat($effectivity_date); ?></strong> | End Date : <strong><?php echo Tools::convertDateFormat($end_date); ?></strong>
                <div class="form_separator"></div>
                <div class="view_schedule">
                    <?php echo $schedule_date_time; ?><br />
                    <?php if($breaktime != ''){ ?>
                        <div class="item-detail-styled"><i class="icon-time icon-fade vertical-middle"></i> <b>Breaktime Schedule(s) : <?php echo $breaktime; ?></b></div>                
                    <?php } ?>
                </div>                
                <div id="form_default" class="yellow_form_action_section form_action_section yellow_section" align="center">
                    <?php echo $btn_edit_schedule;?>&nbsp;&nbsp;&nbsp;
                    <?php echo $btn_delete_schedule;?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="status_message"></div>
<?php echo $btn_import_employee; ?>
<div id="schedule_members_list"></div>

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