<script>

$(document).ready(function() {
$("#hired_date_add_employee").datepicker();

	$("#update_account_form").validationEngine({scroll:false});

	$('#update_account_form').ajaxForm({
		success:function(o) {
			if(o==1) {
				dialogOkBox('Account is successfully updated',{ok_url: 'employee/account'});
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
<form id="update_account_form"  action="<?php echo url('employee/_update_account'); ?>" method="post"  name="employee_account_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="update_module" name="update_module" value="<?php echo $current_module; ?>" />
<input type="hidden" id="user_id" name="user_id" value="<?php echo $user->getId(); ?>"  />

<div id="form_main">
	<h3 class="section_title"><span>Account Information</span></h3>
    <div id="form_default">      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Employee Name:</td>
          <td align="left" valign="top"><div id="department_dropdown_wrapper">
            <input name="name" type="text" class="validate[required]" id="employee_name" value="<?php echo $employee->lastname . ',' . $employee->firstname; ?>" readonly="readonly" />
          </div></td>
          <td width="43%" rowspan="10" align="left" valign="top">
            <img src="<?php echo $filename; ?>" width="150px"  />
            &nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Username:</td>
          <td align="left" valign="top"><div id="position_dropdown_wrapper">
            <input class="validate[required,custom[onlyLetterNumber],maxSize[20],minSize[5]" type="text" name="username_update" id="username_update" value="<?php echo $user->getUsername(); ?>" onchange="javascript:checkUsername();" />
            <div id="username_checker"></div>
          </div></td>
          </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Password:</td>
          <td align="left" valign="top"><div id="status_dropdown_wrapper">
            <input class="validate[minSize[5]]" type="password" name="password_update" id="password_update" />
          </div></td>
          </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Confirm Password:</td>
          <td align="left" valign="top"><input class="validate[equals[password_update]]" type="password" name="confirm_password_update" id="confirm_password_update" /></td>
          </tr>
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
          </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Module:</td>
          <td align="left" valign="top">          
          	<label>
            <input name="mod[group_update]" type="checkbox" class="validate[minCheckbox[1]] checkbox" id="module_update_hr" onclick="javascript:updateModuleEdit('HR');" value="hr"  <?php echo $checked_hr; ?> />
            HR</label></td>
          </tr>
        <!--<tr>
          <td align="left" valign="top" class="field_label">Group: </td>
          <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
        </tr>-->
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label>
            <input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group_update]"  id="module_update_employee" value="employee" onclick="javascript:updateModuleEdit('employee');"  <?php echo $checked_employee; ?> />
            Employee</label></td>
          </tr>
      <!--  <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label>
            <input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group]" value="payroll" id="module_payroll" onclick="javascript:updateModule('Payroll');" />
            Payroll</label></td>
          </tr>-->
        <tr>
          <td align="left" valign="top" class="field_label">&nbsp;</td>
          <td align="left" valign="top"><label>
            <input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="mod[group_update]" value="clerk" id="module_update_clerk" onclick="javascript:updateModuleEdit('Clerk');" <?php echo $checked_clerk; ?> />
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
                <input type="submit" value="Update Account" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:cancel_edit_account_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->


</form>
</div>
<div id="error_message"></div>
