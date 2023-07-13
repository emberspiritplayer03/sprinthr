<script>

$("#requirements_add_form").validationEngine({scroll:false});
$('#requirements_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{height:240,width:390});
			$("#requirements_wrapper").html('');
			loadPage("#requirements");
			loadEmployeeSummary();
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="requirements_add_form" name="form1" method="post" action="<?php echo url('employee/_add_requirements'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
<table>
  	 <tr>
      <td class="field_label">Requirement:</td>
      <td><input type="text" class="validate[required] text-input" name="name" id="name" value="<?php echo $details->name; ?>" />&nbsp;&nbsp;<small><em>(ex: 2x2 picture, resume)</em></small></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="add_link blue float-right" href="javascript:void(0);" onclick="javascript:addDefaultRequirements();"><span class="add blue_icon"></span>Add Default Requirements</a><input class="blue_button" type="submit" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadRequirementsTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
