<script>
$("#import_employee_evaluation_form").validationEngine({scroll:false});

$('#import_employee_evaluation_form').ajaxForm({
			success:function(o) {				
				$("#import_employee_wrapper").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');				
                load_view_all_employee_eval_datatable();
                $('#import_employee_evaluation_form')[0].reset();
				dialogOkBox(o,{height:'auto',width:450}) 
			}, 
			beforeSubmit:function() {
				showLoadingDialog('Importing...');	
			}
		});

</script>

<form method="post" id="import_employee_evaluation_form" action="evaluation/import_employee_evaluation_excel" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="attachment" id="attachment" />
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/evaluation/employee_evaluation_template.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a><!--&nbsp;<a target="_blank" href="<?php echo url("employee/html_show_import_employee_format"); ?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>-->
        </small>
    </div>     
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Save" id="import_employee_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeImportDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>