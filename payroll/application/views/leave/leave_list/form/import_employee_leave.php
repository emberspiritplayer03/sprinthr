<script>
$("#import_employee_form").validationEngine({scroll:false});

$('#import_employee_form').ajaxForm({
			success:function(o) {				
				$("#import_employee_wrapper").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_employee_datatable();

				dialogOkBox(o,{height:'auto',width:450}) 
			}, 
			beforeSubmit:function() {
				showLoadingDialog('Importing...');	
			}
		});

</script>

<form method="post" id="import_employee_form" action="<?php echo $import_action;?>" enctype="multipart/form-data">	
<div id="form_main" class="inner_form popup_form">
	<div id="form_default" align="center">
    	<input type="file" name="employee" id="employee" />
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