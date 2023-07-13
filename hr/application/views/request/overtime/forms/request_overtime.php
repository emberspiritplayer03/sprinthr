<script>
	$(function() {	
		$("#start_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	:function() { 
				$("#end_date").datepicker('option',{minDate:$(this).datepicker('getDate')}); 
				load_show_specific_schedule();
			},	
			
		});	
		$("#end_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			onSelect	:function() { $("#start_date").datepicker('option',{maxDate:$(this).datepicker('getDate')}); },
		});
	});
	
	$(function() {
		$("#request_overtime_form").validationEngine({scroll:false});
		$('#request_overtime_form').ajaxForm({
			success:function(o) {
				if(o.is_saved==1) {
					dialogOkBox(o.message,{});
					$('.clearField').val("");
					
					$('#start_time').val("5:30 pm");
					$('#end_time').val("5:30 pm");
					
					$('#show_specific_schedule_wrapper').html('');
					cancel_request_overtime_form();
					load_requested_overtime_list_dt();
					
				}else { dialogOkBox(o.message,{}); }
				$('#token').val(o.token);
			},
			dataType:'json',
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
	});
</script>

<div id="formcontainer">
<div class="mtshad"></div>
<form id="request_overtime_form" name="request_overtime_form" autocomplete="off" method="POST" action="<?php echo url('request/insert_employee_request_overtime'); ?>">
<input type="hidden" id="request_type" name="request_type" value="<?php echo Utilities::encrypt(Settings_Request::OT); ?>" />
<input type="hidden" id="token" name="token" class="form_token" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Overtime</h3>
<div id="form_main">
    <div id="form_default">
      <h3 class="section_title"></h3>
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date" name="start_date" class="validate[required] clearField" placeholder="" readonly="readonly" value="" /><div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                <div id="show_specific_schedule_wrapper"></div>
                <!--<input type="text" style="width:150px;" id="end_date" name="end_date" class="validate[required]" placeholder="To" readonly="readonly" />-->
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time" name="start_time" class="clearField" placeholder="Starts on" value="5:30 pm" />
                <input type="text" style="width:70px;" id="end_time" name="end_time" class="clearField" placeholder="Ends on" value="5:30 pm" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea class="clearField" id="reason" name="reason" style="height:75px; width:250px"></textarea></td>
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