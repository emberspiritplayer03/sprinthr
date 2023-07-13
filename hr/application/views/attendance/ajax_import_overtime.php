<form method="post" id="import_overtime_form" action="<?php echo $action;?>" enctype="multipart/form-data">	
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo $h_employee_id; ?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="overtime_file" id="overtime_file" />
    </div>
    <div style="text-align: center">
        Set import data as:&nbsp;&nbsp;
        <input id="radio1" checked = "checked" type="radio" name="overtime_status" value="<?php echo G_Overtime::STATUS_PENDING;?>"><?php echo G_Overtime::STATUS_PENDING;?> OT
        &nbsp;&nbsp;
        <input id="radio2" type="radio" name="overtime_status" value="<?php echo G_Overtime::STATUS_APPROVED;?>"><?php echo G_Overtime::STATUS_APPROVED;?> OT
    </div><br>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/overtime/import_overtime.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('attendance/html_import_overtime');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_overtime_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>