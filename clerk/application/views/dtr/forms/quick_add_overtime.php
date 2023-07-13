<script>
$(document).ready(function() {
	$("#start_date").datepicker({
		dateFormat	: 'yy-mm-dd',
		onSelect	: function(o) {
			load_show_specific_schedule();		
		}
	});	
	
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
});
</script>
<form id="quick_add_form" name="quick_add_form" autocomplete="off" method="POST" action="<?php echo url('dtr/insert_employee_request_overtime'); ?>">
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<div id="form_main">     
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">          
          <tr>
            <td style="width:25%" align="left" valign="middle">Date :</td>
            <td style="width:75%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date" name="start_date" class="validate[required]" placeholder="From" value="" readonly="readonly" />
                <div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                <div id="show_specific_schedule_wrapper"></div>               
            </td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Time :</td>
            <td style="width:75%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time" name="start_time" class="" placeholder="Starts on" value="" />
                <input type="text" style="width:70px;" id="end_time" name="end_time" class="" placeholder="Ends on" value="" />
            </td>
          </tr>
          <tr>
            <td style="width:25%" align="left" valign="middle">Reason :</td>
            <td style="width:75%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px"></textarea></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#quick_add_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>