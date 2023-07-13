	<script>
$("#start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#duration_add_form").validationEngine({scroll:false});
$('#duration_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{height:240,width:390});
			$("#duration_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);
			
		}else {
			dialogOkBox(o,{height:240,width:390});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form action="<?php echo url('employee/_update_duration'); ?>" method="post" enctype="multipart/form-data" name="form1" id="duration_add_form">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
    <tr>
      <td class="field_label">From:</td>
      <td valign="top"><input type="text" class="validate[required] text-input" name="start_date" id="start_date" value="<?php echo $details->start_date; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">To:</td>
      <td valign="top"><input name="end_date" class="validate[required] text-input" type="text" id="end_date" value="<?php echo  ucfirst($details->end_date); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Attachment:</td>
      <td><input type="file" name="filename" id="filename" /></td>
    </tr>
    <tr>
      <td class="field_label">Remarks</td>
      <td><textarea name="remarks" id="remarks" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>
      <td class="field_label">Contract Status</td>
      <td><select class="validate[required]" name="is_done" id="is_done">
        <option value="">-- Select Contract Status --</option>
        <option value="0">Current</option>
        <option value="1">Expired</option>
      </select></td>
    </tr>
    </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadDurationTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
