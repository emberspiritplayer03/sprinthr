<div id="form_main" class="inner_form popup_form wider">
    <form name="addEeoCategory" id="addEeoCategory" method="post" action="<?php echo url('settings/add_eeo_job_category'); ?>">    
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td valign="top" class="field_label">Category Name:</td>
        <td valign="top">
        <input type="text" value="" name="category_name" class="validate[required] text" id="category_name" />
        </td>
    </tr>
    <tr>
        <td valign="top" class="field_label">Description:</td>
        <td valign="top">
        <textarea id="description" name="description" style="width:74%; min-width:100px;">
        
        </textarea>
        </td>
    </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addEeoCategory');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>