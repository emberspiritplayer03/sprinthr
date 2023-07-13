 <form method="post" id="import_employees_in_schedule" action="<?php echo $action;?>" enctype="multipart/form-data">	
<input type="hidden" name="public_id" value="<?php echo $public_id;?>" />
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="import_employees" id="import_employees" />
    </div>
    <div id="form_default" class="form_action_section">
        <table class="no_border" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Import" id="import_employees_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>