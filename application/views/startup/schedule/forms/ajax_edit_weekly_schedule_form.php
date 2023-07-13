 <?php
$days[1] = 'Monday';
$days[2] = 'Tuesday';
$days[3] = 'Wednesday';
$days[4] = 'Thursday';
$days[5] = 'Friday';
$days[6] = 'Saturday';
$days[7] = 'Sunday';
?>
<form id="edit_schedule_form" method="post" action="<?php echo $action;?>">
<input type="hidden" name="id" value="<?php echo $public_id;?>" />
<input id="is_changed" type="hidden" name="is_changed" value="no" />
<div id="form_main" class="inner_form popup_form wider">
    <div id="form_default">
    <table class="no_border" width="100%">
    	  <tr>
        	<td width="21%" class="field_label">*Name:</td>
            <td width="79%"><input class="validate[required] text-input" type="text" name="name" id="name" value="<?php echo $group_name;?>" /></td>
        </tr>
        <tr>
        <td width="24%" class="field_label">*Grace period applied:</td>
            <td width="76%">
            	<input class="validate[required,custom[integer]] input-small" type="text" name="grace_period" id="grace_period" value="<?php echo $grace_period; ?>" />
            	<small><i>In minutes(set to <b>0</b> if none)</i></small>
            
            </td>
        </tr>
        <?php //if (!$is_default):?>
        <tr>
          <td class="field_label">*Effectivity Date:</td>
          <td><input onchange="onValueChanged()" class="validate[required] text-input" type="text" name="effectivity_date" id="effectivity_date" value="<?php echo $effectivity_date;?>" /></td>
        </tr>
        <?php //endif;?>  
        <?php foreach ($days as $number => $day):?>
        <?php
			$short_day = strtolower(substr($day, 0, 3));
			$times = G_Schedule_Helper::getTimeInAndOutByDay($short_day, $schedules);
			$time_in = '';
			$time_out = '';
			if ($times) {
				$time_in = date('g:i a', strtotime($times['in']));
				$time_out = date('g:i a', strtotime($times['out']));
			}
		?>
        <tr>
          <td class="field_label"><?php echo $day;?></td>
          <td>
          	<input class="time_in" name="time_in[<?php echo $short_day;?>]" value="<?php echo $time_in;?>" onchange="onStartTimeChanged(<?php echo $number;?>)" type="text" id="start_time_<?php echo $number;?>" style="width:60px"/> to 
          	<input name="time_out[<?php echo $short_day;?>]" value="<?php echo $time_out;?>" onchange="onEndTimeChanged(<?php echo $number;?>)" type="text" id="end_time_<?php echo $number;?>" style="width:60px" />
            <?php 
			$clear_link_visible = '';
			if ($time_in == '' || $time_out == '') {
				$clear_link_visible = 'style="display:none"';
			}?>
            <span <?php echo $clear_link_visible;?> id="clear_link_<?php echo $number;?>" class="clear_link"><a class="btn btn-mini" href="javascript:clearTime(<?php echo $number;?>)"><i class="icon-remove-circle"></i> Clear</a></span>
          	<span class="copy_time" id="copy_time_<?php echo $number;?>">
            	Copy to:
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 1)">Mon</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 2)">Tue</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 3)">Wed</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 4)">Thu</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 5)">Fri</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 6)">Sat</a>
                <a class="btn btn-info btn-mini" href="javascript:copyTimeTo(<?php echo $number;?>, 7)">Sun</a></span>            
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div><!-- #form_default -->

<!--<div><strong>Summary:</strong></div>
<table>
<?php foreach ($schedules as $s):?>
	<tr>
    	<td class="field_label"><?php echo $s->getWorkingDays();?></td>
        <td class="field_label"><?php echo $s->getTimeIn();?> - <?php echo $s->getTimeOut();?></td>
    </tr>
<?php endforeach;?>
</table>-->
    <span id="schedule_message"></span>
    <div id="form_default" class="form_action_section">
        <table class="no_border" width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td>
                    <input value="Update" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
                </td>
            </tr>
        </table>            
    </div><!-- #form_default.form_action_section -->
</div><!-- #form_main.popup_form -->
</form>


<script>
var first_time_number = ''; // which is the first textbox supplied data
$('.copy_time').hide();
//$('.clear_link').hide();

function onValueChanged() {
	$('#is_changed').val('yes');
}

function copyTimeTo(source_number, to_number) {
	$('#is_changed').val('yes');
	var start_time = $('#start_time_' + source_number).val();
	var end_time = $('#end_time_' + source_number).val();
	$('#start_time_' + to_number).val(start_time);
	$('#end_time_' + to_number).val(end_time);
	$('#clear_link_' + to_number).show();
}

function hideCopyTime(number) {
	$('#copy_time_' + number).hide();
}

function clearTime(number) {
	$('#is_changed').val('yes');
	$('#start_time_' + number).val('');
	$('#end_time_' + number).val('');
	hideClearLink(number);
	hideCopyTime(number);
	if (first_time_number == number) {
		first_time_number = '';
	}
}

function showClearLink(number) {
	$('#clear_link_' + number).show();
}

function hideClearLink(number) {
	$('#clear_link_' + number).hide();
}

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
	var start_time_id = '#start_time_' + number;
	var end_time_id = '#end_time_' + number;
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
	$('#start_time_' + i).timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time_' + i).timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});		
}

$("#edit_schedule_form #effectivity_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>