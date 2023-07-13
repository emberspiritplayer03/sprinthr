<div id="form_main" class="inner_form popup_form">
	<form name="addLeaveType" id="addLeaveType" method="post" action="<?php echo url('leave/_insert_leave_type'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Title:</td>
            <td >
                <input type="text" value="" name="leave_title" class="validate[required] text" id="leave_title" />    
            </td>
        </tr> 
        <tr>
            <td class="field_label">Default Credit:</td>
            <td >
                <input type="text" value="0" name="default_credit" class="validate[required] text" id="default_credit" />    
            </td>
        </tr>    
        <tr>
            <td class="field_label">Is paid:</td>
            <td >
                <select class="validate[required] select_option" name="is_paid" id="is_paid">              
                    <option selected="selected" value="<?php echo G_Leave::YES; ?>"><?php echo G_Leave::YES; ?></option> 
                    <option value="<?php echo G_Leave::NO; ?>"><?php echo G_Leave::NO; ?></option>
              	</select>
            </td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addLeaveType');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
