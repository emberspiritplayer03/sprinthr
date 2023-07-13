<script>
$(function(){
  $("#edit_date_start").datepicker({
    dateFormat:'yy-mm-dd',
      changeMonth:true,
      changeYear:true,
      showOtherMonths:true,
      onSelect  :function() { 
        $("#edit_date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
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
      onSelect  :function() { 
        var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),"start_halfday","end_halfday");        
        $("#edit_number_of_days").val(output);
        load_show_specific_schedule();
      }
  });
});



/*$(document).ready(function() {
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
	});*/

/*});*/

</script>

<div id="form_main" class="inner_form popup_form wider">
<form id="request_leave_form" name="request_leave_form"  action="<?php echo url('leave/_update_leave_request'); ?>" method="post" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" name="eid" id="eid" value="<?php echo Utilities::encrypt($leave->getId()); ?>" />
<input type="hidden" id="is_approved" name="is_approved" value="<?php echo $leave->getIsApproved(); ?>" />
<input type="hidden" id="employee_id" name="employee_id" value="<?php echo Utilities::encrypt($e->getId()); ?>" />
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Employee</td>
               <td><input class="validate[required] " type="text" name="employee_name" id="employee_name" value="<?php echo ($e ? $e->getLastname() . ', ' . $e->getFirstname() : ''); ?>" readonly="readonly" /></td>
             </tr>
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option" name="leave_id" id="leave_id">              
                  <option value="">-- select --</option>
                <?php foreach($leaves as $l) { ?>
                <option <?php echo($leave->getLeaveId() == $l->getId() ? 'selected="selected"' : ''); ?> value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>              
            <tr>
              <td class="field_label">Date from:</td>
              <td>
              <input type="text" class="validate[required] " name="date_start" id="edit_date_start" value="<?php echo $leave->getDateStart(); ?>" />
              <br />
              <label class="checkbox">
              <input <?php echo($leave->getApplyHalfDayDateStart() == G_Employee_Leave_Request::YES ? 'checked="checked"' : ''); ?> value="1" type="checkbox" name="start_halfday" id="edit_start_halfday" onclick="javascript:wrapperEditComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');" />Halfday           
              </label>
              </td>
            </tr>
            <tr>
        
              <td class="field_label">Date to:</td>
              <td>
              	<input type="text" class="validate[required] " name="date_end" id="edit_date_end" value="<?php echo $leave->getDateEnd(); ?>" />                
              </td>
            </tr>
            <tr>
              <td class="field_label">Days:</td>
              <td><input name="number_of_days" type="text" id="edit_number_of_days" readonly="readonly" /></td>
            </tr>
            <tr>
              <td class="field_label"></td>
              <td>
              	<div id="show_specific_schedule_wrapper"></div>
              </td>
            </tr>
            <!-- <tr>
              <td class="field_label">Status:</td>
              <td>
              <select class="validate[required] select_option" name="is_approved" id="is_approved" style="width:30%;">              
                <option <?php //echo ($leave->getIsApproved() == G_Employee_Leave_Request::PENDING ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::PENDING; ?>"><?php echo G_Employee_Leave_Request::PENDING; ?></option> 
                <option <?php //echo ($leave->getIsApproved() == G_Employee_Leave_Request::APPROVED ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::APPROVED; ?>"><?php echo G_Employee_Leave_Request::APPROVED; ?></option>
              </select>
              </td>
            </tr> -->
            <tr>
              <td class="field_label">Deduct to leave credit(s):</td>
              <td>
              <select class="validate[required] select_option" name="is_paid" id="is_paid" style="width:30%;">              
              	<option <?php echo ($leave->getIsPaid() == G_Employee_Leave_Request::YES ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option> 
                <option <?php echo ($leave->getIsPaid() == G_Employee_Leave_Request::NO ? 'selected="selected"' : ''); ?> value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
              </select>
              </td>
            </tr>    
            <tr>
              <td class="field_label">Leave Comments:</td>
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

