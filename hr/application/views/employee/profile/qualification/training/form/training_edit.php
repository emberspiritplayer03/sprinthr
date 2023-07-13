<script>
$("#training_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#training_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
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
<form id="training_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_training'); ?>" >
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Description:</td>
      <td valign="top"><input type="text" class="validate[required]" name="description" id="description" value="<?php echo $details->description; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">From:</td>
      <td valign="top"><input type="text" name="from_date" id="training_from" value="<?php echo $details->from_date; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">To:</td>
      <td width="241" valign="top">
      <input type="text" name="to_date" id="training_to" value="<?php echo  ucfirst($details->to_date); ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Provider:</td>
      <td valign="top"><input type="text" class="validate[required]" name="provider" id="provider" value="<?php echo $details->provider; ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Location:</td>
      <td valign="top"><input type="text" class="validate[required]" name="location" id="location" value="<?php echo $details->location; ?>" /></td>
    </tr>
    <tr>

      <td align="right" valign="top">Cost:</td>
      <td valign="top"><input type="text" class="validate[required]" name="cost" id="cost" value="<?php echo $details->cost; ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Renewal Date:</td>
      <td valign="top"><input type="text" class="validate[required]" name="renewal_date" id="renewal_date" value="<?php echo $details->renewal_date; ?>" /></td>
    </tr>
    <tr>
      <td align="left" valign="top"><a href="javascript:void(0);" onclick="javascript:loadTrainingDeleteDialog('<?php echo $details->id; ?>')">delete training</a></td>
      <td valign="top"><input type="submit" name="button" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadTrainingTable();">Cancel</a></td>
    </tr>
  </table>
</form>
