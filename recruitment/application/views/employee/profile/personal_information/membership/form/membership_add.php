<script>
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#commence_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#membership_add_form").validationEngine({scroll:false});
$('#membership_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#membership_wrapper").html('');
			loadPage("#membership");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="membership_add_form" name="form1" method="post" action="<?php echo url('employee/_update_membership'); ?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Membership Type:</td>
      <td valign="top"><input type="text" class="validate[required]" name="name" id="name" value="<?php echo $details->name; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Membership:</td>
      <td valign="top"><input type="text" name="relationship" id="relationship" value="<?php echo $details->relationship; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Birthdate:</td>
      <td width="241" valign="top">
      <input type="text" name="birthdate" id="dependent_birthdate" value="<?php echo  ucfirst($details->birthdate); ?>" /></td>
    </tr>
    <tr>

      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td valign="top"><input type="submit" name="button" id="button" value="Add" /> 
        <a href="javascript:void(0);" onclick="javascript:loadDependentTable();">Cancel</a></td>
    </tr>
  </table>
</form>
