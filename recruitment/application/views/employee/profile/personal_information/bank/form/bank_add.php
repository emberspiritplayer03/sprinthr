<script>

$("#direct_deposit_add_form").validationEngine({scroll:false});
$('#direct_deposit_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#banks_wrapper").html('');
			loadPage("#bank");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="direct_deposit_add_form" name="form1" method="post" action="<?php echo url('employee/_update_direct_deposit'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Bank Name:</td>
      <td><input type="text" class="validate[required] text-input" name="bank_name" id="bank_name" value="<?php echo $details->bank_name; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Account:</td>
      <td><input class="text-input" type="text" name="account" id="account" value="<?php echo $details->account; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Account Type:</td>
      <td>
      <input class="text-input" type="text" name="account_type" id="account_type" value="<?php echo  ucfirst($details->account_type); ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
          <td class="field_label">&nbsp;</td>
          <td><input class="blue_button" type="submit" name="button" id="button" value="Add Account" /> <a href="javascript:void(0);" onclick="javascript:loadDirectDepositTable();">Cancel</a></td>
        </tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
