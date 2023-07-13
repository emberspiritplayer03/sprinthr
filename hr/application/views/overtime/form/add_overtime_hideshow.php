<script>
	$(function() {	
		$('#status').val("Pending");
		$("#start_date_hideshow").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			minDate: from_period,
			maxDate: to_period,

		});	
	});
	
	$(function() {
		$("#request_overtime_hideshow_form").validationEngine({scroll:false});
		$('#request_overtime_hideshow_form').ajaxForm({
			success:function(o) {
				if(o.is_saved==1) {
					dialogOkBox(o.message,{});
					//load_overtime_list_dt();
					load_overtime_list_dt_withselectionfilter();
					
					$('.clearForm').val("");
					$('#status').val("Pending");
					$('#show_specific_schedule_wrapper').html('');
					
				}else {
					dialogOkBox(o.message,{});
				}
				$('#token').val(o.token);
			},
			dataType:'json',
			beforeSubmit:function() {
				
				var employee_id = $('#h_employee_id').val();
				if(employee_id != "") {
					showLoadingDialog('Saving...');	
					return true;
				} else {
					dialogOkBox('Error :  Select employee first',{});
					return false;
				}
			}
		});
		
		var t = new $.TextboxList('#h_employee_id', {max:1,plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'overtime/ajax_get_employees_autocomplete'}
			
			}
		}});
		
		t.addEvent('blur',function(o) {
			load_show_specific_schedule();
		});
	});
	

</script>

<div id="formcontainer">
<div class="mtshad"></div>
<form id="request_overtime_hideshow_form" name="request_overtime_hideshow_form" autocomplete="off" method="POST" action="<?php echo url('overtime/insert_employee_request_overtime'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::OT); ?>" />
<input type="hidden" id="token" name="token" class="form_token" />

<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Overtime</h3>
<div id="form_main">
    <div id="form_default">
      <h3 class="section_title"></h3>
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
      	  <tr>
               <td style="width:15%" align="left" valign="middle">Employee</td>
               <td style="width:15%" align="left" valign="middle"><input class="validate[required] text-input" type="text" name="h_employee_id" id="h_employee_id" value="" onchange="alert(1);" /></td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_hideshow" name="start_date_hideshow" class="validate[required] clearForm" readonly="readonly" onchange="javascript:load_show_specific_schedule();" /><div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                <div id="show_specific_schedule_wrapper"></div>
            </td>
          </tr>
          <tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time_hideshow" name="start_time_hideshow" class="clearForm" placeholder="Starts on" value="" />
                <input type="text" style="width:70px;" id="end_time_hideshow" name="end_time_hideshow" class="clearForm" placeholder="Ends on" value="" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px" class="clearForm"></textarea></td>
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
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:cancel_request_overtime_form();">Cancel</a></td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->
</form>
</div>



<script>
function onStartTimeChanged() {
	var start_time_id = '#start_time_hideshow';
	var end_time_id = '#end_time_hideshow';
	var start_time = $('#start_time_hideshow').val();
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
	$('#start_time_hideshow').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});
	$('#end_time_hideshow').timepicker({
		'minTime': '8:00 am',
		'maxTime': '7:30 am',
		'timeFormat': 'g:i a'
	});		
</script>