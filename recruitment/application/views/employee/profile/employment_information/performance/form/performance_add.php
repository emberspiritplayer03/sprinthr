<script>
$("#date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#performance_add_form").validationEngine({scroll:false});
$('#performance_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#performance_wrapper").html('');
			loadPage("#performance");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="performance_add_form" name="form1" method="post" action="<?php echo url('employee/_update_performance'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Performance:</td>
      <td><input type="text" class="validate[required] text-input" name="name" id="name" value="<?php echo $details->name; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Reviewer:</td>
      <td><input class="text-input" type="text" name="relationship" id="relationship" value="<?php echo $details->relationship; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Job:</td>
      <td>
      <input class="text-input" type="text" name="birthdate" id="dependent_birthdate" value="<?php echo  ucfirst($details->birthdate); ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadPerformanceTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
