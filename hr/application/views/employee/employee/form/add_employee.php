<script>

$(document).ready(function() {
/*jQuery("#tags").tagBox();*/

 addEmployeeActionScripts();

$('#tags').tagsInput({width:'289px'});		

    $("#hired_date_add_employee").datepicker({yearRange: "1990:2040", dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
    $("#birthdate").datepicker({yearRange: "1920:2020", dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

	$("#employee_form").validationEngine({scroll:false});

	$('#employee_form').ajaxForm({
		success:function(o) {
			if(o==0){
				 dialogOkBox('Please Fill Up the Form Completely',{}) 
      }else if(o==1){ //exceed limit
				 dialogOkBox('You have reached your maximum employee number. Contact SprintHR support if you want to add more employees on your system',{}) 
			}else {
				employee_id = o;
				$.post(base_url+"employee/_load_employee_hash",{employee_id:employee_id},
				function(o){
					$("#employee_hash").val(o);
          closeDialog('#' + DIALOG_CONTENT_HANDLER);
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
<input type="hidden" id="branch_id" value="1" />
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Employee</h3>
<div id="form_main">
	<h3 class="section_title"><span>Employment Information</span></h3>
    <div id="form_default">      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- <tr>
          <td align="left" valign="top" class="field_label">Branch:</td>
          <td align="left" valign="top">
          <div id="branch_dropdown_wrapper">
          <select class="validate[required] select_option" name="branch_id" id="branch_id" onchange="javascript:checkForAddBranch();">
            <option value="" selected="selected">-- Select Branch --</option>
				<?php //foreach($branches as $key=>$value) { ?>
                    <option value="<?php //echo $value->id; ?>"><?php //echo $value->name; ?></option>
                <?php //} ?>
            <option value="add">Add Branch...</option>
          </select>
         </div> 
         </td>
        </tr> -->
        <tr>
          <td align="left" valign="top" class="field_label">*Department:</td>
          <td align="left" valign="top">
          <div id="department_dropdown_wrapper">
          <select class="validate[required] select_option" name="department_id" id="department_id" >
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
          <td align="left" valign="top" class="field_label">Section:</td>
          <td align="left" valign="top">
          <div id="section_dropdown_wrapper">
            <select class=" select_option" name="section_id" id="section_id" >
              <option value="" selected="selected">-- Select Section --</option>
            </select>
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">*Position:</td>
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
          <td align="left" valign="top" class="field_label">*Employment Status:</td>
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
              <td align="left" valign="top" class="field_label">*Hired Date:</td>
              <td align="left" valign="top"><input type="text" value="" name="hired_date" class="validate[required] text-input text" id="hired_date_add_employee" /></td>
          </tr>
          <tr>
              <td align="left" valign="top" class="field_label">*Salary Amount:</td>
              <td align="left" valign="top"><input type="text" value="" name="salary_amount" class="validate[required] text-input text" id="salary_amount" /></td>
          </tr>
          <tr>
              <td align="left" valign="top" class="field_label">*Salary Type:</td>
              <td align="left" valign="top"><select class="validate[required] select_option" name="salary_type" id="salary_type">
                      <option value="">-- Select Salary Type --</option>
                      <option value="<?php echo G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY;?>"><?php echo G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY;?></option>
                      <option value="<?php echo G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY;?>"><?php echo G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY;?></option>
                  </select></td>
          </tr>

           <tr>
              <td align="left" valign="top" class="field_label">*Frequency Type: </td>
              <td align="left" valign="top"><select class="validate[required] select_option" name="frequency_type" id="frequency_type">
                      <option value="">-- Select Frequency Type --</option>
                       <?php foreach ($frequencies as $value) {
                       ?>
                    <!--  <?php var_dump($value->getId()); ?> -->
                    <option value="<?php echo $value->getId(); ?>"><?php echo $value->getFrequencyType(); ?></option>
                       <?php
                      } ?>
                  </select></td>
          </tr>
        <!--<tr>
          <td align="left" valign="top" class="field_label">Supervisor / Manager:</td>
          <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
        </tr>-->
        <tr>
          <td align="left" valign="top" class="field_label">Tags:</td>
          <td align="left" valign="top">
          	<!--<label id="tag-tipsy">
          	<input type="text" id="tags" name="tags" />
            </label>-->
          	<input type="text" value="" name="tags" id="tags" />
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label"><!-- Cost Center: --> Project Site:</td>
          <td align="left" valign="top">    
            
            <input type="text" value="" name="cost_center" id="cost_center" style="display:none" />
           

             <select name="project_site_id" id="project_site_id" class="validate[required] select_option" >
               <option value="">--Select Project Site--</option>
                <?php foreach($projects as $key=>$value){  ?>
                 <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

                <?php } ?>
              </select>

          </td>
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
            <td align="left" valign="top" class="field_label">Middlename:</td>
            <td align="left" valign="top"><input type="text" value="" name="middlename" class="text-input text" id="middlename" /></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">Extension Name:</td>
            <td align="left" valign="top"><input type="text" value="" name="extension_name" class="text-input text" id="extension_name" /></td>
          </tr>
            <tr>
                <td align="left" valign="top" class="field_label">*Birth Date:</td>
                <td align="left" valign="top"><input type="text" value="" name="birthdate" class="validate[required] text-input text" id="birthdate" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" class="field_label">*Gender:</td>
            <td align="left" valign="top"><select class="validate[required] select_option" name="gender" id="gender">
              <option value="">-- Select Gender --</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select></td>
          </tr>
          <tr>
            <td align="left" valign="top" class="field_label">*Marital Status:</td>
            <td align="left" valign="top"><select class="validate[required] select_option" name="marital_status" id="marital_status">
              <option value="">-- Select Marital Status --</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Separated">Separated</option>
                <option value="Widowed">Widowed</option>
            </select></td>
          </tr>
          <tr>
              <td align="left" valign="top" class="field_label">Confidential:</td>
              <td align="left" valign="top">
                <select name="is_confidential" id="is_confidential">
                  <option value="1"><?php echo G_Employee::YES;?></option>
                  <option selected="selected" value="0"><?php echo G_Employee::NO;?></option>
                </select>
              </td>
          </tr>
          <tr>
              <td align="left" valign="top" class="field_label">*Number of Dependents:</td>
              <td align="left" valign="top"><input type="text" value="" name="number_of_dependents" class="validate[required] text-input text" id="number_of_dependents" /></td>
          </tr>
          <tr>
            <td align="right" class="field_label" valign="top">Working days in a week:</td>
            <td valign="top">              
              <select name="week_working_days" id="week_working_days">
                <?php foreach($working_days_options as $option){ ?>
                  <option value="<?php echo $option['description']; ?>"><?php echo $option['description']; ?></option>                  
                <?php } ?>
              </select>                
            </td>
          </tr>
          
          <tr>
            <td align="left" valign="top" class="field_label">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
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
<script type='text/javascript'>
  $(function() {	 
 	$('#tags_tag').tipsy({trigger: 'focus',html: true, gravity: 'e'});	 
  });
</script>
