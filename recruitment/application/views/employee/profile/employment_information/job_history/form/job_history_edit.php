<script>
$("#job_history_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#job_history_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#job_history_edit_form").validationEngine({scroll:false});
$('#job_history_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#job_history_wrapper").html('');
			$("#employment_status_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);

			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="job_history_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_job_history'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="id" value="<?php echo $details->id ?>" />
<input type="hidden" name="employee_id" value="<?php echo Utilities::encrypt($details->employee_id); ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Job:</td>
      <td><select name="job_id" id="job_id" class="validate[required] select_option" > 
       <option value="">--Select Job--</option>
        <?php foreach($job as $key=>$value){  ?>
        <option <?php echo ($details->job_id==$value->id) ? 'selected="selected"' : '' ; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Employment Status:</td>
      <td>
      <select class="validate[required] select_option" name="employment_status" id="employment_status" >
			<option value="" >-- Select Employment Status --</option>
				<?php foreach($status as $key=>$value) {
				 $selected = ($value->status==$details->employment_status)? 'selected="selected"' : '' ; ?>
                 
				<option <?php echo $selected; ?>  value="<?php echo $value->status;  ?>"><?php echo $value->status; ?></option>
				<?php if(count($status)==$key) { 
					$selected = ($details->employment_status=='Terminated') ? 'selected="selected"' : '' ; 
				?>
					<option <?php echo $selected; ?> value="0" >Terminated</option>			
				<?php 	}
			
				 } ?>
			
	  </select></td>
    </tr>
    <tr>
      <td class="field_label">Start Date:</td>
      <td>
      <input type="text" class="validate[required] text-input" name="start_date" id="job_history_from" value="<?php echo  ucfirst($details->start_date); ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">End Date:</td>
      <td><input type="text" class="text-input"  name="end_date" id="job_history_to" value="<?php echo $details->end_date; ?>" /></td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadJobHistoryDeleteDialog('<?php echo $details->id; ?>')"><span class="delete"></span>Delete Job History</a><input class="blue_button" type="submit" name="button" id="button" value="Update" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadJobHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
