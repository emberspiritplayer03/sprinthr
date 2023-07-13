<script>
$("#dependent_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#dependent_add_form").validationEngine({scroll:false});
$('#dependent_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#dependents_wrapper").html('');
			loadPage("#dependents");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="dependent_add_form" name="form1" method="post" action="<?php echo url('employee/_update_dependent'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
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
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadDependentTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
