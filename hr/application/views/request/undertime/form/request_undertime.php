<script>
$(document).ready(function() {	
	$('#employee_undertime_form').validationEngine({scroll:false});	
		
	$('#employee_undertime_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {			
				//load_leave_list_dt(o.es_id);				
				load_pending_undertime_list_dt();				
				$('#request_button').show();
				$('#request_undertime_form_wrapper').hide();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});						
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});	
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});		
	
	$("#date_of_undertime").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			load_show_specific_schedule();
		}
	});
	
	$("#date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});
});
</script>
<form id="employee_undertime_form" name="employee_undertime_form"  action="<?php echo url('undertime/_insert_new_employee_undertime_request'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Undertime</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>                                   
            <!--<tr>
              <td class="field_label">Date Applied:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_applied" id="date_applied" value="" />               
              </td>
            </tr>  -->       
            <tr>
              <td class="field_label">Date of Undertime:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_of_undertime" id="date_of_undertime" value="" />         
              </td>
            </tr>
            <tr>        
              <td class="field_label">Time Out:</td>
              <td>
              	 <input type="text" style="width:70px;" id="timeout" name="timeout" class="clearField" placeholder="Starts on" value="08:00 am" />
                                      
              </td>
            </tr>           
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>              
            <tr>
              <td>Reason(s):</td>
              <td><textarea name="reason" id="reason" cols="30" rows="5"></textarea></td>
            </tr>                   
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_request_undertime_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
<script>
$('#timeout').timepicker({
	'minTime': '8:00 am',
	'maxTime': '6:00 pm',
	'timeFormat': 'g:i a'
});	
</script>

