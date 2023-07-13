<script>
$("#import_update_employee_info").validationEngine({scroll:false});

$('#import_update_employee_info').ajaxForm({
	success:function(o) {				
		$("#import_leave_wrapper").dialog("destroy");
		disablePopUp();
		$dialog.dialog('destroy');
		load_overtime_list_dt();
		dialogOkBox(o,{height:'auto',width:450}) 
	}, 
	beforeSubmit:function() {
		showLoadingDialog('Importing...');	
	}
});

</script>

<form method="post" id="import_update_employee_info" action="<?php echo url("benchmark_bio/_update_employee_dependents"); ?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="file_employee_dependents" id="file_employee_dependents" />
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Save" id="import_leave_submit" class="curve blue_button" type="submit"></td>
          </tr>
        </table>		
    </div>
</div>
</form>