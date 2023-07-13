<script>
$("#skills_edit_form").validationEngine({scroll:false});
$("#skills_edit_form").ajaxForm({
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
<form id="skills_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_skill'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Skill:</td>
      <td>
      	<select name="skill" id="skill">
      	<?php foreach($g_skills as $gs){ ?>
        	<option <?php echo($details->skill == $gs->getSkill() ? 'selected="selected"' : ''); ?> value="<?php echo $gs->getSkill(); ?>"><?php echo $gs->getSkill(); ?></option>
        <?php } ?>
        </select>      	
      </td>
    </tr>
    <tr>
      <td class="field_label">Years of Experience:</td>
      <td><input class="validate[required,custom[money]] text-input" type="text" name="years_experience" id="years_experience" value="<?php echo $details->years_experience; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Comment:</td>
      <td><textarea name="comments" id="comments"><?php echo  ucfirst($details->comments); ?></textarea></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadSkillDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Skill</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadSkillTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
