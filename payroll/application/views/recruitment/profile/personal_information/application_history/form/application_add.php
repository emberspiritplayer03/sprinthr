<script>
$("#start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#application_history_add_form").validationEngine({scroll:false});
$('#application_history_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#application_history_wrapper").html('');
			loadPage("#application_history");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="application_history_add_form" name="form1" method="post" action="<?php echo url('employee/_update_application_history'); ?>">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />

  <table class="table_form" width="476" border="0" cellpadding="3" cellspacing="3">
  	 <tr>
  	   <td align="right" valign="top">Institute:</td>
  	   <td valign="top"><input type="text" class="validate[required]" name="institute" id="institute" value="<?php echo $details->institute; ?>" /></td>
    </tr>
  	 <tr>
  	   <td align="right" valign="top">Course:</td>
  	   <td valign="top"><input type="text" name="course" id="training_from" value="<?php echo $details->course; ?>" /></td>
    </tr>
  	 <tr>
  	   <td align="right" valign="top">Year:</td>
  	   <td valign="top"><input type="text" name="year" id="training_to" value="<?php echo  ucfirst($details->year); ?>" /></td>
    </tr>
  	 <tr>
  	   <td align="right" valign="top">Start Date:</td>
  	   <td valign="top"><input type="text" class="validate[required]" name="start_date" id="start_date" value="<?php echo $details->start_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td align="right" valign="top">End Date:</td>
  	   <td valign="top"><input type="text" class="validate[required]" name="end_date" id="end_date" value="<?php echo $details->end_date; ?>" /></td>
    </tr>
  	 <tr>
  	   <td align="right" valign="top">GPA Score:</td>
  	   <td valign="top"><input type="text" class="validate[required]" name="gpa_score" id="gpa_score" value="<?php echo $details->gpa_score; ?>" /></td>
    </tr>
    <tr>
      <td width="156" align="right" valign="top">&nbsp;</td>
      <td width="241" valign="top"><input type="submit" name="button" id="button" value="Add" /> 
        <a href="javascript:void(0);" onclick="javascript:loadEducationTable();">Cancel</a></td>
    </tr>
  </table>
</form>
