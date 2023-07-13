<script>
	$(function() {	
		$("#start_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { $("#end_date_edit").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date_edit").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date_edit").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});
	});
</script>

<form id="edit_request_change_schedule_form" name="edit_request_change_schedule_form" autocomplete="off" method="POST" action="<?php echo url('request/_load_update_change_schedule_request'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::CHANGED_SCHEDULE); ?>" />
<input type="hidden" id="hid" name="hid" value="<?php echo Utilities::encrypt($change_schedule_request->getId()); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_edit" name="start_date_edit" class="validate[required]" placeholder="From" value="<?php echo $change_schedule_request->getDateStart(); ?>" readonly="readonly" />
                <input type="text" style="width:150px;" id="end_date_edit" name="end_date_edit" class="validate[optional]" placeholder="To" value="<?php echo $change_schedule_request->getDateEnd(); ?>" readonly="readonly" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time_edit" name="start_time_edit" class="" onchange="javascript:onStartTimeChanged();" placeholder="Starts on" value="<?php echo Tools::convert12To24Hour($change_schedule_request->getTimeIn()); ?>" />
                <input type="text" style="width:70px;" id="end_time_edit" name="end_time_edit" class="" placeholder="Ends on" value="<?php echo Tools::convert12To24Hour($change_schedule_request->getTimeOut()); ?>" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px"><?php echo $change_schedule_request->getChangeScheduleComments(); ?></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#edit_request_change_schedule_form_wrapper','#edit_request_change_schedule_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

<script>
function onStartTimeChanged() {
	var start_time_id = '#start_time_edit';
	var end_time_id = '#end_time_edit';
	var start_time = $('#start_time_edit').val();
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
}
	$('#start_time_edit').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time_edit').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});		
</script>