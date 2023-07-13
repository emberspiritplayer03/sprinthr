<form method="post" name="import_deductions_form" id="import_deductions_form" action="<?php echo url('deductions/import_deductions');?>" enctype="multipart/form-data">	
<input type="hidden" id="eid" name="eid" value="<?php echo $eid; ?>" />
<div id="form_main" class="inner_form popup_form">	
	<div id="form_default" align="center">
    	<input type="file" name="deduction_file" id="deduction_file" />        
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/deductions/import_deductions_template.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('deductions/html_import_deductions');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td>
            	<input type="submit" value="Import" class="curve blue_button" />            	
            	<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_loan');">Cancel</a>
            </td>
          </tr>
        </table>		
    </div>
</div>

</form>