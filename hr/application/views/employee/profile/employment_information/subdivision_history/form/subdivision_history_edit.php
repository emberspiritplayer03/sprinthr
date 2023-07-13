<script>
$("#subdivision_start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#subdivision_end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#subdivision_history_edit_form").validationEngine({scroll:false});
$('#subdivision_history_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			/*dialogOkBox('Successfully Updated',{});
			$("#subdivision_history_wrapper").html('');
			$("#employment_status_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);*/
      loadPhoto();
      dialogOkBox('Successfully Updated',{});
      $("#employment_status_wrapper").html('');
      $("#job_history_wrapper").html('');
      $("#subdivision_history_wrapper").html('');
      $("#compensation_wrapper").html('');
      $("#compensation_history_wrapper").html('');
      $("#memo_notes_wrapper").html('');
      loadEmployeeSummary();
      loadPage("#employment_status");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="subdivision_history_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_subdivision_history'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Department:</td>
      <td> <select name="subdivision_id" id="subdivision_id" class="validate[required] select_option" > 
       <option value="">--Select Department--</option>
        <?php foreach($department as $key=>$value){  ?>
        <?php $selected = (strtolower($value->title)==strtolower($details->name)) ? "selected='selected'" : '' ; ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select>
      </td>
    </tr>
    <tr>
      <td class="field_label">Start Date:</td>
      <td>
        <input type="text" class="validate[required] text-input" name="subdivision_start_date" id="subdivision_start_date" value="<?php echo  ucfirst($details->start_date); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">End Date:</td>
      <td><input type="text" class="text-input"  name="subdivision_end_date" id="subdivision_end_date" value="<?php echo $details->end_date; ?>" /><br /><small style="font-size:11px;">Note : Leave it blank if current department</small></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadSubdivisionHistoryDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Subdivision History</a>
              <input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadSubdivisionHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
