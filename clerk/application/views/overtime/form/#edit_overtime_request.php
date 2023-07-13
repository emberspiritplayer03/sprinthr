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
<form id="edit_request_overtime_form" name="edit_request_overtime_form" autocomplete="off" method="POST" action="<?php echo url('request/_load_update_overtime_request'); ?>">
<input type="hidden" id="hid" name="hid" value="<?php echo Utilities::encrypt($overtime_request->getId()); ?>" />

<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_edit" onblur="javascript:load_show_specific_schedule_edit();" name="start_date_edit" class="validate[required]" placeholder="From" value="<?php echo $overtime_request->getDateStart(); ?>" readonly="readonly" /><div id="_schedule_loading_wrapper_edit" style="display:inline; margin-left:10px;"></div>
                    <div id="show_specific_schedule_wrapper_edit"></div>
                <!--<input type="text" style="width:150px;" id="end_date_edit" name="end_date_edit" class="validate[required]" placeholder="To" value="<?php echo $overtime_request->getDateEnd(); ?>" readonly="readonly" />-->
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time_edit" name="start_time_edit" class="" placeholder="Starts on" value="<?php echo Tools::convert12To24Hour($overtime_request->getTimeIn()); ?>" />
                <input type="text" style="width:70px;" id="end_time_edit" name="end_time_edit" class="" placeholder="Ends on" value="<?php echo Tools::convert12To24Hour($overtime_request->getTimeOut()); ?>" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px"><?php echo $overtime_request->getOvertimeComments(); ?></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialogBox('#edit_request_overtime_form_wrapper','#edit_request_overtime_form')">Cancel</a></td>
            </tr>
		</table>
    </div>
</div><!-- #form_main.inner_form -->   
</form>

<script>
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