<style>
    .date_input {
        width: 44% !important;
    }
</style>

<form id="edit_schedule_form" method="post" action="<?php echo $action; ?>">
    <div id="form_main" class="inner_form popup_form wider">
        <div id="form_default">
            <table class="no_border" width="100%">
                <tr>
                    <td width="21%" class="field_label">*Name:</td>
                    <td width="79%"><input class="validate[required] text-input" type="text" name="name" id="name" value="<?php echo $group_name; ?>" /></td>
                </tr>
                <tr>
                    <td class="field_label">*Required hours:</td>
                    <td><input class="validate[required]" type="number" name="hours" id="" value="" /></td>
                </tr>
                <tr>
                    <td class="field_label">*Time In:</td>
                    <td>
                        <input class="validate[required] time_in" name="time_in" value="<?php echo $time_in; ?>" onchange="onStartTimeChanged(<?php echo 1; ?>)" type="text" id="flexible_start_time_<?php echo 1; ?>" style="width:60px" />
                    </td>
                </tr>
                <tr>
                    <td class="field_label">*Time Out:</td>
                    <td>
                        <input class="validate[required]" name="time_out" value="<?php echo $time_out; ?>" onchange="onEndTimeChanged(<?php echo 1; ?>)" type="text" id="flexible_end_time_<?php echo 1; ?>" style="width:60px" />
                    </td>
                </tr>
                <?php
                $clear_link_visible = '';
                if ($time_in == '' || $time_out == '') {
                    $clear_link_visible = 'style="display:none"';
                } ?>
            </table>
        </div>
        <span id="schedule_message"></span>
        <div id="form_default" class="form_action_section">
            <table class="no_border" width="100%">
                <tr>
                    <td class="field_label">&nbsp;</td>
                    <td>
                        <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>


<script>
    function onEndTimeChanged(number) {
        $('#is_changed').val('yes');
        if (number == 1) {
            var end_time = $('#end_time_' + number).val();
            if (end_time != '') {
                $('#copy_time_1').show();
            }
        }
    }

    function onStartTimeChanged(number) {
        var start_time_id = '#flexible_start_time_' + number;
        var end_time_id = '#flexible_end_time_' + number;
        var start_time = $('#start_time_' + number).val();
        var split_time = start_time.split(':');
        var hour = parseFloat(split_time[0]) + 9;
        var split_minutes = split_time[1].split(' ');
        var minutes = split_minutes[0];
        var am = split_minutes[1];

        $('#is_changed').val('yes');

        if (hour > 12) {
            hour = hour - 12;
        }

        if (am == 'pm') {
            am = 'am';
        } else {
            am = 'pm';
        }
        $(end_time_id).val(hour + ':' + minutes + ' ' + am);
        $(end_time_id).timepicker({
            'minTime': $(start_time_id).val(),
            'maxTime': $(start_time_id).val(),
            'timeFormat': 'g:i a',
            'showDuration': true
        });

        showClearLink(number);

        if (first_time_number == '') { // monday
            first_time_number = number;
            $('#copy_time_' + number).show();
        }
    }
    for (i = 1; i <= 7; i++) {
        $('#flexible_start_time_' + i).timepicker({
            'minTime': '6:00 am',
            'maxTime': '5:30 am',
            'timeFormat': 'g:i a'
        });
        $('#flexible_end_time_' + i).timepicker({
            'minTime': '6:00 am',
            'maxTime': '5:30 am',
            'timeFormat': 'g:i a'
        });
    }
</script>