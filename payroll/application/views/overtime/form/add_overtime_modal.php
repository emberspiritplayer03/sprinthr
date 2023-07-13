<script>
	$(function() {
		$("#start_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { $("#end_date").datepicker('option',{minDate:$(this).datepicker('getDate')}); },	
		});	
		$("#end_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});
		
		$('#status').val("Pending");
	});
</script>
<form id="request_overtime_form" name="request_overtime_form" class="form" autocomplete="off" method="POST" action="<?php echo url('overtime/insert_employee_request_overtime'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::OT); ?>" />
<input type="hidden" id="token" name="token" class="form_token" />
<input type="hidden" id="h_employee_id" name="h_employee_id" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date" name="start_date" class="validate[required]" placeholder="" readonly="readonly" />
                <!--<input type="text" style="width:150px;" id="end_date" name="end_date" class="" placeholder="To" readonly="readonly" />-->
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time" name="start_time" class="" onchange="javascript:onStartTimeChanged();" placeholder="Starts on" />
                <input type="text" style="width:70px;" id="end_time" name="end_time" class="" placeholder="Ends on" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px"></textarea></td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle"></td>
            <td style="width:85%" align="left" valign="middle">
            	<select id="status" name="status">
                	<option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Disapproved">Disapproved</option>
                </select>
            </td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#request_overtime_form_wrapper','#request_overtime_form');">Cancel</a></td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>

<script>
function onStartTimeChanged() {
	var start_time_id = '#start_time';
	var end_time_id = '#end_time';
	var start_time = $('#start_time').val();
	var split_time = start_time.split(':');
	var hour = parseFloat(split_time[0]) + 5;
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
	$('#start_time').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});		
</script>