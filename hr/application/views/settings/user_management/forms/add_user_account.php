<script>
$(document).ready(function() {
	var t = new $.TextboxList('#employee_id', {unique: true,max:1,plugins: {
			autocomplete: {
				minLength: 3,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'leave/ajax_get_employees_autocomplete'}

			}
		}});

});

function checkForm() {

  var total_checked = $('#add_account_form input[type="checkbox"]:checked').length;
  var employee = $('#employee_id').val();
  if (employee == '') {
    alert('Please select employee name');
    return false;
  } else if (total_checked <= 0) {
    alert('Please select atleast 1 module');
    return false;
  } else {
    return true;
  }
}
</script>

<div id="form_main" class="inner_form popup_form wider">
<form id="add_account_form" onsubmit="return checkForm()" action="<?php echo url('settings/add_account'); ?>" method="post"  name="employee_account_form" >
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />

    <div id="form_default">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" class="field_label">Employee Name:</td>
          <td align="left" valign="top"><div id="department_dropdown_wrapper">
            <input name="employee_id" type="text" class="validate[required]" id="employee_id" value=""/>
          </div></td>          
        </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Username:</td>
          <td align="left" valign="top"><div id="position_dropdown_wrapper">
            <input class="validate[required,custom[onlyLetterNumber],maxSize[20],minSize[5]" type="text" name="username_update" id="username_update" value="" />
            <div id="username_checker"></div>
          </div></td>
          </tr>
        <tr>
          <td align="left" valign="top" class="field_label">Password:</td>
          <td align="left" valign="top"><div id="status_dropdown_wrapper">
            <input class="validate[required,minSize[5]]" type="password" name="password_update" id="password_update" value="" />
          </div></td>
          </tr>
                  <tr>
          <td align="left" valign="top" class="field_label">Confirm Password:</td>
          <td align="left" valign="top"><input class="validate[required,equals[password_update]]" type="password" name="confirm_password_update" id="confirm_password_update" /></td>
          </tr>
          <td align="left" valign="top" class="field_label">Module:</td>
          <td align="left" valign="top">
          	<label class="checkbox"><input name="module[]" type="checkbox" class="validate[optional] checkbox" id="module_update_hr" value="hr" /> HR</label>
            <label class="checkbox"><input class="validate[optional] checkbox" type="checkbox" name="module[]" id="module_update_payroll" value="payroll"/> Payroll</label>
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

