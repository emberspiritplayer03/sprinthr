<script>
$("#from_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#to_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#training_edit_form").validationEngine({scroll:false});
$('#training_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#training_wrapper").html('');
			loadPage("#training");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="training_edit_form" name="form1" method="post" action="<?php echo url('recruitment/_update_training'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->applicant_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Description:</td>
      <td><input type="text" class="validate[required] text-input" name="description" id="description" value="<?php echo $details->description; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">From:</td>
      <td><input class="text-input" type="text" name="from_date" id="from_date" value="<?php echo $details->from_date; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">To:</td>
      <td>
      <input class="text-input" type="text" name="to_date" id="to_date" value="<?php echo  ucfirst($details->to_date); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Provider:</td>
      <td><input type="text" class="validate[required] text-input" name="provider" id="provider" value="<?php echo $details->provider; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Location:</td>
      <td><input type="text" class="validate[required] text-input" name="location" id="location" value="<?php echo $details->location; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Cost:</td>
      <td><input type="text" class="validate[required] text-input" name="cost" id="cost" value="<?php echo $details->cost; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Renewal Date:</td>
      <td><input type="text" class="validate[required] text-input" name="renewal_date" id="renewal_date" value="<?php echo $details->renewal_date; ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadTrainingDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Training</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadTrainingTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
