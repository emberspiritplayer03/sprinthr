<form method="post" id="import_restday_form" action="<?php echo $action;?>" enctype="multipart/form-data">	
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo $h_employee_id; ?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="restday_file" id="restday_file" />
    </div>
    <div class="import_links">
    	<small><a target="_blank" href="<?php echo url('attendance/html_import_overtime');?>" class="btn btn-link btn-mini"><i class="icon-question-sign icon-fade"></i> Need Help?</a></small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_restday_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>