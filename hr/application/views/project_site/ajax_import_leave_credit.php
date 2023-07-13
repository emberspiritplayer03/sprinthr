<form method="post" id="<?php echo $form_id;?>" action="<?php echo $action;?>" enctype="multipart/form-data">
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo $h_employee_id; ?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="file" id="file" />
    </div>
    <div style="text-align: center">
        Covered Year:&nbsp;&nbsp;
        <select name="covered_year">
            <option><?php echo Tools::getNextYear();?></option>
            <option selected="selected"><?php echo Tools::getCurrentYear();?></option>
            <option><?php echo Tools::getPreviousYear();?></option>
        </select>
    </div><br>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/leave/import_leave_credit.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a><!--&nbsp;<a target="_blank" href="<?php echo url('project_site/html_import_overtime');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>-->
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_leave_credit_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>