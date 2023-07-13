<script>
function generateCode()
{
	var location = document.getElementById("location").value;
	var code     = location.substr(0,3)
	document.getElementById("code").value = code.toUpperCase();	
}
</script>
<div id="form_main" class="inner_form popup_form wider">
	<form name="editLocation" id="editLocation" method="post" action="<?php echo url('settings/update_location'); ?>">
    <input type="hidden" value="<?php echo $l->getId() ?>" id="location_id" name="location_id" />
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Location Name:</td>
            <td>
                <input type="text" value="<?php echo $l->getLocation(); ?>" name="location" onchange="javascript:generateCode();" class="validate[required] text" id="location" />    
            </td>
        </tr>  
        <tr>
            <td class="field_label">Location Code:</td>
            <td>
                <input type="text" value="<?php echo $l->getCode(); ?>" name="code" class="validate[required] text" id="code" />    
            </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editLocation');">Cancel</a></td>
            </tr>
		</table>
    </div> 
    </form>
</div>