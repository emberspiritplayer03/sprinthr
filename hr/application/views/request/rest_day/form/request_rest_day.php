<script>
$(document).ready(function() {	
	$('#employee_rest_day_form').validationEngine({scroll:false});	
	
	$("#date_from").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
			//var output  = computeDays($("#date_from").val(),$("#date_to").val());					
			//$("#number_of_days").val(output);
			load_show_specific_schedule();
		}
	});
		
	$("#date_to").datepicker({
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
	
	$("#date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});
});
function checkForm()
{
	var start_time = Date.parse('20 Aug 2000 '+ $("#start_time").val());	
	var end_time   = Date.parse('20 Aug 2000 '+ $("#end_time").val());	
	
	if(start_time > end_time){	
		alert('Invalid start and end time entry...');
		return false;	
	}else{	
	if ($('#employee_rest_day_form').validationEngine({returnIsValid: true })) {		
		$('#employee_rest_day_form').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_pending_rest_day_list_dt();				
					$('#request_button').show();
					$('#request_rest_day_form_wrapper').hide();
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
<div id="formcontainer">
<form id="employee_rest_day_form" onsubmit="return checkForm();" name="employee_rest_day_form"  action="<?php echo url('rest_day/_insert_new_employee_rest_day_request'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Request Rest Day</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>                                             
            <tr>
              <td class="field_label">Date From:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_from" id="date_from" value="" />         
              </td>
            </tr>
            <tr>
              <td class="field_label">Date To:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_to" id="date_to" value="" />         
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
              <td><textarea name="comment" id="comment" cols="30" rows="5"></textarea></td>
            </tr>                   
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_request_rest_day_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>
