<h2 class="field_title"><?php echo $title; ?></h2>
<script>
$(document).ready(function() {
$("#hired_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

	$("#applicant_hire_add_form").validationEngine({scroll:false});

	$('#applicant_hire_add_form').ajaxForm({
		success:function(o) {
				if(o==1) {
					dialogOkBox('Successfully Added',{ok_url: 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo HIRED; ?>#application_history' });	
					//location.href=base_url+ 'recruitment/profile?rid=<?php echo Utilities::encrypt($details->id) ?>&hash=<?php echo $details->getHash();?>&status=<?php echo HIRED; ?>#application_history'; 
				}else {
					dialogOkBox(o,{});	
				}
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
});


</script>
<div>
<form id="applicant_hire_add_form"  action="<?php echo url('recruitment/_update_applicant_event'); ?>" method="post"  name="applicant_examination_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo Utilities::encrypt($details->id); ?>"  />
<input type="hidden" id="application_status" name="application_status" value="<?php echo HIRED; ?>"  />
<input type="hidden" id="position_id" name="position_id" value="<?php echo $details->job_id; ?>"  />
<div id="form_main" class="employee_form"> 
 	<div id="form_default">
      <h3 class="section_title"><span>Employment Information</span></h3>
      <table width="100%">
        <tr>
          <td class="field_label">Branch:</td>
          <td>
          <div id="branch_dropdown_wrapper">
          <select class="validate[required] select_option" name="branch_id" id="branch_id" onchange="javascript:checkForAddBranch();">
            <option value="" selected="selected">-- Select Branch --</option>
				<?php foreach($branches as $key=>$value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                <?php } ?>
           
          </select>
          </div> 
         </td>
        </tr>
        <tr>
          <td class="field_label">Department:</td>
          <td>
          <div id="department_dropdown_wrapper">
          <select class="validate[required] select_option" name="department_id" id="department_id" onchange="javascript:checkForAddDepartment();">
              <option value="" selected="selected">-- Select Department --</option>
				<?php foreach($departments as $key=>$value) { ?>
               	 	<option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
          
          </select><br />
          <small><em>*Select Branch first to load the department option</em></small></div> 
         </td>
        </tr>
        <tr>
          <td class="field_label">Position:</td>
          <td>
          <div id="position_dropdown_wrapper">
          <select class="validate[required] select_option" name="position_id" id="position_id"  onchange="javascript:checkForAddPosition();">
          <option value="" selected="selected">-- Select Position --</option>
			<?php foreach($positions as $key=>$value) { ?>
	            <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
            <?php } ?>
   
          </select>
          </div>
          </td>
        </tr>
        <tr>
          <td class="field_label">Employment Status:</td>
          <td>
          <div id="status_dropdown_wrapper">
          <select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForAddStatus();">
          <option value="" selected="selected" >-- Select Employment Status --</option>
			<?php foreach($employement_status as $key=>$value) { ?>
            <option value="<?php echo $value->id;  ?>"><?php echo $value->status; ?></option>
            <?php } ?>
        
     
          </select>
          </div>
          </td>
        </tr>
    </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table width="100%">
        <tr>
      <td class="field_label">Pay Rate::</td>
      <td><select class="select_option" name="job_salary_rate_id" id="job_salary_rate_id" onchange="javascript:loadMinimumMaximumRate();">
        <option value="<?php echo $employee_rate->id; ?>"><?php echo $employee_rate->job_level; ?></option>
        <?php foreach($rate as $key=>$value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->job_level; ?></option>
        <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td class="field_label">Minimum Salary:</td>
      <td><div id="minimum_rate_label"><?php echo $employee_rate->minimum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Maximum Salary:</td>
      <td><div id="maximum_rate_label"><?php echo $employee_rate->maximum_salary; ?></div></td>
    </tr>
    <tr>
      <td class="field_label">Type:</td>
      <td>
      <select name="type" id="type" class="validate[required] select_option">
        <option <?php echo ($employee_salary->type=='hourly_rate') ? 'selected="selected"' : '' ; ?> value="hourly_rate">Hourly Rate</option>
        <option  <?php echo ($employee_salary->type=='daily_rate') ? 'selected="selected"' : '' ; ?> value="daily_rate">Daily Rate</option>
        <option  <?php echo ($employee_salary->type=='monthly_rate') ? 'selected="selected"' : '' ; ?> value="monthly_rate">Monthly Rate</option>
      </select>
      </td>
    </tr>
    <tr>
      <td class="field_label">Basic Salary:</td>
      <td><?php
	
	   ?><input name="basic_salary" class="validate[required,custom[number]]  text-input" type="text" id="number" value="<?php echo $employee_salary->basic_salary; ?>" /></td>
    </tr>
    <tr>
      <td class="field_label">Pay Frequency:</td>
      <td><select class="validate[required] select_option" name="pay_period_id" id="pay_period_id">
       <option value="<?php echo $employee_pay_period->id; ?>"><?php echo $employee_pay_period->pay_period_code." - ".$employee_pay_period->pay_period_name; ?></option>
      <?php foreach($pay_period as $key=>$value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->pay_period_code. " - ".$value->pay_period_name; ?></option>
      <?php } ?>
      </select></td>
    </tr>
      </table>
    </div>
    <div id="form_default">
      <h3 class="section_title">Hiring Profiling</h3>
      <table width="100%">
        <tr>
          <td class="field_label">&nbsp;</td>
          <td><div>
            <div><strong>Confirm Approval of above applicant</strong></div>
            <div><em>This will mark the applicant as hired   and create an employee entry in the system for the applicant. <br />
              The hiring   manager will be notified, but no emails will be sent to the applicant.</em></div>
          </div></td>
        </tr>
        <tr>
          <td class="field_label">Hired Date:</td>
          <td><input type="text" class="validate[required] text-input" name="hired_date" id="hired_date" /></td>
        </tr>
        <tr>
          <td class="field_label">Hired By:</td>
          <td><input type="text" class="validate[required] text-input" name="hiring_manager_id" id="hiring_manager_id" /></td>
        </tr>
    </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table width="100%">
        <tr>
          <td class="field_label">Notes:</td>
          <td>
            <div id="status_dropdown_wrapper">
              <textarea name="notes" id="notes" cols="45" rows="5"></textarea>
              </div>
            </td>
        </tr>
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Hired" class="curve blue_button" /></td>
          </tr>
        </table>        
    </div>
</div>

</form>
</div>


<script>


$('#hiring_manager_id').textboxlist({unique: true,max:1, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'recruitment/_autocomplete_load_scheduled_by'}
}}});
</script>

