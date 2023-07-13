<script>
$("#import_leave_form").validationEngine({scroll:false});
$('#import_leave_form').ajaxForm({
	success:function(o) {				
		$("#import_leave_wrapper").dialog("destroy");
		disablePopUp();
		$dialog.dialog('destroy');
		load_leave_list_dt();
		$('#leave').val('');
		dialogOkBox(o,{height:'auto',width:450}) 
	}, 
	beforeSubmit:function() {
		showLoadingDialog('Importing...');	
	}
});

</script>

<form method="post" id="import_leave_form" action="<?php echo $import_action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="leave" id="leave" />
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/leave/import_leave_records_template.xls"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download sample template</a><!--&nbsp;<a target="_blank" href="<?php echo url("leave/html_show_import_leave_format"); ?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>-->
        </small>
    </div>    
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Save" id="import_leave_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeImportDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>