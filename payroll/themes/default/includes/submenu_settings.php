<div id="sidebar" class="sidebar_left">
    <ul class="ulmenu">
        <!--<h2> Settings</h2>-->
        <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Settings </a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
                <li id="personal_details_nav" class="left_nav"><a href="<?php echo url('settings/company');  ?>" onclick="javascript:hashClick('#personal_details');">Company Structure</a></li>
                <li id="contact_details_nav" class="left_nav"><a href="<?php echo url('settings/branch');  ?>" onclick="javascript:hashClick('#contact_details');">Branch</a></li>
                <li id="emergency_contacts_nav" class="left_nav"><a href="<?php echo url('settings/job');  ?>" onclick="javascript:hashClick('#emergency_contacts');">Job</a></li>
                <li class="left_nav"><a href="<?php echo url('holiday');  ?>">Holiday</a></li>
                <li id="dependents_nav" class="left_nav"><a href="<?php echo url('settings/user_management');  ?>" onclick="javascript:hashClick('#dependents');">User Management</a></li>
                <li id="dependents_nav" class="left_nav"><a href="<?php echo url('settings/employee_group_management');  ?>">Employee Group Management</a></li>
                <li id="bank_nav" class="left_nav"><a href="<?php echo url('settings/contribution');  ?>" onclick="javascript:hashClick('#bank');">Contribution</a></li>
                <li id="bank_nav" class="left_nav"><a href="<?php echo url('settings/examination_template');  ?>" onclick="javascript:hashClick('#bank');">Examination Template</a></li>
                <li id="bank_nav" class="left_nav"><a href="<?php echo url('settings/performance_template');  ?>" onclick="javascript:hashClick('#bank');">Performance Template</a></li>
                <!-- <li id="medical_history_nav" class="left_nav"><a href="#medical_history" onclick="javascript:hashClick('#medical_history');">Medical History</a></li>-->
            </ul>
        </li>
        <li class="selected">
        	<div class="tabtitle">
            	<a id="employment_information_tab"  style="cursor:pointer">Options </a>
            </div>
            <ul id="employment_information_submenu" class="ulsubmenu">
                <li id="employment_status_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=1');  ?>" onclick="javascript:hashClick('#employment_status');">Subdivision Type</a></li>
                <li id="compensation_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=2');  ?>" onclick="javascript:hashClick('#compensation');">Dependent Relationship</a></li>
                <li id="performance_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=3');  ?>" onclick="javascript:hashClick('#performance');">Pay Period</a></li>                
                <li id="training_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=4');  ?>" onclick="javascript:hashClick('#training');">Skill Management</a></li>
                <li id="memo_notes_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=5');  ?>" onclick="javascript:hashClick('#memo_notes');">License</a></li>
                <li id="supervisor_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=6');  ?>" onclick="javascript:hashClick('#supervisor');">Location</a></li>
                <li id="leave_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=7');  ?>" onclick="javascript:hashClick('#leave');">Membership Type</a></li>
                <li id="leave_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=8');  ?>" onclick="javascript:hashClick('#leave');">Employment Status</a></li>
                 <li id="performance_nav" class="left_nav"><a href="<?php echo url('settings/options?sidebar=10');  ?>" onclick="javascript:hashClick('#performance');">Request Approvers</a></li>
              <!--<li id="membership_nav" class="left_nav"><a href="#membership" onclick="javascript:hashClick('#membership');">Membership</a></li>-->
            </ul>
        </li>
        
       
    </ul>
</div>