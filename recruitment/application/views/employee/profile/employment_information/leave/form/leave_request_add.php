<script>
$("#date_applied").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#date_start").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#date_end").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#leave_request_add_form").validationEngine({scroll:false});
$('#leave_request_add_form').ajaxForm({
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
<form id="leave_request_add_form" name="form1" method="post" action="<?php echo url('employee/_update_leave_request'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
      <td class="field_label">Leave Type:</td>
      <td>
      <select class="validate[required] select_option" name="leave_id" id="leave_id">
          <option value="">-- select --</option>
        <?php foreach($leaves as $key=>$value) { ?>
		<option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
		<?php } ?>
       </select>
      </td>
    </tr>
    <tr>
      <td class="field_label">Date Applied:</td>
      <td><input class="validate[required] text-input" type="text" name="date_applied" id="date_applied" value="" /></td>
    </tr>
    <tr>
      <td class="field_label">Date Start:</td>
      <td>
      <input type="text" class="validate[required] text-input" name="date_start" id="date_start" value="" /></td>
    </tr>
    <tr>

      <td class="field_label">Date End:</td>
      <td><input type="text" class="validate[required] text-input" name="date_end" id="date_end" value="" /></td>
    </tr>
    <tr>
      <td class="field_label">Leave Comments:</td>
      <td><textarea name="leave_comments" id="leave_comments"></textarea></td>
    </tr>
    <tr>
      <td class="field_label">&nbsp;</td>
      <td><select class="select_option" name="is_approved" id="is_approved">
        <option value="0">Pending</option>
        <option value="1">Approved</option>
        <option value="-1">Disapproved</option>
      </select></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadLeaveRequestTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
