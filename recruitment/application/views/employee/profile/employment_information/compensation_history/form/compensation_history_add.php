<script>
$("#compensation_history_add_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#compensation_history_add_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#renewal_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

$("#compensation_history_add_form").validationEngine({scroll:false});
$('#compensation_history_add_form').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Inserted',{});
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
<form id="compensation_history_add_form" name="form1" method="post" action="<?php echo url('employee/_update_compensation_history'); ?>" >
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <table>
    <tr>
      <td class="field_label">Pay Rate:</td>
      <td><select  class="select_option" name="job_salary_rate_id" id="job_salary_rate_id_add" onchange="javascript:loadMinimumMaximumRateHistoryAdd();">
        <option value="<?php echo $employee_rate->id; ?>"><?php echo $employee_rate->job_level; ?></option>
        <?php foreach($rate as $key=>$value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->job_level; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Minimum Salary:</td>
      <td><div id="minimum_rate_label_add"><?php echo $employee_rate->minimum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Maximum Salary:</td>
      <td><div id="maximum_rate_label_add"><?php echo $employee_rate->maximum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Type</td>
      <td><select class="validate[required] select_option" name="type" id="type">
        <option <?php echo ($employee_salary->type=='hourly_rate') ? 'selected="selected"' : '' ; ?> value="hourly_rate">Hourly Rate</option>
        <option  <?php echo ($employee_salary->type=='daily_rate') ? 'selected="selected"' : '' ; ?> value="daily_rate">Daily Rate</option>
        <option  <?php echo ($employee_salary->type=='monthly_rate') ? 'selected="selected"' : '' ; ?> value="monthly_rate">Monthly Rate</option>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Basic Salary:</td>
      <td><?php
	
	   ?><input name="basic_salary_add" class="validate[required,custom[number]] text-input" type="text" id="number" value="<?php echo $employee_salary->basic_salary; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Pay Frequency:</td>
      <td><select class="select_option" name="pay_period_id" id="pay_period_id">
       <option value="<?php echo $employee_pay_period->id; ?>"><?php echo $employee_pay_period->pay_period_code." - ".$employee_pay_period->pay_period_name; ?></option>
      <?php foreach($pay_period as $key=>$value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->pay_period_code. " - ".$value->pay_period_name; ?> - <?php echo $value->cut_off; ?></option>
      <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Start Date</td>
      <td><input class="validate[required]" type="text" name="start_date" id="compensation_history_add_from" value="<?php echo $employee_salary->start_date; ?>" /></td>
    </tr>

    <tr  id="compensation_history_to_tr"  >
      <td class="field_label">End Date</td>
      <td><input class="validate[required]" type="text" name="end_date" id="compensation_history_add_to" value="" /></td>
    </tr>
    <tr>
      <td class="field_label">&nbsp;</td>
      <td><input name="present" type="checkbox" id="present" value="1" onclick="javascript:onCheckCompensationHistoryAdd();" />
        Present</td>
    </tr>
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Add" /><a href="javascript:void(0);" onclick="javascript:loadCompensationHistoryTable();">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
