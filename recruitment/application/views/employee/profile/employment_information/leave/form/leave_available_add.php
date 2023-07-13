<script>
$("#leave_available_add_form").validationEngine({scroll:false});
$('#leave_available_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#leave_wrapper").html('');
			loadPage("#leave");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="leave_available_add_form" name="form1" method="post" action="<?php echo url('employee/_update_leave_available'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Leave Type:</td>
      <td><select class="validate[required] select_option" name="leave_id" id="leave_id">
          <option value="">-- select --</option>
        <?php foreach($leaves as $key=>$value) { ?>
		<option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
		<?php } ?>
       </select></td>
    </tr>
    <tr>
      <td class="field_label">Number of Days Alloted:</td>
      <td><input class="validate[required] text-input" type="text" name="no_of_days_alloted" id="no_of_days_alloted" value="" /></td>
    </tr>
    <tr>
      <td class="field_label">Number of Days Available:</td>
      <td>
      <input type="text" class="validate[required] text-input" name="no_of_days_available" id="no_of_days_available" value="" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
          <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLeaveAvailableTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
