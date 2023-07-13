<div id="form_main" class="inner_form popup_form">
	<form name="editSection" id="editSection" method="post" action="<?php echo url('settings/add_section'); ?>">    
    <input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($d->getId()); ?>" />
    <input type="hidden" id="parent_id" name="parent_id" value="<?php echo Utilities::encrypt($d->getParentId()); ?>" />     
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name:</td>
            <td>
                <input type="text" value="<?php echo $d->getTitle(); ?>" name="name" class="validate[required]  text" id="name" />    
            </td>
        </tr>         
        <tr>
            <td class="field_label">Description:</td>
            <td>
                <input type="text" value="<?php echo $d->getDescription(); ?>" name="description" class="validate[optional]  text" id="description" />    
            </td>
        </tr>        
       </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editGroupTeam');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>