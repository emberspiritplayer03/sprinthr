<script>

$("#addDepartmentForm").validationEngine({});

$('#addDepartmentForm').ajaxForm({
			success:function(o) {
				
				$("#department_wrapper_form").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_department_dropdown();
				$("#department_wrapper_form").html('');
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addDepartmentForm" id="addDepartmentForm" method="post" action="<?php echo $department_form_action; ?>">
    <input type="hidden" value="<?php echo $branch->id; ?>" name="dep_branch_id" id="dep_branch_id" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
          <td class="field_label">Branch:</td>
          <td class="display_value"><div id="dep_branch_name"><strong><?php echo $branch->name; ?></strong></div></td>
        </tr>
        <tr>
            <td class="field_label">*Name:</td>
            <td><input type="text" value="" name="dep_branch_name" class="validate[required] text-input text" id="dep_branch_name" /><br />
            <small><em>(HR Department, Developer Team)</em></small></td>
        </tr>
        <tr>
          <td class="field_label">Decription:</td>
          <td><input class="text-input text" type="text" name="dep_description" id="dep_description" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#department_wrapper_form','#addDepartmentForm');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>