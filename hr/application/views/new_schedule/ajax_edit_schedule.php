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

        $('#a_break_out').timepicker({
            'minTime': '8:00 am',
            'maxTime': '7:30 am',
            'timeFormat': 'g:i a'
        });

        $('#a_break_in').timepicker({
            'minTime': '8:00 am',
            'maxTime': '7:30 am',
            'timeFormat': 'g:i a'
        });
    });
</script>
<form method="post" id="edit_attendance_log" name="edit_attendance_log" action="<?php echo url("new_schedule/_edit_schedule"); ?>">
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="id" id="id" value="<?php echo $schedule_template->getId(); ?>" />
    <br>
    <div id="form_main" class="inner_form popup_form">
        <div id="form_default">
            <table>
                <tr>
                    <td class="field_label">Schedule Type:</td>
                    <td>
                        <input readonly type="text" class="validate[required] " name="schedule_type" id="a_date" value="<?php echo $schedule_template->getScheduleType(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Schedule Name:</td>
                    <td>
                        <input type="text" class="validate[required] " name="name" id="a_date" value="<?php echo $schedule_template->getName(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Required Hours:</td>
                    <td>
                        <input type="text" class="validate[required] " name="required_working_hours" id="a_date" value="<?php echo $schedule_template->getRequiredWorkingHours(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Schedule In:</td>
                    <td>
                        <input type="<?php echo ($schedule_template->getScheduleType() == "Staggered" || $schedule_template->getScheduleType() == "Compress") ? "hidden" : "" ?>" class="validate[required] " name="schedule_in" id="a_time_in" value="<?php echo $schedule_template->getScheduleIn(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Schedule Out:</td>
                    <td>
                        <input type="<?php echo ($schedule_template->getScheduleType() == "Staggered" || $schedule_template->getScheduleType() == "Compress") ? "hidden" : "" ?>" class="validate[required] " name="schedule_out" id="a_time_out" value="<?php echo $schedule_template->getScheduleOut(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Break Out:</td>
                    <td>
                        <input type="<?php echo ($schedule_template->getScheduleType() == "Staggered" || $schedule_template->getScheduleType() == "Compress") ? "hidden" : "" ?>" class="validate[required] " name="break_out" id="a_break_out" value="<?php echo $schedule_template->getBreakOut(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Break In:</td>
                    <td>
                        <input type="<?php echo ($schedule_template->getScheduleType() == "Staggered" || $schedule_template->getScheduleType() == "Compress") ? "hidden" : "" ?>" class="validate[required] " name="break_in" id="a_break_in" value="<?php echo $schedule_template->getBreakIn(); ?>" />
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