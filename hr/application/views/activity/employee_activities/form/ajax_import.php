<form method="post" name="import_activity_form" id="import_activity_form" action="<?php echo url('activity/import_employee_activities');?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form wider">
	<div id="form_default" align="center">
    	<input type="file" name="employee_activities_file" id="employee_activities_file" />        
    </div>   
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/activity/import_employee_activity.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download sample template</a>&nbsp;<a target="_blank" href="<?php echo url('activity/html_import_employee_activities');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td>
            	<input type="submit" value="Import" class="curve blue_button" />            	
            	<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#import_activity_form');">Cancel</a>
            </td>
          </tr>
        </table>		
    </div>
</div>
</form>