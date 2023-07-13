<script>
$(document).ready(function() {
	$("#edit_date_start").datepicker({
		dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() { 
				$("#date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
				var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),"start_halfday","end_halfday");					
				$("#edit_number_of_days").val(output);
				load_show_specific_schedule();
			}
	});
	
	$("#edit_date_end").datepicker({
		dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() { 
				var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),"start_halfday","end_halfday");				
				$("#edit_number_of_days").val(output);
				load_show_specific_schedule();
			}
	});
	
	/*$("#edit_date_applied").datepicker({
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true
	});*/

});

</script>

<div id="form_main" class="inner_form popup_form wider">
<form id="request_leave_form" name="request_leave_form"  action="<?php echo url('leave/_insert_new_employee_leave'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" name="employee_id" id="employee_id" value="<?php echo Utilities::encrypt($leave->getEmployeeId()); ?>" />
<input type="hidden" name="leave_request_id" id="leave_request_id" value="<?php echo Utilities::encrypt($leave->getId()); ?>" />
  
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee</td>
               <td><input class="validate[required]" type="text" name="employee_name" id="employee_name" value="<?php echo ($e ? $e->getLastname() . ', ' . $e->getFirstname() : ''); ?>" readonly="readonly" /></td>
             </tr>
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option_sched" name="leave_id" id="leave_id">              
                  <option value="">-- select --</option>
                <?php foreach($leaves as $l) { ?>
                <option <?php echo($leave->getLeaveId() == $l->getId() ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>  
            <!--<tr>
              <td class="field_label">Date Applied:</td>
              <td><input class="validate[required]" type="text" name="date_applied" id="edit_date_applied" value="<?php //echo $leave->getDateApplied(); ?>" /></td>
            </tr>         
            <tr>-->
              <td class="field_label">Date from:</td>
              <td>
              <input type="text" class="validate[required]" name="date_start" id="edit_date_start" value="<?php echo $leave->getDateStart(); ?>" />&nbsp;
              <label class="checkbox inline">
              <input <?php echo($leave->getApplyHalfDayDateStart() == G_Employee_Leave_Request::YES ? 'checked="checked"' : ''); ?> value="1" type="checkbox" name="start_halfday" id="edit_start_halfday" onclick="javascript:wrapperEditComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');" />Apply Halfday             
              </label>
              </td>
            </tr>
            <tr>
        
              <td class="field_label">Date to:</td>
              <td>
              	<input type="text" class="validate[required]" name="date_end" id="edit_date_end" value="<?php echo $leave->getDateEnd(); ?>" />&nbsp;
                <label class="checkbox inline">
                <input <?php echo($leave->getApplyHalfDayDateEnd() == G_Employee_Leave_Request::YES ? 'checked="checked"' : ''); ?> value="1" type="checkbox" name="end_halfday" id="edit_end_halfday" onclick="javascript:wrapperEditComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');" />Apply Halfday
                </label>
              </td>
            </tr>
            <tr>
              <td class="field_label">Days</td>
              <td><input name="number_of_days" type="text" id="edit_number_of_days" readonly="readonly" /></td>
            </tr>
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>
            <tr>
              <td class="field_label">Is paid:</td>
              <td>
              <select class="validate[required] select_option_sched" name="is_paid" id="is_paid">              
              	<option <?php echo ($leave->getIsPaid() == G_Employee_Leave_Request::YES ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option> 
                <option <?php echo ($leave->getIsPaid() == G_Employee_Leave_Request::NO ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
              </select>
              </td>
            </tr>    
            <tr>
              <td>Leave Comments:</td>
              <td><textarea name="leave_comments" id="leave_comments"><?php echo $leave->getLeaveComments(); ?></textarea></td>
            </tr>           
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#request_leave_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->
<script>
wrapperEditComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');
</script>

