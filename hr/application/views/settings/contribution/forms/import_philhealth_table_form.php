<form method="post" id="import_philhealth_table_form" action="<?php echo url("settings/import_philhealth_table"); ?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="sss_file" id="sss_file" />
    </div>
    <div class="import_links">
        <small>
            <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/contributions/philhealth.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_timesheet_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>