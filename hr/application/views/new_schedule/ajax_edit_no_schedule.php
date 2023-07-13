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
<form method="post" id="edit_attendance_log" name="edit_attendance_log" action="<?php echo url("project_site/_edit_schedule"); ?>">
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="eid_in" id="eid" value="<?php echo $at_time_in->id; ?>" />
    <center>
        <h4>Assign Schedule</h4>
        <?php
        foreach ($schedule as $schedule_name) { ?>

            <input type="radio" id="module[contact_details]" name="schedule" value="<?php echo $schedule_name->getId(); ?>" class="field_label" <?php echo ($schedule_name->getId() == 1) ? "checked" : "" ?> />
            <?php
            if ($schedule_name->getName() == 'default staggered') {
                echo "Staggered";
            } else if ($schedule_name->getName() == 'default compress') {
                echo "Compress";
            } else if ($schedule_name->getName() == 'default shift') {
                echo "Shift";
            } else {
                echo $schedule_name->getName();
            }
            ?>

        <?php }
        ?>
    </center>
    <br>
    <div id="form_main" class="inner_form popup_form">
        <div id="form_default">
            <table>
                <tr>
                    <td class="field_label">Name:</td>
                    <td class="field_label">
                        <?php
                        $employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($at_time_in->id);
                        echo $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Time In:</td>
                    <td>
                        <input disabled type="text" class="" name="" id="" value="No Time In" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">Time Out:</td>
                    <td>
                        <input disabled type="text" class="" name="" id="" value="No Time Out" />
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