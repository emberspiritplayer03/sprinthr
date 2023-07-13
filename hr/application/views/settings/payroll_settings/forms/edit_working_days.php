<div id="form_main" class="inner_form popup_form">
	<form name="editSetting" id="editSetting" method="post" action="<?php echo $action; ?>"> 
    <input type="hidden" name="eid" value="<?php echo $eid; ?>" />  
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label" style="width:40%;"><?php echo $variable_description; ?></td>
            <td >:
               <select class="<?php echo $class_name; ?>" name="field_value" id="<?php echo $variable_field; ?>">
                   <?php foreach($options_working_days as $key => $option){ ?>
                   <option <?php echo($variable_value == $option['num_days'] ? 'selected="selected"' : ''); ?> value="<?php echo $option['num_days']; ?>"><?php echo $option['num_days']; ?></option>
                   <?php } ?>
               </select>               
            </td>
        </tr>
        <tr>
            <td colspan="2"><?php echo $custom_input_a; ?></td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addRequirement');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
