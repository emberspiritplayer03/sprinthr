<script>

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
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Person:</td>
      <td valign="top"><input type="text" name="person" id="person" value="<?php echo $details->person; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Relationship:</td>
      <td valign="top"><input type="text" name="relationship" id="relationship" value="<?php echo $details->employee_code; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Landline:</td>
      <td width="241" valign="top">
      <input type="text" name="home_telephone" id="home_telephone" value="<?php echo  ucfirst($details->salutation); ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Mobile:</td>
      <td valign="top">
      <input type="text" name="mobile" id="mobile" value="<?php echo ucfirst($details->firstname); ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Work Telephone:</td>
      <td valign="top">
      <input type="text" name="work_telephone" id="work_telephone" value="<?php echo  ucfirst($details->lastname); ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Address:</td>
      <td valign="top"><textarea name="address" id="address" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>

      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top"><input type="submit" name="button" id="button" value="Add" /> 
        <a href="javascript:void(0);" onclick="javascript:loadEmergencyContactsTable();">Cancel</a></td>
    </tr>
  </table>
</form>
