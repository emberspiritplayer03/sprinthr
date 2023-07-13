<script>
$("#examination_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#examination_edit_form").validationEngine({scroll:false});
$('#examination_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#examination_wrapper").html('');
			loadPage("#examination");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="examination_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_examination'); ?>" >
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="applicant_id" value="<?php echo Utilities::encrypt($details->applicant_id); ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
      <td width="156" align="right" valign="top">Name:</td>
      <td valign="top"><input type="text" class="validate[required]" name="name" id="name" value="<?php echo $details->name; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Relationship:</td>
      <td valign="top"><input type="text" name="relationship" id="relationship" value="<?php echo $details->relationship; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">Birthdate:</td>
      <td width="241" valign="top">
      <input type="text" name="birthdate" id="dependent_birthdate" value="<?php echo  ucfirst($details->birthdate); ?>" /></td>
    </tr>
    <tr>

      <td align="right" valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top"><a href="javascript:void(0);" onclick="javascript:loadExaminationDeleteDialog('<?php echo $details->id; ?>')">delete Examination</a></td>
      <td valign="top"><input type="submit" name="button" id="button" value="Update" /> 
        <a href="javascript:void(0);" onclick="javascript:loadExaminationTable();">Cancel</a></td>
    </tr>
  </table>
</form>
