<script>
$(document).ready(function() {	
	$("#edit_date_from").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#edit_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			//var output  = computeDays($("#date_from").val(),$("#date_to").val());					
			//$("#number_of_days").val(output);
			load_show_specific_schedule();
		}
	});
		
	$("#edit_date_to").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			//var output  = computeDays($("#date_from").val(),$("#date_to").val());				
			//$("#number_of_days").val(output);
			load_show_specific_schedule();
		}
	});
	
	$("#edit_date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});
});
function checkForm(obj)
{
	var start_time = Date.parse('20 Aug 2000 '+ $("#edit_start_time").val());	
	var end_time   = Date.parse('20 Aug 2000 '+ $("#edit_end_time").val());	
	
	if(start_time > end_time){	
		alert('Invalid start and end time entry...');
		return false;	
	}else{	
	if ($('#edit_employee_make_up_schedule_form').validationEngine({returnIsValid: true })) {		
		$('#edit_employee_make_up_schedule_form').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_pending_make_up_schedule_list_dt();				
					$('#request_button').show();
					$('#request_make_up_schedule_form_wrapper').hide();
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
		return true;			
	}else{return false;}
	}
}
</script>
<form id="edit_employee_make_up_schedule_form" onsubmit="return checkForm();" name="edit_employee_make_up_schedule_form"  action="<?php echo url('make_up_schedule/_insert_new_employee_make_up_schedule_request'); ?>" method="post"> 
<input type="hidden" id="make_up_schedule_request_id" name="make_up_schedule_request_id" value="<?php echo Utilities::encrypt($ms->getId()); ?>"  />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />	
<div id="form_main">     
  
    <div id="form_default">      
        <table>         
            <!--<tr>
              <td class="field_label">Date Applied:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_applied" id="edit_date_applied" value="<?php //echo $ms->getDateApplied(); ?>" />               
              </td>
            </tr>     -->    
            <tr>
              <td class="field_label">Date From:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_from" id="edit_date_from" value="<?php echo $ms->getDateFrom(); ?>" />         
              </td>
            </tr>
            <tr>
              <td class="field_label">Date To:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_to" id="edit_date_to" value="<?php echo $ms->getDateTo(); ?>" />         
              </td>
            </tr>
            <tr>        
              <td class="field_label">Start Time:</td>
              <td>
              	 <input type="text" style="width:70px;" id="edit_start_time" name="start_time" class="clearField" placeholder="Starts on" value="<?php echo $ms->getStarttime(); ?>" />
                                      
              </td>
            </tr>    
             <tr>        
              <td class="field_label">End Time:</td>
              <td>
              	 <input type="text" style="width:70px;" id="edit_end_time" name="end_time" class="clearField" placeholder="Starts on" value="<?php echo $ms->getEndTime(); ?>" />
                                      
              </td>
            </tr>           
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>              
            <tr>
              <td>Comment(s):</td>
              <td><textarea name="comment" id="comment" cols="30" rows="5"><?php echo $ms->getComment(); ?></textarea></td>
            </tr>                   
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_employee_make_up_schedule_form');">Cancel</a>
                </td>
            </tr>
        </table>
</div><!-- #form_main -->
</div>
</form>
<script>
$('#edit_start_time').timepicker({
	'minTime': '8:00 am',
	'maxTime': '6:00 pm',
	'timeFormat': 'g:i a'
});	

$('#edit_end_time').timepicker({
	'minTime': '8:00 am',
	'maxTime': '6:00 pm',
	'timeFormat': 'g:i a'
});	
</script>

