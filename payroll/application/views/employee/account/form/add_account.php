<script>

$(document).ready(function() {
$("#hired_date_add_employee").datepicker();

	$("#employee_account_form").validationEngine({scroll:false});

	$('#employee_account_form').ajaxForm({
		success:function(o) {
			if(o==1) {
				dialogOkBox('Successfully Registered',{ok_url: 'employee/account'});
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
<div id="formcontainer">
<div class="mtshad"></div>
<form id="employee_account_form"  action="<?php echo url('employee/_insert_new_acount'); ?>" method="post"  name="employee_account_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="module" name="module" value="<?php echo $module; ?>" />
<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $company_structure_id; ?>"  />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add Account</h3>
<div id="form_main">
	<h3 class="section_title"><span>Account Information</span></h3>
    <div id="form_default">      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%" align="left" valign="top" class="field_label">Search By<br />
Lastname and Firstname</td>
          <td width="65%" align="left" valign="top"><input type="text" name="quick_search" id="quick_search" /></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Search By Employee ID:</td>
          <td align="left" valign="top">
          <div id="branch_dropdown_wrapper">
            <input type="text" name="employee_code" id="search_by_employee_code" />
          </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Employee Name:</td>
          <td align="left" valign="top">
          <div id="department_dropdown_wrapper">
            <input type="text" name="employee_name" id="employee_name" class="validate[required]" />
          </div> 
         </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Username:</td>
          <td align="left" valign="top">
          <div id="position_dropdown_wrapper">
            <input class="validate[required,custom[onlyLetterNumber],maxSize[20],minSize[5]" type="text" name="username" id="username" />
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Password:</td>
          <td align="left" valign="top">
          <div id="status_dropdown_wrapper">
            <input class="validate[required,minSize[5]]" type="text" name="password" id="password" />
          </div>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Confirm Password:</td>
          <td align="left" valign="top"><input class="validate[required,equals[password]]" type="text" name="confirm_password" id="confirm_password" /></td>
        </tr>
        <!--<tr>
          <td align="left" valign="top" class="field_label">Group: </td>
          <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
        </tr>-->
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Module:</td>
          <td align="left" valign="top"><label><input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group]" value="hr" id="module_hr" onclick="javascript:updateModule('HR');">
            HR</label></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label><input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group]"  id="module_employee" value="employee" onclick="javascript:updateModule('employee');"> 
            Employee</label>
</td>
        </tr>
        <!--<tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label><input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group]" value="payroll" id="module_payroll" onclick="javascript:updateModule('Payroll');">
          Payroll</label></td>
        </tr>-->
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label><input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group]" value="clerk" id="module_clerk" onclick="javascript:updateModule('Clerk');">
HR Clerk</label></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
      </table>
    </div>

<div id="form_default"></div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Add Account" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:cancel_add_account_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div><!-- #formwrap -->

</form>
</div>
<div id="error_message"></div>
