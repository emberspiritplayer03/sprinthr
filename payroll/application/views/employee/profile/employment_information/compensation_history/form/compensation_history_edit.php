<script>
$("#compensation_history_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#compensation_history_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#compensation_history_edit_form").validationEngine({scroll:false});
$('#compensation_history_edit_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});
			$("#compensation_history_wrapper").html('');
			$("#compensation_wrapper").html('');
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
<form id="compensation_history_edit_form" name="form1" method="post" action="<?php echo url('employee/_update_compensation_history'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<input type="hidden" name="compensation_history_id" value="<?php echo $compensation_history_id; ?>" />
<div id="form_default">
  <table>
    <tr>
      <td class="field_label">Pay Rate:</td>
      <td><select class="select_option" name="job_salary_rate_id" id="job_salary_rate_id_edit" onchange="javascript:loadMinimumMaximumRateHistoryEdit();">
        <?php foreach($rate as $key=>$value) { ?>
               <?php $selected = ($employee_rate->id==$value->id) ? "selected='selected'" : '' ; ?>
        <option value="<?php echo $value->id; ?>" <?php echo $selected; ?>><?php echo $value->job_level; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Minimum Salary:</td>
      <td><div id="minimum_rate_label_edit"><?php echo $employee_rate->minimum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Maximum Salary:</td>
      <td><div id="maximum_rate_label_edit"><?php echo $employee_rate->maximum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Type</td>
      <td><select class="select_option" name="type" id="type">
        <option <?php echo ($employee_salary->type=='hourly_rate') ? 'selected="selected"' : '' ; ?> value="hourly_rate">Hourly Rate</option>
        <option  <?php echo ($employee_salary->type=='daily_rate') ? 'selected="selected"' : '' ; ?> value="daily_rate">Daily Rate</option>
        <option  <?php echo ($employee_salary->type=='monthly_rate') ? 'selected="selected"' : '' ; ?> value="monthly_rate">Monthly Rate</option>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Basic Salary:</td>
      <td><?php
	
	   ?><input name="basic_salary_history" class="validate[required,custom[number]] text-input" type="text" id="basic_salary_history" value="<?php echo $employee_salary->basic_salary; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Pay Frequency:</td>
      <td><select class="select_option" name="pay_period_id" id="pay_period_id">
      
      <?php foreach($pay_period as $key=>$value) { ?>

       <?php $selected = ($employee_pay_period->id==$value->id)? "selected='selected'" : '' ; ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->pay_period_code. " - ".$value->pay_period_name; ?> - <?php echo $value->cut_off; ?></option>
      <?php } ?>
      </select></td>
    </tr>
     
    <tr >
      <td class="field_label">Start Date</td>
      <td><input type="text" class="validate[required]" name="compensation_history_from" id="compensation_history_from" value="<?php echo $employee_salary->start_date; ?>" /></td>
    </tr>
    <?php $display = ($employee_salary->end_date) ? '' : "style='display:none'" ; ?>
    <tr  id="compensation_history_to_tr" <?php echo $display; ?> >
      <td class="field_label">End Date</td>
      <td><input type="text" name="compensation_history_to" class="validate[required]" id="compensation_history_to" value="<?php echo $employee_salary->end_date; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">&nbsp;</td>
      <?php $checked = ($employee_salary->end_date) ? '' : "checked='checked'" ; ?>
      <td><input name="present" type="checkbox" id="present" onclick="javascript:onCheckCompensationHistory();" value="1" <?php echo $checked; ?> />
        Present</td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><a class="delete_link red float-right" href="javascript:void(0);" onclick="javascript:loadCompensationHistoryDeleteDialog('<?php echo $compensation_history_id; ?>')"><span class="delete"></span>Delete Compensation History</a><input class="blue_button" type="submit" name="button" id="button" value="Update" /><a href="javascript:void(0);" onclick="javascript:loadCompensationHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
