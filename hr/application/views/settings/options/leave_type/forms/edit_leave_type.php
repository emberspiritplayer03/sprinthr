<div id="form_main" class="inner_form popup_form wider">
<form id="editLeaveType" name="editLeaveType"  action="<?php echo url('leave/_insert_leave_type'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="leave_id" name="leave_id" value="<?php echo Utilities::encrypt($l->getId()); ?>" />
 
    <div id="form_default">      
        <table>
             <tr>
               <td class="field_label">Title:</td>
               <td><input class="validate[required] " type="text" name="leave_title" id="leave_title" value="<?php echo $l->getName(); ?>" /></td>
             </tr>
             <tr>
            <td class="field_label">Default Credit:</td>
                <td >
                    <input type="text" value="<?php echo ($l->getDefaultCredit() == "" ? 0 : $l->getDefaultCredit()); ?>" name="default_credit" class="validate[required] text" id="default_credit" />    
                </td>
            </tr> 
             <tr>
              <td class="field_label">Is paid:</td>
              <td>
              <select class="validate[required] select_option" name="is_paid" id="is_paid">              
              	<option <?php echo($l->getIsPaid() == G_Leave::YES ? 'selected="selected"' : ''); ?> value="<?php echo G_Leave::YES; ?>"><?php echo G_Leave::YES; ?></option> 
                <option <?php echo($l->getIsPaid() == G_Leave::NO ? 'selected="selected"' : ''); ?> value="<?php echo G_Leave::NO; ?>"><?php echo G_Leave::NO; ?></option>
              </select>
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
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#editLeaveType');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

