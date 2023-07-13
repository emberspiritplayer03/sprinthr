<div id="form_main" class="inner_form popup_form wider">
    <form name="addJobRate" id="addJobRate" method="post" action="<?php echo url('settings/add_job_salary_rate'); ?>">
    <!--<h3 class="section_title">Job Salary Rate</h3>-->
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td valign="top" class="field_label">Job Level:</td>
        <td valign="top">        
        <select name="job_level" id="job_level" style="width:78%;">
        	<?php foreach($eeo as $e){ ?>
            <option value="<?php echo $e->getCategoryName(); ?>"><?php echo $e->getCategoryName(); ?></option>
            <?php } ?>
        </select>
        </td>
    </tr>
    <tr>
        <td valign="top" class="field_label">Minimum Salary:</td>
        <td valign="top">
        <input type="text" value="" name="minimum_salary" class="validate[required] text" id="minimum_salary" />
        </td>
    </tr>
    
    <tr>
        <td valign="top" class="field_label">Maximum Salary:</td>
        <td valign="top">
        <input type="text" value="" name="maximum_salary" class="validate[required] text" id="maximum_salary" />
        </td>
    </tr>
    
    <tr>
        <td valign="top" class="field_label">Step Salary:</td>
        <td valign="top">
        <input type="text" value="" name="step_salary" class="validate[required] text" id="step_salary" />
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