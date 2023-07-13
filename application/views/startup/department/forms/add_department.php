<!--<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>-->
<div id="form_main" class="inner_form popup_form">
	<form name="addDepartment" id="addDepartment" method="post" action="<?php echo url('startup/add_department'); ?>">    
    <input type="hidden" id="company_branch_id" name="company_branch_id" value="<?php echo $company_branch_id; ?>" />     
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name:</td>
            <td>
                <input type="text" value="" name="name" class="validate[required] text" id="name" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Description:</td>
            <td>
                <input type="text" value="" name="description" class="validate[optional] text" id="description" />    
            </td>
        </tr>        
       </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addDepartment');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>