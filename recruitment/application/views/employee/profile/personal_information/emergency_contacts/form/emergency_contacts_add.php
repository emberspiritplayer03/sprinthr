<script>
$("#emergency_contacts_add_form").validationEngine({scroll:false});
$('#emergency_contacts_add_form').ajaxForm({
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
<form id="emergency_contacts_add_form" name="form1" method="post" action="<?php echo url('employee/_update_emergency_contacts'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
    <table>
        <tr>
            <td class="field_label">Person:</td>
            <td><input class="validate[required] text-input"  type="text" name="person" id="person" value="<?php echo $details->person; ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Relationship:</td>
            <td><input class="text-input" type="text" name="relationship" id="relationship" value="<?php echo $details->employee_code; ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Landline:</td>
            <td>
            <input class="text-input" type="text" name="home_telephone" id="home_telephone" value="<?php echo  ucfirst($details->salutation); ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Mobile:</td>
            <td>
            <input class="text-input" type="text" name="mobile" id="mobile" value="<?php echo ucfirst($details->firstname); ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Work Telephone:</td>
            <td>
            <input class="text-input" type="text" name="work_telephone" id="work_telephone" value="<?php echo  ucfirst($details->lastname); ?>" /></td>
        </tr>
        <tr>
            <td class="field_label">Address:</td>
            <td><textarea name="address" id="address" cols="45" rows="5"></textarea></td>
        </tr>    
    </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add Contact" /> 
            <a href="javascript:void(0);" onclick="javascript:loadEmergencyContactsTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
