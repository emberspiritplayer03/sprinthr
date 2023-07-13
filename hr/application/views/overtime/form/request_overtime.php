<style>
.leave-header{padding:4px;background-color: #198cc9;color:#ffffff;margin-top:9px;line-height: 27px;}
</style>
<script>
	 $(function() {
		/*var from_period = $('#from_period').val();
		var to_period	= $('#to_period').val();
		$("#start_date_hideshow").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	: function(o) { load_show_specific_schedule(); },
			minDate		: from_period,
			maxDate		: to_period,
		});*/

		$("#start_date_hideshow").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
			onSelect	: function(o) { /*load_show_specific_schedule();*/ }
		});
		
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
		
		$("#request_overtime_form").validationEngine({scroll:false});
		$('#request_overtime_form').ajaxForm({
			success:function(o) {
				if (o.is_saved == 1) {
				    closeDialog('#' + DIALOG_CONTENT_HANDLER);
					dialogOkBox(o.message,{});
					hide_request_overtime_form();
					datatable_loader(1);
					location.reload();
				} else {
					dialogOkBox(o.message,{});
				}
				
				$('.form_token').val(o.token);
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
				minLength: 2,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'overtime/ajax_get_employees_autocomplete'}
			
			}
		}});
		
		t.addEvent('blur',function(o) {
			//if($('#start_date_hideshow').val() != "")
			//load_show_specific_schedule();
			load_show_employee_request_approvers();
		});
	});
</script>


<div id="formcontainer">
<form id="request_overtime_form" name="request_overtime_form" autocomplete="off" method="POST" action="<?php echo url('overtime/_add_overtime'); //insert_employee_request_overtime ?>">
<input type="hidden" id="token" name="token" class="form_token" value="<?php echo $token; ?>" />

<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Overtime</h3>
<div id="form_main">
  
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
      	  <tr>
               <td style="width:15%" align="left" valign="middle">Type Employee Name:</td>
               <td style="width:15%" align="left" valign="middle"><input class="validate[required] text-input" type="text" name="h_employee_id" id="h_employee_id" value="" /></td>
          </tr>
        </table>   
        <div id="show_request_approvers_wrapper"></div>
        <h3 class="leave-header">Overtime Request Details</h3> 
        <table width="100%" border="0" cellspacing="1" cellpadding="2">  
          <tr>
            <td style="width:15%" align="left" valign="middle">Date :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:150px;" id="start_date_hideshow" name="start_date_hideshow" class="validate[required] clearForm" />
                <div id="_schedule_loading_wrapper" style="display:inline; margin-left:10px;"></div>
                <div id="show_specific_schedule_wrapper"></div>
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Time :</td>
            <td style="width:85%" align="left" valign="middle">
                <input type="text" style="width:70px;" id="start_time_hideshow" name="start_time_hideshow" class="validate[required] clearForm" placeholder="Starts on" value="" />
                <input type="text" style="width:70px;" id="end_time_hideshow" name="end_time_hideshow" class="validate[required] clearForm" placeholder="Ends on" value="" />
            </td>
          </tr>
          <tr>
            <td style="width:15%" align="left" valign="middle">Reason :</td>
            <td style="width:85%" align="left" valign="middle"><textarea id="reason" name="reason" style="height:75px; width:250px" class="clearForm"></textarea></td>
          </tr>
         <!-- <tr>
            <td style="width:15%" align="left" valign="middle">Status</td>
            <td style="width:85%" align="left" valign="middle">
            	<select id="status" name="status">
                	 <option value="<?php //echo G_Overtime::STATUS_PENDING; ?>">Pending</option>
                     <option value="<?php //echo G_Overtime::STATUS_APPROVED; ?>">Approved</option>
                     <option value="<?php //echo G_Overtime::STATUS_DISAPPROVED; ?>">Disapproved</option>
                </select>
            </td>
          </tr> -->
        </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_request_overtime_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

