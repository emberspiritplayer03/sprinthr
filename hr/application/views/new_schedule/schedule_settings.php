<?php
$days[1] = 'Monday';
$days[2] = 'Tuesday';
$days[3] = 'Wednesday';
$days[4] = 'Thursday';
$days[5] = 'Friday';
$days[6] = 'Saturday';
$days[7] = 'Sunday';
?>
<h2><?php echo $title; ?></h2>
<script>
    $("#birthdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });
    $("#date_from").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        onSelect: function() {
            $("#date_to").datepicker('option', {
                minDate: $(this).datepicker('getDate')
            });
        }
    });

    $("#date_to").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        onSelect: function() {

        }
    });

    $(function() {
        $("#frm-report-attendance-absence").validationEngine({
            scroll: false
        });
    });
</script>
<div id="form_main" class="employee_form">

    <form id="add_schedule_form" method="post" action="<?php echo $action; ?>">
        <div id="form_main" class="inner_form popup_form wider">
            <div id="form_default">
                <!--<p class="red">Note : Day(s) with empty fields will be mark as restday</p>-->
                <table class="no_border" width="100%">
                    <tr>
                        <td class="">Schedule</td>
                        <td class="field_label"><input name="shift" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_shift == 1) ? "checked" : "" ?> />Shift Schedule </td>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="security" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_security == 1) ? "checked" : "" ?> />Security Schedule </td>
                    </tr>
                    <tr>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="flexible" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_flexible == 1) ? "checked" : "" ?> />Flexi Time Schedule </td>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="actual" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_actual == 1) ? "checked" : "" ?> />Actual Hours </td>
                    </tr>
                    <tr>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="compressed" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_compressed == 1) ? "checked" : "" ?> />Compressed Work Week Schedule</td>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="per_trip" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_per_trip == 1) ? "checked" : "" ?> />Per Trip Schedule </td>
                    </tr>
                    <tr>
                        <td class="field_label"></td>
                        <td class="field_label"><input name="staggered" value="1" type="checkbox" id="" style="width:20px;" <?php echo ($checked_staggered == 1) ? "checked" : "" ?> />Staggered Schedule</td>
                    </tr>

                </table>
                <table style="width:40%;">
                    <tr>
                        <td class="">Compressed Work Week </td>
                        <td class="field_label"><input class="validate[required]" type="number" name="compressed_hours" id="hours" placeholder="Total hours per week.." value="<?php echo $compressed_hours; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="">Staggered Hours </td>
                        <td class="field_label">
                            <input class="validate[required]" type="radio" name="staggered_hours" id="hours" value="8" <?php echo ($staggered_hours == 8) ? "checked" : "" ?>/>&nbsp;8 hours&nbsp;
                            <input class="validate[required]" type="radio" name="staggered_hours" id="hours" value="9" <?php echo ($staggered_hours == 9) ? "checked" : "" ?>/>&nbsp;9 hours&nbsp;
                            <input class="validate[required]" type="radio" name="staggered_hours" id="hours" value="9.5" <?php echo ($staggered_hours == 9.5) ? "checked" : "" ?>/>&nbsp;9.5 hours&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td class="">Flexible Hours </td>
                        <td class="field_label"><input class="validate[required]" type="number" placeholder="Total hours per day.." name="flexible_hours" id="hours" value="<?php echo $flexible_hours; ?>" /></td>
                    </tr>
                </table>
            </div>
            <span id="schedule_message"></span>
            <div id="form_default" class="form_action_section">
                <table class="no_border" width="100%">
                    <tr>
                        <td class="field_label">&nbsp;</td>
                        <td>
                            <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">
                        </td>
                    </tr>
                </table>
            </div><!-- #form_default.form_action_section -->
        </div><!-- #form_main.popup_form -->
    </form>
</div>

<script>
    function onEndTimeChanged(number) {
	if (number == 1) {
		var end_time = $('#end_time_' + number).val();
		if (end_time != '') {
			$('#copy_time_1').show();
		}
	}
}

function onStartTimeChanged(number) {
	var start_time_id = '#start_time_' + number;
	var end_time_id = '#end_time_' + number;
	var start_time = $('#start_time_' + number).val();
	var split_time = start_time.split(':');
	var hour = parseFloat(split_time[0]) + 9;
	var split_minutes = split_time[1].split(' ');
	var minutes = split_minutes[0];
	var am = split_minutes[1];
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
	$('#start_time_' + i).timepicker({
		'minTime': '6:00 am',
		'maxTime': '5:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time_' + i).timepicker({
		'minTime': '6:00 am',
		'maxTime': '5:30 am',
		'timeFormat': 'g:i a'
	});		
}
</script>