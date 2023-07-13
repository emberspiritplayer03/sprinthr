<div id="form_main" class="inner_form popup_form wider">
	<form name="addDefaultLeave" class="addDefaultLeave" id="addDefaultLeave" method="post" action="<?php echo url('startup/save_default_leave'); ?>">
    <div id="form_default">
    <table width="100%"> 
         <tr>
            <td class="field_label">Leave Name:</td>
            <td><input type="text" value="" class="validate[required] text" name="name"  id="name" /></td>
            
        </tr>
        <tr>
            <td class="field_label">*Default Credits:</td>
            <td><input type="text" value="0" name="number_of_days_default"  id="number_of_days_default" /></td>
        </tr>
         <tr>
            <td class="field_label">With Pay:</td>
            <td>
            	<select id="is_paid" name="is_paid" style="width:46%;">
                	<option value="<?php echo G_Leave::YES; ?>"><?php echo G_Leave::YES; ?></option>
                    <option value="<?php echo G_Leave::NO; ?>"><?php echo G_Leave::NO; ?></option>
                </select>
            </td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialog('#_dialog-box_','#addDefaultLeave');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>