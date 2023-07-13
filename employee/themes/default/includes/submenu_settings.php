<?php
	$hr_mod 		= $GLOBALS['module_package']['hr'];
	$attendance_mod = $GLOBALS['module_package']['attendance'];
?>
<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Settings </a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav <?php echo $company_structure_sb; ?>"><a href="<?php echo url('settings/company');  ?>" onclick="javascript:hashClick('#personal_details');">Company Structure</a></li>
                <li id="emergency_contacts_nav" class="left_nav <?php echo $job_sb; ?>"><a href="<?php echo url('settings/job');  ?>" onclick="javascript:hashClick('#emergency_contacts');">Job</a></li>				
                <li class="left_nav <?php echo $holiday_sb; ?> <?php echo ($hr_mod['holiday'] != true ? 'hide-module' : ''); ?>"><a href="<?php echo url('holiday');  ?>">Holiday</a></li>				
                <li id="dependents_nav"class="left_nav <?php echo $user_management_sb; ?>"><a href="<?php echo url('settings/user_management');  ?>" onclick="javascript:hashClick('#dependents');">User Management</a></li>
                <li id="personal_details_nav" class="left_nav <?php echo $payroll_settings; ?>"><a href="<?php echo url('settings/payroll_settings');  ?>" onclick="javascript:hashClick('#personal_details');">Payroll Settings</a></li>
                <li id="personal_details_nav" class="left_nav <?php echo $grace_period_sb; ?>"><a href="<?php echo url('settings/grace_period');  ?>" onclick="javascript:hashClick('#personal_details');">Grace Period</a></li>
                <li id="dependents_nav"class="left_nav <?php echo $employee_group_mgt_sb; ?>"><a href="<?php echo url('settings/employee_group_management');  ?>">Department and Groups</a></li>
                <li id="bank_nav"class="left_nav <?php echo $benefits_sb; ?>"><a href="<?php echo url('settings/benefits');  ?>">Benefits</a></li>
                <li id="bank_nav"class="left_nav <?php echo $contribution_sb; ?>"><a href="<?php echo url('settings/contribution');  ?>" onclick="javascript:hashClick('#bank');">Contribution and Tax</a></li>                
               <li id="bank_nav"class="left_nav <?php echo $default_requirements; ?>"><a href="<?php echo url('settings/requirements');  ?>" onclick="javascript:hashClick('#bank');">Default Requirements</a></li>
                <li id="bank_nav"class="left_nav <?php echo $memo_template_sb; ?>"><a href="<?php echo url('settings/memo_template');  ?>" onclick="javascript:hashClick('#bank');">Memo Template</a></li>                          
            </ul>
        </li>
        <li class="selected">
        	<div class="tabtitle">
            	<a id="employment_information_tab"  style="cursor:pointer">Options </a>
            </div>
            <ul id="employment_information_submenu" class="ulsubmenu">            	            	 
            	<li id="leave_type_nav" class="left_nav <?php echo $leave_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=12');  ?>" onclick="javascript:hashClick('#employment_status');">Leave Type</a></li>
                <li id="compensation_nav"class="left_nav <?php echo $dependent_relation_sb; ?>"><a href="<?php echo url('settings/options?sidebar=2');  ?>" onclick="javascript:hashClick('#compensation');">Dependent Relationship</a></li>					 
                <li id="performance_nav"class="left_nav <?php echo $payperiod_sb; ?>"><a href="<?php echo url('settings/options?sidebar=3');  ?>" onclick="javascript:hashClick('#performance');">Pay Period</a></li>
                <li id="performance_nav" class="left_nav <?php echo $deduction_breakdown_sb; ?>"><a href="<?php echo url('settings/options?sidebar=11');  ?>" onclick="javascript:hashClick('#deduction_breakdown');">Deduction Breakdown</a></li>
                <li id="training_nav"class="left_nav <?php echo $skill_mgt_sb; ?>"><a href="<?php echo url('settings/options?sidebar=4');  ?>" onclick="javascript:hashClick('#training');">Skill Management</a></li>
                <li id="memo_notes_nav"class="left_nav <?php echo $license_sb; ?>"><a href="<?php echo url('settings/options?sidebar=5');  ?>" onclick="javascript:hashClick('#memo_notes');">License</a></li>
                <li id="supervisor_nav"class="left_nav <?php echo $location_sb; ?>"><a href="<?php echo url('settings/options?sidebar=6');  ?>" onclick="javascript:hashClick('#supervisor');">Location</a></li>
                <li id="leave_nav"class="left_nav <?php echo $membership_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=7');  ?>" onclick="javascript:hashClick('#leave');">Membership Type</a></li>
                <li id="leave_nav"class="left_nav <?php echo $employment_status_sb; ?>"><a href="<?php echo url('settings/options?sidebar=8');  ?>" onclick="javascript:hashClick('#leave');">Employment Status</a></li>
                <li id="leave_nav"class="left_nav <?php echo $employee_status_sb; ?>"><a href="<?php echo url('settings/options?sidebar=14');  ?>" onclick="javascript:hashClick('#leave');">Employee Status</a></li>
            </ul>
        </li>
        
       
    </ul>
</div>