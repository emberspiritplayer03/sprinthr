<div id="form_main" class="inner_form popup_form">
	<form name="addProjectType" id="addProjectType" method="post" action="<?php echo url('settings/add_new_project_type'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
        <table width="100%"> 
            <tr>
                <td class="field_label">Project Site Name:</td>
                <td >
                 <input type="text" value="" name="project_site_name" class="validate[required] text" id="project_site_name" style="width:300px;margin-left:20px" />
                </td>
            </tr> 
            <tr>
                <td class="field_label">Project Site Address:</td>
                <td >
                 <input type="text" value="" name="project_site_address" class="validate[required] text" id="project_site_address" style="width:300px;margin-left:20px" /> 
                </td>
            </tr>    
            <tr>
                <td class="field_label">Project Site Descriptions:</td>
                <td >
                 <input type="text" value="" name="project_site_description" class="validate[required] text" id="project_site_description" style="width:300px;margin-left:20px" />        
                </td>
            </tr>
        </table>
   
    </div>
    <div id="form_default" style="float:right;">
            <input value="Save" id="add_project_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addProjectType');">Cancel</a></td>
    </div>    
    </form>
</div>
