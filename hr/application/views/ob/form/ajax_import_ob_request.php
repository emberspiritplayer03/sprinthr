<form method="post" name="import_ob_form" id="import_ob_form" action="<?php echo url('ob/import_ob_request');?>" enctype="multipart/form-data"> 
<div id="form_main" class="inner_form popup_form wider">

      <input type="hidden" id="date_from" name="date_from" value="<?php echo $from; ?>" />
    <input type="hidden" id="date_to" name="date_to" value="<?php echo $to; ?>" />

  <div id="form_default" align="center">
      <input type="file" name="ob_file" id="ob_file" />        
    </div>   
    <div class="import_links">
      <small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/requests/import_official_business_v2.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download sample template</a><!--&nbsp;<a target="_blank" href="<?php echo url('ob/html_import_ob');?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>-->
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