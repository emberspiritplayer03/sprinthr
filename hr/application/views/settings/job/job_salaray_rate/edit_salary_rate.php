<div id="form_main" class="inner_form popup_form wider">
    <form name="editJobRate" id="editJobRate" method="post" action="<?php echo url('settings/update_job_salary_rate'); ?>">
    <input type="hidden" name="id" id="id" value="<?php echo $job_salary_rate->getId(); ?>" />    
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td valign="top" class="field_label">Job Level:</td>
        <td valign="top">        
        <select name="job_level" id="job_level" style="width:78%;">
        	<?php foreach($eeo as $e){ ?>
            <option <?php echo($job_salary_rate->getJobLevel() == $e->getCategoryName() ? 'selected="selected"' : ''); ?> value="<?php echo $e->getCategoryName(); ?>"><?php echo $e->getCategoryName(); ?></option>
            <?php } ?>
        </select>
        </td>
    </tr>   
    <tr>
        <td valign="top" class="field_label">Minimum Salary:</td>
        <td valign="top">
        <input type="text" value="<?php echo $job_salary_rate->getMinimumSalary(); ?>" name="minimum_salary" class="validate[required] text" id="minimum_salary" />
        </td>
    </tr>
    
    <tr>
        <td valign="top" class="field_label">Maximum Salary:</td>
        <td valign="top">
        <input type="text" value="<?php echo $job_salary_rate->getMaximumSalary(); ?>" name="maximum_salary" class="validate[required] text" id="maximum_salary" />
        </td>
    </tr>
    
    <tr>
        <td valign="top" class="field_label">Step Salary:</td>
        <td valign="top">
        <input type="text" value="<?php echo $job_salary_rate->getStepSalary(); ?>" name="step_salary" class="validate[required] text" id="step_salary" />
        </td>
    </tr>
    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addJobRate');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>