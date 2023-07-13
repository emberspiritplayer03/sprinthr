<script>
$("#skill_add_form").validationEngine({scroll:false});
$('#skill_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#skills_wrapper").html('');
			loadPage("#skills");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="skill_add_form" name="form1" method="post" action="<?php echo url('employee/_update_skill'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Skill:</td>
      <td><input type="text" class="validate[required] text-input" name="skill" id="skill" /></td>
    </tr>
    <tr>
      <td class="field_label">Years of Experience:</td>
      <td><input class="text-input" type="text" name="years_experience" id="years_experience" /></td>
    </tr>
    <tr>
      <td class="field_label">Comment:</td>
      <td><textarea name="comments" id="comments"></textarea></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadSkillTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
