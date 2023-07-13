<form method="post" id="import_schedule_specific_form" action="<?php echo $action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="import_schedule_specific_file" id="import_schedule_specific_file" />
    </div>
    <div class="import_links">
    	<small><a target="_blank" href="<?php echo url('schedule/html_import_changed_schedule');?>" class="btn btn-link btn-mini"><i class="icon-question-sign icon-fade"></i> Need Help?</a></small>
    </div>
    <div id="form_default" class="form_action_section">
        <table class="no_border" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_schedule_specific_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>