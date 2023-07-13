<?php $cat = 'sprint_hr[sub_module_access]['.HR.']'; ?>

<a href="javascript:void(0);" onclick="javascript:toggleList('#hr_recruitment_submodule_list');">Recruitment Module</a>

   <div id="hr_recruitment_submodule_list" class="hr_submodule_list">
   <table width="100%" border="0" cellspacing="1" cellpadding="2">
   <tr>
       <td style="width:25%" align="left" valign="middle"><strong class="red">Main Settings:</strong></td>
       <td style="width:75%" align="left" valign="middle">
       	<select id="hr_recruitment_main_settings" name="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][main_settings]" style="width:150px;" onchange="javascript:follow_access_rights_main_settings('#hr_recruitment_main_settings','.hr_recruitment_sub_module');">
        	<option <?php echo $selected = ($rights['hr_recruitment_main_settings'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_main_settings'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_main_settings'] == G_Access_Rights::CUSTOM ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CUSTOM;  ?>">Custom</option>
        </select>
       </td>
   </tr>
   <tr>
       <td style="width:25%" align="left" valign="middle">Candidate:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_recruitment_sub_module" id="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][candidate]" name="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][candidate]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_recruitment_main_settings');">
        	<option <?php echo $selected = ($rights['hr_recruitment_candidate'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_candidate'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_candidate'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
   <tr>
       <td style="width:25%" align="left" valign="middle">Job Vacancy:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_recruitment_sub_module" id="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][job_vacancy]" name="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][job_vacancy]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_recruitment_main_settings');">
        	<option <?php echo $selected = ($rights['hr_recruitment_job_vacancy'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_job_vacancy'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_job_vacancy'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
    <tr>
       <td style="width:25%" align="left" valign="middle">Examination:</td>
       <td style="width:75%" align="left" valign="middle">
       	<select class="hr_recruitment_sub_module" id="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][examination]" name="<?php echo $cat; ?>[<?php echo RECRUITMENT; ?>][examination]" style="width:150px;" onchange="javascript:revert_access_rights_main_settings('#hr_recruitment_main_settings');">
        	<option <?php echo $selected = ($rights['hr_recruitment_examination'] == G_Access_Rights::NO_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::NO_ACCESS;  ?>">No Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_examination'] == G_Access_Rights::HAS_ACCESS ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::HAS_ACCESS;  ?>">Has Access</option>
            <option <?php echo $selected = ($rights['hr_recruitment_examination'] == G_Access_Rights::CAN_MANAGE ? 'selected="selected"' : ''); ?> value="<?php echo G_Access_Rights::CAN_MANAGE;  ?>">Can Manage</option>
        </select>
       </td>
   </tr>
   </table>
   </div>
   
   <br />