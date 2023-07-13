<script>
var date_from_str = $("#date_from").val();
var date_to_str   = $("#date_to").val();

$(document).ready(function() {	
	$('#edit_request_form').validationEngine({scroll:false});	
	
	$("#ob_date_from").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#ob_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
		}
	});	
	
	$("#ob_date_to").datepicker({
		minDate: date_from_str,
    	maxDate: date_to_str,
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
		
		}
	});	
	
	/*var t = new $.TextboxList('#employee_id', {
			unique: true,
			max:1,
			plugins: {
				autocomplete: {
					minLength: 3,				
					onlyFromValues: true,
					queryRemote: true,
					remote: {url: base_url + 'ob/ajax_get_employees_autocomplete'}			
				}
		}});
		
	<?php
		//Employees				
		if($gobr->getEmployeeId()) {						
			$emp     = G_Employee_Finder::findById($gobr->getEmployeeId());					
			if($emp){
	?>
		t.add('Entry','<?php echo Utilities::encrypt($emp->getId()); ?>', '<?php echo $emp->getFirstname(). ' '. $emp->getLastname(); ?>');
	<?php
				}
			}		
	?>*/		



	  $('#ob_time_start').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });


	  $('#ob_time_end').timepicker({
        'minTime': '8:00 am',
        'maxTime': '7:30 am',
        'timeFormat': 'g:i a'
    });

	  showLogsInput();
});

function showLogsInput(){
	if($('#has_time_logs').is(':checked')){
		$(".ob_logs_wrapper").show();
		$('#ob_time_start').addClass("validate[required]");
		$('#ob_time_end').addClass("validate[required]");
	}
	else{
		$(".ob_logs_wrapper").hide();
		$('#ob_time_start').removeClass("validate[required]");
		$('#ob_time_end').removeClass("validate[required]");
	}
}



function checkForm()
{
	if ($('#edit_request_form').validationEngine({returnIsValid: true })) {		
		$('#edit_request_form').ajaxForm({
			success:function(o) {
				if (o.is_success == 1) {								
					load_ob_list_dt(o.from,o.to);								
					closeDialog('#' + DIALOG_CONTENT_HANDLER);	
					dialogOkBox(o.message,{});						
				} else {
					
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
</script>
<div id="form_main" class="inner_form popup_form wider">
<form id="edit_request_form" name="edit_request_form" onsubmit="javascript:checkForm();"  action="<?php echo url('ob/_update_ob_request'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="ob_request_id" name="ob_request_id" value="<?php echo Utilities::encrypt($gobr->getId()); ?>" />      
<input type="hidden" id="date_from" name="date_from" value="<?php echo $from; ?>" />
<input type="hidden" id="date_to" name="date_to" value="<?php echo $to; ?>" />
<input type="hidden" name="employee_id" value="<?php echo $eid; ?>" />
    <div id="form_default">      
        <table>        	 
             <tr>
               <td class="field_label">Employee:</td>
               <td>
               		<input class="validate[required] " type="text" name="employee_name" id="employee_name" value="<?php echo $employee_name; ?>" readonly="readonly" />               		
               </td>
             </tr>
             <tr>
               <td class="field_label">From:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="ob_date_from" id="ob_date_from" value="<?php echo $gobr->getDateStart(); ?>" />
               </td>
             </tr>  
             <tr>
               <td class="field_label">To:</td>
               <td>
               		<input class="validate[required] input-small" type="text" name="ob_date_to" id="ob_date_to" value="<?php echo $gobr->getDateEnd(); ?>" />
               </td>
             </tr>                                                                   
             <!-- <tr>
               <td class="field_label">Is Approved:</td>
               <td>
               		<select class="validate[required] select_option" name="is_approved" id="is_approved">        
               		<option <?php //echo ($gobr->getIsApproved() == G_Employee_Official_Business_Request::YES ? 'selected="selected"' : ''); ?> value="<?php echo Employee_Official_Business_Request::YES; ?>"><?php echo Employee_Official_Business_Request::YES; ?></option>  
                    <option <?php //echo ($gobr->getIsApproved() == G_Employee_Official_Business_Request::NO ? 'selected="selected"' : ''); ?> value="<?php echo Employee_Official_Business_Request::NO; ?>"><?php echo Employee_Official_Business_Request::NO; ?></option>                                  
                    </select>
               </td>
             </tr> -->

             <tr>
                <td class="field_label"></td>
               <td>
               		<label class="checkbox">
	                <input value="1" type="checkbox" name="has_time_logs" id="has_time_logs" <?php if($gobr->getWholeDay() === 'No') echo 'checked="checked"';?> onchange="showLogsInput()" />Insert Time Range 
	                </label>
               </td>
             </tr>

              
               <tr class="ob_logs_wrapper">
               <td class="field_label">Time Start:</td>
               <td>
               		<input class="input-small" type="text" name="ob_time_start" id="ob_time_start" value="<?php echo $gobr->getTimeStart(); ?>" />
               </td>
               	 </tr>
               	 <tr class="ob_logs_wrapper">
	               <td class="field_label">Time End:</td>
	               <td>
	               		<input class="input-small" type="text" name="ob_time_end" id="ob_time_end" value="<?php echo $gobr->getTimeEnd(); ?>" />
	               </td>
	             </tr>   



             <tr>
               <td class="field_label">Comments:</td>
               <td>
               		<textarea class="input-large" rows="3" id="comments" name="comments"><?php echo $gobr->getComments(); ?></textarea>               		
               </td>
             </tr>                                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

