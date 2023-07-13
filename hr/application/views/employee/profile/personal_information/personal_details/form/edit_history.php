<script>
	$(function() {
		$("#history_date").datepicker({
			dateFormat	: 'yy-mm-dd',
			autoSize  	: true,
		});		
	});
</script>

<div id="form_main" class="inner_form popup_form wider">
<form id="edit_history_form" name="edit_history_form" autocomplete="off" method="POST" action="<?php echo url('employee/_insert_update_history'); ?>">
<input type="hidden" id="h_id" name="h_id" value="<?php echo Utilities::encrypt($history->getId()); ?>" />
<input type="hidden" id="h_employee_id" name="h_employee_id" value="<?php echo Utilities::encrypt($history->getEmployeeId()); ?>" />
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
            <tr>
                <td class="field_label">History Date:</td>
                <td><input type="text" class="validate[required]" id="history_date" name="history_date" value="<?php echo $history->getHistoryDate(); ?>" readonly="readonly"/></td>
            </tr>
            <tr>
                <td class="field_label">Remarks:</td>
                <td><textarea id="remarks" name="remarks"><?php echo $history->getRemarks(); ?></textarea></td>
            </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#edit_history_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->