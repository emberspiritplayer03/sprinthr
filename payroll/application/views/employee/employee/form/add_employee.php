<script>

$(document).ready(function() {
$("#hired_date_add_employee").datepicker();

	$("#employee_form").validationEngine({scroll:false});

	$('#employee_form').ajaxForm({
		success:function(o) {
			if(o==0){
				 dialogOkBox('Please Fill Up the Form Completely',{}) 
			}else {
				employee_id = o;
				$.post(base_url+"employee/_load_employee_hash",{employee_id:employee_id},
				function(o){
					$("#employee_hash").val(o);
					load_add_employee_confirmation(employee_id);
				});	
			}
			
		},
		beforeSubmit:function() {
			showLoadingDialog('Saving...');	
		}
	});
	var t = new $.TextboxList('#supervisor_id', {plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
	
	}
}});
	
	
});


</script>
<div id="formcontainer">
<div class="mtshad"></div>
<form id="employee_form"  action="<?php echo url('employee/_insert_new_employee'); ?>" method="post"  name="employee_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Employee</h3>
<div id="form_main">
	<h3 class="section_title"><span>Employment Information</span></h3>
    <div id="form_default">      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Branch:</td>
          <td align="left" valign="top">
          <div id="branch_dropdown_wrapper">
          <select class="validate[required] select_option" name="branch_id" id="branch_id" onchange="javascript:checkForAddBranch();">
            <option value="" selected="selected">-- Select Branch --</option>
				<?php foreach($branches as $key=>$value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                <?php } ?>
            <option value="add">Add Branch...</option>
          </select>
         </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Department:</td>
          <td align="left" valign="top">
          <div id="department_dropdown_wrapper">
          <select class="validate[required] select_option" name="department_id" id="department_id" onchange="javascript:checkForAddDepartment();">
              <option value="" selected="selected">-- Select Department --</option>
				<?php foreach($departments as $key=>$value) { ?>
               	 	<option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
              <option value="add">Add Department...</option>
          </select>
         </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Position:</td>
          <td align="left" valign="top">
          <div id="position_dropdown_wrapper">
          <select class="validate[required] select_option" name="position_id" id="position_id"  onchange="javascript:checkForAddPosition();">
          <option value="" selected="selected">-- Select Position --</option>
			<?php foreach($positions as $key=>$value) { ?>
	            <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
            <?php } ?>
          <option value="add">Add Position...</option>
          </select>
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Employment Status:</td>
          <td align="left" valign="top">
          <div id="status_dropdown_wrapper">
          <select class="validate[required] select_option" name="employment_status_id" id="employment_status_id" onchange="javascript:checkForAddStatus();">
          <option value="" selected="selected" >-- Select Employment Status --</option>
			<?php foreach($employement_status as $key=>$value) { ?>
            <option value="<?php echo $value->id;  ?>"><?php echo $value->status; ?></option>
            <?php } ?>
          <option value="0" >Terminated</option>
          <option value="add">Add Status...</option>
          </select>
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Supervisor / Manager:</td>
          <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
        </tr>
      </table>
    </div>
    <div class="form_separator"></div>
    <h3 class="section_title"><span>Personal Information</span></h3>
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" class="field_label">*Employee ID:</td>
            <td align="left" valign="top"><input name="employee_code" type="text" class="validate[required] text-input text" id="employee_code" value="" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" class="field_label">*Firstname:</td>
            <td align="left" valign="top"><input type="text" value="" name="firstname" class="validate[required] text-input text" id="firstname" /></td>
            </tr>    
          <tr>
            <td align="left" valign="top" class="field_label">*Lastname:</td>
            <td align="left" valign="top"><input type="text" value="" name="lastname" class="validate[required] text-input text" id="lastname" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" class="field_label">*Hired Date:</td>
            <td align="left" valign="top"><input type="text" value="" name="hired_date" class="validate[required] text-input text" id="hired_date_add_employee" /></td>
          </tr>
        </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Add New Employee" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:cancel_add_employee_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->

</form>
</div>
<div id="error_message"></div>
