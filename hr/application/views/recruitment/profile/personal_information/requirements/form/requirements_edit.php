<script>
$("#requirements_edit_form").validationEngine({scroll:false});
$('#requirements_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#requirements_wrapper").html('');
			loadPage("#requirements");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="requirements_edit_form" name="form1" method="post" action="<?php echo url('recruitment/_update_requirements'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->applicant_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Name:</td>
      <td><input type="text" class="validate[required] text-input" name="name" id="name" value="<?php echo $details->name; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Relationship:</td>
      <td><input class="text-input" type="text" name="relationship" id="relationship" value="<?php echo $details->relationship; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Birthdate:</td>
      <td>
      <input class="text-input" type="text" name="birthdate" id="dependent_birthdate" value="<?php echo  ucfirst($details->birthdate); ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadRequirementsDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Dependent</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadDependentTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
