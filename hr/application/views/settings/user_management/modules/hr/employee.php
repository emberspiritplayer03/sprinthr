<?php $cat = 'sprint_hr[sub_module_access]['.HR.']'; ?>

<a href="javascript:void(0);" onclick="javascript:toggleList('#hr_employee_submodule_list');">Employee Module</a>
<div id="hr_employee_submodule_list" class="hr_submodule_list">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
<tr>
   <td style="width:25%" align="left" valign="middle"><strong class="red">Main Settings:</strong></td>
   <td style="width:75%" align="left" valign="middle">
    <select id="hr_employee_main_settings" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][main_settings]" style="width:150px;" onchange="javascript:follow_access_rights_main_settings('#hr_employee_main_settings','.hr_employee_sub_module');">
        <option <?php echo $selected = ($rights['hr_employee_main_settings'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_main_settings'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_main_settings'] == G_Access_Rights::CUSTOM ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CUSTOM;  ?>">Custom</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Employee Management:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][employee_management]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][employee_management]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_employee_management'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_employee_management'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_employee_management'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Account Management:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][account_management]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][account_management]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_account_management'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_account_management'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_account_management'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Deduction Management:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][deduction_management]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][deduction_management]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_deduction_management'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_deduction_management'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_deduction_management'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Schedule:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][schedule]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][schedule]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_schedule'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_schedule'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_schedule'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Leave:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][leave]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][leave]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_leave'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_leave'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_leave'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Overtime:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][overtime]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][overtime]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_overtime'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_overtime'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_overtime'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Attendance:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][attendance]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][attendance]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_attendance'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_attendance'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_attendance'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
<tr>
   <td style="width:25%" align="left" valign="middle">Performance:</td>
   <td style="width:75%" align="left" valign="middle">
    <select class="hr_employee_sub_module" id="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][performance]" name="<?php echo $cat; ?>[<?php echo EMPLOYEE_MODULE; ?>][performance]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_employee_main_settings');">
        <option <?php echo $selected = ($rights['hr_employee_performance'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
        <option <?php echo $selected = ($rights['hr_employee_performance'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
        <option <?php echo $selected = ($rights['hr_employee_performance'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
    </select>
   </td>
</tr>
</table>
</div>