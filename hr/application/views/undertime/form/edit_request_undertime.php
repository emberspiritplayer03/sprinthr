<script>
$(document).ready(function() {	
	$("#edit_date_of_undertime").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			load_show_specific_schedule();
		}
	});
	
	$("#edit_date_of_undertime").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});
	
	$("#edit_date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});
});
</script>
<form id="edit_employee_undertime_form" name="edit_employee_undertime_form"  action="<?php echo url('undertime/_insert_new_employee_undertime_request'); ?>" method="post"> 
<input type="hidden" id="undertime_request_id" name="undertime_request_id" value="<?php echo Utilities::encrypt($u->getId()); ?>"  />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo Utilities::encrypt($u->getEmployeeId()); ?>" />
<div id="form_main">     
  
    <div id="form_default">      
        <table>             
             <tr>
              <td class="field_label">Employee Name</td>
              <td>
              	<?php $employee = G_Employee_Finder::findById($u->getEmployeeId()); ?>
                <input type="text" disabled="disabled" value="<?php echo $employee->getName(); ?>" />
              </td>
             </tr>            
           <!-- <tr>
              <td class="field_label">Date Applied:</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_applied" id="edit_date_applied" value="<?php echo $u->getDateApplied(); ?>" />               
              </td>
            </tr>        --> 
            <tr>
              <td class="field_label">Date</td>
              <td>
              	<input class="validate[required] text-input" type="text" name="date_of_undertime" id="edit_date_of_undertime" value="<?php echo $u->getDateOfUndertime(); ?>" size="25" />
              </td>
            </tr>
            <tr>        
              <td class="field_label">Out:</td>
              <td>
              	 <input type="text" style="width:70px;" id="edit_timeout" name="timeout" class="clearField" placeholder="Starts on" value="<?php echo $u->getTimeOut(); ?>" />
                                      
              </td>
            </tr>           
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>              
            <tr>
              <td class="field_label">Reason(s):</td>
              <td><textarea name="reason" id="reason" cols="45" rows="5"><?php echo $u->getReason(); ?></textarea></td>
            </tr>                   
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_employee_undertime_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</form>
<script>
$('#edit_timeout').timepicker({
	'minTime': '8:00 am',
	'maxTime': '6:00 pm',
	'timeFormat': 'g:i a'
});	
</script>

