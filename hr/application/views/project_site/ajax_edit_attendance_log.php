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
<form method="post" id="edit_attendance_log" name="edit_attendance_log" action="<?php echo url("project_site/_update_site_attendance_log"); ?>">
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="eid_in" id="eid" value="<?php echo Utilities::encrypt($at_time_in->getId()); ?>" />
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
                        <input type="text" class="validate[required] " name="a_time_in" id="a_time_in" value="<?php echo $at_time_in->getTimeIn(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Time Out:</td>
                    <td>
                        <input type="text" class="validate[required] " name="a_time_out" id="a_time_out" value="<?php echo $at_time_in->getTimeOut(); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Project Site:</td>
                    <td>
                        <select name="a_project_site_in" id="a_type">
                            <?php foreach ($project_sites as $project_site) { ?>
                                <option value="<?php echo $project_site->id; ?>" <?php echo (strtoupper($at_time_in->getProjectSiteId()) == strtoupper($project_site->id) ? 'selected="selected"' : ""); ?>><?php echo strtoupper($project_site->projectname); ?></option>
                            <?php } ?>
                        </select>
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