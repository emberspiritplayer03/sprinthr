<form method="post" name="import_earnings_form" id="import_earnings_form" action="<?php echo url('earnings/import_earnings');?>" enctype="multipart/form-data">	
<input type="hidden" id="eid" name="eid" value="<?php echo $eid; ?>" />
<div id="form_main" class="inner_form popup_form">	
	<div id="form_default" align="center">
    	<input type="file" name="earning_file" id="earning_file" />        
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/earnings/import_new_earnings_template2.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('earnings/html_import_earnings');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
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