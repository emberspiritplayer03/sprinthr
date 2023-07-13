<div id="form_main" class="inner_form popup_form">
	<form name="addMembership" id="addMembership" method="post" action="<?php echo url('settings/add_membership_type'); ?>">
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Type:</td>
            <td><input type="text" value="" name="type" class="validate[required] text" id="type" /></td>
        </tr>          
    </table>
    </div>
     <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addMembership');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>