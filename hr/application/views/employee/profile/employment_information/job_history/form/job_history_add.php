<script>
$("#job_history_from").datepicker(
	{
		dateFormat:'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		showOtherMonths:true,
		onSelect	:function() { 
			$("#job_history_to").datepicker('option',{minDate:$(this).datepicker('getDate')});			
		}
	}
);
$("#job_history_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#job_history_add_form").validationEngine({scroll:false});
$('#job_history_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			/*dialogOkBox('Successfully Updated',{});
			$("#job_history_wrapper").html('');
			$("#employment_status_wrapper").html('');
			var hash = window.location.hash;
			loadPage(hash);			*/
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
<form id="job_history_add_form" name="form1" method="post" action="<?php echo url('employee/_update_job_history'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
  	 <tr>
  	   <td class="field_label">Job:</td>
  	   <td> <select name="job_id" id="job_id" class="validate[required] select_option" > 
       <option value="">--Select Job--</option>
        <?php foreach($job as $key=>$value){  ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
        <?php } ?>
      </select></td>
    </tr>
  	 <tr>
  	   <td class="field_label">Employment Status:</td>
  	   <td>
       <select class="validate[required] select_option" name="employment_status" id="employment_status" >
			<option value="" selected="selected">-- Select Employment Status --</option>
				<?php foreach($status as $key=>$value) {?>
				<option value="<?php echo $value->status;  ?>"><?php echo $value->status; ?></option>
				<?php
				
				 } ?>
			<!-- <option value="0" >Terminated</option> -->
		</select>
       </td>
    </tr>
  	 <tr>
  	   <td class="field_label">Start Date:</td>
  	   <td><input class="validate[required] text-input" type="text" name="start_date" id="job_history_from" value="" /></td>
    </tr>
  	 <tr>
  	   <td class="field_label">End Date:</td>
  	   <td>
  	   		<input class="text-input" type="text"  name="end_date" id="job_history_to" value="" /><br /><small style="font-size:11px;">Note : Leave it blank if current position</small>

  	   </td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadJobHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
