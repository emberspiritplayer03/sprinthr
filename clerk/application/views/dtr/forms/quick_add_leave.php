<script>
$(document).ready(function() {	
	$("#edit_date_start").datepicker({
		dateFormat:'yy-mm-dd',
			changeMonth:true,
			changeYear:true,
			showOtherMonths:true,
			onSelect	:function() { 
				$("#edit_date_end").datepicker('option',{minDate:$(this).datepicker('getDate')});
				var output  = computeDaysWithHalfDay($("#edit_date_start").val(),$("#edit_date_end").val(),"start_halfday","end_halfday");					
				$("#edit_number_of_days").val(output);				
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
			}
	});
});

</script>

<form id="quick_add_form" name="quick_add_form"  action="<?php echo url('dtr/_insert_new_employee_leave'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="form_main"> 
    <div id="form_default">      
        <table>            
             <tr>
              <td class="field_label">Leave Type:</td>
              <td>
              <select class="validate[required] select_option" name="leave_id" id="leave_id">              
                  <option value="">-- select --</option>
                <?php foreach($leaves as $l) { ?>
                <option value="<?php echo Utilities::encrypt($l->getId()); ?>"><?php echo $l->getName(); ?></option>
                <?php } ?>
               </select>
              </td>
            </tr>                   
            <tr>
              <td class="field_label">Date Start:</td>
              <td>
              <input type="text" class="validate[required] text-input" name="date_start" id="edit_date_start" value="" /> 
              <label>
              <input value="1" type="checkbox" name="start_halfday" id="edit_start_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');" />
              Apply Halfday             
              </label>
              </td>
            </tr>
            <tr>
        
              <td class="field_label">Date End:</td>
              <td>
              	<input type="text" class="validate[required] text-input" name="date_end" id="edit_date_end" value="" />
                <label>
                <input value="1" type="checkbox" name="end_halfday" id="edit_end_halfday" onclick="javascript:wrapperComputeDaysWithHalfDay('edit_start_halfday','edit_end_halfday','edit_number_of_days');" />
                Apply Halfday
                </label>
              </td>
            </tr> 
            <tr>
              <td class="field_label">Days</td>
              <td><input name="number_of_days" type="text" id="edit_number_of_days" readonly="readonly" /></td>
            </tr>                       
            <tr>
              <td class="field_label">Is paid:</td>
              <td>
              <select class="validate[required] select_option" name="is_paid" id="is_paid">              
              	<option value="<?php echo G_Employee_Leave_Request::YES; ?>"><?php echo G_Employee_Leave_Request::YES; ?></option> 
                <option value="<?php echo G_Employee_Leave_Request::NO; ?>"><?php echo G_Employee_Leave_Request::NO; ?></option>
              </select>
              </td>
            </tr>    
            <tr>
              <td>Leave Comments:</td>
              <td><textarea name="leave_comments" id="leave_comments" cols="45" rows="5"></textarea></td>
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
