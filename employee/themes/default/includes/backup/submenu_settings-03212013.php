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
                <li id="contact_details_nav" class="left_nav <?php echo $branch_sb; ?>"><a href="<?php echo url('settings/branch');  ?>" onclick="javascript:hashClick('#contact_details');">Branch</a></li>
                <li id="emergency_contacts_nav" class="left_nav <?php echo $job_sb; ?>"><a href="<?php echo url('settings/job');  ?>" onclick="javascript:hashClick('#emergency_contacts');">Job</a></li>
			
					 <?php if($hr_mod['holiday'] == true){ ?>                           
                	<li class="left_nav <?php echo $holiday_sb; ?> <?php echo ($hr_mod['holiday'] != true ? 'hide-module' : ''); ?>"><a href="<?php echo url('holiday');  ?>">Holiday</a></li>
                <?php } ?>
					
					 <?php if($attendance_mod['payroll'] == true || $attendance_mod['dtr'] == true){ ?>
                	<li id="personal_details_nav" class="left_nav <?php echo $payroll_period; ?>"><a href="<?php echo url('settings/payroll_period');  ?>" onclick="javascript:hashClick('#personal_details');">Payroll Period</a></li>
                <?php } ?>
                
                <!--<li id="dependents_nav"class="left_nav <?php echo $user_management_sb; ?>"><a href="<?php echo url('settings/user_management');  ?>" onclick="javascript:hashClick('#dependents');">User Management</a></li>-->
                
                <?php if($attendance_mod['dtr'] == true){ ?>
                  <!--<li id="personal_details_nav" class="left_nav <?php echo $grace_period_sb; ?>"><a href="<?php echo url('settings/grace_period');  ?>" onclick="javascript:hashClick('#personal_details');">Grace Period</a></li>-->
                <?php } ?>
                
                <?php if($hr_mod['employee'] == true){ ?>           
                   <li id="dependents_nav"class="left_nav <?php echo $employee_group_mgt_sb; ?>"><a href="<?php echo url('settings/employee_group_management');  ?>">Employee Group Management</a></li>
                <?php } ?>
                
                <?php if($attendance_mod['payroll'] == true){ ?>
                	<li id="bank_nav"class="left_nav <?php echo $contribution_sb; ?>"><a href="<?php echo url('settings/contribution');  ?>" onclick="javascript:hashClick('#bank');">Contribution</a></li>
                <?php } ?>
                
					<?php if($hr_mod['recruitment'] == true){ ?>                       
                <li id="bank_nav"class="left_nav <?php echo $examination_template_sb; ?>"><a href="<?php echo url('settings/examination_template');  ?>" onclick="javascript:hashClick('#bank');">Examination Template</a></li>
               <?php } ?>
               
               <?php if($hr_mod['employee'] == true){ ?>           
                <li id="bank_nav"class="left_nav <?php echo $performance_template_sb; ?>"><a href="<?php echo url('settings/performance_template');  ?>" onclick="javascript:hashClick('#bank');">Performance Template</a></li>
               <?php } ?>
                <!-- <li id="medical_history_nav"class="left_nav <?php echo $medical_history_sb; ?>"><a href="#medical_history" onclick="javascript:hashClick('#medical_history');">Medical History</a></li>-->
            </ul>
        </li>
        <li class="selected">
        	<div class="tabtitle">
            	<a id="employment_information_tab"  style="cursor:pointer">Options </a>
            </div>
            <ul id="employment_information_submenu" class="ulsubmenu">            	
            	 <?php if($attendance_mod['leave_request'] == true){ ?>
            	 	<li id="leave_type_nav" class="left_nav <?php echo $leave_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=12');  ?>" onclick="javascript:hashClick('#employment_status');">Leave Type</a></li>
            	 <?php } ?>
            
            	 <?php if($attendance_mod['payroll'] == true){ ?>
                	<li id="deduction_type_nav" class="left_nav <?php echo $deduction_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=13');  ?>" onclick="javascript:hashClick('#employment_status');">Deduction Type</a></li>
            	 <?php } ?>

					 <?php if($hr_mod['employee'] == true){ ?>                        
                	<li id="employment_status_nav"class="left_nav <?php echo $subdivision_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=1');  ?>" onclick="javascript:hashClick('#employment_status');">Subdivision Type</a></li>
                <?php } ?>

                <li id="compensation_nav"class="left_nav <?php echo $dependent_relation_sb; ?>"><a href="<?php echo url('settings/options?sidebar=2');  ?>" onclick="javascript:hashClick('#compensation');">Dependent Relationship</a></li>
	
					 <?php if($attendance_mod['payroll'] == true || $attendance_hr['employee'] == true){ ?>                
                	<li id="performance_nav"class="left_nav <?php echo $payperiod_sb; ?>"><a href="<?php echo url('settings/options?sidebar=3');  ?>" onclick="javascript:hashClick('#performance');">Pay Period</a></li> 
					 <?php } ?>                
                
                <?php if($attendance_mod['payroll'] == true){ ?> 
                  <li id="performance_nav" class="left_nav <?php echo $deduction_breakdown_sb; ?>"><a href="<?php echo url('settings/options?sidebar=11');  ?>" onclick="javascript:hashClick('#deduction_breakdown');">Deduction Breakdown</a></li>
                <?php } ?>   
                           
                <li id="training_nav"class="left_nav <?php echo $skill_mgt_sb; ?>"><a href="<?php echo url('settings/options?sidebar=4');  ?>" onclick="javascript:hashClick('#training');">Skill Management</a></li>
                <li id="memo_notes_nav"class="left_nav <?php echo $license_sb; ?>"><a href="<?php echo url('settings/options?sidebar=5');  ?>" onclick="javascript:hashClick('#memo_notes');">License</a></li>
                <li id="supervisor_nav"class="left_nav <?php echo $location_sb; ?>"><a href="<?php echo url('settings/options?sidebar=6');  ?>" onclick="javascript:hashClick('#supervisor');">Location</a></li>
                <li id="leave_nav"class="left_nav <?php echo $membership_type_sb; ?>"><a href="<?php echo url('settings/options?sidebar=7');  ?>" onclick="javascript:hashClick('#leave');">Membership Type</a></li>
                <li id="leave_nav"class="left_nav <?php echo $employment_status_sb; ?>"><a href="<?php echo url('settings/options?sidebar=8');  ?>" onclick="javascript:hashClick('#leave');">Employment Status</a></li>
                 <!--<li id="performance_nav"class="left_nav <?php //echo $request_approvers_sb; ?>"><a href="<?php //echo url('settings/options?sidebar=10');  ?>" onclick="javascript:hashClick('#performance');">Request Approvers</a></li>-->
              <!--<li id="membership_nav"class="left_nav <?php echo $membership_sb; ?>"><a href="#membership" onclick="javascript:hashClick('#membership');">Membership</a></li>-->
            </ul>
        </li>
        
       
    </ul>
</div>