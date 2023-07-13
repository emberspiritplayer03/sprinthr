<script>
$("#work_experience_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#work_experience_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#work_experience_edit_form").validationEngine({scroll:false});
$('#work_experience_edit_form').ajaxForm({
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
<form id="work_experience_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_work_experience'); ?>" >
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Company:</td>
      <td valign="top"><input type="text" class="validate[required]" name="company" id="company" value="<?php echo $details->company; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Address:</td>
      <td valign="top"><input type="text" name="address" id="address" value="<?php echo $details->address; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Position</td>
      <td width="241" valign="top">
      <input type="text" name="job_title" id="training_to" value="<?php echo  ucfirst($details->job_title); ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">From:</td>
      <td valign="top"><input type="text" class="validate[required]" name="from_date" id="from_date" value="<?php echo $details->from_date; ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">To:</td>
      <td valign="top"><input type="text" class="validate[required]" name="to_date" id="to_date" value="<?php echo $details->to_date; ?>" /></td>
    </tr>
    <tr>

      <td align="right" valign="top">Comment:</td>
      <td valign="top"><input type="text"  name="comment" id="comment" value="<?php echo $details->comment; ?>" /></td>
    </tr>
    <tr>
      <td align="left" valign="top"><a href="javascript:void(0);" onclick="javascript:loadWorkExperienceDeleteDialog('<?php echo $details->id; ?>')">delete work experience</a></td>
      <td valign="top"><input type="submit" name="button" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadWorkExperienceTable();">Cancel</a></td>
    </tr>
  </table>
</form>
