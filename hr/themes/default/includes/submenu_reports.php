<div id="sidebar" class="sidebar_left">
	<!-- <h2>Reports1</h2> -->
    <ul class="ulmenu">        
        <?php if($sub_menu_recruitment) { ?>
		<li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Recruitment <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
             <li id="applicant_list_nav" class="left_nav"><a href="#applicant_list" onClick="javascript:hashClick('#applicant_list');">Applicant List</a></li>
             <li id="applicant_by_schedule_nav" class="left_nav"><a href="#applicant_by_schedule" onClick="javascript:hashClick('#applicant_by_schedule');">Applicant By Schedule</a></li>
           	 <!--<li id="applicants_education_training_nav" class="left_nav"><a href="#applicants_education_training" onClick="javascript:hashClick('#applicants_education_training');">Education and Training</a></li>-->
            <!-- <li id="applications_received_nav" class="left_nav"><a href="#applications_received" onClick="javascript:hashClick('#applications_received');">Applications Received </a></li>-->
            <li id="applicants_statistics_nav" class="left_nav"><a href="#applicants_statistics" onClick="javascript:hashClick('#applicants_statistics');">Applicant Statistics</a></li>
             <!--<li id="planned_activities_nav" class="left_nav"><a href="#planned_activities" onClick="javascript:hashClick('#planned_activities');">Planned Activities</a></li>-->
            <li id="pending_applicants_nav" class="left_nav"><a href="#pending_applicants" onClick="javascript:hashClick('#pending_applicants');">Pending Applicants</a></li>
            <li id="job_advertisements_nav" class="left_nav"><a href="#job_advertisements" onClick="javascript:hashClick('#job_advertisements');">Job Advertisements</a></li>

            </ul>
        </li>	
		<?php } ?>
        
         <?php if($sub_menu_personnel_administration) { ?>
      <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Personnel Administration<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
         <ul id="personal_information_submenu" class="ulsubmenu">
         <!--  <li id="task_overview_nav" class="left_nav"><a href="#task_overview" onClick="javascript:hashClick('#task_overview');">Task Overview</a></li>
           <li id="anniversaries_nav" class="left_nav"><a href="#anniversaries" onClick="javascript:hashClick('#anniversaries');">Anniversaries</a></li>
           <li id="power_of_attorney_nav" class="left_nav"><a href="#power_of_attorney" onClick="javascript:hashClick('#power_of_attorney');">Power of Attorney</a></li>
           <li id="education_nav" class="left_nav"><a href="#education" onClick="javascript:hashClick('#education');">Education</a></li>
           <li id="employee_entered_left_nav" class="left_nav"><a href="#employee_entered_left" onClick="javascript:hashClick('#employee_entered_left');">Employees who have Entered/Left the Company</a></li>
           <li id="family_members_nav" class="left_nav"><a href="#family_members" onClick="javascript:hashClick('#family_members');">Family Members</a></li>-->
           <li id="birthday_list_nav" class="left_nav"><a href="#birthday_list" onClick="javascript:hashClick('#birthday_list');">Birthday List</a></li>
           <!--<li id="vehicle_list_nav" class="left_nav"><a href="#vehicle_list" onClick="javascript:hashClick('#vehicle_list');">Vehicle Search List</a></li>-->
         <li id="telephone_directory_nav" class="left_nav"><a href="#telephone_directory" onClick="javascript:hashClick('#telephone_directory');">Telephone Directory</a></li>
           <!--  <li id="time_spend_pay_scale_nav" class="left_nav"><a href="#time_spend_pay_scale" onClick="javascript:hashClick('#time_spend_pay_scale');">Time Spent in Pay Scale</a></li>
           <li id="hr_master_data_sheet_nav" class="left_nav"><a href="#hr_master_data_sheet" onClick="javascript:hashClick('#hr_master_data_sheet');">HR Master Data Sheet</a></li>
           <li id="flexible_employee_data_nav" class="left_nav"><a href="#flexible_employee_data" onClick="javascript:hashClick('#flexible_employee_data');">Flexible Employee Data</a></li>-->
           <li id="list_of_employees_nav" class="left_nav"><a href="#list_of_employees" onClick="javascript:hashClick('#list_of_employees');">List of Employees</a></li>
           <li id="leave_overview_nav" class="left_nav"><a href="#leave_overview" onClick="javascript:hashClick('#leave_overview');">Leave Overview</a></li>
          <!-- <li id="headcount_development_nav" class="left_nav"><a href="#headcount_development" onClick="javascript:hashClick('#headcount_development');">Headcount Development</a></li>-->
          <!-- <li id="nationalities_nav" class="left_nav"><a href="#nationalities" onClick="javascript:hashClick('#nationalities');">Nationalities</a></li>-->
           <li id="salary_list_nav" class="left_nav"><a href="#salary_list" onClick="javascript:hashClick('#salary_list');">List of Salaries According to Seniority</a></li>
 		<!--   <li id="certificate_of_employment_nav" class="left_nav"><a href="#certificate_of_employment" onClick="javascript:hashClick('#certificate_of_employment');">Certificate Of Employment</a></li>      --> 
         </ul>
        </li>
        <?php } ?>
         <?php if($sub_menu_personnel_development) { ?>
         <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Personnel Development <!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
            <li id="profile_matchup_nav" class="left_nav"><a href="#profile_matchup" onClick="javascript:hashClick('#profile_matchup');">Profile Matchup</a></li>
            <li id="profile_evaluation_nav" class="left_nav"><a href="#profile_evaluation" onClick="javascript:hashClick('#profile_evaluation');">Profile Evaluation</a></li>
			<li id="qualification_nav" class="left_nav"><a href="#qualification" onClick="javascript:hashClick('#qualification');">Qualification</a></li>
            <li id="development_plan_nav" class="left_nav"><a href="#development_plan" onClick="javascript:hashClick('#development_plan');">Development Plan</a></li>
            <li id="development_item_nav" class="left_nav"><a href="#development_item" onClick="javascript:hashClick('#development_item');">Development Item</a></li>
            <li id="appraisal_evaluation_nav" class="left_nav"><a href="#appraisal_evaluation" onClick="javascript:hashClick('#appraisal_evaluation');">Appraisal Evaluation</a></li>
            <li id="qualification_template_nav" class="left_nav"><a href="#qualification_template" onClick="javascript:hashClick('#qualification_template');">Qualification Template</a></li>
            <li id="development_plan_template_nav" class="left_nav"><a href="#development_plan_template" onClick="javascript:hashClick('#development_plan_template');">Development Plan Template</a></li>
            <li id="appraisal_template_nav" class="left_nav"><a href="#appraisal_template" onClick="javascript:hashClick('#appraisal_template');">Appraisal Template</a></li>
            <li id="careers_nav" class="left_nav"><a href="#careers" onClick="javascript:hashClick('#careers');">Careers</a></li>
            <li id="vacant_obselete_position_nav" class="left_nav"><a href="#vacant_obselete_position" onClick="javascript:hashClick('#vacant_obselete_position');">Vacant / Obselete Position</a></li>
            <li id="qualification_overview_nav" class="left_nav"><a href="#qualification_overview" onClick="javascript:hashClick('#qualification_overview');">Qualification Overview for Department</a></li>

           
            </ul>
        </li>
        <?php } ?>
         <?php if($sub_menu_benefits) { ?>
         <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Benefits Report<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
            <li id="eligible_employee_nav" class="left_nav"><a href="#eligible_employee" onClick="javascript:hashClick('#eligible_employee');">Eligible Employee</a></li>
           	<li id="participation_nav" class="left_nav"><a href="#participation" onClick="javascript:hashClick('#participation');"> Participation</a></li>
            <li id="emergency_contacts_nav" class="left_nav"></li>
           
            </ul>
        </li>
        <?php } ?>
         <?php if($sub_menu_compensation_management) { ?>
         <li class="selected">
        	<div class="tabtitle">
            	<a id="personal_information_tab"  style="cursor:pointer"  >Compensation Management<!--<img id="personal_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="personal_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
            </div>
            <ul id="personal_information_submenu" class="ulsubmenu">
            <li id="total_compensation_statement_nav" class="left_nav"><a href="#total_compensation_statement" onClick="javascript:hashClick('#total_compensation_statement');">Total Compensation Statement</a></li>
            <li id="job_salary_rate_nav" class="left_nav"><a href="#job_salary_rate" onClick="javascript:hashClick('#job_salary_rate');">Job Salary Rate</a></li>
			<li id="plan_labor_cost_nav" class="left_nav"><a href="#plan_labor_cost" onClick="javascript:hashClick('#plan_labor_cost');">Plan Labor Costs</a></li>
            </ul>
        </li>
        <?php } ?>
         <?php if($sub_menu_time_management) { ?>
        <li class="selected">
            <?php if(!empty($btn_absences) || !empty($btn_tardiness) || !empty($btn_overtime) || !empty($btn_undertime) || !empty($btn_leave) || !empty($btn_manpower_count) || !empty($btn_end_of_contract) || !empty($btn_daily_time_record) || !empty($btn_incomplete_time_in_out) || !empty($btn_timesheet) || !empty($btn_employment_status) || !empty($btn_ee_er_contribution) ) { ?>
            	<div class="tabtitle">
                	<a id="employment_information_tab"  style="cursor:pointer">HR Reports&nbsp;<!-- Time Management --><!-- <img id="employment_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="employment_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" />--></a>
                </div>
                <ul id="employment_information_submenu" class="ulsubmenu">
             		<!--<li id="personal_work_schedule_nav" class="left_nav"><a href="#personal_work_schedule" onClick="javascript:hashClick('#personal_work_schedule');">Personal Work Schedule</a></li>
                	<li id="daily_work_schedule_nav" class="left_nav"><a href="#daily_work_schedule" onClick="javascript:hashClick('#daily_work_schedule');">Daily Work Schedule</a></li>-->
               		<?php echo $btn_absences; ?>
                    <?php echo $btn_tardiness; ?>                    
                    <?php echo $btn_undertime; ?>
                    <?php echo $btn_overtime; ?>
                    <?php echo $btn_leave; ?>
                    <!-- ?php echo $btn_incentive_leave; ?> -->
                    <?php echo $btn_leave_balance_config; ?>
                    <?php echo $btn_manpower_count; ?>
                    <?php echo $btn_terminated; ?>
                    <?php echo $btn_resigned; ?>       
                   <!--  <?php echo $btn_final_pay_config; ?>   -->
                   <!--  <?php echo $btn_birthday_config ?>    -->
                    <?php echo $btn_shift_schedule_config; ?>        
                    <?php echo $btn_end_of_contract; ?>                    
                    <?php echo $btn_daily_time_record; ?>
                    <?php echo $btn_incomplete_time_in_out; ?>
                    <?php echo $btn_incorrect_shift; ?>
                    <?php echo $btn_timesheet; ?>
                    <?php echo $btn_disciplinary_action; ?>
                    <?php echo $btn_loans; ?>
                    <?php echo $btn_employment_status; ?>
                    <?php echo $btn_employee_details; ?>
                    <!-- <?php echo $btn_ee_er_contribution; ?>                -->      
                    
                    

                    <?php 
                        if(PERFECT_ATTENDANCE_REPORT_ENABLED)
                        {
                            echo $btn_perfect_attendance; 
                        }
                    ?>         
                    <?php echo $btn_coe; ?> 
                  <!--   <?php echo $btn_actual_hours; ?> -->
                <!--     <?php echo $btn_required_shift; ?>  -->  
                   <!--  <?php echo $btn_government_remittances; ?>    -->  
                    <!-- <?php echo $btn_last_pay; ?>  -->      
                    <!-- <li id="attendance_absence_data_nav" class="left_nav"><a href="#attendance_absence_data" onClick="javascript:hashClick('#attendance_absence_data');">Absences</a></li>
               		<li id="display_absence_quota_information_nav" class="left_nav"><a href="#display_absence_quota_information" onClick="javascript:hashClick('#display_absence_quota_information');">Tardiness</a></li>
                    <li id="display_overtime_nav" class="left_nav"><a href="#display_overtime" onClick="javascript:hashClick('#display_overtime');">Overtime</a></li>
                    <li id="display_undertime_nav" class="left_nav"><a href="#display_undertime" onClick="javascript:hashClick('#display_undertime');">Undertime</a></li>
                    <li id="display_leave_nav" class="left_nav"><a href="#display_leave" onClick="javascript:hashClick('#display_leave');">Leave</a></li>
                    <li id="display_manpower_count_nav" class="left_nav"><a href="#display_manpower_count" onClick="javascript:hashClick('#display_manpower_count');">Manpower Count</a></li>
                    <li id="display_end_of_contract_nav" class="left_nav"><a href="#display_end_of_contract" onClick="javascript:hashClick('#display_end_of_contract');">End of Contract</a></li>
                    <li id="display_daily_time_record_nav" class="left_nav"><a href="#display_daily_time_record" onClick="javascript:hashClick('#display_daily_time_record');">Daily Time Record</a></li>
                    <li id="display_incomplete_time_in_out_nav" class="left_nav"><a href="#display_incomplete_time_in_out" onClick="javascript:hashClick('#display_incomplete_time_in_out');">Incomplete Time In / Out</a></li>
                    <li id="display_timesheet_nav" class="left_nav"><a href="#display_timesheet" onClick="javascript:hashClick('#display_timesheet');">Timesheet</a></li>
                    <li id="display_employment_status_nav" class="left_nav"><a href="#display_employment_status" onClick="javascript:hashClick('#display_employment_status');">Employment Status</a></li>-->
                    <li id="display_ee_er_contribution_nav" class="left_nav"><a href="#display_ee_er_contribution" onClick="javascript:hashClick('#display_ee_er_contribution');">EE / ER Contribution</a></li> 
                </ul>

                <div class="tabtitle" style="margin-top:20px;">
                    <a id="employment_information_tab"  style="cursor:pointer; ">General Reports</a>
                </div>

                <ul id="employment_information_submenu" class="ulsubmenu">
                    
                     <li id="audit_trail_data_nav" class="left_nav"><a href="#audit_trail_data" onClick="javascript:hashClick('#audit_trail_data');">Audit Trail</a></li> 
                    <?php //echo $btn_audit_trail; ?>

                </ul>
                
            <?php } ?>
        </li>
        <?php } ?>
         <?php if($sub_menu_payroll_management) { ?>
        <li class="selected">
        	<div class="tabtitle">
            	<a id="employment_information_tab"  style="cursor:pointer">Payroll Management<img id="employment_information_min_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /><img id="employment_information_max_button" class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_left_icon.png" style="display:none" /></a>
            </div>
            <ul id="employment_information_submenu" class="ulsubmenu">
                 <li id="sss_r1a_nav" class="left_nav"><a href="#sss_r1a" onClick="javascript:hashClick('#sss_r1a');">SSS R-1A</a></li>
                <li id="philhealth_nav" class="left_nav"><a href="#philhealth" onClick="javascript:hashClick('#philhealth');">PhilHealth</a></li>
                <li id="pagibig_nav" class="left_nav"><a href="#pagibig" onClick="javascript:hashClick('#pagibig');">Pagibig</a></li>
                <li id="contribution_nav" class="left_nav"><a href="#contribution" onClick="javascript:hashClick('#contribution');">Contribution</a></li>
                <li id="payslip_nav" class="left_nav"><a href="#payslip" onClick="javascript:hashClick('#payslip');">Payslip</a></li>
                <li id="payroll_register_nav" class="left_nav"><a href="#payroll_register" onclick="javascript:hashClick('#payroll_register');">Payroll Register</a></li>
            </ul>
        </li>     
       <?php } ?>
    </ul>
</div>