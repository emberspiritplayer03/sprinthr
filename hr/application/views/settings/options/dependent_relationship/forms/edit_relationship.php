<div id="form_main" class="inner_form popup_form">
	<form name="editRelationship" id="editRelationship" method="post" action="<?php echo url('settings/update_relationship'); ?>">
    <input type="hidden" value="<?php echo $d->getId(); ?>" name="dependent_id" id="dependent_id" />  
    <div id="form_default">   
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="formControl">Relationship:</td>
            <td>
                <input type="text" value="<?php echo $d->getRelationship(); ?>" name="relationship" class="validate[required] text" id="relationship" />    
            </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editRelationship');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>