<script>
$("#training_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#training_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#training_add_form").validationEngine({scroll:false});
$('#training_add_form').ajaxForm({
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
<form id="training_add_form" name="form1" method="post" action="<?php echo url('employee/_update_training'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Description:</td>
  	   <td><input type="text" class="validate[required] text-input" name="description" id="description" value="<?php echo $details->description; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">From:</td>
  	   <td><input class="text-input" type="text" name="from_date" id="training_from" value="<?php echo $details->from; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">To:</td>
  	   <td><input class="text-input" type="text" name="to_date" id="training_to" value="<?php echo  ucfirst($details->to); ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Provider:</td>
  	   <td><input type="text" class="validate[required] text-input" name="provider" id="provider" value="<?php echo $details->provider; ?>" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Location:</td>
  	   <td><input type="text" class="validate[required] text-input" name="location" id="location" value="<?php echo $details->location; ?>" /></td>
    </tr>
    <!-- 
  	<tr>
  	   <td class="field_label">Cost:</td>
  	   <td><input type="text" class="validate[required] text-input" name="cost" id="cost" value="<?php echo $details->cost; ?>" /></td>
    </tr>
  	<tr>
  	   <td class="field_label">Renewal Date:</td>
  	   <td><input type="text" class="validate[required] text-input" name="renewal_date" id="renewal_date" value="<?php echo $details->renewal_date; ?>" /></td>
    </tr>
    -->
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadTrainingTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
