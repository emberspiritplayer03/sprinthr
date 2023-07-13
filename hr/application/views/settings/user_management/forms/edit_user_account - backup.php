<div id="form_main" class="inner_form popup_form wider">
<form id="update_account_form"  action="<?php echo url('settings/update_account'); ?>" method="post"  name="employee_account_form" > 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="update_module" name="update_module" value="<?php echo $current_module; ?>" />
<input type="hidden" id="user_id" name="user_id" value="<?php echo $user->getId(); ?>"  />

    <div id="form_default">      
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Employee Name:</td>
          <td align="left" valign="top"><div id="department_dropdown_wrapper">
            <input name="name" type="text" class="validate[required]" id="employee_name" value="<?php echo $employee->lastname . ',' . $employee->firstname; ?>" readonly="readonly" />
          </div></td>          
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
          <td align="left" valign="top" class="field_label">Module:</td>
          <td align="left" valign="top">          
          	<label class="checkbox"><input name="mod[group_update]" type="checkbox" class="validate[optional] checkbox" id="module_update_hr" onclick="javascript:updateModuleEdit('HR');" value="hr"  <?php echo $checked_hr; ?> /> HR</label>
          <!--  <label class="checkbox"><input class="validate[optional] checkbox" type="checkbox" name="module_update_employee"  id="module_update_employee" value="employee" onclick="javascript:updateModuleEdit('employee');"  <?php echo $checked_employee; ?> /> Employee</label> -->
            <label class="checkbox"><input class="validate[optional] checkbox" type="checkbox" name="module_update_payroll" id="module_update_payroll" value="payroll"  onclick="javascript:updateModuleEdit('Payroll');"  <?php echo $checked_payroll; ?>  /> Payroll</label>
           <!-- <label class="checkbox"><input class="validate[optional] checkbox" type="checkbox" name="module_update_clerk" value="clerk" id="module_update_clerk" onclick="javascript:updateModuleEdit('Clerk');" <?php echo $checked_clerk; ?> /> HR Clerk</label> -->
          </td>
          </tr>
        <!--<tr>
          <td align="left" valign="top" class="field_label">Group: </td>
          <td align="left" valign="top"><input type="text" name="supervisor_id" id="supervisor_id" /></td>
        </tr>-->        
      </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Update" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:closeDialogBox('#_dialog-box_','#update_account_form');">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</form>
</div><!-- #form_main -->

