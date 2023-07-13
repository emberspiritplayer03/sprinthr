<script>
$("#emergency_contacts_edit_form").validationEngine({scroll:false});
$('#emergency_contacts_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#emergency_contacts_wrapper").html('');
			loadPage("#emergency_contacts");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="emergency_contacts_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_emergency_contacts'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Person:</td>
      <td><input class="validate[required] text-input" type="text" name="person" id="person" value="<?php echo $details->person; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Relationship:</td>
      <td><input class="text-input" type="text" name="relationship" id="relationship" value="<?php echo $details->relationship; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Landline:</td>
      <td>
      <input class="text-input" type="text" name="home_telephone" id="home_telephone" value="<?php echo  ucfirst($details->home_telephone); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Mobile:</td>
      <td>
      <input class="text-input" type="text" name="mobile" id="mobile" value="<?php echo ucfirst($details->mobile); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Work Telephone:</td>
      <td>
      <input class="text-input" type="text" name="work_telephone" id="work_telephone" value="<?php echo  ucfirst($details->work_telephone); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Address:</td>
      <td><textarea name="address" id="address" cols="45" rows="5"><?php echo $details->address; ?></textarea></td>
    </tr>    
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
          <td class="field_label"></td>
          <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadEmergencyContactDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete this Contact</a><input class="blue_button" type="submit" name="button" id="button" value="Update" /> <a href="javascript:void(0);" onclick="javascript:loadEmergencyContactsTable();">Cancel</a></td>
        </tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
