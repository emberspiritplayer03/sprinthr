<script>
$("#work_experience_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#work_experience_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#work_experience_add_form").validationEngine({scroll:false});
$('#work_experience_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#work_experience_wrapper").html('');
			loadPage("#work_experience");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="work_experience_add_form" name="form1" method="post" action="<?php echo url('employee/_update_work_experience'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Company:</td>
  	   <td><input type="text" class="validate[required] text-input" name="company" id="company" value="<?php echo $details->company; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Address:</td>
  	   <td><input class="text-input" type="text" name="address" id="training_from" value="<?php echo $details->address; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Position:</td>
  	   <td><input class="validate[required] text-input" type="text" name="job_title" id="training_to" value="<?php echo  ucfirst($details->job_title); ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">From:</td>
  	   <td><input type="text" class="validate[required] text-input" name="from_date" id="work_experience_from" value="<?php echo $details->to_from; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">To:</td>
  	   <td><input type="text" class="validate[required] text-input" name="to_date" id="work_experience_to" value="<?php echo $details->to_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Comment:</td>
  	   <td><textarea name="comment" id="comment"><?php echo $details->comment; ?></textarea></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadWorkExperienceTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
