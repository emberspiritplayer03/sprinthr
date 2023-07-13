<?php $cat = 'sprint_hr[sub_module_access]['.HR.']'; ?>

<a href="javascript:void(0);" onclick="javascript:toggleList('#hr_dashboard_submodule_list');">Dashboard Module</a>
 <div id="hr_dashboard_submodule_list" class="hr_submodule_list">
   <table width="100%" border="0" cellspacing="1" cellpadding="2">
   <tr>
       <td style="width:25%" align="left" valign="middle"><strong class="red">Main Settings:</strong></td>
       <td style="width:75%" align="left" valign="middle">
       	<select id="hr_dashboard_main_settings" name="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][main_settings]" style="width:150px;" onchange="javascript:follow_access_rights_main_settings('#hr_dashboard_main_settings','.hr_dashboard_sub_module');">
        	<option <?php echo $selected = ($rights['hr_dashboard_main_settings'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_main_settings'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_main_settings'] == G_Access_Rights::CUSTOM ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CUSTOM;  ?>">Custom</option>
        </select>
       </td>
   </tr>
   <tr>
       <td style="width:25%" align="left" valign="middle">General Information:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_dashboard_sub_module" id="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][general_information]" name="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][general_information]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_dashboard_main_settings');">
        	<option <?php echo $selected = ($rights['hr_dashboard_gen_info'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_gen_info'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_gen_info'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
   <tr>
       <td style="width:25%" align="left" valign="middle">Recruitment:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_dashboard_sub_module" id="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][recruitment]" name="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][recruitment]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_dashboard_main_settings');">
        	<option <?php echo $selected = ($rights['hr_dashboard_recruitment'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_recruitment'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_recruitment'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
   <tr>
       <td style="width:25%" align="left" valign="middle">Employee:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_dashboard_sub_module" id="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][employee]" name="<?php echo $cat; ?>[<?php echo DASHBOARD; ?>][employee]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_dashboard_main_settings');">
        	<option <?php echo $selected = ($rights['hr_dashboard_employee'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_employee'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_dashboard_employee'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
   </table>
   </div>
   
   <br />