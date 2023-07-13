<script type="text/javascript">
$(function() {
	$("#credit_condition_form").validationEngine({scroll:false});
});

$(document).ready(function() {		
	$('#credit_condition_form').ajaxForm({
		success:function(o) {
			if (o.is_updated == 1) {
				hide_leave_credits_form();
        load_leave_credit_list()
				closeDialog('#' + DIALOG_CONTENT_HANDLER);				
				$("#message_container").html(o.message);
				$('#message_container').show();
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#message_container").html(o.message);
				$('#message_container').show();
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
			return true;
		}
	});		
});
</script>
<div class="formwrap inner_form">
<form action="<?php echo $action; ?>" method="post"  name="credit_condition_form" id="credit_condition_form" >
<input type="hidden" id="leave_credit_id" name="leave_credit_id" value="<?php echo Utilities::encrypt($leave_data->getId()); ?>" >
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" >
<h3 class="form_sectiontitle"><span>Edit Leave Credit Condition</span></h3>
<div id="form_main" class="credit_condition_form_summary">
    <div id="form_default">
        <table border="0" cellpadding="3" cellspacing="0" width="100%"> 
            <tbody>
             
                <tr>
                    <td class="" width="100%" valign="">
                    <p style="font-size:11px;">
                      On Employee's 
                      <select style="width:10%" name="employment_years[]" id="employment_years">
                        <option <?php echo $leave_data->getEmploymentYears() == 1 ? 'selected' : ''; ?> value="1">1st</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 2 ? 'selected' : ''; ?> value="2">2nd</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 3 ? 'selected' : ''; ?> value="3">3rd</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 4 ? 'selected' : ''; ?> value="4">4th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 5 ? 'selected' : ''; ?> value="5">5th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 6 ? 'selected' : ''; ?> value="6">6th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 7 ? 'selected' : ''; ?> value="7">7th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 8 ? 'selected' : ''; ?> value="8">8th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 9 ? 'selected' : ''; ?> value="9">9th</option>
                        <option <?php echo $leave_data->getEmploymentYears() == 10 ? 'selected' : ''; ?> value="10">10th</option>
                      </select>
                      year onwards add <input type="text" style="width:15px;" value="<?php echo $leave_data->getDefaultCredit(); ?>" name="default_credit[]" class="validate[required] text" id="default_credit" /> credits in 
                      <select style="width:18%" name="leave_id[]" id="leave_id">
                        <?php foreach($leave_type as $leave) { ?>
                        <option <?php echo $leave_data->getLeaveId() == $leave->getId() ? 'selected' : ''; ?> value="<?php echo $leave->getId(); ?>"><?php echo $leave->getName();?></option>
                        <?php } ?>
                      </select>                      
                      to all 
                      <select style="width:15%" name="employment_status_id[]" id="employment_status_id">
                        <?php foreach($employment_status as $emp_status) { ?>
                        <option <?php echo $leave_data->getEmploymentStatusId() == $emp_status->getId() ? 'selected' : ''; ?> value="<?php echo $emp_status->getId(); ?>"><?php echo $emp_status->getStatus(); ?></option>
                        <?php } ?>
                      </select> employee
                    </p>
                    </td>
                </tr>
               
            </tbody>
        </table>
    </div>
   
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">&nbsp;</td>
            <td align="left" valign="top"><input type="submit" value="Update" class="curve blue_button" />&nbsp;<a href="javascript:hide_leave_credits_form();">Cancel</a></td>
          </tr>
        </table>  
    </div>
</div>
</form>
</div>



<!--</script>-->