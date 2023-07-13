<div id="form_main" class="inner_form popup_form">
	<form name="editSSS" id="editSSS" method="post" class="frmEditContribution" action="<?php echo url('settings/update_sss'); ?>">    
    <input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt(($d ? $d->getId() : '')); ?>" />  
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Monthly Salary Credit:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getMonthlySalaryCredit() : ''); ?>" name="monthly_salary_credit" class="validate[required,custom[number]]  text" id="monthly_salary_credit" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">From Salary:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getFromSalary() : ''); ?>" name="from_salary" class="validate[required,custom[number]]  text" id="from_salary" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">To Salary:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getToSalary() : ''); ?>" name="to_salary" class="validate[required,custom[number]]  text" id="to_salary" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Employee Share:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getEmployeeShare() : ''); ?>" name="employee_share" class="validate[required,custom[number]]  text" id="employee_share" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Company Share:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getCompanyShare() : ''); ?>" name="company_share" class="validate[required,custom[number]]  text" id="company_share" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Company EC:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getCompanyEc() : ''); ?>" name="company_ec" class="validate[required,custom[number]]  text" id="company_ec" />    
            </td>
        </tr>
       </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editSSS');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>