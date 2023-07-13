<div id="form_main" class="inner_form popup_form">
	<form name="editPagibig" id="editPagibig" class="frmEditContribution" method="post" action="<?php echo url('settings/update_pagibig'); ?>">    
    <input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt(($d ? $d->getId() : '')); ?>" />  
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td colspan="2" class="field_label"><small><b>Salary To: </b> 0 = Over </small></td>

        </tr>
        <tr>
            <td class="field_label">Salary From:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getSalaryFrom() : ''); ?>" name="salary_from" class="validate[required,custom[number]]  text" id="salary_from" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Salary To:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getSalaryTo() : ''); ?>" name="salary_to" class="validate[required,custom[number]]  text" id="salary_to" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Multiplier Employee:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getMultiplierEmployee() : ''); ?>" name="multiplier_employee" class="validate[required,custom[number]]  text" id="multiplier_employee" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Multiplier Employer:</td>
            <td>
                <input type="text" value="<?php echo($d ? $d->getMultiplierEmployer() : ''); ?>" name="multiplier_employer" class="validate[required,custom[number]]  text" id="multiplier_employer" />    
            </td>
        </tr>
       </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editPagibig');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>