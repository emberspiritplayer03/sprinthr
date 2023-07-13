<form onsubmit="return checkForm()" method="post" id="edit_overtime_form" action="<?php echo $action;?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>" />
<input type="hidden" name="date" value="<?php echo $date;?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
	  <table class="no_border" width="100%">
        <tr>
            <td>Date:</td>
            <td><?php echo $date_string;?></td>
        </tr>
	    <tr>
	      <td class="field_label">Time In/Out:</td>
	      <td><input style="width:55px" type="text" name="time_in" id="schedule_time_in" value="<?php echo $time_in;?>" />
	      to
          <input style="width:55px" type="text" name="time_out" id="schedule_time_out" value="<?php echo $time_out;?>" /></td>
        </tr>
	    <!--<tr>
	      <td class="field_label"></td>
          <td><input type="checkbox" name="is_restday" value="yes" /> Rest Day</td>
        </tr>-->
      </table>
	</div>
    <div id="form_default" class="form_action_section">
      <table class="no_border" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="33%" class="field_label">&nbsp;</td>
            <td width="67%"><input value="Save" id="edit_overtime_form_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>


<script type="text/javascript">
	function onStartTimeChanged() {
		var start_time_id = '#schedule_time_in';
		var end_time_id = '#schedule_time_out';
		var start_time = $('#schedule_time_in').val();
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
			
	$('#edit_overtime_form #schedule_time_in').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#edit_overtime_form #schedule_time_out').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});	
	
	//$("#edit_overtime_form #schedule_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
	//$("#edit_overtime_form #schedule_end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
		
	function checkForm() {
		var time_in = $('#edit_overtime_form #schedule_time_in').val();
		var time_out = $('#edit_overtime_form #schedule_time_out').val();
		var date = $('#edit_overtime_form #schedule_date').val();
		if (date == '' || time_in == '' || time_out == '') {
			return false;	
		} else {
			return true;	
		}
	}
</script>