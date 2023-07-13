<div id="form_main" class="inner_form popup_form">
	<form name="editEmployeeStatus" id="editEmployeeStatus" method="post" action="<?php echo url('settings/_insert_employee_status'); ?>">
	 <input type="hidden" name="eid" id="eid" value="<?php echo Utilities::encrypt($es->getId()); ?>" />   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name:</td>
            <td >
                <input type="text" name="name" class="validate[required] text" id="name" value="<?php echo $es->getName(); ?>" />    
            </td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editEmployeeStatus');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
