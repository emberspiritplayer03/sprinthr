<script>
    $(document).ready(function() {
        /*$("#a_date").datepicker({
        	dateFormat:'yy-mm-dd',
        		changeMonth:true,
        		changeYear:true,
        		showOtherMonths:true			
        });*/

        $('#a_time_in').timepicker({
            'minTime': '8:00 am',
            'maxTime': '7:30 am',
            'timeFormat': 'g:i a'
        });

        $('#a_time_out').timepicker({
            'minTime': '8:00 am',
            'maxTime': '7:30 am',
            'timeFormat': 'g:i a'
        });
    });
</script>
<form method="post" id="edit_attendance_log" name="edit_attendance_log" action="<?php echo url("new_schedule/_edit_employee_schedule"); ?>">
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="v2_employee_attendance_id" id="token" value="<?php echo $at_time_in->getId(); ?>" />
    <input type="hidden" name="employee_id" id="token" value="<?php echo $at_time_in->getEmployeeId(); ?>" />
    <center>
	<h4>Change Schedule</h4>
	<?php
			foreach($schedule as $schedule_name){?>
			
				<input type="radio" id="module[contact_details]" name="schedule_id" value="<?php echo $schedule_name->getId();?>" class="field_label" <?php echo ($schedule_name->getId() == 1) ? "checked" : ""?> /> 
                <?php
					echo $schedule_name->getName();
				?>
				
			<?php }
		?>
	</center>
    <br>
    <div id="form_main" class="inner_form popup_form">
        <div id="form_default">
            <table>
                <tr>
                    <td class="field_label">Date:</td>
                    <td>
                        <input readonly type="text" class="validate[required] " name="a_date_in" id="a_date" value="<?php echo Tools::convertDateFormat($at_time_in->getDate()); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Time In:</td>
                    <td>
                        <input readonly type="text" class="validate[required] " name="a_time_in" id="a_time_in" value="<?php echo $at_time_in->getTimeIn(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Time Out:</td>
                    <td>
                        <input readonly type="text" class="validate[required] " name="a_time_out" id="a_time_out" value="<?php echo $at_time_in->getTimeOut(); ?>" />
                    </td>
                </tr>
            </table>
            <br />
            <div id="form_default" class="form_action_section">
            <table width="100%">
                <tr>
                    <td class="field_label">&nbsp;</td>
                    <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
                </tr>
            </table>
        </div>
    </div>
</form>