<script>

$("#addSectionForm").validationEngine({});

$('#addSectionForm').ajaxForm({
			success:function(o) {
				
				$("#department_wrapper_form").dialog("destroy");
				disablePopUp();
				$dialog.dialog('destroy');
				load_section_dropdown_by_department_id($("#dep_department_id").val());
				$("#department_wrapper_form").html('');
			},
			beforeSubmit:function() {
				showLoadingDialog('Saving...');	
			}
		});
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addSectionForm" id="addSectionForm" method="post" action="<?php echo $section_form_action; ?>">
    <input type="hidden" value="<?php echo $branch->id; ?>" name="dep_branch_id" id="dep_branch_id" />
    <input type="hidden" value="<?php echo $department_id; ?>" name="dep_department_id" id="dep_department_id" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
          <td class="field_label">Branch:</td>
          <td class="display_value"><div id="dep_branch_name"><strong><?php echo $branch->name; ?></strong></div></td>
        </tr>
        <tr>
          <td class="field_label">Department:</td>
          <td class="display_value"><div id="dept_name"><strong><?php echo $dept_name; ?></strong></div></td>
        </tr>
        <tr>
            <td class="field_label">* Section Name:</td>
            <td><input type="text" value="" name="dep_branch_name" class="validate[required] text-input text" id="dep_branch_name" /><br />
            <!-- <small><em>(HR Department, Developer Team)</em></small> --></td>
        </tr>
        <tr>
          <td class="field_label">Description:</td>
          <td><input class="text-input text" type="text" name="dep_description" id="dep_description" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#department_wrapper_form','#addSectionForm');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>