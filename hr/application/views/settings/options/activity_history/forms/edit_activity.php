<div id="form_main" class="inner_form popup_form">
	<form name="edit_project_form" id="editProjectType" method="post" action="<?php echo url('settings/update_activity'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="activity_id" id="project_id" value="<?php echo $results[0]->id;?>" />
    <div id="form_default">
        <table width="100%"> 
            <tr>
                <td class="field_label">Name:</td>
                <td >
                 <input type="text"  name="activity_name" class="validate[required] text" id="project_site_name" value="<?php echo $results[0]->activity_skills_name;?>" style="width:300px;margin-left:20px" />
                </td>
            </tr> 
            <tr>
                <td class="field_label">Description:</td>
                <td >
                 <input type="text" value="<?php echo $results[0]->activity_skills_description;?>" name="activity_description" class="validate[required] text" id="project_site_address" style="width:300px;margin-left:20px" /> 
                </td>
            </tr>    
            <tr>
                <td class="field_label">Start:</td>
                <td >
                 <input type="date" value="<?php echo $results[0]->date_started;?>" name="activity_start" class="validate[required] text" id="project_site_description" style="width:300px;margin-left:20px" />        
                </td>
            </tr>
            <tr>
                <td class="field_label">End:</td>
                <td >
                 <input type="date" value="<?php echo $results[0]->date_ended;?>" name="activity_end" class="validate[required] text" id="project_site_description" style="width:300px;margin-left:20px" />        
                </td>
            </tr>
        </table>
   
    </div>
    <div id="form_default" style="float:right;">
            <input value="Update" id="add_project_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editProjectType');">Cancel</a>
    </div>    
    </form>
</div>
