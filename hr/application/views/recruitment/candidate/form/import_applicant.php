<script>
$("#import_applicant_form").validationEngine({scroll:false});

$('#import_applicant_form').ajaxForm({
			success:function(o) {	

				$("#import_applicant_wrapper").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_view_all_candidate_datatable();
				show_load_import_button();
				dialogOkBox(o,{height:'auto',width:450}) 
			}, 
			beforeSubmit:function() {
				showLoadingDialog('Importing...');	
			}
		});

</script>

<form method="post" id="import_applicant_form" action="<?php echo $import_action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="applicant" id="applicant" />
    </div>
    <div class="import_links">
    	<small>
        <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/applicant/import_applicant_template.xls"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url("recruitment/html_show_import_applicant_format"); ?>" class="btn btn-mini btn-link"><i class="icon-question-sign icon-fade"></i> Need Help?</a>
        </small>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input value="Save" id="import_applicant_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeImportDialog()">Cancel</a></td>
          </tr>
        </table>		
    </div>
</div>
</form>
<div id="total_imported"></div>