<?php
class Settings_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'settings');
		$this->validatePermission(G_Sprint_Modules::HR,'settings','');

		Loader::appMainScript('settings.js');
		Loader::appMainScript('settings_base.js');		
		Loader::appStyle('style.css');		

		Loader::appMainScript('settings_base_extends3.js');	
		Jquery::loadMainJqueryDatatable();
        Model::open('G_Employee_Project_Site_Model');

		$this->eid                  = $this->global_user_eid;				
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);
		
		$this->var['settings'] = 'current';

		$this->validatePermission(G_Sprint_Modules::HR,'settings','');
	}
	
	function index()
	{
		$this->company();
	}
	
	function company()
	{	
		//Jquery::loadMainInlineValidation();	
		//Jquery::loadMainModalExetend();		
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		//Jquery::loadMainTreeView();
		//Jquery::loadAsyncTreeView();
		$this->var['page_title'] 			= 'Settings';
		$this->var['company_structure_sb']	= 'selected';
		$this->var['module_title']			= 'Company Structure';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/company/index.php',$this->var);

	}

	function leave()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();	
		
		Loader::appMainScript('leave_setting.js');
		Loader::appMainScript('leave_setting_base.js');

		$leave 		= G_Leave_Finder::findAll();
		$slv = G_Settings_Leave_Credit_Finder::findAll();

		$slg = G_Settings_Leave_General_Finder::findById(1);

		$this->var['token'] = Utilities::createFormToken();

		$this->var['action_leave_general'] 	= url('settings/_update_leave_general_settings');
		$this->var['leave_general']	    	= $slg;
		$this->var['leave_type']			= $leave; 		
		$this->var['leave_credits']			= $slv;
		$this->var['page_title'] 			= 'Settings';
		$this->var['leave_settings_sb']		= 'selected';
		$this->var['module_title']			= 'Leave Settings';

		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/leave/index.php',$this->var);
	}

	function payslip()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		$payslip_template = G_Payslip_Template_Finder::findAll();
		
		$this->var['token'] = Utilities::createFormToken();

		$this->var['action_payslip']		= url('settings/update_payslip_settings');
		$this->var['payslip_template']		= $payslip_template;
		$this->var['page_title'] 			= 'Settings';		
		$this->var['payslip_settings_sb']	= 'selected';
		$this->var['module_title']			= 'Payslip Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/payslip/index.php',$this->var);		
	}

	function benefits()
	{
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapCollapsible();

		$this->var['module_title'] = 'Benefits';
		$this->var['benefits_sb']  = 'selected';
		$this->var['page_title']   = 'Settings';	
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/benefits/index.php',$this->var);		
	}

	function _load_add_structure()
	{
		if(!empty($_POST['company_structure_id'])){
			$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$cs 		 = G_Company_Structure_Finder::findById($_POST['company_structure_id']);	
			$b		     = G_Company_Branch_Finder::findById($_POST['branch_id']);
			$sst 		 = G_Settings_Subdivision_Type_Finder::findByCompanyStructureId($cstructure->getId());
			$gcb		 = G_Company_Branch_Finder::findByCompanyStructureId($cstructure->getId());
			$this->var['b']		  		   = $b;
			$this->var['main_parent']      = $_SESSION['sprint_hr']['company_structure_id'];
			$this->var['branches'] 		   = $gcb;
			$this->var['subdivision_type'] = $sst;
			$this->var['cs']		       = $cs;
			$this->var['p_id']             = $_POST['company_structure_id'];
			
			//$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_company_structure.php',$this->var);
		}
	}
	
	function _load_add_new_group_team()
	{
		if($_POST['eid']){						
			$this->var['parent_id'] = $_POST['eid'];
			$this->view->render('settings/company/forms/add_group_team.php',$this->var);			
		}
	}

	function _load_add_new_section()
	{
		if($_POST['eid']){						
			$this->var['parent_id'] = $_POST['eid'];
			$this->view->render('settings/company/forms/add_section.php',$this->var);			
		}
	}
	
	function _load_add_new_department()
	{
		if($_POST['eid']){
			$this->var['company_branch_id'] = $_POST['eid'];			
			$this->view->render('settings/company/forms/add_department.php',$this->var);
		}
	}
	
	function _load_add_new_leave_type()
	{
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/leave_type/forms/add_leave_type.php',$this->var);
	}
	
	function _load_add_new_employee_status()
	{
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/employee_status/forms/add_employee_status.php',$this->var);
	}
	
	function _load_add_new_requirement()
	{
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/requirements/forms/add_requirement.php',$this->var);
	}
	
	function _load_add_new_company_benefit()
	{
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/company_benefits/forms/add_company_benefit.php',$this->var);
	}
	
	function _load_add_new_deduction_type()
	{
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/deduction_type/forms/add_loan_deduction_type.php',$this->var);
	}
	
	function _load_assign_company_benefit()
	{
		if($_POST['eid']){
			$b = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($b){
				$count = G_Employee_Benefit_Helper::countTotalRecordsByBenefitIdAndAppliedToAll($b->getId());
				if($count > 0){					
					$eb = G_Employee_Benefit_Finder::findByBenefitId($b->getId());		
					$this->var['is_applied_to_all'] = G_Employee_Benefit::YES;
				}else{
					$eb = G_Employee_Benefit_Finder::findAllByBenefitId($b->getId());
					$this->var['is_applied_to_all'] = G_Employee_Benefit::NO;
				}
				$this->var['eb']			    = $eb;				
				$this->var['b']     		    = $b;
				$this->var['token'] 		    = Utilities::createFormToken();
				$this->view->render('settings/company_benefits/forms/assign_company_benefit.php',$this->var);
			}
		}
	}
	
	function _load_edit_deduction_type()
	{
		if($_POST['eid']){
			$d = G_Loan_Type_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['d']     = $d;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/options/deduction_type/forms/edit_loan_deduction_type.php',$this->var);
		}
	}

    function _load_add_user_account()
	{
	    $this->var['token'] = Utilities::createFormToken();
	    $this->view->render('settings/user_management/forms/add_user_account.php',$this->var);
	}

	function _load_edit_user_account()
	{
		if($_POST['id']){
			$user = G_User_Finder::findById($_POST['id']);
			if($user){
				$e = G_Employee_Finder::findById($user->getEmployeeId());
				$this->var['employee'] = $e;

				$mod = explode(',',$user->getModule());
                $this->var['modules'] = explode(',',$user->getModule());
				foreach($mod as $key=>$value) {
					if($value=='hr') {
						$current_module .= 'hr,';
						$this->var['checked_hr'] = "checked='checked'";
					}
					if($value=='clerk') {
						$current_module .= 'clerk,';
						$this->var['checked_clerk'] = "checked='checked'";
					}
					if($value=='employee') {
						$current_module .= 'employee,';
						$this->var['checked_employee'] = "checked='checked'";
					}

					if($value=='payroll') {
						$current_module .= 'payroll';
						$this->var['checked_payroll'] = "checked='checked'";	
					}
				}
			}

			$this->var['user']  = $user;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/user_management/forms/edit_user_account.php',$this->var);
		}
	}
	
	function _load_payroll_period()
	{
		$this->var['selected_year']= $_POST['selected_year'];
		$this->var['current_year'] = date("Y");
		$this->var['start_year']   = G_Cutoff_Period::YEAR_START;		
		$this->var['token'] 	   = Utilities::createFormToken();
		$this->view->render('settings/payroll_period/forms/add_payroll_period.php',$this->var);
	}
	
	function _load_edit_employee_status()
	{
		if(!empty($_POST['eid'])){
			$es = G_Settings_Employee_Status_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['es']    = $es;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/options/employee_status/forms/edit_employee_status.php',$this->var);
		}
	}
	
	function _load_edit_requirement()
	{
		if(!empty($_POST['eid'])){
			$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['gsr']    = $gsr;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/requirements/forms/edit_requirement.php',$this->var);
		}
	}

	function _load_edit_payroll_settings()
	{
		if(!empty($_GET['field'])){
			$field = $_GET['field'];	
			$sv = G_Sprint_Variables_Finder::findByVariableName($field);
			if( $sv ){
				$description      = $sv->getVariableDescription();
				$value            = $sv->getValue();
				$id 			  = $sv->getId();
				$custom_input_a   = $sv->getCustomValueDefaultFormInputValue('custom_value_a')->variableCustomValueFormInput();
				$remarks          = "";				
				switch ($field) {
					case G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS:
					    $class_name = "validate[required] text";
						$options    = $sv->optionsWorkingDays();
						$form_name  = 'edit_working_days.php'; 						
						$this->var['options_working_days'] = $options;					
						break;
					case G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR:					
						$class_name = "validate[required] text";						
						$remarks    = "<small class='settings-remarks'>Must be 24hr format and separated by 'to' word</small>";
						$form_name  = 'edit_settings.php'; 			
						break;	
					case G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR:											
						$a_values = explode(" ", $value);
						$month    = $a_values[0];
						$day      = $a_values[1];

						$option_months = array('January','February','March','April','May','June','July','August','September','October','November','December');
						$selected      = trim($month);
						$form_object[] = array('input_type' => 'select', 'selected' => $selected, 'class' => 'select-small', 'name' => 'month_selected', 'options' => $option_months);

						for($x = 1; $x<=31;$x++){
							$option_days[] = $x;
						}
						$selected      = trim($day);
						$form_object[] = array('input_type' => 'select', 'selected' => $selected, 'class' => 'select-small', 'name' => 'day_selected', 'options' => $option_days);

						$form_object[] = array('input_type' => 'hidden', 'name' => 'variable', 'value' => Utilities::encrypt($field));

						$this->var['form_object'] = $form_object;
						$form_name  = 'custom_edit_settings.php';

						break;
					case G_Sprint_Variables::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES:
					    $class_name = "validate[required] text";
						$options = array('Enable', 'Disable');
						$form_name  = 'edit_mandated_payroll_rates.php'; 						
						$this->var['options'] = $options;					
						break;
					case G_Sprint_Variables::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES:
					    $class_name = "validate[required] text";
						$options = array('Enable', 'Disable');
						$form_name  = 'edit_mandated_payroll_rates.php'; 						
						$this->var['options'] = $options;					
						break;


					case G_Sprint_Variables::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES:
					    $class_name = "validate[required] text";
						$options = array('Enable', 'Disable');
						$form_name  = 'edit_mandated_payroll_rates.php'; 						
						$this->var['options'] = $options;					
						break;
						
					default:	
						$class_name = "validate[required,custom[number]] text";
						$form_name  = 'edit_settings.php'; 				
						break;
				}

				$this->var['eid']                  = Utilities::encrypt($id);
				$this->var['custom_input_a']       = $custom_input_a;
				$this->var['remarks']			   = $remarks;
				$this->var['variable_field']       = $field;				
				$this->var['variable_description'] = $description;
				$this->var['variable_value'] 	   = $value;
				$this->var['action']      = url('settings/_update_payroll_settings');
				$this->var['class_name']  = $class_name;
	 			$this->var['token']       = Utilities::createFormToken();
				$this->view->render("settings/payroll_settings/forms/{$form_name}",$this->var);	

			}else{
				echo "Record not found";
			}				
		}else{
			echo "Record not found";
		}
	}
	
	function _load_edit_leave_type()
	{
		if(!empty($_POST['eid'])){
			$l = G_Leave_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['l']     = $l;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/options/leave_type/forms/edit_leave_type.php',$this->var);
		}
	}
	
	function _load_edit_department()
	{
		if($_POST['eid']){
			$this->var['d']	= G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->view->render('settings/company/forms/edit_department.php',$this->var);
		}
	}
	
	function _load_edit_team_group()
	{
		if($_POST['eid']){
			$this->var['d']	= G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->view->render('settings/company/forms/edit_group_team.php',$this->var);
		}
	}

	function _load_edit_section()
	{
		if($_POST['eid']){
			$this->var['d']	= G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->view->render('settings/company/forms/edit_section.php',$this->var);
		}
	}

	function _load_edit_contribution()
	{
		if($_POST['eid'] && $_POST['type']){
			if($_POST['type'] == 'sss') {
				$this->var['d']	= G_SSS_Finder::findById(Utilities::decrypt($_POST['eid']));
				$this->view->render('settings/contribution/forms/edit_sss.php',$this->var);
			}elseif($_POST['type'] == 'philhealth'){
				$this->var['d']	= G_Philhealth_Table_Finder::findById(Utilities::decrypt($_POST['eid'])); //$this->var['d']	= G_Philhealth_Finder::findById(Utilities::decrypt($_POST['eid']));
				$this->view->render('settings/contribution/forms/edit_philhealth.php',$this->var);
			}elseif($_POST['type'] == 'pagibig'){
				$this->var['d']	= G_Pagibig_Table_Finder::findById(Utilities::decrypt($_POST['eid']));
				$this->view->render('settings/contribution/forms/edit_pagibig.php',$this->var);
			}
			
		}
	}
	
	function _load_add_branch()
	{
		if(!empty($_POST['company_structure_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
			
			$this->var['locations'] = $locations;
			$this->var['p_id']      = $_POST['company_structure_id'];
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_company_branch.php',$this->var);
		}else {
			
		}
	}
	
	function _load_edit_company_branch()
	{
		if($_POST['eid']){
			$b 		    = G_Company_Branch_Finder::findById(Utilities::decrypt($_POST['eid']));	
			$locations  = G_Settings_Location_Finder::findByCompanyStructureId($this->company_structure_id);
			
			$this->var['locations'] = $locations;
			$this->var['b']		    = $b;
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/edit_company_branch.php',$this->var);
		}else {
			
		}
	}
	
	function _load_company_info()
	{		
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		$this->var['cs'] = $c_structure;
		$this->var['ci'] = $c_info;	
		$this->view->noTemplate();
		$this->view->render('settings/company/company_info.php',$this->var);
	}
	
	function _load_company_structure()
	{
		
		$t = new BreadCrumbs($this->company_structure_id);
		
		$this->var['trail']				   = $t->constructCompanyStructureBreadCrumbs();
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['branches']		       = G_Company_Branch_Finder::findAllIsNotArchiveByCompanyStructureId($this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('settings/company/company_structure.php',$this->var);
	}
	
	function _load_department_teams_groups_old()
	{
		
		$t = new BreadCrumbs($this->company_structure_id);
		$t->setBranchId(Utilities::decrypt($_POST['branch_id']));
		$t->setTrailId(Utilities::decrypt($_POST['eid']));
		$this->var['eid']				   = $_POST['eid'];
		$this->var['trail']				   = $t->constructCompanyStructureBreadCrumbs();
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['data']		           = G_Company_Structure_Finder::findAllTeamsAndGroupsIsNotArchiveByCompanyBranchIdAndParentId(Utilities::decrypt($_POST['eid']),$this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('settings/company/department_groups_teams.php',$this->var);
	}
	
	function _load_department_teams_groups()
	{
		
		$t = new BreadCrumbs($this->company_structure_id);
		//Root Dept
		$d = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));
		//
		$t->setBranchId($d->getCompanyBranchId());
		$t->setTrailId(Utilities::decrypt($_POST['eid']));
		
		$this->var['eid']				   = $_POST['eid'];
		$this->var['dept_id']			   = Utilities::encrypt($d->getId());			
		$this->var['trail']				   = $t->constructCompanyStructureBreadCrumbs();
		$this->var['company_structure_id'] = $this->company_structure_id;
		//$this->var['data']		           = G_Company_Structure_Finder::findAllTeamsAndGroupsIsNotArchiveByParentId(Utilities::decrypt($_POST['eid']));
		$this->var['data']		           = G_Company_Structure_Finder::findAllSectionsIsNotArchiveByParentId(Utilities::decrypt($_POST['eid']));
		$this->view->noTemplate();
		//$this->view->render('settings/company/department_groups_teams.php',$this->var);
		$this->view->render('settings/company/department_sections.php',$this->var);
	}
	
	function _load_branch_departments()
	{
		if($_POST['eid']){
			$t = new BreadCrumbs($this->company_structure_id);
			$t->setBranchId(Utilities::decrypt($_POST['eid']));
			
			$this->var['trail']		  = $t->constructCompanyStructureBreadCrumbs();
  			$this->var['eid'] 		  = $_POST['eid'];
			$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByBranchIdAndParentId(Utilities::decrypt($_POST['eid']),$this->company_structure_id);
			$this->view->noTemplate();
			$this->view->render('settings/company/branch_departments.php',$this->var);
		}	
	}
	
	function _load_delete_confirmation()
	{
		if(!empty($_POST['structure_id'])){
			$c = G_Company_Structure_Finder::findById($_POST['structure_id']);
			if($c){	
				$this->var['structure_name'] = $c->getTitle();
				$this->view->noTemplate();
				$this->view->render('settings/company/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_branch_confirmation()
	{
		if(!empty($_POST['branch_id'])){
			$b = G_Company_Branch_Finder::findById($_POST['branch_id']);
			if($b){	
				$this->var['branch'] = $b->getName();
				$this->view->noTemplate();
				$this->view->render('settings/branch/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_job_specification_confirmation()
	{
		if(!empty($_POST['job_specification_id'])){
			$js = G_Job_Specification_Finder::findById($_POST['job_specification_id']);
			if($js){	
				$this->var['title'] = $js->getName();
				$this->view->noTemplate();
				$this->view->render('settings/job/job_specification/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_job_title_confirmation()
	{
		if(!empty($_POST['job_title_id'])){
			$j = G_Job_Finder::findById($_POST['job_title_id']);			
			if($j){	
				$this->var['title'] = $j->getTitle();
				$this->view->noTemplate();
				$this->view->render('settings/job/job_title/delete_confirmation.php',$this->var);
			}			
		}
	}
	
	function _load_delete_job_eeo_category()
	{
		if(!empty($_POST['job_eeo_category_id'])){
			$j = G_Eeo_Job_Category_Finder::findById($_POST['job_eeo_category_id']);			
			if($j){	
				$this->var['title'] = $j->getCategoryName();
				$this->view->noTemplate();
				$this->view->render('settings/job/eeo_job_category/delete_confirmation.php',$this->var);
			}			
		}
	}
	
	function _load_delete_job_salary_rate()
	{
		if(!empty($_POST['salary_rate_id'])){
			$sr = G_Job_Salary_Rate_Finder::findById($_POST['salary_rate_id']);			
			if($sr){	
				$this->var['title'] = $sr->getJobLevel() . ': ' .$sr->getMinimumSalary() . '-' . $sr->getMaximumSalary();
				$this->view->noTemplate();
				$this->view->render('settings/job/job_salaray_rate/delete_confirmation.php',$this->var);
			}			
		}
	}
	
	function _load_confirmation()
	{
		if(!empty($_POST['msg'])){			
			$this->var['msg'] = $_POST['msg'];
			$this->view->noTemplate();
			$this->view->render('settings/company/confirmation.php',$this->var);			
		}
	}

	function _load_add_benefit_form()
	{
		$gsb       = new G_Settings_Employee_Benefit();
		$occurance = $gsb->getBenefitOccuranceOptions();
		$multiplied_by = $gsb->getMultipliedByOptions();

		$this->var['multiplied_by'] = $multiplied_by;
		$this->var['occurance']     = $occurance;
		$this->var['token']         = Utilities::createFormToken();
 		$this->var['benefit_yes']   = G_Settings_Employee_Benefit::YES;
		$this->var['benefit_no']    = G_Settings_Employee_Benefit::NO;
		$this->view->render('settings/benefits/forms/add_benefit.php',$this->var);
	}

	function _load_add_approvers()
	{
		$level = $_GET['level'];
		$this->var['approvers_level'] = $level + 1;
		$this->view->render('settings/request_approvers/forms/_add_approvers.php',$this->var);
	}

	function _load_add_breaktime()
	{
		$level = $_GET['level'];
		$br = new G_Break_Time_Schedule_Details();
		$day_type_options = $br->validDayTypeOptions();

		$this->var['day_type_options'] = $day_type_options;
		$this->var['append_level'] = $level + 1;
		$this->view->render('settings/breaktime_schedules/forms/_add_breaktime.php',$this->var);
	}

	function _load_add_request_approvers_form()
	{
		$ini_approvers = 1;
		for($ini_start = 1; $ini_start <= $ini_approvers; $ini_start++){
			$ini_script .= "
				var t{$ini_start} = new $.TextboxList('#approver_{$ini_start}', {unique: true,plugins: {
			    autocomplete: {
			      minLength: 2,
			      onlyFromValues: true,
			      queryRemote: true,
			      remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}

			    }
			  }});
			";
		}
		$this->var['ini_approvers'] = $ini_approvers;
		$this->var['ini_script']    = $ini_script;
		$this->var['token']         = Utilities::createFormToken(); 		
		$this->view->render('settings/request_approvers/forms/add_request_approvers.php',$this->var);
	}

	function _load_add_breaktime_schedule_form()
	{	
		$gbs = new G_Break_Time_Schedule_Details();
		$options_to_deduct = $gbs->getToDeductSelections();
		$options_obj_types = $gbs->getAllObjType();
		$day_type_options  = $gbs->validDayTypeOptions();
		$ini_breaktime_schedule = 1;

		$this->var['day_type_options']       = $day_type_options;
		$this->var['options_to_deduct'] 	 = $options_to_deduct;		
		$this->var['ini_breaktime_schedule'] = $ini_breaktime_schedule;	
		$this->var['token']         = Utilities::createFormToken(); 	
		$this->var['all_employee']  = G_Break_Time_Schedule_Details::YES;	
		$this->view->render('settings/breaktime_schedules/forms/add_breaktime_schedules.php',$this->var);
	}

	function _load_edit_breaktime_schedule_form()
	{

		if( !empty($_GET['eid']) ){
			$id = Utilities::decrypt($_GET['eid']);
			$br = G_Break_Time_Schedule_Header_Finder::findById($id);
			if( !empty($br) ){
				$data = $br->getBreakTimeHeaderAndDetailsData();				
				if( !empty($data) ){										
					$ini_breaktime_schedule = 0;

					$br = new G_Break_Time_Schedule_Details();
					$day_type_options = $br->validDayTypeOptions();

					$this->var['day_type_options']		 = $day_type_options;
					$this->var['break_time_header']      = $data['header'];
					$this->var['break_time_details']     = $data['details'];					
					$this->var['to_deduct']				 = G_Break_Time_Schedule_Details::YES;	
					$this->var['to_required_logs']		 = G_Break_Time_Schedule_Details::YES;	
					$this->var['ini_breaktime_schedule'] = $ini_breaktime_schedule;	
					$this->var['token']         = Utilities::createFormToken(); 						
					$this->view->render('settings/breaktime_schedules/forms/edit_breaktime_schedules.php',$this->var);
				}else{
					echo "Record not found";
				}			
			}else{
				echo "Record not found";
			}
		}else{
			echo "Record does not exists!";
		}		
	}

	function _load_add_role_form()
	{

		$mp = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
		$mod_payroll = $mp->getModuleList();

		$mhr = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$mod_hr = $mhr->getModuleList();

		$mhr = new G_Sprint_Modules(G_Sprint_Modules::DTR);
		$mod_dtr = $mhr->getModuleList(); 

		$m_emp = new G_Sprint_Modules(G_Sprint_Modules::EMPLOYEE);
		$mod_employee = $m_emp->getModuleList(); 

		$m_at = new G_Sprint_Modules(G_Sprint_Modules::AUDIT_TRAIL);
		$mod_audit_trail = $m_at->getModuleList(); 

		$this->var['dtr_key']     = G_Sprint_Modules::DTR;
 		$this->var['hr_key']      = G_Sprint_Modules::HR;
		$this->var['payroll_key'] = G_Sprint_Modules::PAYROLL;
		$this->var['employee_key'] = G_Sprint_Modules::EMPLOYEE;
		$this->var['audit_trail_key'] = G_Sprint_Modules::AUDIT_TRAIL;
		$this->var['token']       = Utilities::createFormToken();
		$this->var['no_access']   = Sprint_Modules::PERMISSION_04;
		$this->var['mod_payroll'] = $mod_payroll;
		$this->var['mod_hr']      = $mod_hr;
		$this->var['mod_dtr']     = $mod_dtr;
		$this->var['mod_employee']= $mod_employee;
		$this->var['mod_audit_trail']= $mod_audit_trail;
		$this->var['user_position']	  = $this->global_user_position;
		$this->view->render('settings/user_management/forms/add_role.php',$this->var);
	}

	function _load_add_user_form()
	{
		
		$r = new G_Role();
		
		$order_by = "";
		$limit    = "";
		$fields   = array("id", "name");
		$roles = $r->getAllRecordsIsNotArchive($order_by, $limit, $fields);
		$roles = $r->encryptId($roles);

		$this->var['roles'] = $roles;   
		$this->var['token'] = Utilities::createFormToken();		
		$this->view->render('settings/user_management/forms/add_user.php',$this->var);
	}

	function _load_import_user_form()
	{
		
		$r = new G_Role();
		
		$order_by = "";
		$limit    = "";
		$fields   = array("id", "name");
		$roles = $r->getAllRecordsIsNotArchive($order_by, $limit, $fields);
		$roles = $r->encryptId($roles);

		$this->var['roles'] = $roles;   
		$this->var['token'] = Utilities::createFormToken();		
		$this->view->render('settings/user_management/forms/import_user.php',$this->var);
	}

	function ajax_verify_username()
	{
		$return = array();

		if(!empty($_GET['username'])){
			$username = $_GET['username'];
			$id       = Utilities::decrypt($_GET['eid']);

			$eu = new G_Employee_User();
			$eu->setId($id);
			$eu->setUsername($username);
			$is_exists = $eu->isUserNameExists();

			if($is_exists > 0){
				$return['message'] = "<div class=\"label label-warning\" style=\"margin-top:4px;width:auto;padding-right:9px;\"><i class=\"icon-remove icon-white\"></i>Username not available</div>";	
			}else{
				$return['message'] = "<div class=\"label label-success\" style=\"margin-top:4px;width:auto;padding-right:9px;\"><i class=\"icon-ok icon-white\"></i> Username is available</div>";	
			}

		}else{
			$return['message'] = "<div class=\"label label-warning\" style=\"margin-top:4px;width:auto;padding-right:9px;\"><i class=\"icon-remove icon-white\"></i> Please specify desired username</div>";			
		}

		echo json_encode($return);
	}

	function import_sss_table()
	{
		ob_start();		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);					
		
		$file = $_FILES['sss_file']['tmp_name'];		
		$sss  = new G_SSS();
		$sss->importSSSTable($file);

		$return['is_imported'] = true;
		$return['message'] = 'SSS table was successfully imported';

		$ec = new G_Employee_Contribution();
		$ec->updateEmployeeContribution();

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}

	function import_employee_benefits()
	{
		ob_start();		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);					
		
		$file   = $_FILES['sss_file']['tmp_name'];				
		$fields = array("company_structure_id","benefit_id","applied_to","employee_department_id","description");
        $bf = new G_Employee_Benefits_Main();
		$bf->setDateCreated($this->c_date);
		$bf->setCompanyStructureid($this->company_structure_id);	
		$data = $bf->setImportFile($file)->createImportBulkData()->importBulkSettingsBenefits()->bulkSave(array(),$fields);		

		$return['is_imported'] = true;
		$return['message'] = 'Employee benefits was successfully imported';

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}

	function import_philhealth_table()
	{
		ob_start();		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);					
		
		$file = $_FILES['sss_file']['tmp_name'];		
		$ph  = new G_Philhealth();
		$ph->importPhilHealthTable($file);

		$return['is_imported'] = true;
		$return['message'] = 'Philhealth table was successfully imported';

		$ec = new G_Employee_Contribution();
		$ec->updateEmployeeContribution();

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}

	function import_pagibig_table()
	{
		ob_start();		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);					
		
		$file = $_FILES['sss_file']['tmp_name'];		
		$pg  = new G_Pagibig_Table();
		$pg->setCompanyStructureId($this->company_structure_id);
		$pg->importPagibigTable($file);

		$return['is_imported'] = true;
		$return['message'] = 'Pagibig table was successfully imported';

		$ec = new G_Employee_Contribution();
		$ec->updateEmployeeContribution();

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}

	function _import_employee_user_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$return['is_success'] = false;
		
		$role_id = Utilities::decrypt($_POST['role']);
		$is_role_id_exists  = G_Role_Helper::sqlIsIdExists($role_id);

		if($is_role_id_exists > 0) {	
			$file = $_FILES['employee_user_file']['tmp_name'];	

	        $lr = new G_Employee_User_Importer($file);
	        $lr->import($role_id,$this->company_structure_id);
					
			if ($lr->imported_records > 0) {
				$return['is_imported'] = true;
				if ($lr->error_count > 0) {
	                $lr->total_records = $lr->total_records - 1; // minus the excel title header
					$msg =  $lr->imported_records. ' of '.$lr->total_records .' records has been successfully imported.';
					if($lr->error_employee_code>0) {
						$msg .= '<br> '. $lr->error_employee_code.' error(s) found in Employee Code.<br>
								List of Employee Code does not exist<br>
						';	
						foreach($lr->code as $key=>$value) {
							$msg .= "Row: " .$value.'<br>';
						}
					}

					if($lr->error_username>0) {
						$msg .= '<br> '. $lr->error_username.' error(s) found in Username.<br>
								List of Username already exist<br>
						';	
						foreach($lr->existing_username as $key=>$value) {
							$msg .= "Row: " .$value.'<br>';
						}
					}

					if($lr->error_invalid_username>0) {
						$msg .= '<br> '. $lr->error_invalid_username.' error(s) found in Username.<br>
								Username must not contain any special characters and spaces<br>
						';	
						foreach($lr->existing_invalid_username as $key=>$value) {
							$msg .= "Row: " .$value.'<br>';
						}
					}

					if($lr->error_employee_user>0) {
						$msg .= '<br> '. $lr->error_employee_user.' error(s) found in Employee Code.<br>
								List of Employee Code already registered.<br>
						';	
						foreach($lr->existing_employee_user as $key=>$value) {
							$msg .= "Row: " .$value.'<br>';
						}
					}
			
					$return['message']= $msg;
				} else {
					$return['message'] = $lr->imported_records . ' Record(s) has been successfully imported.';

					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_IMPORT, ' User Account ', $lr->imported_records, '', '', 1, '', '');
				}
				$return['is_success'] = true;
			} else {
				$return['message'] = 'Error in importing excel file.';
				//General Reports / Shr Audit Trail
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_IMPORT, ' User Account ', $lr->imported_records, '', '', 0, '', '');
			}
		}else{
			$return['message'] = 'Role does not exist.';
			//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_IMPORT, 'User Account ', $lr->imported_records, '', '', 0, '', '');
		}
		echo json_encode($return);	
		//echo $return['message'];
	}

	function save_user()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($_POST) ){						
			$employee_id = Utilities::decrypt($data['employee_id']);
			$role_id     = Utilities::decrypt($data['role']);
			$company_id  = $this->company_structure_id;

			$u = new G_Employee_User();
			$u->setCompanyStructureId($company_id);
	        $u->setEmployeeId($employee_id);        
	        $u->setUsername($data['username']);                
	        $u->setPassword($data['password']);                
	        $u->setRoleId($role_id);                
	        $u->setDateCreated($this->c_date);                
			$json = $u->addUser();

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();


		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' User Account ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

		echo json_encode($json);
	}

	function update_user()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($_POST) ){						
			$id 		= Utilities::decrypt($data['eid']);
			$role_id    = Utilities::decrypt($data['role']);
			$company_id = $this->company_structure_id;

			$u = G_Employee_User_Finder::findById($id);
			if( $u ){
				$u->setCompanyStructureId($company_id);
				$u->setUsername($data['username']);                
		        $u->setPassword($data['password']);                
		        $u->setRoleId($role_id);
		        $u->setLastModified($this->c_date);
		        $json = $u->updateUser();                
			}else{				
				$json['is_success'] = false;
				$json['message']    = "Record not found";
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId($id);
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' User Account of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

		echo json_encode($json);
	}

	function _load_edit_user_form()
	{		
		if( !empty($_GET['eid']) ){
			$id = Utilities::decrypt($_GET['eid']);
			$u  = new G_Employee_User();
			$u->setId($id);
			$data = $u->getUserDataById();
			if( !empty($data) ){				
				$data = $u->encryptId($data);
				$data = $u->encryptId($data,"role_id");

				$r = new G_Role();
				$order_by = "";
				$limit    = "";
				$fields   = array("id", "name");
				$roles = $r->getAllRecordsIsNotArchive($order_by, $limit, $fields);
				$roles = $r->encryptId($roles);

				$this->var['roles'] = $roles;
				$this->var['user']  = $data;
				$this->var['token'] = Utilities::createFormToken();
				$this->view->render('settings/user_management/forms/edit_user.php',$this->var);

			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}

	function _load_edit_role_form()
	{		


		if( !empty($_GET['eid']) ){
			$id   = Utilities::decrypt($_GET['eid']);
			$role = G_Role_Finder::findById($id);
			if( !empty($role) ){				
				$role_modules 		= $role->getRoleActions();
				$hr_data      		= $role_modules['hr'];
				$payroll_data 		= $role_modules['payroll'];
				$dtr_data     		= $role_modules['dtr'];
				$employee_data		= $role_modules['employee'];
				$audit_trail_data	= $role_modules['audit_trail'];

				$mp          = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
				$mod_payroll = $mp->getModuleList();

				$mhr         = new G_Sprint_Modules(G_Sprint_Modules::HR);
				$mod_hr      = $mhr->getModuleList();

				$mhr    	 = new G_Sprint_Modules(G_Sprint_Modules::DTR);
				$mod_dtr     = $mhr->getModuleList();

				$m_emp = new G_Sprint_Modules(G_Sprint_Modules::EMPLOYEE);
				$mod_employee = $m_emp->getModuleList(); 

				$m_at = new G_Sprint_Modules(G_Sprint_Modules::AUDIT_TRAIL);
				$mod_audit_trail = $m_at->getModuleList(); 
				
				if( !empty($hr_data) ){
					$hr_checked = "checked=\"checked\"";
				}else{
					$hr_checked = "";
				}

				if( !empty($payroll_data) ){
					$payroll_checked = "checked=\"checked\"";
				}else{
					$payroll_checked = "";
				}

				if( !empty($dtr_data) ){
					$dtr_checked = "checked=\"checked\"";
				}else{
					$dtr_checked = "";
				}

				if( !empty($employee_data) ){
					$employee_checked = "checked=\"checked\"";
				}else{
					$employee_checked = "";
				}

				if( !empty($audit_trail_data) ){
					$audit_trail_checked = "checked=\"checked\"";
				}else{
					$audit_trail_checked = "";
				}

				$this->var['eid']             = Utilities::encrypt($role->getId());
				$this->var['hr_checked']      = $hr_checked;
				$this->var['dtr_checked']     = $dtr_checked;
				$this->var['payroll_checked'] = $payroll_checked;
				$this->var['employee_checked']= $employee_checked;
				$this->var['audit_trail_checked']= $audit_trail_checked;
				$this->var['hr_data']         = $hr_data;
				$this->var['dtr_data']        = $dtr_data;
				$this->var['payroll_data']    = $payroll_data;
				$this->var['employee_data']   = $employee_data;
				$this->var['audit_trail_data']   = $audit_trail_data;
				$this->var['role']		      = $role;
				$this->var['hr_key']          = G_Sprint_Modules::HR;
				$this->var['dtr_key']         = G_Sprint_Modules::DTR;
				$this->var['payroll_key']     = G_Sprint_Modules::PAYROLL;
				$this->var['employee_key']    = G_Sprint_Modules::EMPLOYEE;
				$this->var['audit_trail_key'] = G_Sprint_Modules::AUDIT_TRAIL;
				$this->var['token']           = Utilities::createFormToken();
				$this->var['no_access']       = Sprint_Modules::PERMISSION_04;
				$this->var['mod_payroll']     = $mod_payroll;
				$this->var['mod_hr']          = $mod_hr;
				$this->var['mod_dtr']         = $mod_dtr;
				$this->var['mod_employee']    = $mod_employee;
				$this->var['mod_audit_trail'] = $mod_audit_trail;
				$this->var['user_position']	  = $this->global_user_position;
				$this->view->render('settings/user_management/forms/edit_role.php',$this->var);
			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}

	function _load_edit_benefit_form()
	{		
		if( !empty($_GET['eid']) ){
			$id      = Utilities::decrypt($_GET['eid']);
			$benefit = G_Settings_Employee_Benefit_Finder::findById($id);
			if( $benefit ){		
				$multiplied_by = $benefit->getMultipliedByOptions();		
				$occurance     = $benefit->getBenefitOccuranceOptions();
				$this->var['multiplied_by'] = $multiplied_by;
				$this->var['occurance']   = $occurance;
				$this->var['token']       = Utilities::createFormToken();
				$this->var['benefit_yes'] = G_Settings_Employee_Benefit::YES;
				$this->var['benefit_no']  = G_Settings_Employee_Benefit::NO;
				$this->var['eid']         = Utilities::encrypt($benefit->getId());				
				$this->var['benefit']     = $benefit;
				$this->view->render('settings/benefits/forms/edit_benefit.php',$this->var);
			}else{
				echo "Record not found";
			}
		}else{
			echo "Record not found!";
		}
	}

	function _load_employees_enrolled_to_benefit()
	{
		if( !empty($_GET['eid']) ){
			$eid = $_GET['eid'];
			$benefit = G_Settings_Employee_Benefit_Finder::findById(Utilities::decrypt($eid));
			if( $benefit ){
				$this->var['benefit_name'] = $benefit->getCode() . " : " . $benefit->getName();
				$this->var['eid'] 		   = $eid;
				$this->view->render('settings/benefits/employees_enrolled.php',$this->var);
			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}

	function _load_enroll_employee_form()
	{		
		if( !empty($_GET['eid']) ){
			$id      = Utilities::decrypt($_GET['eid']);
			$benefit = G_Settings_Employee_Benefit_Finder::findById($id);
			if( $benefit ){
				$bm = new G_Employee_Benefits_Main();
				$this->var['criteria_options']        = $bm->getCriteriaOptions();
				$this->var['custom_criteria_options'] = $bm->getCustomCriteriaOptions();
				$this->var['all_yes']	   = Employee_Benefits_Main::YES;
				$this->var['all_no']       = Employee_Benefits_Main::NO;
				$this->var['token']        = Utilities::createFormToken();
				$this->var['benefit_name'] = $benefit->getCode() . " : " . $benefit->getName();
				$this->var['eid']          = Utilities::encrypt($benefit->getId());								
				$this->view->render('settings/benefits/forms/enroll_employee.php',$this->var);
			}else{
				echo "Record not found";
			}
		}else{
			echo "Record not found!";
		}
	}

	function update_role()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($_POST) ){			
			$modules = $_POST['mod'];
			$id      = Utilities::decrypt($_POST['eid']);

			$r = G_Role_Finder::findById($id);
			if( !empty($r) ){
				$r->setName($data['role_name']);
				$r->setDescription($data['role_description']);
				$r->setLastModified($this->c_date);
				$r->save();
				$json = $r->updateModuleActions($modules);
			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot save record";
			}

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}

	function enroll_to_benefit()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($data) ){						
			$benefit_id      = Utilities::decrypt($data['eid']);
			$employee_ids    = $data['employee_id'];

			$criteria_value  = $data['criteria'];			
			$criteria = implode(",", array_keys($criteria_value)); 

			$custom_criteria_field = $data['custom_criteria'];	
			$custom_criteria_value = $data['custom_criteria_value'];
			$custom_criteria_from  = $data['custom_criteria_from'];
			$custom_criteria_to    = $data['custom_criteria_to'];					
			foreach( $custom_criteria_field as $key => $cc ){
				if( trim($custom_criteria_value[$key]) != "" ){
					if( trim($custom_criteria_from[$key]) != "" && trim($custom_criteria_to[$key]) != "" ){
						$a_cc[] = $key . " : " . $custom_criteria_value[$key] . " / " . $custom_criteria_from[$key] . " to " . $custom_criteria_to[$key];
					}else{
						$a_cc[] = $key . " : " . $custom_criteria_value[$key];
					}
				}
			}
			$custom_criteria = implode(",", $a_cc);			

			$b = new G_Employee_Benefits_Main();
			$b->setCompanyStructureid($this->company_structure_id);
			$b->setBenefitId($benefit_id);
			if( $data['apply_to_all_employees'] == Employee_Benefits_Main::YES ){				
				$b->setEmployeeDepartmentId(0);
				$b->setAppliedTo(Employee_Benefits_Main::ALL_EMPLOYEE);
				$b->setDescription(Employee_Benefits_Main::ALL_EMPLOYEE);
				$b->setCriteria($criteria);
				$b->setCustomCriteria($custom_criteria);
				$json = $b->save();
			}else{
				$dept_section      = $data['dept_section_id'];
				$employment_status = $data['employment_status_id'];
				$employee_ids      = $data['employee_id'];
				$employment_status_exclude_employee = $data['employment_status_exclude_employee'];
				$excluded_employees = $data['excluded_employees'];

				$a_enrollees['employees']         = explode(",", $employee_ids);
				$a_enrollees['dept_section']      = explode(",", $dept_section);
				$a_enrollees['employment_status'] = explode(",", $employment_status);
				
				$benefit      = new G_Employee_Benefits_Main();
				$decrypt_data = true;	
				
				if($employment_status_exclude_employee){
					$b->setExcludedEmployeeId($excluded_employees);
				}
				
				$json = $b->setCustomCriteria($custom_criteria)->setCriteria($criteria)->setBulkEnrollees($a_enrollees)->removeDuplicateBulkEnrollees()->sanitizeBulkEnrollees( $decrypt_data )->createBulkSaveArray()->deleteDuplicateData()->bulkSave();
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}

	function save_breaktime_schedule()
	{
		Utilities::verifyFormToken($_POST['token']);
		$data = $_POST;
		if( !empty($data) ){
			$schedule_in  = $data['schedule_in'];
			$schedule_out = $data['schedule_out'];
			$breaktime    = $data['breaktime'];
			$applied_to   = $data['breaktime_applied_to'];
			$applied_to_all = $data['breaktime_applied_to_all'];
			$date_start   = $data['date_start'];

			$br = new G_Break_Time_Schedule_Header();
			$br->setScheduleIn($schedule_in);
			$br->setScheduleOut($schedule_out);
			$br->setAppliedTo($applied_to);
			$br->setIsAppliedToAll($applied_to_all);
			$br->setDateStart($date_start);
			$br->setDateCreated($this->c_date);
			$json = $br->addBreakTimeSchedule($breaktime);
		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}

	function update_breaktime_schedule()
	{
		Utilities::verifyFormToken($_POST['token']);
		$data = $_POST;		
		if( !empty($data) ){

			$id = Utilities::decrypt($data['heid']);		
			$schedule_in    = $data['schedule_in'];
			$schedule_out   = $data['schedule_out'];
			$breaktime      = $data['breaktime'];		
			$date_start     = $data['date_start'];

			$br = G_Break_Time_Schedule_Header_Finder::findById($id);
			if( $br ){
				$br->setScheduleIn($schedule_in);
				$br->setScheduleOut($schedule_out);						
				$br->setDateStart($date_start);
				$json = $br->updateBreakTimeSchedule($breaktime);
			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot save record";	
			}

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}

	function save_request_approvers()
	{
		Utilities::verifyFormToken($_POST['token']);		
		$data = $_POST;
		if( !empty($data) ){
			$title      = $data['request_title'];
			$approvers  = $data['approvers'];		
			$requestors = $data['requestors_id'];

			$gr = new G_Request_Approver();
			$gr->setTitle($title);
			$gr->setApprovers($approvers);
			$gr->setRequestors($requestors);
			$gr->setDateCreated($this->c_date);
			$json = $gr->addRequestApprovers();
		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		
		echo json_encode($json);
	}

	function update_request_approvers()
	{
		Utilities::verifyFormToken($_POST['token']);		
		$data = $_POST;
		if( !empty($data) ){
			$id 		= Utilities::decrypt($data['eid']);
			$title      = $data['request_title'];
			$approvers  = $data['approvers'];		
			$requestors = $data['requestors_id'];

			$gr = new G_Request_Approver();
			$gr->setId($id);
			$gr->setTitle($title);
			$gr->setApprovers($approvers);
			$gr->setRequestors($requestors);
			$gr->setDateCreated($this->c_date);
			$json = $gr->updateRequestApprovers();

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}

	function save_benefit()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($data) ){						
			
			if( $data['chk_multiplied_by'] ){
				$multiplied_by = $data['multiplied_by_selected'];
			}else{
				$multiplied_by = '';
			}

			$b = new G_Settings_Employee_Benefit();			
			$b->setCode($data['benefit_code']);
	        $b->setName($data['benefit_name']);    
	        $b->setCutOff($data['benefit_occurance']);    
	        $b->setDescription($data['benefit_description']);        
	        $b->setAmount($data['benefit_amount']);	       
	        $b->setMultipliedBy($multiplied_by);
	        $b->setIsTaxable($data['is_taxable']);	       
	        $b->setDateCreated($this->c_date);	        
			$json = $b->isMultipliedByValidSelection()->saveBenefit();		

			//general reports / shr audit trail
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_ADD, ' New Benefits of ', $data['benefit_name'], 'None', $data['benefit_amount'], 1, '', '');

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";

			//general reports / shr audit trail
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_ADD, ' New Benefits ', $data['benefit_name'], 'None', $data['benefit_amount'], 0, '', '');
		}

		echo json_encode($json);
	}

	function update_benefit()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($data) ){						
			$id = Utilities::decrypt($data['eid']);
			$b  = G_Settings_Employee_Benefit_Finder::findById($id);
			var_dump($b);
			if( $b ){
				if( $data['chk_multiplied_by'] ){
					$multiplied_by = $data['multiplied_by_selected'];
				}else{
					$multiplied_by = '';
				}
				$b->setCode($data['benefit_code']);
		        $b->setName($data['benefit_name']);        
		        $b->setDescription($data['benefit_description']);        
		        $b->setAmount($data['benefit_amount']);
		        $b->setMultipliedBy($multiplied_by);
		        $b->setCutOff($data['benefit_occurance']);
		        $b->setIsTaxable($data['is_taxable']);	       
		        $b->setDateLastModified($this->c_date);	        
				$json = $b->isMultipliedByValidSelection()->saveBenefit();	

				//general reports / shr audit trail

        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_NEW_UPDATE, ' Benefits ', $data['benefit_name'], $b->name, $data['benefit_name'], 1, '', '');

			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot save record";	

				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_NEW_UPDATE, ' Benefits ', $data['benefit_name'], $b->name, $data['benefit_name'], 0, '', '');
			}
		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_NEW_UPDATE, ' Benefits ', $data['benefit_name'], $b->name, $data['benefit_name'], 0, '', '');
		}

		echo json_encode($json);
	}

	function save_role()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();

		if( !empty($_POST) ){			
			$modules = $_POST['mod'];
			$r = new G_Role();			
			$r->setName($data['role_name']);
			$r->setDescription($data['role_description']);
			$r->setIsArchive(G_Role::NO);
			$r->setDateCreated($this->c_date);
			$id = $r->save();						
			if( $id > 0 ){				
				$r->setId($id);
				$json = $r->addModuleActions($modules);
			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot save record";
			}		 

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
		
	}
	
	function _load_sss_table() 
	{
		$sss = G_SSS_Finder::findAll('from_salary ASC');				
		$this->var['sss'] = $sss;
		$this->view->render('settings/contribution/sss_table.php',$this->var);
	}

	function ajax_import_sss_table() {	
		$this->view->render('settings/contribution/forms/import_sss_table_form.php', $this->var);	
	}

	function ajax_import_employee_benefits() {	
		$this->view->render('settings/benefits/forms/import_employee_benefits_form.php', $this->var);	
	}

	function ajax_import_philhealth_table() {	
		$this->view->render('settings/contribution/forms/import_philhealth_table_form.php', $this->var);	
	}

	function ajax_import_pagibig_table() {	
		$this->view->render('settings/contribution/forms/import_pagibig_table_form.php', $this->var);	
	}

	function update_sss()
	{
		$json = array();
		$json['is_success'] = false;
		$json['message']    = "Invalid Record";

		if($_POST['from_salary'] > $_POST['to_salary']) {
			$json['message']    = "Invalid Salary Range";
		}

		if( !empty($_POST) ){						
			$id 		= Utilities::decrypt($_POST['eid']);
			$d = G_SSS_Finder::findById($id);
			if( $d ){
				$d->setCompanyShare($_POST['company_share']);
				$d->setEmployeeShare($_POST['employee_share']);
				$d->setCompanyEc($_POST['company_ec']);
				$d->setMonthlySalaryCredit($_POST['monthly_salary_credit']);
				$d->setFromSalary($_POST['from_salary']);
				$d->setToSalary($_POST['to_salary']);
		        $result = $d->update(); 
		        if($result) {
		        	$json['c_type'] = 'sss';
		        	$json['is_success'] = true;
					$json['message']    = "Successfully Updated";    

					$ec = new G_Employee_Contribution();
					$ec->updateEmployeeContribution();

		        }        
			}
		}
		echo json_encode($json);
	}

	function _load_payroll_settings()
	{
		$sv = new G_Sprint_Variables();				
		$payroll_variables = $sv->getPayrollDefaultVariables();

		$variables['Payroll']   = $payroll_variables;		
		$this->var['variables'] = $variables;
		$this->view->render('settings/payroll_settings/_payroll_settings.php',$this->var);
	}

	function _load_notification_settings() {
		$setting_notif = G_Settings_Notifications_Finder::findAll();
		$this->var['setting_notif'] = $setting_notif;
		$this->view->render('settings/notifications/_notification_settings.php',$this->var);
	}	

	function _load_breaktime_schedules()
	{
		$p = new G_Payroll_Variables();		
		$data = $p->getDefaultSettings();
		$data = Utilities::encryptArrayId("id",$data);				
		$this->var['data'] = $data;
		$this->view->render('settings/payroll_settings/_breaktime_schedules.php',$this->var);
	}

	function _load_request_approvers()
	{
		$p = new G_Payroll_Variables();		
		$data = $p->getDefaultSettings();
		$data = Utilities::encryptArrayId("id",$data);				
		$this->var['data'] = $data;
		$this->view->render('settings/request_approvers/_request_approvers_dt.php',$this->var);


	}
	
	function _load_payroll_period_list_selected_year() 
	{
		$year    = $_POST['selected_year'];
		$gcp     = new G_Cutoff_Period();
		$cutoffs = $gcp->generatePayrollPeriodByYear($year);
		$this->var['cutoffs'] = $cutoffs;
		$this->view->render('settings/payroll_period/forms/_cutoff_periods.php',$this->var);
	}
	
	function _load_philhealth_tableOLD2017() 
	{
		$philhealth = G_Philhealth_Finder::findAll('from_salary ASC');				
		$this->var['philhealth'] = $philhealth;
		$this->view->render('settings/contribution/philhealth_table.php',$this->var);
	}

	function _load_philhealth_table() 
	{
		$philhealth = G_Philhealth_Table_Finder::findAll();				
		$this->var['philhealth'] = $philhealth;
		$this->view->render('settings/contribution/philhealth_table.php',$this->var);
	}		

	function update_philhealth()
	{
		$json = array();
		$json['is_success'] = false;
		$json['message']    = "Invalid Record";

		if($_POST['from_salary'] > $_POST['to_salary']) {
			$json['message']    = "Invalid Salary Range";
			echo json_encode($json);
			exit();
		}

		if( !empty($_POST) ){						
			$id 		= Utilities::decrypt($_POST['eid']);

			$d 	= G_Philhealth_Table_Finder::findById($id);

			if($d) {

				//check if philhealth_history 
				if($d->getMultiplierEmployee() != $_POST['multiplier_employee']){
					
					$dh =  G_Philhealth_History_Finder::findHistory($d);

					//if no history
					if(!$dh){

						$ph = new G_Philhealth_history;
						$ph->setCompanyStructureId($d->getCompanyStructureId());
						$ph->setSalaryFrom($d->getSalaryFrom());
						$ph->setSalaryTo($d->getSalaryTo());
						$ph->setMultiplierEmployee($d->getMultiplierEmployee());
				        $ph->setMultiplierEmployer($d->getMultiplierEmployer());
				        $ph->setIsFixed($d->getIsFixed());
				        $ph->setDateEnd($_POST['effectivity_date']);
				        $ph->save();

					}  

				}

				//$d->setCompanyStructureId(1);
				$d->setSalaryFrom($_POST['salary_from']);
				$d->setSalaryTo($_POST['salary_to']);
				$d->setMultiplierEmployee($_POST['multiplier_employee']);
		        $d->setMultiplierEmployer($_POST['multiplier_employer']);
		        $d->setIsFixed($_POST['is_fixed']);
		        $d->setEffectiveDate($_POST['effectivity_date']);
				$result = $d->save();
				if($result) {
		        	$json['c_type'] = 'philhealth';
		        	$json['is_success'] = true;
					$json['message']    = "Successfully Updated"; 

					$ec = new G_Employee_Contribution();
					$ec->updateEmployeeContribution(); 
		        }	
			}			

			// OLD Philhealth 2017 - Start
			/*
			$d = G_Philhealth_Finder::findById($id);
			if( $d ){
				$d->setCompanyShare($_POST['company_share']);
				$d->setEmployeeShare($_POST['employee_share']);
				$d->setSalaryBase($_POST['salary_base']);
				$d->setSalaryBracket($_POST['salary_bracket']);
				$d->setFromSalary($_POST['from_salary']);
				$d->setToSalary($_POST['to_salary']);
				$d->setMonthlyContribution($_POST['monthly_contribution']);
		        $result = $d->update(); 
		        if($result) {
		        	$json['c_type'] = 'philhealth';
		        	$json['is_success'] = true;
					$json['message']    = "Successfully Updated";   

					$ec = new G_Employee_Contribution();
					$ec->updateEmployeeContribution(); 
		        }        
			}
			*/
			// OLD Philhealth 2017 - End
		}
		echo json_encode($json);
	}
	
	function _load_pagibig_table() 
	{		
		$pagibig = G_Pagibig_Table_Finder::findAllByCompanyStructureId($this->company_structure_id);
		$this->var['pagibig'] = $pagibig;
		$this->view->render('settings/contribution/pagibig_table.php',$this->var);
	}

	function update_pagibig()
	{
		$json = array();
		$json['is_success'] = false;
		$json['message']    = "Invalid Record";

		if($_POST['salary_from'] > $_POST['salary_to'] && $_POST['salary_to'] > 0) {
			$json['message']    = "Invalid Salary Range";
			echo json_encode($json);
			exit();
		}

		if( !empty($_POST) ){						
			$id 		= Utilities::decrypt($_POST['eid']);
			$d = G_Pagibig_Table_Finder::findById($id);
			if( $d ){
				$d->setSalaryFrom($_POST['salary_from']);
				$d->setSalaryTo($_POST['salary_to']);				
				$d->setMultiplierEmployee($_POST['multiplier_employee']);				
				$d->setMultiplierEmployer($_POST['multiplier_employer']);	
		        $result = $d->update(); 
		        if($result) {
		        	$json['c_type'] = 'pagibig';
		        	$json['is_success'] = true;
					$json['message']    = "Successfully Updated";    

					$ec = new G_Employee_Contribution();
					$ec->updateEmployeeContribution();
		        }        
			}
		}
		echo json_encode($json);
	}
	
	function _load_tax_table() 
	{		
		$monthly      = G_Tax_Table_Finder::findAllMonthlyByCompanyStructure($this->company_structure_id);
		$semi_monthly = G_Tax_Table_Finder::findAllSemiMonthlyByCompanyStructure($this->company_structure_id);
		$this->var['monthly']      = $monthly;
		$this->var['semi_monthly'] = $semi_monthly;		
		$this->view->render('settings/contribution/tax_table.php',$this->var);
	}
	
	function _load_edit_company_info()
	{
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		$this->var['c']     = $c_structure;
		$this->var['cinfo'] = $c_info;
		$this->view->noTemplate();
		$this->view->render('settings/company/forms/edit_company_info.php',$this->var);
	}
	
	function load_add_subdivision_type()
	{
		if(!empty($_POST['parent_id'])){
			$this->var['p_id'] = $_POST['parent_id'];
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_subdivision_type.php',$this->var);
		}
	}
	
	function update_company_info()
	{
		if(!empty($_POST)){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			$cstructure->setTitle($_POST['title']);
			$cstructure->save();
				
			$cinfo = new G_Company_Info($cstructure->getId());
			$cinfo->setAddress($_POST['address']);
			$cinfo->setAddress1($_POST['address1']);
			$cinfo->setCity($_POST['city']);
			$cinfo->setState($_POST['state']);
			$cinfo->setZipCode($_POST['zip_code']);
			$cinfo->setRemarks($_POST['remarks']);
			$cinfo->setPhone($_POST['phone']);
			$cinfo->setFax($_POST['fax']);
			$cinfo->setPagibigNumber($_POST['pagibig_number']);
			$cinfo->setSssNumber($_POST['sss_number']);
			$cinfo->setTinNumber($_POST['tin_number']);
			$cinfo->setPhilhealthNumber($_POST['philhealth_number']);			
			$cinfo->save($cstructure);
			
			$json['is_succes']= 1;
			$json['message']  = 'Record was successfully saved.' . $err;
			
		}else{
			$json['is_succes']= 0;
			$json['message']  = $err;
		}
		echo json_encode($json);
	}
	
	function update_skill()
	{
		if(!empty($_POST['skill_id'])){		
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			if( $cstructure ){
				$gss       	= G_Settings_Skills_Finder::findById($_POST['skill_id']);
				$gss->setSkill($_POST['skill']);
				$gss->save($cstructure);

				$return['is_success'] = true;
				$return['message']    = "Record saved";
			}else{
				$return['is_success'] = false;
				$return['message']    = "Invalid form entry";
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = "Invalid form entry";
		}

		echo json_encode($return);
	}

    function add_account()
	{
		Utilities::verifyFormToken($_POST['token']);
        //$user = G_User_Finder::findById($_POST['user_id']);
        $employee_id = Utilities::decrypt($_POST['employee_id']);
		$e    = G_Employee_Finder::findById($employee_id);

        $user = new G_User;
        $user->setEmployeeId($employee_id);
        $user->setUsername($_POST['username_update']);
        if ($_POST['password_update'] == ''){
		    $is_success = 2;
			$message    = 'Password must not be empty';
        } else if ($_POST['password_update'] != '' && ($_POST['password_update'] != $_POST['confirm_password_update'])) {
		    $is_success = 2;
			$message    = 'The password you entered did not match';
        } else {
        	if($_POST['password_update']=='') {
        		$password = $user->getPassword();
        	}else {
        		$password = Utilities::encryptPassword($_POST['password_update']);
        		$password_update = 1;
        	}
            $user->setPassword($password);
            $user->setCompanyStructureId($_SESSION['sprint_hr']['company_structure_id']);
            $user->setModule(implode(',', $_POST['module']));
        	if($e){
        		$user->setHash($e->getHash());
        	}
            $user->setDateModified(date("Y-m-d"));
            $insert_id = $user->save();

            if ($insert_id > 0) {
    		    $is_success = 1;
    		    $message    = 'User account is successfully added.';
            } else {
                $is_success = 2;
    		    $message    = 'An error occured please contact the administrator';
            }
        }
        $return['is_success'] = $is_success;
        $return['message'] = $message;
		echo json_encode($return);
	}

	function update_account()
	{
		Utilities::verifyFormToken($_POST['token']);
      $user = G_User_Finder::findById($_POST['user_id']);
		$e    = G_Employee_Finder::findById($user->getEmployeeId());
		//$j    = G_Employee_Job_History_Finder::findCurrentJob($e);
		if($user) {
			$user->setUsername($_POST['username_update']);

            if ($_POST['password_update'] != '' && ($_POST['password_update'] != $_POST['confirm_password_update'])) {
			    $is_success = 2;
			    $message    = 'The password you entered did not match';
            } else {
    			if($_POST['password_update'] == '') {
    				$password = $user->getPassword();
    			} else {
        		    $password = Utilities::encryptPassword($_POST['password_update']);
    			}

    			$user->setPassword($password);
    			$user->setModule(implode(',', $_POST['module']));
    			if($e){
    				$user->setHash($e->getHash());
    			}
    			$user->setDateModified(date("Y-m-d"));
    			$user->save();
    			$is_success = 1;
    			$message    = 'User account was successfully updated.';
            }
		}else {
			$is_success = 2;
			$message    = 'User not found';
		}

        $return['is_success'] = $is_success;
        $return['message'] = $message;
		echo json_encode($return);

	}
	
	function _check_username()
	{
		$username = $_POST['username'];
		$user_id = $_POST['user_id'];
		$user = G_User_Finder::findById($user_id);
		
		$is_username_taken = G_User_Finder::findByUsername($username);
		
		if($user->getUsername()==$username) {
			echo "";
		}elseif($is_username_taken) {
			echo $username. " is already taken";
		}else {
			if($username!='') {
				echo $username. " is available";				
			}
		}	
	}
	
	function add_skill()
	{
		if(!empty($_POST['skill'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			if( $cstructure ){
				$gss = new G_Settings_Skills();
				$gss->setSkill($_POST['skill']);
				$gss->save($cstructure);
				$return['is_success'] = true;
				$return['message']    = "Record saved";
 			}else{
				$return['is_success'] = false;
				$return['message']    = "Invalid form entry";
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = "Invalid form entry";
		}
		echo json_encode($return);
	}
	
	function add_subdivision_type()
	{	
		if(!empty($_POST['parent_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsst = new G_Settings_Subdivision_Type();
			$gsst->setType($_POST['type']);
			$gsst->save($cstructure);
			
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';
			
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
		
	}
	
	function update_subdivision_type()
	{	
		if(!empty($_POST['subdivision_id'])){		
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);		
			$gsst       = G_Settings_Subdivision_Type_Finder::findById($_POST['subdivision_id']);
			$gsst->setType($_POST['type']);
			$gsst->save($cstructure);
			
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';
			
		}else{
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';	
		}
		
		echo json_encode($json);
		
	}
	
	function add_company_branch()
	{	
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		if($_POST['eid']){
			$gcb = G_Company_Branch_Finder::findById(Utilities::decrypt($_POST['eid']));
		}else{
			$gcb = new G_Company_Branch();
			$gcb->setIsArchive(G_Company_Branch::NO);	
		}
		
		$gcb->setName($_POST['name']);
		$gcb->setProvince($_POST['province']);	
		$gcb->setCity($_POST['city']);				
		$gcb->setAddress($_POST['address']);
		$gcb->setZipCode($_POST['zip_code']);
		$gcb->setPhone($_POST['phone']);
		$gcb->setFax($_POST['fax']);
		$gcb->setLocationId($_POST['location_id']);
		
		if($_POST['eid']){
			$gcb->save($cstructure);
			$id = $gcb->getId();
		}else{
			$id = $gcb->save($cstructure);
		}
		
		if($id > 0){
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Error in SQL';
		}
		
		echo json_encode($return);		
	}
	
	function _json_get_datatable_branch()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
		$data       = G_Company_Branch_Finder::findAll();
		$result     = G_Company_Branch_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		
		header("Content-Type: application/json");
		echo "{\"recordsReturned\":{$records_returned}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data)  . "}";
	}
	
	function _load_subdivision_type_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$subdivision_type   = G_Settings_Subdivision_Type_Finder::findAllByCompanyStructureId($this->company_structure_id,$order,$limit);
		$total_records 		= G_Settings_Subdivision_Type_Helper::subCountTotalRecordsByCompanyStructureId($this->company_structure_id);
		
		foreach ($subdivision_type as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _load_dependent_relationship_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$dependent_relationship   = G_Settings_Dependent_Relationship_Finder::findAll($order_by,$limit);
		$total_records 		=  G_Settings_Subdivision_Type_Helper::countTotalRecords();
		
		foreach ($dependent_relationship as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _load_license_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$license		= G_Settings_License_Finder::findAll($order_by,$limit);
		$total_records 	=  G_Settings_License_Helper::countTotalRecords();
		
		foreach ($license as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}

	function _load_employees_enrolled_to_benefit_dt()
	{
		//$limit      = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];	
		//$order_by   = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;	

		$limit    = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results']; 
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$benefit_id = Utilities::decrypt($_GET['eid']);

		$b = new G_Employee_Benefits_Main();
		$b->setBenefitId($benefit_id);	
		$data 		   = $b->getAllEnrolledToBenefits($order_by, $limit);
		$total_records = $b->countTotalEnrolledToBenefit();

		foreach( $data as $key => $d){
			foreach($d as $sub_key => $value){				
				if( $sub_key == "id" ){
					$new_data[$key][$sub_key] = Utilities::encrypt($value);
				}else{
					$new_data[$key][$sub_key] = $value;
				}
			}
		}

		$total = count($new_data);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($new_data) . "}";	
	}

	function _load_employees_exclude_to_benefit_dt()
	{
		//$limit      = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];	
		//$order_by   = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;	

		$benefit_id = Utilities::decrypt($_GET['eid']);

		$b = new G_Employee_Benefits_Main();
		$b->setBenefitId($benefit_id);	
		$data 		   = $b->getAllEnrolledToBenefits();

		// $total_records = $b->countTotalEnrolledToBenefit();
		$excluded_employees_array = [];
		foreach( $data as $key => $d){
			if($d['applied_to'] == "Employment Status" && $d['excluded_emplooyee_id'] != "")
			 array_push($excluded_employees_array, $d['excluded_emplooyee_id']);
		}
		$excluded_employees = join(',', $excluded_employees_array);

		$e = G_Employee_Helper::findAllExcludedEmployeeById($excluded_employees);
		$total_records = count($e);
		$total = count($new_data);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo json_encode($e);	
		
	}

	function _load_benefits_dt()
	{

		$limit    = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];	
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;
		$field_cutoff = "(CASE cutoff WHEN " . G_Settings_Employee_Benefit::OCCURANCE_FIRST_CUTOFF . " THEN 'First cutoff' WHEN " . G_Settings_Employee_Benefit::OCCURANCE_SECOND_CUTOFF . " THEN 'Second cutoff' ELSE 'Every cutoff' END) AS cutoff";
		$fields   = array('id','code','name',"{$field_cutoff}",'amount','description','is_taxable');

		$b = new G_Settings_Employee_Benefit();	
		$benefits = $b->getAllRecordsIsNotArchive($order_by, $limit, $fields);
		
		foreach( $benefits as $key => $benefit){
			foreach($benefit as $sub_key => $value){				
				if( $sub_key == "id" ){
					$new_data[$key][$sub_key] = Utilities::encrypt($value);
				}else{
					$new_data[$key][$sub_key] = $value;
				}
			}
		}
		
		$total_records = $b->countTotalRecordsIsNotArchive();

		$total = count($new_data);
		$total_records = $total_records;

		//General Reports / Shr Audit Trail
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'PAYROLL', ACTION_LOAD, ' ('.$total_records.') Total Records of Benefits', '', '', '', 1, '', '');
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($new_data) . "}";	
	}
	
	function _load_pay_period_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$location		= G_Settings_Pay_Period_Finder::findAll($order_by,$limit);
		$total_records 	=  G_Settings_Pay_Period_Helper::countTotalRecords();
		echo $order_by;

		foreach ($location as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}

	function _load_request_approvers_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;
		$fields = array("id,title,approvers_name,requestors_name");

		$request_approvers = new G_Request_Approver();
		$data 		   = $request_approvers->getAllRequestApprovers($fields, $order_by, $limit);
		$data          = $request_approvers->encryptIds($data, "id");
		$total_records = $request_approvers->getTotalRecords();
		
		$total = count($data);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_location_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;		
		if($order_by == 'action asc'){$order_by = '';}
		
		$c 				= G_Company_Structure_Finder::findById($this->company_structure_id);
		if($c){
			$location		= G_Settings_Location_Finder::findAllByCompanyStructureId($this->company_structure_id,$order_by,$limit);
			$total_records 	= G_Settings_Location_Helper::countTotalRecordsByCompanyStructureId($c);
		}
		foreach ($location as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_membership_type_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$membership_type		= G_Settings_Membership_Type_Finder::findAll($order_by,$limit);
		$total_records 	=  G_Settings_Membership_Type_Helper::countTotalRecords();
		
		foreach ($membership_type as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_employment_status_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		//$employment_status		= G_Settings_Employment_Status_Finder::findAll($order_by,$limit);
		$csid = $this->company_structure_id;
		$employment_status		= G_Settings_Employment_Status_Finder::findByCompanyStructureId($csid,$order_by,$limit);
		//$total_records 			= G_Settings_Employment_Status_Helper::countTotalRecords();
		$total_records 			= G_Settings_Employment_Status_Finder::findByCompanyStructureId($csid,$order_by);
		
		foreach ($employment_status as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
			
		}				
		
		$data = $array;
		$total = count($array);
		$total_records = count($total_records);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_skill_management_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '' && $_GET['sort'] != 'action') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$skills					= G_Settings_Skills_Finder::findAll($order_by,$limit);
		$total_records 			=  G_Settings_Skills_Helper::countTotalRecords();
		
		foreach ($skills as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function update_company_branch()
	{	
		if(!empty($_POST['branch_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gcb = G_Company_Branch_Finder::findById($_POST['branch_id']);
			$gcb->setName($_POST['name']);
			$gcb->setProvince($_POST['province']);	
			$gcb->setCity($_POST['city']);				
			$gcb->setAddress($_POST['address']);
			$gcb->setZipCode($_POST['zip_code']);
			$gcb->setPhone($_POST['phone']);
			$gcb->setFax($_POST['fax']);
			$gcb->setLocationId($_POST['location_id']);
			$gcb->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function add_company_structure()
	{	
		if(!empty($_POST['parent_id'])){
			$name = $_POST['name'] . ' - ' . $_POST['s_type'];
			$gcs = new G_Company_Structure();	
			$gcs->setCompanyBranchId($_POST['company_branch_id']);
			$gcs->setTitle($name);	
			$gcs->setDescription($_POST['name']);				
			$gcs->setParentId($_POST['parent_id']);
			$gcs->setType($_POST['s_type']);
			$gcs->setIsArchive(G_Company_Structure::NO);			
			$gcs->save();
			
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';			
		}else{
			$return['is_success'] = 0;
			$return['message']    = 'Error in SQL';
		}		
		
		echo json_encode($return);
	}
	
	function add_department()
	{	
		if($_POST['company_branch_id']){
			if($_POST['eid']){
				$gcs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));	
			}else{
				$gcs = new G_Company_Structure();	
				$gcs->setCompanyBranchId(Utilities::decrypt($_POST['company_branch_id']));
				$gcs->setParentId($this->company_structure_id);
				$gcs->setType(G_Company_Structure::DEPARTMENT);
				$gcs->setIsArchive(G_Company_Structure::NO);			
			}
			
			$gcs->setTitle($_POST['name']);	
			$gcs->setDescription($_POST['description']);							
			$gcs->save();
			
			$return['branch_id']  = $_POST['company_branch_id'];
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';			
		}else{
			$return['is_success'] = 0;
			$return['message']    = 'Error in SQL';
		}		
		
		echo json_encode($return);
	}
	
	function add_group_team()
	{	
		if($_POST['parent_id']){
			$d = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['parent_id']));
			if($d){
				if($_POST['eid']){
					$gcs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));	
				}else{
					$gcs = new G_Company_Structure();	
					$gcs->setCompanyBranchId($d->getCompanyBranchId());
					$gcs->setParentId($d->getId());				
					$gcs->setIsArchive(G_Company_Structure::NO);			
				}
				
				$gcs->setType($_POST['type']);
				$gcs->setTitle($_POST['name']);	
				$gcs->setDescription($_POST['description']);							
				$gcs->save();
				
				$return['parent_id']  = Utilities::encrypt($d->getParentId());
				$return['branch_id']  = Utilities::encrypt($d->getCompanyBranchId());
				$return['is_success'] = 1;
				$return['message']    = 'Record Saved.';
			}else{
				$return['is_success'] = 0;
				$return['message']    = 'Error in SQL';
			}
		}else{
			$return['is_success'] = 0;
			$return['message']    = 'Error in SQL';
		}		
		
		echo json_encode($return);
	}

	function add_section()
	{	
		if($_POST['parent_id']){
			$d = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['parent_id']));
			if($d){
				if($_POST['eid']){
					$gcs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));	
				}else{
					$gcs = new G_Company_Structure();	
					$gcs->setType(G_Company_Structure::SECTION);
					$gcs->setCompanyBranchId($d->getCompanyBranchId());
					$gcs->setParentId($d->getId());				
					$gcs->setIsArchive(G_Company_Structure::NO);			
				}

				$gcs->setTitle($_POST['name']);	
				$gcs->setDescription($_POST['description']);							
				$gcs->save();
				
				$return['parent_id']  = Utilities::encrypt($d->getParentId());
				$return['branch_id']  = Utilities::encrypt($d->getCompanyBranchId());
				$return['is_success'] = 1;
				$return['message']    = 'Record Saved.';
			}else{
				$return['is_success'] = 0;
				$return['message']    = 'Error in SQL';
			}
		}else{
			$return['is_success'] = 0;
			$return['message']    = 'Error in SQL';
		}		
		
		echo json_encode($return);
	}
	
	function add_relationship()
	{	
		if(!empty($_POST['relationship'])){			
			$gsdr       = new G_Settings_Dependent_Relationship();
			$gsdr->setCompanyStructureId($this->company_structure_id);
			$gsdr->setRelationship($_POST['relationship']);				
			$gsdr->save();
			
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';
			
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';	
		}
		
		echo json_encode($return);		
	}
	
	function add_employment_status()
	{
		if(!empty($_POST['status'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			if($cstructure){	
				$gses       = new G_Settings_Employment_Status();
				$gses->setCode($_POST['code']);
				$gses->setStatus($_POST['status']);
				$gses->save($cstructure);			
			
				$return['is_success'] = 1;
				$return['message']    = 'Record Saved.';
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';	
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';	
		}		
		echo json_encode($return);		
	}
	
	function update_employment_status()
	{
		if(!empty($_POST['employment_status_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			if($cstructure){	
				$gses = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
				if($gses){
					$gses->setCode($_POST['code']);
					$gses->setStatus($_POST['status']);				
					$gses->save($cstructure);				
	
					$return['is_success'] = 1;
					$return['message']    = 'Record Saved.';
				}else{
					$return['is_success'] = 2;
					$return['message']    = 'Record cannot be save.';	
				}
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';	
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';	
		}	
		echo json_encode($return);			
	}
	
	function add_license()
	{	
		if(!empty($_POST['license_type'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsdr       = new G_Settings_License();
			$gsdr->setLicenseType($_POST['license_type']);
			$gsdr->setDescription($_POST['description']);				
			$gsdr->save($cstructure);
			//echo 'true';
			$return['is_success'] = 1;
			$return['message']    = 'Record saved.';
		}else{//echo 'false';   
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';}	
		echo json_encode($return);		
	}

	function add_pay_period()
	{
		if( !empty($_POST['pay_period_name']) && !empty($_POST['first_cutoff_a']) && !empty($_POST['first_cutoff_b']) && !empty($_POST['first_cutoff_payday']) && !empty($_POST['second_cutoff_a']) && !empty($_POST['second_cutoff_b']) && !empty($_POST['second_cutoff_payday']) ){
			//if( ($_POST['first_cutoff_a'] <= $_POST['first_cutoff_b']) && ($_POST['second_cutoff_a'] <= $_POST['second_cutoff_b']) && ($_POST['second_cutoff_a'] > $_POST['first_cutoff_b']) ){
				//Create cutoff and payout day format	
				$cutoff     = $_POST['first_cutoff_a'] . "-" . $_POST['first_cutoff_b'] . "," . $_POST['second_cutoff_a'] . "-" . $_POST['second_cutoff_b'];
				$payout_day = $_POST['first_cutoff_payday']	. "," . $_POST['second_cutoff_payday'];	

				$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
				if( $cstructure ){
					$gspp       = new G_Settings_Pay_Period();
					if($_POST['is_default'] == G_Settings_Pay_Period::IS_DEFAULT){
						$gspp->setAllNotDefault($cstructure);
					}
					$gspp->setPayPeriodCode($_POST['pay_period_code']);
					$gspp->setPayPeriodName($_POST['pay_period_name']);	
					$gspp->setCutOff($cutoff);	
					$gspp->setPayOutDay($payout_day);
					$gspp->setIsDefault($_POST['is_default']);	
					$gspp->save($cstructure);
				
					$return['is_success'] = true;
					$return['message']    = 'Record Saved'; 

				}else{
					$return['is_success'] = false;
					$return['message']    = 'Invalid form entry'; 
				}
			/*}else{
				$return['is_success'] = false;
				$return['message']    = 'Invalid form entry'; 
			}*/

		}else{
			$return['is_success'] = false;
			$return['message']    = 'Invalid form entry'; 
		}	
		echo json_encode($return);	
	}
	
	function update_pay_period()
	{	
	 if($_POST['frequency'] == 1){
		if($_POST['pay_period_code'] == "BMO"){
			if( !empty($_POST['pay_period_name']) && !empty($_POST['first_cutoff_a']) && !empty($_POST['first_cutoff_b']) &&  !empty($_POST['first_cutoff_payday']) && !empty($_POST['second_cutoff_a']) &&!empty($_POST['second_cutoff_b']) && !empty($_POST['second_cutoff_payday']) ){

				$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
				$gspp       = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
				if($gspp){

					$year = date("Y");
					$count_locked_cutoff = G_Cutoff_Period_Helper::countTotalLockedCutoffByYear($year);

					if($count_locked_cutoff <= 0 ) {
						$cutoff     = $_POST['first_cutoff_a'] . "-" . $_POST['first_cutoff_b'] . "," . $_POST['second_cutoff_a'] . "-" . $_POST['second_cutoff_b'];
						$payout_day = $_POST['first_cutoff_payday']	. "," . $_POST['second_cutoff_payday'];	

						//if($_POST['is_default'] == G_Settings_Pay_Period::IS_DEFAULT){
						$gspp->setAllNotDefault($cstructure);
						//}

						//$gspp->setPayPeriodCode($_POST['pay_period_code']);
						$gspp->setPayPeriodName($_POST['pay_period_name']);	
						$gspp->setCutOff($cutoff);	
						$gspp->setPayOutDay($payout_day);
						$gspp->setIsDefault(G_Settings_Pay_Period::IS_DEFAULT);	
						$gspp->save($cstructure);

						//Reset Cutoff Period					
						$data[1]['a']      = $_POST['first_cutoff_a'];
						$data[1]['b']      = $_POST['first_cutoff_b'];
						$data[1]['payday'] = $_POST['first_cutoff_payday'];
						$data[2]['a']      = $_POST['second_cutoff_a'];
						$data[2]['b']      = $_POST['second_cutoff_b'];
						$data[2]['payday'] = $_POST['second_cutoff_payday'];
						
						
						$c = new G_Cutoff_Period();
						$return = $c->deleteAllByYear($year)->setNumberOfMonths(12)->generateIniCutOffPeriods($data);					

						$return['is_success'] = true;
						$return['message']    = 'Record Saved'; 
					} else { 
						$return['is_success'] = true;
						$return['message']    = 'Cannot update pay periods, current year already have locked period/s.'; 
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = 'Invalid form entry'; 
				}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Invalid form entry';
		}	
	}else{

			if( !empty($_POST['pay_period_name']) &&  !empty($_POST['first_cutoff_a']) &&  !empty($_POST['first_cutoff_b']) &&  !empty($_POST['first_cutoff_payday'])  ){

				$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
				$gspp       = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
				if($gspp){

					$year = date("Y");
					$count_locked_cutoff = G_Cutoff_Period_Helper::countTotalLockedCutoffByYear($year);

					if($count_locked_cutoff <= 0 ) {
						$cutoff     = $_POST['first_cutoff_a'] . "-" . $_POST['first_cutoff_b'] . "," . $_POST['second_cutoff_a'] . "-" . $_POST['second_cutoff_b'];
						$payout_day = $_POST['first_cutoff_payday']	. "," . $_POST['second_cutoff_payday'];	

						//if($_POST['is_default'] == G_Settings_Pay_Period::IS_DEFAULT){
						$gspp->setAllNotDefault($cstructure);
						//}

						//$gspp->setPayPeriodCode($_POST['pay_period_code']);
						$gspp->setPayPeriodName($_POST['pay_period_name']);	
						$gspp->setCutOff($cutoff);	
						$gspp->setPayOutDay($payout_day);
						$gspp->setIsDefault(0);	
						$gspp->save($cstructure);

						//Reset Cutoff Period					
						$data[1]['a']      = $_POST['first_cutoff_a'];
						$data[1]['b']      = $_POST['first_cutoff_b'];
						$data[1]['payday'] = $_POST['first_cutoff_payday'];
						$data[2]['a']      = $_POST['second_cutoff_a'];
						$data[2]['b']      = $_POST['second_cutoff_b'];
						$data[2]['payday'] = $_POST['second_cutoff_payday'];
						
						
						$c = new G_Cutoff_Period();
						$return = $c->deleteAllByYear($year)->setNumberOfMonths(12)->generateIniCutOffPeriods($data);					

						$return['is_success'] = true;
						$return['message']    = 'Record Saved'; 
					} else { 
						$return['is_success'] = true;
						$return['message']    = 'Cannot update pay periods, current year already have locked period/s.'; 
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = 'Invalid form entry'; 
				}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Invalid form entry';
		}	
		
	}
		}elseif ($_POST['frequency']== 2) {
		

				if(!empty($_POST['pay_period_name']) && !empty($_POST['first_cutoff_a']) &&	!empty($_POST['first_cutoff_b']) &&
		   		!empty($_POST['first_cutoff_payday'])){

							$year = date("Y");
							$count_locked_cutoff = G_Weekly_Cutoff_Period_Helper::countTotalLockedCutoffByYear($year);

							$pay_period_id = $_POST['pay_period_id'];
							$pay_period_name = $_POST['pay_period_name'];
							$start_day = $_POST['first_cutoff_a'];
							$end_day = $_POST['first_cutoff_b'];
							$pay_day = $_POST['first_cutoff_payday'];
							$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);

							$gspp       = G_Settings_Pay_Period_Finder::findById($pay_period_id);	

								$created  = date("Y-m-d H:i:s");
								$string_date = "1 January".$year;

      						    $given_year = strtotime($string_date);

							if($count_locked_cutoff <= 0){
								
								//echo "reset all";
								$reset_all = G_Weekly_Cutoff_Period_Manager::deleteAllByYear($year);
								
                              $cw = new G_Weekly_Cutoff_Period_Helper(); 
                              $generate = $cw->generateWeeklyCutoffPeriods($created,$given_year,$start_day,$count_locked_cutoff);

					        	$cutoff = $start_day ." - ".$end_day; 
					        	// $gspp->setAllNotDefault($cstructure);
									$gspp->setPayPeriodName($pay_period_name);	
									$gspp->setCutOff($cutoff);	
									$gspp->setPayOutDay($pay_day);
									
									$gspp->save($cstructure);

								
        
      							  $return['is_success'] = true;
								$return['message']    = 'Record Saved'; 
        
        
							}else{

								/*$reset_all = G_Weekly_Cutoff_Period_Manager::deleteAllByYearAndLock($year);
								$cw = new G_Weekly_Cutoff_Period_Helper(); 
        						$generate = $cw->generateWeeklyCutoffPeriods($created,$given_year,$start_day,$count_locked_cutoff);

       						       $cutoff = $start_day ." - ".$end_day; 
        							$gspp->setAllNotDefault($cstructure);
									$gspp->setPayPeriodName($pay_period_name);	
									$gspp->setCutOff($cutoff);	
									$gspp->setPayOutDay($pay_day);
								
									$gspp->save($cstructure);

       							 $return['is_success'] = true;
								$return['message']    = 'Record Saved'; */


								$return['is_success'] = true;
								$return['message']    = 'Cannot update pay periods, current year already have locked period/s.';

								//echo "not all";
							}




				}else{

						$return['is_success'] = false;
						$return['message']    = 'Invalid form entry';
				}
		}

		else if ($_POST['frequency'] == 3){


		 if( !empty($_POST['pay_period_name']) &&
		 !empty($_POST['first_cutoff_a']) &&
		  !empty($_POST['first_cutoff_b']) &&
		   !empty($_POST['first_cutoff_payday'])  ){

				$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
				$gspp       = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
				if($gspp){

					$year = new DateTime();
					$year = $year->format('Y');
					//$year = date("Y");
					$count_locked_cutoff = G_Monthly_Cutoff_Period_Helper::countTotalLockedCutoffByYear($year);

					if($count_locked_cutoff <= 0 ) {
						$cutoff     = $_POST['first_cutoff_a'] . "-" . $_POST['first_cutoff_b'] ;
						$payout_day = $_POST['first_cutoff_payday'];	

						//if($_POST['is_default'] == G_Settings_Pay_Period::IS_DEFAULT){
						//$gspp->setAllNotDefault($cstructure);
						//}

						//$gspp->setPayPeriodCode($_POST['pay_period_code']);
						$gspp->setPayPeriodName($_POST['pay_period_name']);	
						$gspp->setCutOff($cutoff);	
						$gspp->setPayOutDay($payout_day);
						//$gspp->setIsDefault(0);	
						$gspp->save($cstructure);

						//Reset Cutoff Period					
						$data[1]['a']      = $_POST['first_cutoff_a'];
						$data[1]['b']      = $_POST['first_cutoff_b'];
						$data[1]['payoutday'] = $_POST['first_cutoff_payday'];
						
						
						
						$c = new G_Monthly_Cutoff_Period();
						$return = $c->deleteAllByYear($year)->setNumberOfMonths(12)->generateIniCutOffPeriods($data);					

						$return['is_success'] = true;
						$return['message']    = 'Record Saved'; 
					} else { 
						$return['is_success'] = true;
						$return['message']    = 'Cannot update pay periods, current year already have locked period/s.'; 
					}

				}else{
					$return['is_success'] = false;
					$return['message']    = 'Invalid form entry'; 
				}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Invalid form entry';
		}	

		}



		echo json_encode($return);	
	}
	
	function update_license()
	{	
		if(!empty($_POST['license_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsl        = G_Settings_License_Finder::findById($_POST['license_id']);
			$gsl->setLicenseType($_POST['license_type']);
			$gsl->setDescription($_POST['description']);				
			$gsl->save($cstructure);
			$return['is_success'] = 1;
			$return['message']    = 'Record saved.';
		}else{//echo 'false';   
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';}
		echo json_encode($return);		
	}
	
	function add_location()
	{	
		if(!empty($_POST['location'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			if($cstructure){	
				$location   = strtoupper($_POST['location']);
				$code		= strtoupper($_POST['code']);
				
				$gsl = new G_Settings_Location();				
				$gsl->setCode($code);
				$gsl->setLocation($location);				
				$gsl->save($cstructure);
				
				$return['is_success'] = 1;
				$return['message']    = 'Record saved.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';
		}		
		echo json_encode($return);
	}

	function add_membership_type()
	{	
		if(!empty($_POST['type'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			if($cstructure){	
				$gsmt       = new G_Settings_Membership_Type();		
				$gsmt->setType($_POST['type']);						
				$gsmt->save($cstructure);
		
				$return['is_success'] = 1;
				$return['message']    = 'Record saved.';
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be saved.';
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be saved.';
		}	
		echo json_encode($return);	
	}
	
	function update_membership_type()
	{	
		if(!empty($_POST['membership_type_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			if($cstructure){
				$gsmt       = G_Settings_Membership_Type_Finder::findById($_POST['membership_type_id']);		
				$gsmt->setType($_POST['type']);						
				$gsmt->save($cstructure);
			
				$return['is_success'] = 1;
				$return['message']    = 'Record saved.';
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be saved.';
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be saved.';
		}	
		echo json_encode($return);	
	}
	
	function update_location()
	{	
		if(!empty($_POST['location_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			if($cstructure){
				$location   = strtoupper($_POST['location']);
				$code		= strtoupper($_POST['code']);
				
				$gsl        = G_Settings_Location_Finder::findById($_POST['location_id']);
				if($gsl){
					$gsl->setCode($code);
					$gsl->setLocation($location);				
					$gsl->save($cstructure);
					
					$return['is_success'] = 1;
					$return['message']    = 'Record saved.';
				}else{
					$return['is_success'] = 2;
					$return['message']    = 'Record cannot be save.';			
				}
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';		
			}
			
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';
		}	
		
		echo json_encode($return);	
	}
	
	function update_relationship()
	{	
		if(!empty($_POST['dependent_id'])){			
			$gsdr       = G_Settings_Dependent_Relationship_Finder::findById($_POST['dependent_id']);			
			if($gsdr){
				$gsdr->setRelationship($_POST['relationship']);				
				$gsdr->save();
				
				$return['is_success'] = 1;
				$return['message']    = 'Record saved.';
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';
			}
			
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';
		}
		
		echo json_encode($return);
	}
	
	function _load_delete_dependent_confirmation()
	{
		if(!empty($_POST['dependent_id'])){
			$d = G_Settings_Dependent_Relationship_Finder::findById($_POST['dependent_id']);
			if($d){	
				$this->var['dependent'] = $d->getRelationship();
				$this->view->noTemplate();
				$this->view->render('settings/options/dependent_relationship/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_license_confirmation()
	{
		if(!empty($_POST['license_id'])){
			$l = G_Settings_License_Finder::findById($_POST['license_id']);
			if($l){	
				$this->var['license'] = $l->getLicenseType();
				$this->view->noTemplate();
				$this->view->render('settings/options/license/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_location_confirmation()
	{
		if(!empty($_POST['location_id'])){
			$l = G_Settings_Location_Finder::findById($_POST['location_id']);
			if($l){	
				$this->var['location'] = $l->getLocation();
				$this->view->noTemplate();
				$this->view->render('settings/options/location/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_membership_type_confirmation()
	{
		if(!empty($_POST['membership_type_id'])){
			$m = G_Settings_Membership_Type_Finder::findById($_POST['membership_type_id']);
			if($m){	
				$this->var['membership'] = $m->getType();
				$this->view->noTemplate();
				$this->view->render('settings/options/membership_type/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_pay_period_confirmation()
	{
		if(!empty($_POST['pay_period_id'])){
			$pp = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
			if($pp){	
				$this->var['pay_period'] = $pp->getPayPeriodName();
				$this->view->noTemplate();
				$this->view->render('settings/options/pay_period/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_subdivision_confirmation()
	{
		if(!empty($_POST['subdivision_id'])){
			$s = G_Settings_Subdivision_Type_Finder::findById($_POST['subdivision_id']);
			if($s){	
				$this->var['subdivision'] = $s->getType();
				$this->view->noTemplate();
				$this->view->render('settings/options/subdivision_type/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_skill_confirmation()
	{
		if(!empty($_POST['skill_id'])){
			$s = G_Settings_Skills_Finder::findById($_POST['skill_id']);
			if($s){	
				$this->var['skill'] = $s->getSkill();
				$this->view->noTemplate();
				$this->view->render('settings/options/skill_management/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function _load_delete_employment_status_confirmation()
	{
		if(!empty($_POST['employment_status_id'])){
			$es = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
			if($es){	
				$this->var['employment_status'] = $es->getStatus();
				$this->view->noTemplate();
				$this->view->render('settings/options/employment_status/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function delete_job_employment_status()
	{
		$job_employment_status_id = (int) $_POST['job_employment_status_id'];	
		$j = G_Job_Employment_Status_Finder::findById($job_employment_status_id);
		$j->delete();
		echo 1;
		
	}
	
	
	function _load_delete_job_employment_status_confirmation()
	{
	
		if(!empty($_POST['job_employment_status_id'])){
		
			$job_employment_status = G_Job_Employment_Status_Finder::findById($_POST['job_employment_status_id']);
			$job = G_Job_Finder::findById($job_employment_status->getJobId());
			
			if($job_employment_status){	
				echo "test " . $job_employment_status->countEmployee();
				$this->var['job_name'] = $job->title;
				
				$this->var['employment_status'] = $job_employment_status->getEmploymentStatus();
				$this->view->noTemplate();
				$this->view->render('settings/job/job_employment_status/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function delete_dependent()
	{	
		if(!empty($_POST['dependent_id'])){
				$gsdr = G_Settings_Dependent_Relationship_Finder::findById($_POST['dependent_id']);				
				$gsdr->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_membership_type()
	{	
		if(!empty($_POST['membership_type_id'])){
				$gsmt = G_Settings_Membership_Type_Finder::findById($_POST['membership_type_id']);				
				$gsmt->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_license()
	{	
		if(!empty($_POST['license_id'])){
				$gsl = G_Settings_License_Finder::findById($_POST['license_id']);				
				$gsl->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_location()
	{	
		if(!empty($_POST['location_id'])){
				$gsl          = G_Settings_Location_Finder::findById($_POST['location_id']);				
				$gsl->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_skill()
	{
		if(!empty($_POST['skill_id'])){
				$gss = G_Settings_Skills_Finder::findById($_POST['skill_id']);				
				$gss->delete();
				echo 1;
		}else{echo 0;}		
	}
	
	function delete_pay_period()
	{
		if(!empty($_POST['pay_period_id'])){
				$gsst = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);				
				$gsst->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}		
	}
	
	function delete_subdivision()
	{	
		if(!empty($_POST['subdivision_id'])){
				$gsst = G_Settings_Subdivision_Type_Finder::findById($_POST['subdivision_id']);				
				$gsst->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}		
	}
	
	function delete_employment_status()
	{
		if(!empty($_POST['employment_status_id'])){
				$gses = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);				
				$gses->delete();
				echo 1;
				//$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				//if($count_member == 0){
				//	$gcs->delete();
				//	echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_structure()
	{	
		if(!empty($_POST['structure_id'])){
				$gcs          = G_Company_Structure_Finder::findById($_POST['structure_id']);				
				$count_member = G_Employee_Helper::countTotalRecordsByCompanyStructureId($gcs);
				if($count_member == 0){
					$gcs->setIsArchive(G_Company_Structure::YES);
					$gcs->archive();
					echo 1;
				}else{echo 2;}			
		}else{echo 0;}
		
	}
	
	function delete_branch()
	{	
		if(!empty($_POST['branch_id'])){
				$b            = G_Company_Branch_Finder::findById($_POST['branch_id']);					
				$count_member = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($b->getId());
				if($count_member == 0){
					$b->delete();
					$return['is_success'] = 1;
					$return['message']    = 'Record was successfully deleted.'; 
				}else{
					$return['is_success'] = 2;	
					$return['message']    = 'Error in SQL'; 
				}			
		}else{
			$return['is_success'] = 2;	
			$return['message']    = 'No Record to Delete'; 
		}
		
		echo json_encode($return);

	}	
	
	function delete_job_title()
	{	
		if(!empty($_POST['job_title_id'])){
				$j = G_Job_Finder::findById($_POST['job_title_id']);		
				//Enable and set to Employee		
				//$count_member = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($b->getId());
				//if($count_member == 0){
					$j->delete();
					echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}	
	
	function delete_job_eeo_category()
	{	
		if(!empty($_POST['job_eeo_category_id'])){
				$ej = G_Eeo_Job_Category_Finder::findById($_POST['job_eeo_category_id']);		
				//Enable and set to Employee		
				//$count_member = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($b->getId());
				//if($count_member == 0){
					$ej->delete();
					echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}	
	
	function delete_job_salary_rate()
	{	
		if(!empty($_POST['salary_rate_id'])){
				$sr = G_Job_Salary_Rate_Finder::findById($_POST['salary_rate_id']);		
				//Enable and set to Employee		
				//$count_member = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($b->getId());
				//if($count_member == 0){
					$sr->delete();
					echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}	
	
	function delete_job_specification()
	{	
		if(!empty($_POST['job_specification_id'])){
				$js = G_Job_Specification_Finder::findById($_POST['job_specification_id']);		
				//Enable and set to Employee		
				//$count_member = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($b->getId());
				//if($count_member == 0){
					$js->delete();
					echo 1;
				//}else{echo 2;}			
		}else{echo 0;}
		
	}	

	function branch()
	{	
		Jquery::loadMainInlineValidation();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainTipsy();
		Yui::loadMainDataTable();
		$this->var['page_title'] 	= 'Settings';
		$this->var['branch_sb']		= 'selected';
		$this->var['module_title']	= 'Branch';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/branch/index.php',$this->var);
		
	}

	function _load_branch_list()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$branches   = G_Company_Branch_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['branches'] = $branches;
		$this->view->noTemplate();
		$this->view->render('settings/branch/branch_list.php',$this->var);
	}
	
	function _load_branch_list_dt() {	
			
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$branches   = G_Company_Branch_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit);
		$total_records =  G_Company_Branch_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		foreach ($branches as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _load_location_list()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['locations'] = $locations;
		$this->view->noTemplate();
		$this->view->render('settings/options/location/location_list.php',$this->var);
	}
	
	function _load_membership_type_list()
	{
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$memberships = G_Settings_Membership_Type_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['memberships'] = $memberships;
		$this->view->noTemplate();
		$this->view->render('settings/options/membership_type/membership_list.php',$this->var);
	}
	
	function _load_subdivision_list()
	{
		$cstructure   = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$subdivisions = G_Settings_Subdivision_Type_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['subdivisions'] = $subdivisions;
		$this->view->noTemplate();
		$this->view->render('settings/options/subdivision_type/subdivision_list.php',$this->var);
	}
	
	function _load_dependent_relationship()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$dependents = G_Settings_Dependent_Relationship_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['dependents'] = $dependents;
		$this->view->noTemplate();
		$this->view->render('settings/options/dependent_relationship/dependent_list.php',$this->var);
	}
	
	function _load_license_list()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$licenses   = G_Settings_License_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['licenses'] = $licenses;
		$this->view->noTemplate();
		$this->view->render('settings/options/license/license_list.php',$this->var);
	}
	
	function _load_add_new_branch()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
		$this->var['locations'] = $locations;
		$this->var['p_id']      = $cstructure->getId();
		$this->view->noTemplate();		
		$this->view->render('settings/branch/forms/add_new_branch.php',$this->var);
	}
	
	function _load_add_new_pay_period()
	{
		$this->var['action_pay_period'] = url('settings/add_pay_period');
		$this->view->noTemplate();		
		$this->view->render('settings/options/pay_period/forms/add_new_pay_period.php',$this->var);
	}
	
	function _load_add_new_location()
	{	
		$this->view->render('settings/options/location/forms/add_new_location.php',$this->var);
	}
	
	function _load_add_new_membership()
	{	
		$this->view->render('settings/options/membership_type/forms/add_new_membership.php',$this->var);
	}
	
	function _load_edit_location()
	{		
		if(!empty($_POST['id'])){			
			$l = G_Settings_Location_Finder::findById($_POST['id']);
			$this->var['l']    = $l;			
			$this->view->render('settings/options/location/forms/edit_location.php',$this->var);
		}
	}
	
	function _load_edit_membership_type()
	{		
		if(!empty($_POST['id'])){			
			$m = G_Settings_Membership_Type_Finder::findById($_POST['id']);
			$this->var['m']    = $m;			
			$this->view->render('settings/options/membership_type/forms/edit_membership_type.php',$this->var);
		}
	}
	
	function _load_edit_employment_status()
	{
		if(!empty($_POST['id'])){			
			$es = G_Settings_Employment_Status_Finder::findById($_POST['id']);
			$this->var['es']    = $es;			
			$this->view->render('settings/options/employment_status/forms/edit_employment_status.php',$this->var);
		}		
	}
	
	function _load_add_new_skill()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$this->var['p_id'] = $cstructure->getId();
 		$this->view->noTemplate();		
		$this->view->render('settings/options/skill_management/forms/add_skill.php',$this->var);
	}
	
	function _load_add_new_subdivision_type()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$this->var['p_id'] = $cstructure->getId();
 		$this->view->noTemplate();		
		$this->view->render('settings/options/subdivision_type/forms/add_subdivision_type.php',$this->var);
	}
	
	function _load_add_job_employment_status()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$this->var['p_id'] = $cstructure->getId();
		
		$job = G_Job_Finder::findByCompanyStructureId2($cstructure->getId());
		$employment_status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($cstructure->getId());

		
		$this->var['job'] = $job;
		$this->var['employment_status'] = $employment_status;
		
 		$this->view->noTemplate();		
		$this->view->render('settings/job/job_employment_status/add_job_employment_status.php',$this->var);
	}
	
	function add_job_employment_status() 
	{
		echo "company structure id: " . $this->company_structure_id;
		print_r($_POST);
		if(!empty($_POST)){
		$j = new G_Job_Employment_Status();
		$j->setCompanyStructureId($this->company_structure_id);
		$j->setJobId($_POST['job_id']);
		$j->setEmploymentStatus($_POST['status']);	
		$j->save();
		/*$g = new G_Job();	
		$g->setCompanyStructureId($this->company_structure_id);
		$g->setJobSpecificationId($_POST['job_specification_id']);	
		$g->setTitle($_POST['title']);		
		$g->setIsActive($_POST['is_active']);
		$g->save();
		// redirect('settings/job_title');
		echo 'true';*/
		}else{echo 'false';}	
	}
	
	
	function _load_add_new_employment_status()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$this->var['p_id'] = $cstructure->getId(); 		
		$this->view->render('settings/options/employment_status/forms/add_employment_status.php',$this->var);
	}
	
	function _load_edit_skill_management()
	{
		if(!empty($_POST['skill_id'])){			
			$s    	    = G_Settings_Skills_Finder::findById($_POST['skill_id']);
			$this->var['s']    = $s;
			$this->view->noTemplate();		
			$this->view->render('settings/options/skill_management/forms/edit_skill.php',$this->var);
		}
	}
	
	function _load_edit_subdivision()
	{
		if(!empty($_POST['id'])){			
			$s    	    = G_Settings_Subdivision_Type_Finder::findById($_POST['id']);
			$this->var['s']    = $s;
			$this->view->noTemplate();		
			$this->view->render('settings/options/subdivision_type/forms/edit_subdivision_type.php',$this->var);
		}
	}
	
	function _load_edit_branch()
	{
		if(!empty($_POST['branch_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			$b          = G_Company_Branch_Finder::findById($_POST['branch_id']);
			$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
			$this->var['locations'] = $locations;
			$this->var['b']         = $b;
			$this->view->noTemplate();		
			$this->view->render('settings/branch/forms/edit_branch.php',$this->var);
		}
	}
	
	function _load_edit_dependent()
	{
		if(!empty($_POST['id'])){			
			$d          	= G_Settings_Dependent_Relationship_Finder::findById($_POST['id']);						
			$this->var['d'] = $d;
			$this->view->noTemplate();		
			$this->view->render('settings/options/dependent_relationship/forms/edit_relationship.php',$this->var);
		}
	}
	
	function _load_edit_pay_period()
	{
		if(!empty($_POST['pay_period_id'])){			
			$pp          = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);	
			if( $pp ){									
				$payoutday     = explode(",", $pp->getPayOutDay());
				$cutoff        = explode(",", $pp->getCutOff());
				$first_cutoff  = explode("-",$cutoff[0]);
				$second_cutoff = explode("-",$cutoff[1]);

				$days_a_week = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				$this->var['days_a_week'] = $days_a_week;
				$this->var['payoutday']		    = $payoutday;
				$this->var['first_cutoff']      = $first_cutoff;
				$this->var['second_cutoff']     = $second_cutoff;
				$this->var['pp'] 				= $pp;
				$this->var['action_pay_period'] = url('settings/update_pay_period');
				$this->view->noTemplate();		
				$this->view->render('settings/options/pay_period/forms/edit_pay_period.php',$this->var);
			}else{
				echo "Object not found!";
			}
		}
	}
	
	function _load_edit_license()
	{
		if(!empty($_POST['license_id'])){			
			$l          = G_Settings_License_Finder::findById($_POST['license_id']);						
			$this->var['l'] = $l;
			$this->view->noTemplate();		
			$this->view->render('settings/options/license/forms/edit_license.php',$this->var);
		}
	}
	
	function job()
	{
		Yui::loadMainDatatable();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation();
		Jquery::loadMainModalExetend();
		$this->var['page_title'] 	= 'Settings';
		$this->var['job_sb']		= 'selected';
		$this->var['module_title']	= 'Job';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/job/index.php',$this->var);
		
	}
	
	function _load_job_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort']=='action') {
			$_GET['sort'] = 'g_job.id';
		}
		
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

	
		
		$cstructure    = G_Company_Structure_Finder::findById($this->company_structure_id);			
		$array 		   = G_Job_Helper::sqlFindByCompanyStructureId($cstructure->getId(), $order_by,$limit);
		$total_records = G_Job_Helper::countTotalRecordsByCompanyStructureId($cstructure);
				
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_job_specification_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure    = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$job_specification  = G_Job_Specification_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit); //$cstructure->getId()
		$total_records = G_Job_Specification_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		
		//print_r($job);
		foreach ($job_specification as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}		
	
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_job_employment_status_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure    = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$job           = G_Job_Employment_Status_Finder::findByCompanyStructureIdSub($cstructure->getId(), $order_by,$limit); //$cstructure->getId()
		$total_records = G_Job_Employment_Status_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		//print_r($job);
		foreach ($job as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
		
		//print_r($array);
	
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_eeo_job_list_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure    			= G_Company_Structure_Finder::findById($this->company_structure_id);	
		$job_specification      = G_Eeo_Job_Category_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit); //$cstructure->getId()
		$total_records 			= G_Eeo_Job_Category_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		
		//print_r($job);
		foreach ($job_specification as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}		
	
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_job_rate_list_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure    			= G_Company_Structure_Finder::findById($this->company_structure_id);	
		$job_rate			    = G_Job_Salary_Rate_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit); 
		$total_records 			= G_Job_Salary_Rate_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		
		//print_r($job);
		foreach ($job_rate as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}		
	
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_branch_dt() 
	{
		$limit    = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$cstructure    = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$branch        = G_Company_Branch_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit); //$cstructure->getId()
		$total_records = G_Company_Branch_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		//print_r($job);
		foreach ($branch as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}		
	
		$data  = $array;	
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	
	function job_title()
	{
		$spec = G_Job_Specification_Finder::findAll();
		$this->var['spec'] = $spec;	
		$this->view->render('settings/job/job_title/add_job_title.php',$this->var);
	}
	
	function edit_job_title()
	{	

		$this->var['job_info'] = G_Job_Finder::findById($_POST['id']);
		$spec = G_Job_Specification_Finder::findAll();
		$this->var['spec'] = $spec;	
		$this->view->render('settings/job/job_title/edit_job_title.php',$this->var);
	}
	
	function add_job_title()
	{
		if(!empty($_POST)){
		$g = new G_Job();	
		$g->setCompanyStructureId($this->company_structure_id);
		$g->setJobSpecificationId($_POST['job_specification_id']);	
		$g->setTitle($_POST['title']);		
		$g->setIsActive(G_Job::ACTIVE);
		$g->save();		
		echo 'true';
		}else{echo 'false';}		
		
	}
	
	function update_job_title()
	{
		if(!empty($_POST)){
		$g = G_Job_Finder::findById($_POST['id']);
			if( $g ){
				$g->setCompanyStructureId($this->company_structure_id);
				$g->setJobSpecificationId($_POST['job_specification_id']);	
				$g->setTitle($_POST['title']);		
				//$g->setIsActive($_POST['is_active']);
				$g->save($g);
				echo 'true';
			}else{
				echo 'false';
			}
		}else{echo 'false';}		
		
	}
	
	function add_job()
	{
		$g = new G_Job();	
		$g->setCompanyStructureId($this->company_structure_id);
		$g->setJobSpecificationId(1);	
		$g->setTitle('test');		
		$g->setIsActive(1);
		$g->save();
	}
	
 	function job_specification()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
		
		$this->view->render('settings/job/job_specification/add_job_specification.php',$this->var);
	}
	
	function edit_job_specification()
	{
		$this->var['job_spec_info'] = G_Job_Specification_Finder::findById($_POST['id']);
		
		$this->view->render('settings/job/job_specification/edit_job_specification.php',$this->var);
	}
	
	
	function add_job_specification()
	{
		if(!empty($_POST)){
			$g = new G_Job_Specification();	
			$g->setCompanyStructureid($this->company_structure_id);
			$g->setName($_POST['name']);		
			$g->setDescription($_POST['description']);
			$g->setDuties($_POST['duties']);
			$g->save();
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
	
	function update_job_specification()
	{
		if(!empty($_POST)){
			$g = G_Job_Specification_Finder::findById($_POST['id']);
			$g->setCompanyStructureid($this->company_structure_id);
			$g->setName($_POST['name']);		
			$g->setDescription($_POST['description']);
			$g->setDuties($_POST['duties']);
			$g->save($g);
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
	
	function edit_job_employment_status()
	{
		$job = G_Job_Finder::findById($_POST['sid']);
		
		$this->var['job'] = $job;		
		$this->view->render('settings/job/job_employment_status/edit_job_employment_status.php',$this->var);
	}
	
	function update_job_employment_status()
	{
		if(!empty($_POST)){
			$g = G_Job_Employment_Status_Finder::findById($_POST['status_id']);
			$g->setJobId($_POST['job_id']);
			$g->setEmploymentStatus($_POST['status']);					
			$g->save($g);
			echo 'true';
		}else{
			echo 'false';
		}	
	}
	
	
	function eeo_job_category()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
		
		$this->view->render('settings/job/eeo_job_category/add_eeo_job_category.php',$this->var);
	}
	
	function edit_eeo_job_category()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
		$this->var['eeo_info'] = G_Eeo_Job_Category_Finder::findById($_POST['id']);
		$this->view->render('settings/job/eeo_job_category/edit_eeo_job_category.php',$this->var);
	}
	
	function add_eeo_job_category()
	{
		if(!empty($_POST)){
			$g = new G_Eeo_Job_Category();	
			$g->setCompanyStructureId($this->company_structure_id);
			$g->setCategoryName($_POST['category_name']);	//$_POST['category_name']
			$g->setDescription($_POST['description']);	
			$g->save();
			
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';
		 //redirect('settings/job_specification');
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';
		}	
		
		echo json_encode($return);
	}
	
	function update_eeo_job_category()
	{
		if(!empty($_POST)){
			$g = G_Eeo_Job_Category_Finder::findById($_POST['id']);	
			if($g){
				//$g->setCompanyStructureId($this->company_structure_id);
				$g->setCategoryName($_POST['category_name']);	//$_POST['category_name']	
				$g->setDescription($_POST['description']);
				$g->save($g);
				
				$return['is_success'] = 1;
				$return['message']    = 'Record Saved.';		 
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record not save...';
			}
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record not save...';
		}	
		
		echo json_encode($return);
	}
	
	function job_salary_rate()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
		$eeo = G_Eeo_Job_Category_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['eeo'] = $eeo;
		$this->view->render('settings/job/job_salaray_rate/add_salary_rate.php',$this->var);
	}
	
	function edit_job_salary_rate()
	{
		$eeo = G_Eeo_Job_Category_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['eeo']			  = $eeo;
		$this->var['job_salary_rate'] = G_Job_Salary_Rate_Finder::findById($_POST['id']);
		$this->view->render('settings/job/job_salaray_rate/edit_salary_rate.php',$this->var);
	}
	
	function add_job_salary_rate()
	{
		if(!empty($_POST)){
			$g = new G_Job_Salary_Rate();
			$g->setCompanyStructureId($this->company_structure_id);
			$g->setJobLevel($_POST['job_level']);	
			$g->setMinimumSalary($_POST['minimum_salary']);	
			$g->setMaximumSalary($_POST['maximum_salary']);	
			$g->setStepSalary($_POST['step_salary']);	
			$g->save();
			
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved';
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Record cannot be save.';
		}	
		
		echo json_encode($return);
	}
	
	function update_job_salary_rate()
	{
		if(!empty($_POST)){
			$g = G_Job_Salary_Rate_Finder::findById($_POST['id']);
			if($g){				
				$g->setJobLevel($_POST['job_level']);	
				$g->setMinimumSalary($_POST['minimum_salary']);	
				$g->setMaximumSalary($_POST['maximum_salary']);	
				$g->setStepSalary($_POST['step_salary']);	
				$g->save();
				
				$return['is_success'] = 1;
				$return['message']    = 'Record Saved';
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Record cannot be save.';
			}
		}else{
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved';
		}	
		echo json_encode($return);
	}

	function user_management(){		
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapCollapsible();
		
		$this->var['page_title'] 			= 'Settings';
		$this->var['user_management_sb'] 	= 'selected';
		$this->var['module_title']			= 'User Management';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/user_management/index.php',$this->var);
	}

	function requests_approvers(){		
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		//Jquery::loadMainBootStrapCollapsible();

		$this->var['page_title'] 			= 'Settings';
		$this->var['user_management_ra'] 	= 'selected';
		$this->var['module_title']			= '';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/request_approvers/index.php',$this->var);
	}

	function ip_management() {
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();

		$this->var['ip_management_sb'] = 'selected';
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/ip_management/index.php',$this->var);
	}

	function _load_ip_address_list() {
		$this->view->render('settings/ip_management/_ip_address_list_dt.php',$this->var);
	}

	function _load_add_ip_address_form()
	{
		$this->var['token']         = Utilities::createFormToken(); 		
		$this->view->render('settings/ip_management/forms/add_ip_address_form.php',$this->var);
	}

	function ajax_edit_ip_address() {	
		$ai = G_Allowed_Ip_Finder::findById(Utilities::decrypt($_GET['eid']));
		if($ai) {
			$e = G_Employee_Finder::findById($ai->getEmployeeId());
			if($e) {
				$this->var['employee_name'] = $e->getFirstName() . ' ' . $e->getLastName();
			}
			$this->var['ai'] = $ai;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('settings/ip_management/forms/edit_ip_address_form.php', $this->var);	
		}else{
			echo "<div class=\"alert alert-error\">Record not found</div>";
		}
	}

	function _load_ip_address_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(ALLOWED_IP);
		$dt->setSQL("
			SELECT ai.*, e.employee_code, CONCAT(e.firstname,' ',e.lastname) as employee_name
			FROM ". ALLOWED_IP ." ai 
			LEFT JOIN ". EMPLOYEE ." e 
				ON ai.employee_id = e.id	
		");		
		$dt->setCountSQL("SELECT COUNT(ai.id) as c FROM ". ALLOWED_IP ." ai LEFT JOIN ". EMPLOYEE ." e ON ai.employee_id = e.id	");	
		

		//$dt->setCondition("elr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::PENDING) ." AND elr.employee_id = ". Model::safeSql($user_id) ." AND elr.is_archive = ". Model::safeSql(G_Employee_Leave_Request::NO));
		$dt->setColumns('employee_code,employee_name,ip_address,date_created');	
		$dt->setPreDefineSearch(
			array(				
				"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-edit-ip-address\" ><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-delete-ip-address\" ><i class=\"icon-trash\"></i> Delete </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function save_ip_address() {
		$return['is_success'] = false;
		if(Utilities::isFormTokenValid($_POST['token'])) {	
			if( !empty($_POST['ip_address']) ) {
				$return['message'] = "Record was successfully updated.";

				$gai = G_Allowed_Ip_Finder::findById(Utilities::decrypt($_POST['eid']));
				if(!$gai) {
					$gai = new G_Allowed_Ip();
					$gai->setEmployeeId(Utilities::decrypt($_POST['employee_id']));   
					$gai->setDateCreated(date("Y-m-d h:i:s")); 
					$return['message'] = "Record was successfully saved.";
				}
				
		        $gai->setIpAddress($_POST['ip_address']);       
		        $gai->setDateModified(date("Y-m-d h:i:s"));   	        
		        $gai->save();

		        $return['is_success'] = true;
		        
			}else{
				$return['message'] = "Please fill the form.";
			}
		}else {
			$return['message'] = "Invalid form token";
		}
		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function delete_ip_address() {
		$return['message'] = "No record found.";
		$gai = G_Allowed_Ip_Finder::findById(Utilities::decrypt($_POST['eid']));
		if($gai) {
			$gai->delete();
			$return['message'] = "Record was successfully deleted.";
		}
		echo json_encode($return);
	}
	
	function _quick_autocomplete_search_by_user_group() {
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			
			$records = G_User_Helper::findUserEmployee($q);
			foreach ($records as $record) {	
				$response[] = array(
					'id'=>Utilities::encrypt($record['id']),
					'label'=>$record['name'],
					'hash'=>$record['hash'],
					'return_type'=>G_Access_Rights::USER,
					'user_group_id' => Utilities::encrypt($record['user_group_id']),
					'employee_name' => $record['employee_name'],
					'employment_status' => $record['employment_status'],
					'group_name'=>'',
					'group_description'=>'');
			}
			
			$records = G_User_Group_Helper::findGroupName($q);
			foreach ($records as $record) {	
				$response[] = array(
					'id'=>Utilities::encrypt($record['id']),
					'label'=>$record['name'],
					'hash'=>'',
					'return_type'=>G_Access_Rights::GROUP,
					'user_group_id' => Utilities::encrypt($record['id']),
					'employee_name' => '',
					'employment_status' => '',
					'group_name'=>$record['group_name'],
					'group_description'=>$record['group_description']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}

		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function _load_user_group_user_rights_option() {
		if(!empty($_POST)) {
			$group = G_User_Group_Finder::findById(Utilities::decrypt($_POST['h_uar_id']));
			$user  = G_User_Finder::findById(Utilities::decrypt($_POST['h_uar_id']));
			
			if($group || $user) {
				$this->var['ar'] 	 = $ar = G_Access_Rights_Finder::findByUserGroupId(Utilities::decrypt($_POST['h_uar_id']));
				$this->var['rights'] = $rights = G_Access_Rights_Helper::getUnserializeRights($ar);
				$this->view->render('settings/user_management/user_group_access_rights_option.php',$this->var);
			} else {
				echo 'User / Group  does not exists!';
			}
		}
	}
	
	function _insertUserGroupAccessRights(){
 		if(!empty($_POST)) {
			$rights = serialize($_POST['sprint_hr']);
			
			$ar = G_Access_Rights_Finder::findByUserGroupId(Utilities::decrypt($_POST['h_user_group_id']));
			if(!$ar) {
				$ar = new G_Access_Rights();
			}
			$ar->setCompanyStructureId($this->company_structure_id);
			$ar->setUserGroupId(Utilities::decrypt($_POST['h_user_group_id']));
			$ar->setPolicyType($_POST['policy_type']);
			$ar->setRights($rights);
			$ar->save();
		}
	}
	
	function contribution()
	{
		Utilities::checkModulePackageAccess('attendance','payroll');
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		//Jquery::loadMainEditInPlace();
		$this->var['page_title'] 		= 'Settings';
		$this->var['contribution_sb']	= 'selected';
		$this->var['module_title']		= 'Contribution';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/contribution/index.php',$this->var);
		
	}
	
	function examination_template()
	{
		Utilities::checkModulePackageAccess('hr','recruitment');
		Jquery::loadMainTipsy();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		
		$e = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_hr']['employee_id'])); 
		if($e){		
			$this->var['ename']= $e['salutation'].' '. $e['firstname'].' '.$e['lastname'].' '.$e['extension_name'];
		}else{
			$this->var['ename']= '';
		}
		$this->var['company_structure_id'] 		= $this->company_structure_id;		
		$this->var['page_title'] 				= 'List of Examination';
		$this->var['examination_template_sb']	= 'selected';
		$this->var['module_title']				= 'Examination Template';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/examination/index.php',$this->var);
		
	}
	
	function requirements()
	{
		//Utilities::checkModulePackageAccess('hr','recruitment');		
		Jquery::loadMainTipsy();			
		Jquery::loadMainJqueryDatatable();	
		Jquery::loadMainInlineValidation2();		
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();	
		
		Loader::appMainScript('settings.js');
		Loader::appMainScript('settings_base.js');
		
		$this->var['page_title'] 			= 'Default Requirements';
		$this->var['default_requirements']	= 'selected';
		$this->var['module_title']			= 'Default Requirements';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/requirements/requirements.php',$this->var);
		
	}
	
	function company_benefits()
	{
		//Utilities::checkModulePackageAccess('hr','recruitment');		
		Jquery::loadMainTipsy();			
		Jquery::loadMainJqueryDatatable();	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();		
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();
		
		Loader::appMainScript('settings.js');
		Loader::appMainScript('settings_base.js');
		
		$this->var['page_title'] 			= 'Company Benefits';
		$this->var['company_benefits_sb']	= 'selected';
		$this->var['module_title']			= 'Company Benefits';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/company_benefits/company_benefits.php',$this->var);
		
	}
	
	function _json_encode_examination_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY id asc' ;
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$exam = G_Exam_Helper::findByCompanyStructureId($company->id,$order_by,$limit);
		foreach ($exam as $key=> $object) { 
			$current = Tools::limitCharater($object["description"], 50, " ");
			$object["description"] = $current ;
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
		}
		
		$count_total =  G_Exam_Helper::countTotalRecordsByCompanyStructureId($company);
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _insert_examination()
	{
	//	print_r($_POST);
		$duration = $_POST['days'] . ":" . $_POST['hours'] . ":" . $_POST['minutes'];
		$is_title_exists = G_Exam_Helper::isTitleExists($_POST['title'],$this->company_structure_id);	
		if($is_title_exists == 0){		
			$x = new G_Exam;		
			$x->setCompanyStructureId($_POST['company_structure_id']);
			$x->setTitle($_POST['title']);
			$x->setDescription($_POST['description']);
			$x->setPassingPercentage($_POST['passing_percentage']);
			$x->setTimeDuration($duration);
			$x->setCreatedBy($_POST['created_by']);
			$x->setDateCreated($_POST['date_created']);
			$new_id = $x->save();
			
			$json['eid']		= Utilities::encrypt($new_id);
			$json['is_success'] = 1;			
			$json['message']    = 'Record was successfully saved.';
		}else{
			$json['is_success'] = 2;			
			$json['message']    = 'Title Already exists.';
		}
		
		echo json_encode($json);
		//echo Utilities::encrypt($new_id);
		
	}
	
	function examination_details()
	{
	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		//Style::loadMainTableThemes();
		Loader::appMainScript('examination.js');
		
		$examination_id = Utilities::decrypt($_GET['examination_id']);		
		$e 				= G_Exam_Finder::findById($examination_id);
		
		if($e){
			$arD    = explode(":",$e->getTimeDuration());	
			$d['d'] = $arD[0];
			$d['h'] = $arD[1];
			$d['m'] = $arD[2];
			
			$this->var['d'] = $d;
			$this->var['company_structure_id'] = $this->company_structure_id;
			$this->var['details'] 		= $e;
			$this->var['questions'] 	= G_Exam_Question_Finder::findByExamId($examination_id);
			$this->var['page_title'] 	= 'Examination';
			$this->var['module_title']	= 'Examination Template: '. $e->title;
			$this->var['examination_template_sb']	= 'selected';
			$this->view->setTemplate('template_settings.php');
			$this->view->render('settings/examination/details/index.php',$this->var);
		}
	}
	
	function _load_question_edit()
	{
		$question_id = $_POST['question_id'];	
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['details'] = G_Exam_Finder::findById($examination_id);
		$this->var['e'] = G_Exam_Question_Finder::findById($question_id);
	
		$this->view->noTemplate();
		$this->view->render('settings/examination/details/form/question_edit.php',$this->var);
	}
	
	function _load_examination_details()
	{
		Loader::appMainScript('examination.js');
		
		$examination_id = $_POST['examination_id'];
		
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['details'] = G_Exam_Finder::findById($examination_id);
		
		$this->view->noTemplate();
		$this->view->render('settings/examination/details/examination_table.php',$this->var);
	}
	
	function _insert_choice()
	{
		//print_r($_POST);
		
		$q = G_Exam_Question_Finder::findById($_POST['question_id']);
		if(strtolower($q->answer)!=strtolower(trim(stripslashes($_POST['choice'])))) 
		{
			//check if the choice has duplicate
			$c = G_Exam_Choices_Finder::findByQuestionId($_POST['question_id']);
			$has_duplicate=false;
			foreach($c as $key=>$val)
			{
				if(strtolower($val->choices)==strtolower(stripslashes(trim($_POST['choice']))))
				{
						$has_duplicate=true;
				}
			}
			if($has_duplicate==false) {
					$count = G_Exam_Choices_Helper::countTotalRecords($_POST['question_id']);
					$c = new G_Exam_Choices;	
					$c->setExamQuestionId($_POST['question_id']);
					$c->setChoices($_POST['choice']);
					$new_count = $count+1;
					$c->setOrderBy($new_count);
					echo $c->save();
			}else {
				echo -1;	
			}
			
			
			
		}else {
			echo 0;	
		}
		
	}
	
	function _load_exam_questions()
	{
		sleep(2);
		$this->var['questions'] = G_Exam_Question_Finder::findByExamId($_POST['examination_id']);
		$this->view->noTemplate();
		$this->view->render('settings/examination/details/include/question_table.php',$this->var);	
	}
	
	function _update_examination_details()
	{
		$row = $_POST;
		$gsl = new G_Exam(Utilities::decrypt($row['examination_id']));
		$duration = $_POST['days'] . ":" . $_POST['hours'] . ":" . $_POST['minutes'];
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setTitle($row['title']);
		$gsl->setDescription($row['description']);	
		$gsl->setPassingPercentage($row['passing_percentage']);
		$gsl->setTimeDuration($duration);
		$gsl->setCreatedBy($row['created_by']);
		$gsl->setDateCreated($row['date_created']);	
		$gsl->save();
		echo 1;		
	}
	
	//this is for edit
	
	function _edit_question()
	{
		$row = $_POST;
		$gsl = new G_Exam_Question($row['id']);
		$gsl->setExamId($row['examination_id']);
		$gsl->setQuestion($row['question']);
		$gsl->setAnswer($row['answer']);
		$gsl->setOrderBy($row['order_by']);
		$gsl->setType($row['type']);
		$gsl->save();
		
		if($row['type']=='choices') {
			//check if the answer already inserted
			$c = G_Exam_Choices_Finder::findByQuestionId($row['id']);
			$has_answer=false;
			foreach($c as $key=>$val)
			{
				if(strtolower($val->choices)==strtolower(trim(stripslashes($row['answer']))))
				{
						$has_answer=true;
				}
			}
			if($has_answer==false) {
				$count = G_Exam_Choices_Helper::countTotalRecords($row['id']);
				$c = new G_Exam_Choices;	
				$c->setExamQuestionId($row['id']);
				$c->setChoices($row['answer']);
				$new_count = $count+1;
				$c->setOrderBy($new_count);
				$c->save();
			}
		}
		echo 1;
	}
	
	// this is for add
	function _update_question()
	{
		$row = $_POST;
		$gsl = new G_Exam_Question($row['id']);
		$gsl->setExamId($row['examination_id']);
		$gsl->setQuestion($row['question']);
		$gsl->setAnswer(($row['answer']));
		$gsl->setOrderBy($row['order_by']);
		$gsl->setType($row['type']);
		
		if($row['type']=='choices') {
			//check the blank
			$ctr=1;
			$blank=0;
			while($ctr<=4) {
				if($row['choice'.$ctr]=='') {
					$blank++;
				}
				$ctr++;
			}
			
			if($blank>2) {
				echo "Please input choices";
				exit;	
			}
			
			
			$ctr=1;
			$same=0;
			$result=0;
			while($ctr<=4) {
				if(strtolower(trim($row['choice'.$ctr]))==strtolower(trim($row['answer'])) ) {
					$result=1;
					$same++;
				}
				$ctr++;
			}
			
			if($same>1) {
				echo "There are ".$same . " choice(s) same with the answer.<br> Please input once.";
				exit;	
			}
			
			if($result==0) {
				echo "There are no choices same with the answer";
				exit;	
			}
			
			//check if has a duplicate
			$ctr=1;
			$ctr2=1;
			$has=0;
			while($ctr<=4) {
				$ctr2=1;
				while($ctr2<=4) {
					if( $ctr!=$ctr2 ){
						if(strtolower(trim($row['choice'.$ctr]))!='' && strtolower(trim($row['choice'.$ctr2])!=''))
						{
							if(strtolower(trim($row['choice'.$ctr]))==strtolower(trim($row['choice'.$ctr2])))
							{
								$has=1;
							}	
						}
						
					}
					$ctr2++;
				}
				$ctr++;
			}
			if($has==1) {
				echo "There are duplicate options";
				exit;	
			}
			
			if($result==1) {
				$exam_question_id = $gsl->save();
				if($row['type']=='choices') {
					$ctr=1;
					while($ctr<=4) {
						if($row['choice'.$ctr]!='') {
							$choices = new G_Exam_Choices;
							$choices->setExamQuestionId($exam_question_id);
							$choices->setChoices(($row['choice'.$ctr]));
							$choices->setOrderBy($ctr);
							$choices->save();	
						}
						
						$ctr++;
					}
					echo 1;	
				}	
			}	
		}else {
			$exam_question_id = $gsl->save();	
			echo 1;
		}
		$count = G_Exam_Question_Helper::countTotalRecordsByExamId($row['examination_id']);
		if($count) {
			$examination = G_Exam_Question_Finder::findById($exam_question_id);
			$examination->setOrderBy($count);
			$examination->save();	
		}
		
	}
	
	function _delete_question()
	{
		//print_r($_POST);
		$question_id = (int) $_POST['question_id'];
		$q = G_Exam_Question_Finder::findById($_POST['question_id']);
		$exam_id = $q->exam_id;
		$order_by = $q->order_by;
		$q->delete();
		
		$c = G_Exam_Choices_Finder::findByQuestionId($_POST['question_id']);
		foreach($c as $key=>$value) {
			$value->delete();
		}

		$sql = "SELECT id FROM g_exam_question WHERE exam_id=".Model::safeSql($exam_id)." AND order_by>".$order_by." ORDER BY order_by";
		$s = Model::runSql($sql,true);
		foreach($s as $key=>$val) {
			$q = G_Exam_Question_Finder::findById($val['id']);
			$q->setOrderBy($order_by);
			$q->save();
			$order_by++;
		}
				
		echo 1;
	}
	
	function _choice_move_up()
	{
		$choice_id = (int) $_POST['choice_id'];
		$qq = G_Exam_Choices_Finder::findById($choice_id);
		$order_by = $qq->order_by;
		$exam_question_id = $qq->exam_question_id;
		$new_order_by = $order_by-1;
		$qq->setOrderBy($new_order_by);
		
		
		$sql = "SELECT id FROM g_exam_choices WHERE exam_question_id=".Model::safeSql($exam_question_id)." AND order_by<".$order_by." ORDER BY order_by DESC LIMIT 1 ";
		$s = Model::runSql($sql,true);

		print_r($s);
		foreach($s as $key=>$val) {
			$q = G_Exam_Choices_Finder::findById($val['id']);
			$order_by = $q->order_by;
			$new_order = $order_by+1;
			$q->setOrderBy($new_order);
			$q->save();
			$order_by++;
		}
		$qq->save();
		echo 1;
	}
	
	function _question_move_up()
	{
		$question_id = (int) $_POST['question_id'];
		$qq = G_Exam_Question_Finder::findById($_POST['question_id']);
		$order_by = $qq->order_by;
		$exam_id = $qq->exam_id;
		$new_order_by = $order_by-1;
		$qq->setOrderBy($new_order_by);
		
		$sql = "SELECT id FROM g_exam_question WHERE exam_id=".Model::safeSql($exam_id)." AND order_by<".$order_by." ORDER BY order_by DESC LIMIT 1 ";
		$s = Model::runSql($sql,true);
		foreach($s as $key=>$val) {
			$q = G_Exam_Question_Finder::findById($val['id']);
			$order_by = $q->order_by;
			$new_order = $order_by+1;
			$q->setOrderBy($new_order);
			$q->save();
			$order_by++;
		}
		$qq->save();
		echo 1;
	}
	
	function _delete_choice()
	{
		$c = G_Exam_Choices_Finder::findById($_POST['choice_id']);
		$exam_question_id = $c->exam_question_id;
		$order_by = $c->order_by;
		$c->delete();
		echo 1;
		
		$sql = "SELECT id FROM g_exam_choices WHERE exam_question_id=".Model::safeSql($exam_question_id)." AND order_by>".$order_by." ORDER BY order_by";
		$s = Model::runSql($sql,true);
		foreach($s as $key=>$val) {
			$c = G_Exam_Choices_Finder::findById($val['id']);
			$c->setOrderBy($order_by);
			$c->save();
			$order_by++;
		}
	}
	
	function _load_question_choices()
	{
		sleep(1);
		$this->var['question_id'] = $_POST['question_id'];
		$this->view->noTemplate();
		$this->view->render('settings/examination/details/include/choices.php',$this->var);
	}
	
	
	function _load_add_examination_confirmation()
	{
		$this->var['msg'] = "Successfully Added";
		$this->view->noTemplate();
		$this->view->render('recruitment/candidate/confirmation.php',$this->var);
	}
	
	function _load_add_form_questions()
	{
		$e = G_Exam_Finder::findById($_POST['examination_id']);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('settings/examination/details/form/question_add.php',$this->var);
	}
	
	//performance
	
	function performance_template()
	{
		Yui::loadMainDatatable();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('performance.js');
		$this->var['company_structure_id'] = $this->company_structure_id;
		
		$this->var['page_title'] 				= 'List of Performance';
		$this->var['performance_template_sb']	= 'selected';
		$this->var['module_title']				= 'Performance Template';
		
		$this->var['job'] = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/performance/index.php',$this->var);
		
	}
	
	function memo_template()
	{
		Jquery::loadMainTipsy();			
		Jquery::loadMainJqueryDatatable();	
		Jquery::loadMainInlineValidation2();		
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();	
		
		Loader::appMainScript('memo.js');
	
		$this->var['page_title'] 				= 'List of Memo Template';
		$this->var['memo_template_sb']		= 'selected';
		$this->var['module_title']				= 'Memo Template';
		
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/memo/index.php',$this->var);
	}

	function notifications()
	{
		Jquery::loadMainTipsy();			
		Jquery::loadMainJqueryDatatable();	
		Jquery::loadMainInlineValidation2();		
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();

		$this->var['page_title'] 		= 'List of Notification Settings';
		$this->var['notifications_sb']	= 'selected';
		$this->var['module_title']		= 'Notifications';

		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/notifications/index.php',$this->var);		
	}		
	
	function _load_memo_template_form()
	{
		$this->getAdminUserInfo();
		$this->var['token'] 					= Utilities::createFormToken();
		$this->var['action'] 					= url('settings/_insert_memo');
		
		$this->view->render('settings/memo/forms/add_memo_form.php',$this->var);		
	}

	function _load_credit_condition_form()
	{
		$emp_status = G_Settings_Employment_Status_Finder::findAll();	
		$leave 		= G_Leave_Finder::findAll();
		$this->var['initial_count']			    = 1;
		$this->var['leave_type']			    = $leave; 
		$this->var['employment_status']			= $emp_status;
		$this->var['token'] 					= Utilities::createFormToken();
		$this->var['action'] 					= url('settings/_insert_credit_condition');
		
		$this->view->render('settings/leave/forms/add_credit_condition_form.php',$this->var);		
	}

	function _load_edit_credit_condition_form() {
		$leave_id = Utilities::decrypt($_POST['leave_credit_id']);

		$leave_data = G_Settings_Leave_Credit_Finder::findById($leave_id);

		$emp_status = G_Settings_Employment_Status_Finder::findAll();	
		$leave 		= G_Leave_Finder::findAll();

		$this->var['leave_data']				= $leave_data;
		$this->var['leave_type']			    = $leave; 
		$this->var['employment_status']			= $emp_status;
		$this->var['token'] 					= Utilities::createFormToken();
		$this->var['action'] 					= url('settings/_update_credit_condition');
		
		$this->view->render('settings/leave/forms/edit_credit_condition_form.php',$this->var);		
	}

	function _load_leave_credit_list()
	{

		$leave_credit_list 			= G_Settings_Leave_Credit_Helper::getAllLeaveCredits();
		$this->var['leave_credits']	= $leave_credit_list; 
		$this->view->render('settings/leave/_ajax_leave_credit_list.php',$this->var);		
	}
	
	function _load_memo_template_list_dt()
	{
		$this->view->render('settings/memo/_load_memo_template_list_dt.php',$this->var);	
	}
	
	function _load_archive_memo_template_list_dt()
	{
		$this->view->render('settings/memo/_load_archive_memo_template_list_dt.php',$this->var);	
	}
	
	function _load_server_memo_template_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_MEMO);
		$dt->setCondition('is_archive ="' . G_Settings_Memo::NO . '"');	
		//$dt->setCustomField();				
		$dt->setColumns('title,created_by');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Edit Memo\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editMemoTemplate(\'e_id\');\"></a></li><li><a title=\"Archive Memo\" id=\"edit\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveMemo(\'e_id\');\"></a></li></ul></div>'));	
		echo $dt->constructDataTable();		
	}
	
	function _load_server_archive_memo_template_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_MEMO);
		$dt->setCondition('is_archive ="' . G_Settings_Memo::YES . '"');	
		//$dt->setCustomField();				
		$dt->setColumns('title,created_by');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Restore Archive\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreMemo(\'e_id\')\"></a></li></ul></div>'));	
		echo $dt->constructDataTable();		
	}
	
	function _load_delete_memo_template_confirmation()
	{
		if(!empty($_POST['memo_id'])){
			$m = G_Settings_Memo_Finder::findById(Utilities::decrypt($_POST['memo_id']));
			if($m){	
				$this->var['memo'] = $m->getTitle();
				$this->view->noTemplate();
				$this->view->render('settings/memo/delete_confirmation.php',$this->var);
			}
		}

	}
	
	function delete_memo()
	{
		if(!empty($_POST['memo_id'])){
				$sm = G_Settings_Memo_Finder::findById(Utilities::decrypt($_POST['memo_id']));
				$sm->delete();
				$return = 1;
		}else{$return = 0; }
				
		echo $return;
	}
	
	function edit_memo()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTipsy();
		
		$this->var['token'] 		= Utilities::createFormToken();
		$this->var['action'] 	= url('settings/_insert_memo');

		$this->var['memo_id']	= $_POST['memo_id'];
		$this->var['memo_info'] = G_Settings_Memo_Finder::findById(Utilities::decrypt($_POST['memo_id']));	
		$this->view->render('settings/memo/forms/edit_memo_form.php',$this->var);		
	} 
	
	function _json_encode_performance_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY id asc' ;
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$performance = G_Performance_Helper::findByCompanyStructureId($company->id,$order_by,$limit);
		foreach ($performance as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$job = G_Job_Finder::findById($object['job_id']);
			$data[$key]['job_name'] = $job->title;
		}
		
		$count_total =  G_Performance_Helper::countTotalRecordsByCompanyStructureId($company);
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _json_encode_is_archive_performance_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY id asc' ;
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$performance = G_Performance_Helper::findAllIsArchiveByCompanyStructureId($company->id,$order_by,$limit);
		foreach ($performance as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$job = G_Job_Finder::findById($object['job_id']);
			$data[$key]['job_name'] = $job->title;
		}
		
		$count_total =  G_Performance_Helper::countTotalRecordsIsArchiveByCompanyStructureId($company);
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";		
	}
	
	function _insert_performance()
	{
	//	print_r($_POST);
		$x = new G_Performance;
		$x->setCompanyStructureId($_POST['company_structure_id']);
		$x->setTitle($_POST['title']);
		$x->setJobId($_POST['job_id']);
		$x->setDescription($_POST['description']);
		$x->setCreatedBy($_POST['created_by']);
		$x->setDateCreated($_POST['date_created']);
		$new_id = $x->save();
		echo Utilities::encrypt($new_id);
		
		//$json['eid']		= $new_id;
		//$json['is_success'] = 1;			
		//$json['message']    = 'Record was successfully saved.';
		
		//echo json_encode($json);
	}

	function update_payslip_settings()
	{
		Utilities::verifyFormToken($_POST['token']);
		if(!empty($_POST['template_id'])) {
	        $gpt = G_Payslip_Template_Finder::findById($_POST['template_id']);
            $gpt->setIsDefault(G_Payslip_Template::IS_DEFAULT_YES);       
			$saved = $gpt->clearDefaultTemplate()->save();

			if($saved) {
		    	$return['message'] 		= 'Payslip Template has been updated';	
		    	$return['is_updated'] 	= 1;	
			} else {
				$return['message'] 		= 'An error occured. Cant saved record';	
				$return['is_updated'] 	= 0;					
			}

		} else {
			$return['message'] 		= 'An error occured. Please contact the developer';	
			$return['is_updated'] 	= 0;				
		}

		echo json_encode($return);
	}

	function _update_leave_general_settings()
	{		
		//Utilities::verifyFormToken($_POST['token']);
		if(!empty($_POST)) {
	        $slv = G_Settings_Leave_General_Finder::findById(1);
	        if(!empty($slv)) {
		        $slv->setConvertLeaveCriteria($_POST['leave_criteria']);
		        $slv->setLeaveId($_POST['leave_id']);       
		        $saved = $slv->save();	        	
		        if($saved) {
			    	$return['message'] 		= 'Leave General Setting has been updated';	
			    	$return['is_updated'] 	= 1;		        	
		        } else {
					//$return['message'] 		= 'An error occured. Cant saved record';	
					$return['message'] 		= 'Leave General Setting has been updated';	
					$return['is_updated'] 	= 0;			        	
		        }
	        } else {
				$return['message'] 		= 'An error occured. Leave General Setting no located';	
				$return['is_updated'] 	= 0;					        	
	        }

		} else {
			$return['message'] 		= 'An error occured. Please contact the developer';	
			$return['is_updated'] 	= 0;				
		}

    	echo json_encode($return);
	}

	function _insert_credit_condition()
	{	        	
		Utilities::verifyFormToken($_POST['token']);

		$count_total = count($_POST['leave_id']) - 1;		
		$save_status = false;		
		$data        = $_POST;
		
		foreach($data as $post){
			if(!empty($post['default_credit'])) {
		        $slv = new G_Settings_Leave_Credit();
		        $slv->setEmploymentYears($post['employment_years']);
		        $slv->setDefaultCredit($post['default_credit']);
		        $slv->setLeaveId($post['leave_id']);       
		        $slv->setEmploymentStatusId($post['employment_status_id']);        
		        $slv->setIsArchived(G_Settings_Leave_Credit::NO); 
		        $saved = $slv->save();
		        if($saved) {
		        	$save_status = true;
		        } else { $save_status = false; }
			}	
		}

        if($save_status) {
        	$return['message'] 		= 'New Credit Condition has been added';	
        	$return['is_added'] 	= 1;	
        } else {
			$return['message'] 		= 'An error occured. Please contact the developer';	
			$return['is_added'] 	= 0;	
        }
	
		echo json_encode($return);
	}

	function _update_credit_condition()
	{
		Utilities::verifyFormToken($_POST['token']);
		$leave_credit_id = Utilities::decrypt($_POST['leave_credit_id']);
		$lc_data = G_Settings_Leave_Credit_Finder::findById($leave_credit_id);

		if(!empty($lc_data)) {

			$count_total = count($_POST['leave_id']) - 1;		
			$save_status = false;
			for ($post_id = 0; $post_id <= $count_total; $post_id++) {
				if(!empty($_POST['default_credit'][$post_id])) {
			        $lc_data->setEmploymentYears($_POST['employment_years'][$post_id]);
			        $lc_data->setDefaultCredit($_POST['default_credit'][$post_id]);
			        $lc_data->setLeaveId($_POST['leave_id'][$post_id]);       
			        $lc_data->setEmploymentStatusId($_POST['employment_status_id'][$post_id]);        
			        $lc_data->setIsArchived(G_Settings_Leave_Credit::NO); 
			        $saved = $lc_data->save();
			        if($saved) {
			        	$save_status = true;
			        } else { $save_status = false; }
				}		    			
			} 

	        if($save_status) {
	        	$return['message'] 		= 'Credit Condition has been updated';	
	        	$return['is_updated'] 	= 1;	
	        } else {
				$return['message'] 		= 'An error occured. Please contact the developer';	
				$return['is_updated'] 	= 0;	
	        }

		} else {
			$return['message'] 		= 'An error occured. Cant locate credit condition data';	
			$return['is_updated'] 	= 0;				
		}

		echo json_encode($return);
	}
	
	function _insert_memo()
	{
		Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		if (!Tools::hasValue($_POST['title'])) { $error++; }
		if (!Tools::hasValue($_POST['content'])) { $error++; }
		if (!Tools::hasValue($_POST['date_created'])) { $error++; }
		if (!Tools::hasValue($_POST['created_by'])) { $error++; }
		
		if($error == 0) {			
			//save memo
			$memo_id = Utilities::decrypt($_POST['memo_id']);
			if($memo_id) {
				$sm = G_Settings_Memo_Finder::findById($memo_id);

				$sm->setTitle($_POST['title']);
				$sm->setContent($_POST['content']);
				$sm->setCreatedBy($_POST['created_by']);				
				$sm->setDateCreated($_POST['date_created']);
				$sm->save();
				$saved = 1;  
				
			}else{ 
				$sm = new G_Settings_Memo();
				$sm->setTitle($_POST['title']);
				$sm->setContent($_POST['content']);
				$sm->setCreatedBy($_POST['created_by']);
				$sm->setIsArchive(G_Settings_Memo::NO);
				$sm->setDateCreated($_POST['date_created']);
				$saved = $sm->save();
			}
			
							
			if($saved) {
				
				if($memo_id) {
					$return['message'] 	= 'Memo has been updated';
				}else{
					$return['message'] 	= 'New Memo has been added';	
				}
					
				$return['is_added'] 	= 1;
				
			} else {

				$return['message'] 	= 'An error occured. Please contact the developer';	
				$return['is_added'] 	= 0;
				
			}
		}
		echo json_encode($return);
	}
	
	function _insert_employee_status()
	{
		$t = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['name'])) { $error++; }			
		if($error == 0) {			
			//save Employee Status			
			if($_POST['eid']) {
				$gses = G_Settings_Employee_Status_Finder::findById(Utilities::decrypt($_POST['eid']));
				$gses->setName($_POST['name']);
				$gses->save();
				$saved = 1;
			}else{
				$gses = new G_Settings_Employee_Status();
				$gses->setName($_POST['name']);		
				$gses->setCompanyStructureId($this->company_structure_id);	
				$gses->setIsArchive(G_Settings_Employee_Status::NO);
				$gses->setDateCreated($this->c_date);
				$saved = $gses->save();
			}
							
			if($saved) {				
				if($_POST['eid']) {
					$return['message'] 	= 'Record updated';
				}else{
					$return['message'] 	= 'Record saved';	
				}
					
				$return['is_success'] 	= 1;
				
			}else{

				$return['message'] 	= 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
				
			}
		}
		echo json_encode($return);
	}
	
	function _insert_payroll_period()
	{
		$t = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['p_cutoff_period'])) { $error++; }			
		if($error == 0) {						
			$cycle	 = G_Salary_Cycle_Finder::findDefault();
			$cutoff  = explode("/",$_POST['p_cutoff_period']);			
			if($cycle){
				$payout_date = Tools::getPayoutDate($cutoff[0], $cycle->getCutOffs(), $cycle->getPayoutDays());
				if($_POST['eid']) {
					$gcp = G_Cutoff_Period::findById(Utilities::decrypt($_POST['eid']));
					if($gcp){		
						$gcp->setYearTag($_POST['payroll_year']);
						$gcp->setStartDate($cutoff[0]);
						$gcp->setEndDate($cutoff[1]);
						$gcp->setPayoutDate($payout_date);
						$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
						$gcp->setIsLock($_POST['is_lock']);
						$gcp->save();
						
						$return['message'] 	= 'Record updated';
					}else{
						$return['message'] 		= 'An error occured. Please contact the developer';	
						$return['is_success'] 	= 0;
					}
				}else{					
					$gcp = new G_Cutoff_Period();
					$gcp->setYearTag($_POST['payroll_year']);
					$gcp->setStartDate($cutoff[0]);
					$gcp->setEndDate($cutoff[1]);
					$gcp->setPayoutDate($payout_date);
					$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
					$gcp->setIsLock($_POST['is_lock']);
					$gcp->save();
					
					$return['message'] 	= 'Record saved';	
				}
			}else{
				$return['message'] 		= 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
			}
		}else{
			if($_POST['generate_all']){
				$gcp = new G_Cutoff_Period();
				$gcp->savePayrollPeriodByYear($_POST['payroll_year']);
				$return['message'] 	= 'Payroll cutoff was successfully generated';	
			}
		}
		$return['selected_year'] = $_POST['payroll_year'];
		echo json_encode($return);
	}

	function _update_payroll_settings()
	{
		$t    = Utilities::verifyFormToken($_POST['token']);	
		$data = $_POST;
		$json = array();

		if( !empty($data) ){
			$id = Utilities::decrypt($data['eid']);
			$sv = G_Sprint_Variables_Finder::findById($id);
			if( $sv ){			
				if( isset($data['variable']) ){
					$variable_name = Utilities::decrypt($data['variable']);
					if( $variable_name == G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR ){
						$month_num = date("m", strtotime( date("Y") . "-" . $data['month_selected'] . "-1"));
						$dateString = date("Y") . "-" . $month_num . "-01";
						$lastDayOfMonth = date("t", strtotime($dateString));

						if($data['day_selected'] <= $lastDayOfMonth) {
							$new_value = $data['month_selected'] . ' ' . $data['day_selected'];
							$sv->setValue($new_value);								
						} else {
							$json['is_success'] = false;
							$json['message']    = "Cannot Update record, fiscal day is greater than the last day of the month";	
							echo json_encode($json);
							exit;
						}						
					}
				}else{
					$sv->setValue($data['field_value']);	
				}			
				
				if( !empty( $data['custom_value_a'] ) ){
					$sv->setCustomValueA($data['custom_value_a']);	
				}else{
					$sv->setCustomValueA('');	
				}
				$json = $sv->save();
				
			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot Update record";	
			}

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot Update record";
		}

		echo json_encode($json);
	}
	
	function _insert_requirement()
	{
		$t = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['name'])) { $error++; }			
		if($error == 0) {
			//save Requirement			
			if($_POST['eid']) {
				$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($_POST['eid']));
				$gsr->setName($_POST['name']);
				$gsr->save();
				$saved = 1;
			}else{		
				$gsr = new G_Settings_Requirement();
				$gsr->setName($_POST['name']);
				$gsr->setCompanyStructureId($this->company_structure_id);
				$gsr->setIsArchive(G_Settings_Requirement::NO);
				$gsr->setDateCreated($this->c_date);
				$saved = $gsr->save();
			}
							
			if($saved) {				
				if($_POST['eid']) {
					$return['message'] 	= 'Record updated';
				}else{
					$return['message'] 	= 'Record saved';	
				}
					
				$return['is_success'] 	= 1;
				
			}else{

				$return['message'] 	= 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
				
			}
		}
		echo json_encode($return);
	}
	
	function _assign_company_benefit()
	{
		$t 	   = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['eid'])) { $error++; }			
		if($error == 0) {
			$b = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($b){
				if($_POST['apply_to_all_employee']){	
					$criteria['is_apply_to_all'] = G_Employee_Benefit::YES;
					$criteria['obj_type'] 		 = G_Employee_Benefit::EMPLOYEE;
					
				}else{	
					$criteria['is_apply_to_all'] = G_Employee_Benefit::NO;
					$criteria['obj_type'] 		 = G_Employee_Benefit::EMPLOYEE;
					$eid_array = $_POST['employee_id'];			
				}
				
				$eb = new G_Employee_Benefit();					
				$eb->setBenefitId($b->getId());
				$eb->setDateCreated($this->c_date);
				$count = $eb->assignBenefit($eid_array,$criteria);
				
						
			$return['message'] 	    = "Record saved.<br /><br />Total records inserted: <b>" . $count . "</b>";	
			$return['is_success'] 	= 1;
			}else{
				$return['message'] 	    = 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
			}
			
		}else{
			$return['message'] 	    = 'An error occured. Please contact the developer';	
			$return['is_success'] 	= 0;
		}
		echo json_encode($return);
	}
	
	function _insert_company_benefit()
	{
		$t 	   = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['benefit_name'])) { $error++; }			
			if($error == 0) {
				//save Company Benefit			
				if($_POST['eid']) {
					$gcb = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));				
				}else{		
					$gcb = new G_Settings_Company_Benefits();
					$gcb->setDateCreated($this->c_date);   
				}
				
				$gcb->setCompanyStructureId($this->company_structure_id);
				$gcb->setBenefitCode($_POST['benefit_code']);
				$gcb->setBenefitName($_POST['benefit_name']);
				$gcb->setBenefitDescription($_POST['benefit_description']);
				$gcb->setBenefitType($_POST['benefit_type']);
				$gcb->setBenefitAmount($_POST['benefit_amount']);
				$gcb->setIsTaxable($_POST['is_taxable']);
				$gcb->setIsArchive(G_Settings_Company_Benefits::NO);
				$gcb->save();
				
								
				if($_POST['eid']){
					$return['message'] 	= 'Record updated';
				}else{
					$return['message'] 	= 'Record saved';	
				}
						
				$return['is_success'] 	= 1;
				
			}else{
				$return['message'] 	    = 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
			}
		echo json_encode($return);
	}
	
	function _company_benefit()
	{
		$t = Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		
		if (!Tools::hasValue($_POST['name'])) { $error++; }			
		if($error == 0) {
			//save Requirement			
			if($_POST['eid']) {
				$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($_POST['eid']));
				$gsr->setName($_POST['name']);
				$gsr->save();
				$saved = 1;
			}else{		
				$gsr = new G_Settings_Requirement();
				$gsr->setName($_POST['name']);
				$gsr->setCompanyStructureId($this->company_structure_id);
				$gsr->setIsArchive(G_Settings_Requirement::NO);
				$gsr->setDateCreated($this->c_date);
				$saved = $gsr->save();
			}
							
			if($saved) {				
				if($_POST['eid']) {
					$return['message'] 	= 'Record updated';
				}else{
					$return['message'] 	= 'Record saved';	
				}
					
				$return['is_success'] 	= 1;
				
			}else{

				$return['message'] 	= 'An error occured. Please contact the developer';	
				$return['is_success'] 	= 0;
				
			}
		}
		echo json_encode($return);
	}	
	
	function _load_add_performance_confirmation()
	{
		$this->var['msg'] = "Successfully Added";
		$this->view->noTemplate();
		$this->view->render('recruitment/candidate/confirmation.php',$this->var);
	}
	
	function performance_details()
	{
	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Style::loadMainTableThemes();
		Loader::appMainScript('performance.js');
		
		$performance_id = Utilities::decrypt($_GET['performance_id']);
		
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['job'] = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$performance = G_Performance_Finder::findById($performance_id);
		
		$job = G_Job_Finder::findById($performance->job_id);
		$performance->job_name = $job->title;
		
		
		$this->var['kpis'] = G_Performance_Indicator_Finder::findByPerformanceId($performance_id);
			
		$this->var['details'] = $performance;
		$this->var['page_title'] = 'Performance Details';
		
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/performance/details/index.php',$this->var);
	}
	
	function _edit_kpi()
	{
		$row = $_POST;
		$gsl = new G_Performance_Indicator($row['id']);
		$gsl->setPerformanceId($row['performance_id']);
		$gsl->setTitle($row['title']);
		$gsl->setDescription($row['description']);
		$gsl->save();
		echo 1;
		
	}
	
	function _kpi_move_up()
	{
		$kpi_id = (int) $_POST['kpi_id'];
		$qq = G_Performance_Indicator_Finder::findById($_POST['kpi_id']);
		$order_by = $qq->order_by;
		$performance_id = $qq->performance_id;
		$new_order_by = $order_by-1;
		$qq->setOrderBy($new_order_by);
		
		$sql = "SELECT id FROM g_performance_indicator WHERE performance_id=".Model::safeSql($performance_id)." AND order_by<".$order_by." ORDER BY order_by DESC LIMIT 1 ";
		$s = Model::runSql($sql,true);
		foreach($s as $key=>$val) {
			$q = G_Performance_Indicator_Finder::findById($val['id']);
			$order_by = $q->order_by;
			$new_order = $order_by+1;
			$q->setOrderBy($new_order);
			$q->save();
			$order_by++;
		}
		$qq->save();
		echo 1;
	}
	
	function _load_performance_kpi()
	{
		sleep(2);
		$this->var['kpis'] = G_Performance_Indicator_Finder::findByPerformanceId($_POST['performance_id']);
		$this->view->noTemplate();
		$this->view->render('settings/performance/details/include/kpi_table.php',$this->var);	
	}
	
	function _load_add_form_kpi()
	{
		$e = G_Performance_Finder::findById($_POST['performance_id']);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('settings/performance/details/form/kpi_add.php',$this->var);
	}
	
	function _update_performance_details()
	{
		$row = $_POST;
		$gsl = new G_Performance(Utilities::decrypt($row['performance_id']));
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setTitle($row['title']);
		$gsl->setJobId($row['job_id']);
		$gsl->setDescription($row['description']);	
		$gsl->setCreatedBy($row['created_by']);
		$gsl->setDateCreated($row['date_created']);	
		$gsl->save();
		echo 1;		
	}
	
	function _load_performance_details()
	{
		Loader::appMainScript('performance.js');
		
		$performance_id = $_POST['performance_id'];
		$performance = G_Performance_Finder::findById($performance_id);
		$job = G_Job_Finder::findById($performance->job_id);
		$performance->job_name = $job->title;
		
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['details'] = $performance;
		
		$this->view->noTemplate();
		$this->view->render('settings/performance/details/performance_table.php',$this->var);
	}
	
	function _update_kpi()
	{
		$row = $_POST;
		$count = G_Performance_Indicator_Helper::countTotalRecordsByPerformanceId($row['performance_id']);
		$gsl = new G_Performance_Indicator($row['id']);
		$gsl->setPerformanceId($row['performance_id']);
		$gsl->setTitle($row['title']);
		$gsl->setDescription($row['description']);
		$new_count = $count+1;
		$gsl->setOrderBy($new_count);
		$gsl->save();
		echo 1;
	}
	
	function _delete_kpi()
	{
		//print_r($_POST);
		$kpi_id = (int) $_POST['kpi_id'];
		$q = G_Performance_Indicator_Finder::findById($_POST['kpi_id']);
		$performance_id = $q->performance_id;
		$order_by = $q->order_by;
		$q->delete();
		

		$sql = "SELECT id FROM g_performance_indicator WHERE performance_id=".Model::safeSql($performance_id)." AND order_by>".$order_by." ORDER BY order_by";
		$s = Model::runSql($sql,true);
		foreach($s as $key=>$val) {
			$q = G_Performance_Indicator_Finder::findById($val['id']);
			$q->setOrderBy($order_by);
			$q->save();
			$order_by++;
		}
				
		echo 1;
	}
	
	//end of performance
	
	function options2()
	{
		Jquery::inline_validation();	
		Jquery::modal_exetend();	
		Jquery::jquery_tipsy();
		Jquery::jq_datatable();
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		//$this->view->render('settings/options/membership_type/index.php',$this->var);
		$this->view->render('settings/options/license/index.php',$this->var);
		//$this->view->render('settings/options/location/index.php',$this->var);
		//$this->view->render('settings/options/subdivision_type/index.php',$this->var);
		//$this->view->render('settings/options/dependent_relationship/index.php',$this->var);
	}
	
	function _load_add_new_relationship()
	{		
		$this->view->render('settings/options/dependent_relationship/forms/add_new_relationship.php',$this->var);
	}
	
	function _load_add_new_license()
	{
		$this->view->noTemplate();
		$this->view->render('settings/options/license/forms/add_new_license.php',$this->var);
	}
	
	function _load_add_new_application_status()
	{
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$this->var['p_id'] = $cstructure->getId();
 		$this->view->noTemplate();		
		$this->view->render('settings/options/application_status/forms/add_application_status.php',$this->var);
	}
	
	function _load_edit_application_status()
	{
		if(!empty($_POST['id'])){			
			$es = G_Settings_Application_Status_Finder::findById($_POST['id']);
			$this->var['es']    = $es;
			$this->view->noTemplate();		
			$this->view->render('settings/options/application_status/forms/edit_application_status.php',$this->var);
		}		
	}
	
	function _load_delete_application_status_confirmation()
	{
		if(!empty($_POST['id'])){
			$es = G_Settings_Application_Status_Finder::findById($_POST['id']);
			if($es){	
				$this->var['employment_status'] = $es->getStatus();
				$this->view->noTemplate();
				$this->view->render('settings/options/application_status/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function delete_application_status()
	{
			
	}
	
	function add_application_status()
	{
		if(!empty($_POST['status'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);
			$cid = 	$this->company_structure_id;
			$gses  = new G_Settings_Application_Status();
			$gses->setCompanyStructureId($cid);
			$gses->setStatus($_POST['status']);
			$gses->save();
			echo 'true';
		}else{echo 'false';}		
	}
	
	function update_application_status()
	{
		if(!empty($_POST['id'])){
			$gses        = G_Settings_Application_Status_Finder::findById($_POST['id']);
			$cid = $this->company_structure_id;
			$gses->setCompanyStructureId($_POST['status']);
			$gses->setStatus($_POST['status']);				
			$gses->save();
			echo 'true';
		}else{echo 'false';}		
	}
	
	function _load_user_management_dt_depre()
	{
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort'] =='group_name') {
			$_GET['sort'] ='g.group_name';
		}

		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;
		
		$user				=  G_User_Helper::findAll($order_by,$limit);
		$total_records		=  G_User_Helper::countTotalRecords();
		//print_r($user);
		$total = count($user);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($user) . "}";	
	}

	function _load_user_management_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort'] =='group_name') {
			$_GET['sort'] ='g.group_name';
		}

		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$u = new G_Employee_User();
		$user          = $u->getAllUserIsNotArchive($order_by,$limit);
		$user          = $u->encryptIds($user);
		$total_records = $u->getTotalRecordsIsNotArchive();
		
		//$user				=  G_User_Helper::findAll($order_by,$limit);
		//$total_records		=  G_User_Helper::countTotalRecords();

		//Utilities::displayArray($user);

		$total = count($user);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($user) . "}";	
	}

	function _load_roles_dt()
	{
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort'] =='group_name') {
			$_GET['sort'] ='g.group_name';
		}

		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;
		$fields   = array("id","name","description");

		//newly added for filtering the display of role name on not super admin accouont
		$user_role = $this->global_user_role_name;

		$r = new G_Role();	
		$roles 		   = $r->getAllRecordsIsNotArchive($order_by, $limit, $fields, $user_role);
		
		foreach( $roles as $key => $role){
			foreach($role as $sub_key => $value){				
				if( $sub_key == "id" ){
					$new_role_data[$key][$sub_key] = Utilities::encrypt($value);
				}else{
					$new_role_data[$key][$sub_key] = $value;
				}
			}
		}
		
		$total_records = $r->countTotalRecordsIsNotArchive();

		$total = count($new_role_data);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($new_role_data) . "}";	
	}

	function _load_breaktime_schedules_dt()
	{
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort'] =='group_name') {
			$_GET['sort'] ='g.group_name';
		}

		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;
		$fields   = array("id","CONCAT(DATE_FORMAT(schedule_in,'%h:%i%p'),' to ', DATE_FORMAT(schedule_out,'%h:%i%p'))AS schedule","break_time_schedules","applied_to","DATE_FORMAT(date_start,'%M %d, %Y')AS starts_on");

		$br    = new G_Break_Time_Schedule_Header();	
		$schedules = $br->getAllActiveRecords($order_by, $limit, $fields);
		
		foreach( $schedules as $key => $schedule){
			foreach($schedule as $sub_key => $value){				
				if( $sub_key == "id" ){					
					$data[$key]['eid'] = Utilities::encrypt($value);
				}
				$data[$key][$sub_key] = $value;
			}
		}
		
		$total_records = $br->countTotalActiveRecords();

		$total = count($data);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _load_application_status_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$application_status		= G_Settings_Application_Status_Finder::findAll($order_by,$limit);
		$total_records 			=  G_Settings_Application_Status_Helper::countTotalRecords();
		
		foreach ($application_status as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _sub_load_request_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$application_status		= G_Settings_Request_Finder::findAll($order_by,$limit);
		$total_records 			= G_Settings_Request_Helper::countTotalRecords();
		
		foreach ($application_status as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_request_dt()
	{
		$requests = G_Settings_Request_Finder::findAllByIsNotArchiveAndIsActive();
		$this->var['requests'] = $requests;			
		$this->view->noTemplate();
		$this->view->render('settings/options/request_approvers/_request_dt.php',$this->var);
	}
	
	function _load_request_dt_serversided()
	{			
		$this->view->noTemplate();
		$this->view->render('settings/options/request_approvers/_request_dt.php',$this->var);
	}
	
	function _load_request_approvers_dt_depre()
	{
		$this->var['gsr']      = G_Settings_Request_Finder::findById($_POST['request_id']);
		$this->var['approvers']= G_Settings_Request_Approver_Finder::findAllBySettingsRequestId($_POST['request_id'],"level ASC");	
		$this->view->noTemplate();
		$this->view->render('settings/options/request_approvers/_request_approvers_dt.php',$this->var);
	}
	
	function _request_dt()
	{
		//Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_REQUEST);		
		$dt->setColumns('title,request_type,applied_to_description');	
		$dt->setCondition('is_archive = "' . Settings_Request::NO . '"');	
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		/*$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editRequest(id);\"></a></li><li><a title=\"Copy Settings\" id=\"edit\" class=\"ui-icon ui-icon-copy  g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:copyRequestSettings(id);\"></a></li><li><a title=\"Approvers\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"' . url('settings/approvers?hid=id') . '\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveRequestSettings(id);\"></a></li></ul></div>'));*/
		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editRequest(id);\"></a></li><li><a title=\"Copy Settings\" id=\"edit\" class=\"ui-icon ui-icon-copy  g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:copyRequestSettings(id);\"></a></li><li><a title=\"Approvers\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"' . url('settings/approvers?hid=id') . '\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveRequestSettings(id);\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function approvers()
	{
		if($_GET['hid']){
			$r = G_Settings_Request_Finder::findById(Utilities::decrypt($_GET['hid']));
			if($r){
				Jquery::loadMainInlineValidation2();
				Jquery::loadMainModalExetend();	
				Jquery::loadMainTipsy();
				Yui::loadMainDatatable();
				Jquery::loadMainJqueryFormSubmit();
				Jquery::loadMainJqueryDatatable();
				Jquery::loadMainTextBoxList();
				$this->var['r']			 = $r;
				$this->var['approvers']  = 'selected';
				$this->var['page_title'] = 'Settings';
				$this->view->setTemplate('template_settings.php');
				$this->view->render('settings/options/request_approvers/approvers.php',$this->var);		
			}else{
				redirect('settings/options?sidebar=10');
			}
		}else{
			redirect('settings/options?sidebar=10');
		}
	}
	
	
	function options()
	{
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainTipsy();
		Yui::loadMainDatatable();
		$selected = $_GET['sidebar'];
		if($selected==1 || $selected=='') {
			/*$this->var['subdivision_type_sb']	= 'selected';
			$this->var['module_title']			= 'Subdivision Type';

			$render = 'settings/options/subdivision_type/index.php';*/
			redirect("settings");
		}elseif($selected==2){
			$this->var['module_title']			= 'Dependent Relation';
			$this->var['dependent_relation_sb'] = 'selected';
			$render = 'settings/options/dependent_relationship/index.php';
		}elseif($selected==3){
			
			$this->var['module_title']	= 'Pay Period';
			$this->var['payperiod_sb'] 	= 'selected';
			$render = 'settings/options/pay_period/index.php';
		}elseif($selected==4){
			Jquery::loadMainJqueryFormSubmit();
			$this->var['module_title']	= 'Skill Management';
			$this->var['skill_mgt_sb'] 	= 'selected';
			$render = 'settings/options/skill_management/index.php';
		}elseif($selected==5){
			$this->var['module_title']	= 'License';
			$this->var['license_sb'] 	= 'selected';
			$render = 'settings/options/license/index.php';
		}elseif($selected==6){
			$this->var['module_title']	= 'Location';
			$this->var['location_sb'] 	= 'selected';
			$render = 'settings/options/location/index.php';
		}elseif($selected==7){
			$this->var['module_title']			= 'Membership';
			$this->var['membership_type_sb'] 	= 'selected';
			$render = 'settings/options/membership_type/index.php';
		}elseif($selected==8){
			$this->var['module_title']			= 'Employment Status';
			$this->var['employment_status_sb'] 	= 'selected';
			$render = 'settings/options/employment_status/index.php';
		}elseif($selected==9){
			$this->var['module_title']			= 'Application Status';
			$this->var['application_status'] 	= 'selected';
			$render = 'settings/options/application_status/index.php';
		}elseif($selected==10){			
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainJqueryDatatable();			
			Jquery::loadMainTextBoxList();
			$this->var['module_title']			= 'Request Approvers';
			$this->var['request_approvers_sb'] 	= 'selected';
			$render = 'settings/options/request_approvers/request.php';
		}elseif($selected==11){			
			Utilities::checkModulePackageAccess('attendance','payroll');
			Jquery::loadMainJqueryFormSubmit();
			$this->var['approvers'] 			= 'selected';
			$this->var['deduction_breakdown_sb']= 'selected';
			$render = 'settings/options/deduction_breakdown/index.php';
		}elseif($selected==12){		
			Utilities::checkModulePackageAccess('attendance','leave_request');	
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainJqueryDatatable();	
			Jquery::loadMainTipsy();
			$this->var['module_title']	= 'Leave Type Management';
			$this->var['leave_type_sb'] = 'selected';
			$render = 'settings/options/leave_type/leave_type.php';
		}elseif($selected==13){			
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainJqueryDatatable();	
			Jquery::loadMainTipsy();
			$this->var['module_title']	    = 'Deduction Type Management';
			$this->var['deduction_type_sb'] = 'selected';
			$render = 'settings/options/deduction_type/index.php';
		}elseif($selected==14){			
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainJqueryDatatable();	
			Jquery::loadMainTipsy();			
			$this->var['module_title']	      = 'Employee Status Management';
			$this->var['employee_status_sb'] = 'selected';
			$render = 'settings/options/employee_status/employee_status.php';
		}

		elseif($selected==15){	

			$this->var['module_title']	      = 'Project History';
			$this->var['project_history_status_sb'] = 'selected';
			$render = 'settings/options/project_history/project_history_view.php';
		}

		elseif($selected==16){	

			$this->var['module_title']	      = 'Activities History';
			$this->var['activity_history_status_sb'] = 'selected';
			$render = 'settings/options/activity_history/activity_history_view.php';
		}
		
		
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render($render,$this->var);
		
	}
	
	function _load_deduction_breakdown_list() {
		$d = new G_Settings_Deduction_Breakdown();
		$options = $d->getOptionsSalaryCredit();
		$data    = $d->getAllContributions();

		$wd = new G_Settings_Weekly_Deduction_Breakdown();
		$weekly_options = $wd->getOptionsSalaryCredit();
		$weekly_data    = $wd->getAllContributions();


		$md = new G_Settings_Monthly_Deduction_Breakdown();
		$monthly_options = $md->getOptionsSalaryCredit();
		$monthly_data    = $md->getAllContributions();

		$this->var['salary_credit_options'] = $options;
		$this->var['deductions'] = $data;
		$this->var['weekly_deductions'] = $weekly_data;
		$this->var['monthly_deductions'] = $monthly_data;
		$this->var['is_taxable'] = G_Settings_Deduction_Breakdown::YES;
		$this->view->render('settings/options/deduction_breakdown/deduction_breakdown_list.php',$this->var);
	}
	
	function ajax_edit_deduction_breakdown() {
		if(!empty($_GET)) {
			$eid = $_GET['h_id'];
			$id  = Utilities::decrypt($eid);
			$deduction = G_Settings_Deduction_Breakdown_Finder::findById($id);
			if( $deduction ){
				$yes_no        = $deduction->getOptionsIsTaxable();
				$salary_credit = $deduction->getOptionsSalaryCredit();
				unset($salary_credit[G_Settings_Deduction_Breakdown::OPTION_SALARY_CREDIT_NA]); //Remove NA
				
				$this->var['yes_no']        = $yes_no;
				$this->var['salary_credit'] = $salary_credit;
				$this->var['action']	= 'settings/_update_deduction_breakdown';
				$this->var['deduction'] = $deduction;
				$this->view->render('settings/options/deduction_breakdown/forms/ajax_edit_deduction_breakdown.php',$this->var);		
			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}
	
	function _update_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setBreakdown($_POST['1st_cutoff'].':'.$_POST['2nd_cutoff']);
				$db->setIsTaxable($_POST['is_taxable']);
				$db->setSalaryCredit($_POST['salary_credit']);
				$db->save();				
				$json['is_saved'] = true;		
			} else {$json['is_saved'] = false;}
		} else {$json['is_saved'] = false;}
		echo json_encode($json);
	}
	
	function ajax_edit_weekly_deduction_breakdown() {
		if(!empty($_GET)) {
			$eid = $_GET['h_id'];
			$id  = Utilities::decrypt($eid);
			$deduction = G_Settings_Weekly_Deduction_Breakdown_Finder::findById($id);
			if( $deduction ){
				$yes_no        = $deduction->getOptionsIsTaxable();
				$salary_credit = $deduction->getOptionsSalaryCredit();
				unset($salary_credit[G_Settings_Weekly_Deduction_Breakdown::OPTION_SALARY_CREDIT_NA]); //Remove NA
				
				$this->var['yes_no']        = $yes_no;
				$this->var['salary_credit'] = $salary_credit;
				$this->var['action']	= 'settings/_update_weekly_deduction_breakdown';
				$this->var['deduction'] = $deduction;
				$this->view->render('settings/options/deduction_breakdown/forms/ajax_edit_weekly_deduction_breakdown.php',$this->var);		
			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}
	
	function _update_weekly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Weekly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$total = $_POST['1st_cutoff'] + $_POST['2nd_cutoff'] + $_POST['3rd_cutoff']+ $_POST['4th_cutoff'];
				
					if( !($total > 100)){
								$db->setBreakdown($_POST['1st_cutoff'].':'.$_POST['2nd_cutoff'].':'.$_POST['3rd_cutoff'].':'.$_POST['4th_cutoff']);
				$db->setIsTaxable($_POST['is_taxable']);
				$db->setSalaryCredit($_POST['salary_credit']);
				$db->save();				
				$json['is_saved'] = true;		
					}else{
						$json['is_saved'] = false;
						$json['message'] = 'Contribution Exceeds to 100%';
					}
			
			} else {$json['is_saved'] = false;}
		} else {$json['is_saved'] = false;}
		echo json_encode($json);
	}
	
	function _deactivate_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Deduction_Breakdown::NO);
				$db->save();
			}
		}
	}
	
	function _deactivate_weekly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Weekly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Weekly_Deduction_Breakdown::NO);
				$db->save();
			}
		}
	}
	
	function _activate_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Deduction_Breakdown::YES);
				$db->save();
			}
		}
	}
	
	function _activate_weekly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Weekly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Weekly_Deduction_Breakdown::YES);
				$db->save();
			}
		}
	}
	
	function ajax_get_positions_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array('emp-' . Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
			
			$positions = G_Job_Finder::searchByTitle($q,$this->company_structure_id);
			
			foreach ($positions as $p) {
				$response[] = array('pos-' . Utilities::encrypt($p->getId()), $p->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_get_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_get_departments_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);	
		$t = $_GET['type'];	
		if ($q != '') {
			$departments = G_Company_Structure_Finder::searchByTitle($q,$this->company_structure_id);
			
			foreach ($departments as $d) {
				$response[] = array(Utilities::encrypt($d->getId()), $d->getTitle(), null);				
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function verifyIfDescriptionExists($description, $type, $request_id)
	{
		$count = G_Settings_Request_Helper::isDescriptionExists($description, $type, $request_id);
		return $count;		
	}
	
	function ajax_get_positions_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$positions = G_Job_Finder::searchByTitle($q,$this->company_structure_id);
			
			foreach ($positions as $p) {
				$response[] = array(Utilities::encrypt($p->getId()), $p->getTitle(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_add_new_request() 
	{		
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['action']	 = 'settings/_load_save_update_settings_request';
		$this->var['page_title'] = 'Settings';		
		$this->view->render('settings/options/request_approvers/forms/ajax_add_request.php',$this->var);
	}
	
	function ajax_edit_request() 
	{
		if($_POST['request_id']){
			$gsr = G_Settings_Request_Finder::findById($_POST['request_id']);						
			$this->var['gsr']  	     = $gsr;
			$this->var['employees']  = $employees   = unserialize($gsr->getEmployees());
			$this->var['positions']  = $positions   = unserialize($gsr->getPositions());
			$this->var['departments']= $departments = unserialize($gsr->getDepartments());
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['action']	 = 'settings/_load_save_update_settings_request';
			$this->var['page_title'] = 'Settings';				
			$this->view->render('settings/options/request_approvers/forms/ajax_edit_request.php',$this->var);			
		}
	}

	function ajax_edit_request_approvers() 
	{
		if($_GET['eid']){			
			$id = Utilities::decrypt($_GET['eid']);			
			$ra = new G_Request_Approver();
			$ra->setId($id);
			$data = $ra->getDataById();			

			if( !empty($data) ){
				$key_level = 0;				
				foreach($data['level'] as $key => $value){	
					$data_level = $value['level'];
					if( $key_level != $data_level ){
						$key_level   =$data_level; 
						$ini_script .= "
							var t{$key_level} = new $.TextboxList('#approver_{$key_level}', {unique: true,plugins: {
						    autocomplete: {
						      minLength: 2,
						      onlyFromValues: true,
						      queryRemote: true,
						      remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}						    
						    }}});								
						";
					} 
					$employee_name = $value['employee_name'];
					$eid           = Utilities::encrypt($value['employee_id']);
					$ini_script .= "t{$key_level}.add('{$employee_name}','{$eid}',null);";
								
				}				

				$requestors = $data['requestors'];
				foreach($requestors as $key => $value){ 
				    $eid   = Utilities::encrypt($value['employee_department_group_id']) . ":" . $value['employee_department_group'];
				    $value = $value['description'];								
				    $ini_requestors_script .= "t_requestors.add('{$value}','{$eid}');";
				}
				
				$this->var['ini_requestors_script'] = $ini_requestors_script;
				$this->var['ini_script'] = $ini_script;
				$this->var['header']     = $data['header'][0];
				$this->var['eid']        = Utilities::encrypt($data['header'][0]['id']);
				$this->var['level']      = $data['level'];				
				$this->var['token']		 = Utilities::createFormToken();
				$this->var['action']	 = 'settings/update_request_approvers';
				$this->var['page_title'] = 'Settings';				
				$this->view->render('settings/request_approvers/forms/edit_request_approvers.php',$this->var);			
			}else{
				echo "Record not found";
			}			
		}else{
			echo "Record not found";
		}
	}

	function ajax_copy_request_settings() 
	{
		if($_POST['request_id']){
			$gsr = G_Settings_Request_Finder::findById($_POST['request_id']);				
			$this->var['gsr']  	     = $gsr;
			$this->var['employees']  = unserialize($gsr->getEmployees());
			$this->var['positions']  = unserialize($gsr->getPositions());
			$this->var['departments']= unserialize($gsr->getDepartments());
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['action']	 = 'settings/_load_save_update_settings_request';
			$this->var['page_title'] = 'Settings';		
			$this->view->render('settings/options/request_approvers/forms/ajax_copy_request_settings.php',$this->var);
		}
	}
	
	function ajax_add_request_approvers() 
	{
		if($_POST['request_id']){
			$gsr = G_Settings_Request_Finder::findById($_POST['request_id']);				
			$this->var['gsr']  	     = $gsr;			
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['action']	 = 'settings/_load_add_request_approvers';
			$this->var['page_title'] = 'Settings';		
			$this->view->render('settings/options/request_approvers/forms/ajax_add_approvers.php',$this->var);
		}
	}
	
	function ajax_sort_approvers_level() 
	{
		if($_POST['request_id']){
			$approvers = G_Settings_Request_Approver_Finder::findAllBySettingsRequestId($_POST['request_id'],'level ASC');
			$this->var['request_id'] = $_POST['request_id'];				
			$this->var['approvers']  = $approvers;			
			$this->var['page_title'] = 'Settings';		
			$this->view->render('settings/options/request_approvers/forms/ajax_sort_approvers_level.php',$this->var);
		}
	}
	
	function _sort_approvers_level()
	{
		$action 				= mysql_real_escape_string($_POST['action']); 
		$updateRecordsArray 	= $_POST['view'];
		$table_name = $_GET['dbname'];
		$field_name = $_GET['field'];
		if ($action == "updateRecordsListings"){
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {				
				$query = "UPDATE " . $table_name ." SET " . $field_name . " = " . $listingCounter . " WHERE id = " . $recordIDValue;				
				mysql_query($query) or die('Error, insert query failed');
				$listingCounter++;	
			}
			$return['request_id'] = $_GET['request_id'];
			$return['is_success'] = 1;			
		}else{$return['is_success'] = 0;}
		echo json_encode($return);
	}	
	
	function _load_delete_exam()
	{
		if($_POST['eid']){
			$e = G_Exam_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($e){				
				//Get Questions
				$q = G_Exam_Question_Finder::findByExamId($e->getId());
				if($q){
					//Get Choices - Delete
					$c = G_Exam_Choices_Finder::findByQuestionId($q->getId());
					if($q){
						$c->delete();						
					}
					$q->delete();
				}	
				$e->delete();
				$return['url']		  = url('settings/examination_template');			
				$return['is_success'] = 1;
				$return['message']	  = 'Record was successfully deleted.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Error in sql.';
			}
		}		
		echo json_encode($return);
	}	
	
	function _load_archive_request_settings()
	{
		if($_POST['request_id']){
			$gsr = G_Settings_Request_Finder::findById($_POST['request_id']);
			if($gsr){
				$gsr->setIsArchive(Settings_Request::YES);
				$gsr->archive();
				
				$return['is_success'] = 1;
				
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_employee_status()
	{
		if($_POST['eid']){
			$gses = G_Settings_Employee_Status_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gses){			
				$gses->archive();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_requirement()
	{
		if($_POST['eid']){
			$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gsr){			
				$gsr->archive();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_benefit()
	{
		if($_POST['eid']){
			$gscb = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gscb){			
				$gscb->archive();				
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_restore_benefit()
	{
		if($_POST['eid']){
			$gscb = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gscb){			
				$gscb->restore();				
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_memo()
	{
		if($_POST['eid']){
			$gsm = G_Settings_Memo_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gsm){			
				$gsm->archive();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_restore_requirement()
	{
		if($_POST['eid']){
			$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gsr){			
				$gsr->restore();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_restore_memo()
	{
		if($_POST['eid']){
			$gsm = G_Settings_Memo_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gsm){			
				$gsm->restore();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_restore_employee_status()
	{
		if($_POST['eid']){
			$gses = G_Settings_Employee_Status_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gses){			
				$gses->restore();
				$return['is_success'] = 1;
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_company_branch()
	{
		if($_POST['eid']){
			$gcb = G_Company_Branch_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gcb){				
				$gcb->archive();
								
				$return['is_success'] = 1;
				$return['message']	  = 'Record was successfully sent to archive.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Error in sql.';
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_performance_template()
	{
		if($_POST['eid']){
			$gp = G_Performance_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gp){				
				$gp->archive();
				
				$return['url']		  = url('settings/performance_template');			
				$return['is_success'] = 1;
				$return['message']	  = 'Record was successfully sent to archive.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Error in sql.';
			}
		}		
		echo json_encode($return);
	}
	
	function _load_restore_performance_template()
	{
		if($_POST['eid']){
			$gp = G_Performance_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gp){				
				$gp->restore();
								
				$return['is_success'] = 1;
				$return['message']	  = 'Record was successfully restored.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Error in sql.';
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_company_department()
	{
		if($_POST['eid']){
			$gcs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gcs){		
				$return['branch_id']  = Utilities::encrypt($gcs->getCompanyBranchId());		
				$gcs->directArchive();
								
				$return['is_success'] = 1;
				$return['message']	  = 'Record was successfully sent to archive.';
				
			}else{
				$return['is_success'] = 2;
				$return['message']    = 'Error in sql.';
			}
		}		
		echo json_encode($return);
	}
	
	function _load_archive_group()
	{
		if($_POST['group_id']){
			$gcs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['group_id']));
			if($gcs){
				$parent_id = Utilities::encrypt($gcs->getParentId());
				
				$gcs->setIsArchive(Settings_Request::YES);
				$gcs->archive();
				
				$return['parent_id']  = $parent_id;
 				$return['is_success'] = 1;
				
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_delete_group_member()
	{
		if($_POST['employee_id']){
			$gsh = G_Employee_Subdivision_History_Finder::findById(Utilities::decrypt($_POST['employee_id']));
			if($gsh){				
				$gsh->delete();
 				$return['is_success'] = 1;
				
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}

	function _load_delete_request_approvers()
	{
		$return['is_success'] = 0;

		if($_POST['eid']){
			$id = Utilities::decrypt($_POST['eid']);
			$ra = new G_Request_Approver();
			$ra->setId($id);
			$return = $ra->deleteRequestApprovers();
		}		

		echo json_encode($return);
	}

	function _load_delete_role()
	{
		$return = array();
		if($_POST['role_id']){
			$id = Utilities::decrypt($_POST['role_id']); 
			$r  = G_Role_Finder::findById($id);
			if( $r ){
				$return = $r->deleteRoleAndActions();
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		
		echo json_encode($return);
	}

	function _load_delete_benefit_enrollee()
	{
		$return = array();
		if($_POST['eid']){
			$id = Utilities::decrypt($_POST['eid']); 
			$b  = G_Employee_Benefits_Main_Finder::findById($id);

			if( $b ){				
				$return = $b->deleteEnrollee();
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		

		//General Reports / Shr Audit Trail
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, ' Load BENEFITS', '', '', '', 1, '', '');

		echo json_encode($return);
	}

	function _load_delete_benefit()
	{
		$return = array();
		if($_POST['benefit_id']){
			$id = Utilities::decrypt($_POST['benefit_id']); 
			$b  = G_Settings_Employee_Benefit_Finder::findById($id);
			if( $b ){
				$return = $b->deleteBenefit();
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		

		//General Reports / Shr Audit Trail
		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, 'BENEFITS', $b->name, '', '', 1, '', '');

		echo json_encode($return);
	}

	function _load_delete_credit_condition() {
		$return = array();
		if($_POST['lc_id']){
			$id = Utilities::decrypt($_POST['lc_id']); 
			$u  = G_Settings_Leave_Credit_Finder::findById($id);
			if( $u ){
				$u->delete();
				$return['message'] 	  = '';
				$return['is_success'] = true;
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		
		echo json_encode($return);		
	}

	function _load_delete_user()
	{
		$return = array();
		if($_POST['user_id']){
			$id = Utilities::decrypt($_POST['user_id']); 
			$u  = G_Employee_User_Finder::findById($id);
			if( $u ){
				
				//General Reports / Shr Audit Trail
				
				 $sql = "
					SELECT * 
					FROM " . G_EMPLOYEE_USER ." 
					WHERE id =". Model::safeSql($id) ."
					LIMIT 1
				";		
				$result = Model::runSql($sql);
				$row = Model::fetchAssoc($result);
				
				$shr_emp = G_Employee_Helper::findByEmployeeId($row['employee_id']);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, ' User Account of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

				$return = $u->deleteUser();

			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;

				//General Reports / Shr Audit Trail
				
				 $sql = "
					SELECT * 
					FROM " . G_EMPLOYEE_USER ." 
					WHERE id =". Model::safeSql($id) ."
					LIMIT 1
				";		
				$result = Model::runSql($sql);
				$row = Model::fetchAssoc($result);
				
				$shr_emp = G_Employee_Helper::findByEmployeeId($row['employee_id']);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, ' User Account of ', $emp_name, '', '', 0, $shr_emp['position'], $shr_emp['department']);

			}
		}		
		echo json_encode($return);
		
	}

	function _load_delete_breaktime_schedule()
	{
		$return = array();
		if($_POST['eid']){
			$id = Utilities::decrypt($_POST['eid']); 
			$br = G_Break_Time_Schedule_Header_Finder::findById($id);
			if( $br ){
				$return = $br->deleteBreakTimeSchedule();
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_delete_request_approver()
	{
		if($_POST['approver_id']){
			$gsra = G_Settings_Request_Approver_Finder::findById(Utilities::decrypt($_POST['approver_id']));
			if($gsra){				
				$return['request_id'] = $gsra->getSettingsRequestId();
				$gsra->delete();
				$return['is_success'] = 1;
				
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function _load_assign_override_level()
	{
		if($_POST['approver_id']){
			$gsra = G_Settings_Request_Approver_Finder::findById(Utilities::decrypt($_POST['approver_id']));
			if($gsra){				
				$return['request_id'] = $gsra->getSettingsRequestId();			
				$gsra->updateOverrideLevel();
				$return['is_success'] = 1;
				
			}else{
				$return['is_success'] = 0;
			}
		}		
		echo json_encode($return);
	}
	
	function create_csv($string,$type)
	{	
		$arr = explode(",",$string);
		foreach($arr as $p){			
			if (strpos($p,$type)!== false) {				
				$new_array[] = str_replace($type,"",$p);
			}
		}

		return implode(",",$new_array);
	}
	
	function _load_save_update_settings_request()
	{
		$this->login();
		if(Utilities::isFormTokenValid($_POST['token'])) {			
			if($_POST['title']){
				if($_POST['req_id']){
					$gsr = G_Settings_Request_Finder::findById(Utilities::decrypt($_POST['req_id']));
					$return['message'] = 'Record was successfully updated';										
				}else{
					$gsr = new G_Settings_Request();			
					$gsr->setDateCreated($this->c_date);
					$return['message'] = 'Record Saved';						
				}
				
				//Create csv
					$depts = $this->create_csv($_POST['pos_emp_dept'],'dept-');
					$emp   = $this->create_csv($_POST['pos_emp_dept'],'emp-');
					$pos   = $this->create_csv($_POST['pos_emp_dept'],'pos-');
				//
				
				if($_POST['apply_to_all_departments']){
					$dep_ids = Settings_Request::APPLY_TO_ALL;
					$tags .= 'All Departments <br>';
				}else{					
					$dep = $this->generateDepartmentArray($depts,$_POST['type'],Utilities::decrypt($_POST['req_id']));
					if($dep){
						$tags   .= $dep['desc'];
						$dep_ids = $dep['ids']; 
						$exists  = $dep['exists']; 
						if($exists){
							$return['message']  .= " <br ><br >Cannot save the below data due to being used of the same request type <br>" . $exists; 
						}
					}
				}
				
				if($_POST['apply_to_all_positions']){
					$pos_ids = Settings_Request::APPLY_TO_ALL;
					$tags .= 'All Positions <br>';
				}else{
					$pos = $this->generatePositionArray($pos,$_POST['type'],Utilities::decrypt($_POST['req_id']));
					if($pos){
						$tags   .= "," . $pos['desc'];
						$pos_ids = $pos['ids'];  
						$exists  = $pos['exists'];  
						if($exists){
							$return['message']  .= $exists; 
						}
					}
				}
				
				if($_POST['apply_to_all_employees']){
					$emp_ids = Settings_Request::APPLY_TO_ALL;
					$tags .= 'All Employees <br>';
				}else{
					$emp = $this->generateEmployeeArray($emp,$_POST['type'],Utilities::decrypt($_POST['req_id']));
					if($emp){
						$tags   .= "," . $emp['desc'];
						$emp_ids = $emp['ids'];  
						$exists  = $emp['exists']; 
						if($exists){
							$return['message']  .= $exists;  
						}
					}
				}
				
				$gsr->setTitle($_POST['title']);
				$gsr->setType($_POST['type']);		
				$gsr->setDepartments(serialize($dep_ids));
				$gsr->setPositions(serialize($pos_ids));
				$gsr->setEmployees(serialize($emp_ids));
				$gsr->setDescription($tags);		
				$gsr->setIsActive(Settings_Request::YES);	
				$gsr->setIsArchive(Settings_Request::NO);	
				$gsr->setDateCreated($this->c_date);								
				$rId = $gsr->save();
				
				//Copy Approvers
					if($_POST['copy_approvers_settings']){
						$approvers = G_Settings_Request_Approver_Finder::findAllBySettingsRequestId($_POST['org_request_id']);
						$gsr = G_Settings_Request_Finder::findById($rId);
						foreach($approvers as $a){
							$rep = new G_Settings_Request_Approver();							
							$rep->setPositionEmployeeId($a->getPositionEmployeeId());
							$rep->setType($a->getType());	
							$rep->setLevel($a->getLevel());				
							$rep->setOverrideLevel($a->getOverrideLevel());	
							$rep->save($gsr);			
						}
					}
				//
				 
				$return['is_saved'] = true;
			}else{
				$return['message']  = 'Error on saving your request.';
				$return['is_saved'] = false;
			}
		}else{
			$return['message']  = 'Error on saving your request.';
			$return['is_saved'] = false;
		}		
		echo json_encode($return);		
	}
	
	function get_sort_approvers_last_entry($request_id)
	{
		$gsra = G_Settings_Request_Approver_Finder::findLastEntryBySettingsRequestId($request_id);
		if($gsra){
			return $gsra->getLevel() + 1;
		}else{
			return 1;	
		}
	}
	
	function _load_add_request_approvers()
	{
		$this->login();
		if(Utilities::isFormTokenValid($_POST['token'])) {			
			if($_POST['request_id']){
				$gsr = G_Settings_Request_Finder::findById(Utilities::decrypt($_POST['request_id']));	
				$duplicates = 0;
				
				//Create csv
					$pos = $this->create_csv($_POST['emp_pos'], 'pos-');
					$emp = $this->create_csv($_POST['emp_pos'], 'emp-');
				//
				if($gsr){
					if($_POST['apply_to_all_positions']){
						//Validate if already exsits
						$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Settings_Request::APPLY_TO_ALL,Settings_Request_Approver::POSITION_ID);	
						if($count == 0){
							$gsra = new G_Settings_Request_Approver();	
							$gsra->setLevel($this->get_sort_approvers_last_entry($gsr->getId()));																
							$gsra->setPositionEmployeeId(Settings_Request::APPLY_TO_ALL);
							$gsra->setType(Settings_Request_Approver::POSITION_ID);							
							//$gsra->setOverrideLevel('');
							$gsra->save($gsr);
						}else{
							$duplicates++;
						}
						
					}else{
						if($pos){
							//Verify if all apply to all positions already exists
							$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Settings_Request::APPLY_TO_ALL,Settings_Request_Approver::POSITION_ID);	
							if($count > 0){
								$errPAll = '<br><b>Cannot add position. All Positions is already applied.</b><br>';
							}else{
								$pos_ids = explode(",",$pos);
								foreach($pos_ids as $pos){
									//Validate if already exsits
									$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Utilities::decrypt($pos),Settings_Request_Approver::POSITION_ID);	
									if($count == 0){
										$gsra = new G_Settings_Request_Approver();	
										$gsra->setLevel($this->get_sort_approvers_last_entry($gsr->getId()));													
										$gsra->setPositionEmployeeId(Utilities::decrypt($pos));
										$gsra->setType(Settings_Request_Approver::POSITION_ID);									
										//$gsra->setOverrideLevel('');
										$gsra->save($gsr);	
									}else{
										$duplicates++;
									}
								}
							}
						}
					}
					
					if($_POST['apply_to_all_employees']){	
						//Validate if already exsits
						$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Settings_Request::APPLY_TO_ALL,Settings_Request_Approver::EMPLOYEE_ID);	
							if($count == 0){										
								$gsra = new G_Settings_Request_Approver();
								$gsra->setLevel($this->get_sort_approvers_last_entry($gsr->getId()));								
								$gsra->setPositionEmployeeId(Settings_Request::APPLY_TO_ALL);
								$gsra->setType(Settings_Request_Approver::EMPLOYEE_ID);	
								$gsra->save($gsr);
							}else{
								$duplicates++;
							}
									
					}else{
						if($emp){
							//Verify if all apply to all employees already exists
							$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Settings_Request::APPLY_TO_ALL,Settings_Request_Approver::EMPLOYEE_ID);	
							if($count > 0){
								$errEAll = '<br><b>Cannot add employees. All Employees is already applied.</b><br>';
							}else{
								$emp_ids = explode(",",$emp);
								foreach($emp_ids as $emp){
									//Validate if already exsits
									$count = G_Settings_Request_Approver_Helper::isPositionEmployeeIdExistsInRequestId($gsr->getId(),Utilities::decrypt($emp),Settings_Request_Approver::EMPLOYEE_ID);	
										if($count == 0){	
											$gsra = new G_Settings_Request_Approver();		
											$gsra->setLevel($this->get_sort_approvers_last_entry($gsr->getId()));												
											$gsra->setPositionEmployeeId(Utilities::decrypt($emp));
											$gsra->setType(Settings_Request_Approver::EMPLOYEE_ID);									
											$gsra->save($gsr);	
										}else{
											$duplicates++;
										}
								}
							}
						}
					}
					
					//Construct Err / Duplicate Msg
					if($duplicates >0){
						$err_msg = '<br><br><b>Cannot save duplicate entries.<br>Duplicate entries found :' . $duplicates . '</b>';
					}
					
					$return['message']    = "Record Updated" . $err_msg . $errEAll . $errPAll;
					$return['request_id'] = $gsr->getId();
					$return['is_success'] = true;
					
				}else{
					$return['is_success'] = false;
				}
			}else{
				
			}
		}
		echo json_encode($return);		
	}
	
	function generatePositionArray($arPos , $type,  $request_id = 0)
	{
		$arPos  = explode(",",$arPos);
		//Position
		if(!empty($arPos)){
			foreach($arPos as $p){							
				$pos = G_Job_Finder::findById(Utilities::decrypt($p));
				if($pos){
					$check = $this->verifyIfDescriptionExists($pos->getTitle(),$type, $request_id);
					if($check > 0){
						$arExists[] = $pos->getTitle() . "<br>";
					}else{
						$newArPos[] = $pos->getId();	
						$arTitle[]  = $pos->getTitle() . "<br>";
					}
				}
			}	
			$pos_ids = implode(",",$newArPos);
		}
		
		$desc   = implode(",",$arTitle);
		$exists = implode(",",$arExists);		
		return array("ids" => $pos_ids , "desc" => $desc, "exists" => $exists);
	}
	
	function generateEmployeeArray($arEmp, $type,  $request_id = 0)
	{		
		$arEmp  = explode(",", $arEmp); 
		
		//Employee
		if(!empty($arEmp)){
			foreach($arEmp as $e){
				$emp = G_Employee_Finder::findById(Utilities::decrypt($e));
				if($emp){
					$check = $this->verifyIfDescriptionExists($emp->getFirstname() . " " . $emp->getLastname(),$type, $request_id);
					if($check > 0){
						$arExists[] = $emp->getFirstname() . " " . $emp->getLastname() . "<br>";
					}else{
						$newArEmp[] = $emp->getId();			
						$arTitle[]  = $emp->getFirstname() . " " . $emp->getLastname() . "<br>";			
					}
				}
			}
			$emp_ids = implode(",",$newArEmp);
		}
		
		$desc   = implode(",",$arTitle);
		$exists = implode(",",$arExists);		
		return array("ids" => $emp_ids , "desc" => $desc, "exists" => $exists);
	}
	
	function generateDepartmentArray($arDept, $type, $request_id = 0)
	{		
		$arDept = explode(",", $arDept);			
		
		//Department
		if(!empty($arDept)){
			foreach($arDept as $d){
				$dept = G_Company_Structure_Finder::findById(Utilities::decrypt($d));
				if($dept){
					$check = $this->verifyIfDescriptionExists($dept->getTitle(),$type, $request_id);
					if($check > 0){
						$arExists[]  = $dept->getTitle() . "<br>";
					}else{
						$newArDept[] = $dept->getId();				
						$arTitle[]   =  $dept->getTitle() . "<br>";
					}
				}
			}
			$dept_ids = implode(",",$newArDept);
		}
		
		$desc   = implode(",",$arTitle);
		$exists = implode(",",$arExists);
		return array("ids" => $dept_ids , "desc" => $desc, "exists" => $exists);
	}
	
	
	function employee_group_management_depre(){	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['branch'] = $branch = G_Company_Branch_Finder::findAll();

		$this->var['page_title'] 			= 'Settings';
		$this->var['employee_group_mgt_sb']	= 'selected';
		$this->var['module_title']			= 'Employee Group Management';
		//$this->view->setTemplate('template_settings.php');
		//$this->view->render('settings/employee_group_management/index.php',$this->var);
        //load_child_group_list('NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg');
        redirect('settings/group_tab?id=NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg');
	}

	function employee_group_management(){
		redirect('settings/company');
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$branch = G_Company_Branch_Finder::findAll();
        $g 	    = G_Group_Finder::findById($this->company_structure_id);

        $this->var['branch'] 				 = $branch;
        $this->var['is_parent_group'] 		 = $g->isParent();
		$this->var['h_company_structure_id'] = Utilities::encrypt($this->company_structure_id);
		$this->var['employee_group_mgt_sb']	 = 'selected';
		$this->var['page_title'] 		     = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/employee_group_management/employee_group_tab.php',$this->var);
	}

	function group_tab() {
		if(!empty($_GET)) {
			$eid = $_GET['id'];
			$hash = $_GET['hash'];
			//Utilities::verifyHash(Utilities::decrypt($eid),$hash);
			
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			$branch = G_Company_Branch_Finder::findAll();
	        $g      = G_Group_Finder::findById(Utilities::decrypt($_GET['id']));

	        $this->var['branch'] 				 = $branch; 
	        $this->var['is_parent_group']        = $g->isParent();
			$this->var['h_company_structure_id'] = $_GET['id'];
			$this->var['employee_group_mgt_sb']	 = 'selected';
			$this->var['page_title'] 		     = 'Settings';
			$this->view->setTemplate('template_settings.php');
			$this->view->render('settings/employee_group_management/employee_group_tab.php',$this->var);
		}
	}
	
	function _load_department_list_dt() {
		//if(!empty($_POST)) {
			//$this->var['h_branch_id'] = $_POST['h_branch_id'];
            $b = G_Company_Branch_Finder::findMain();
            $branch_id = Utilities::decrypt($b->getId());
            $this->var['h_branch_id'] = $branch_id;
			$this->view->render('settings/employee_group_management/_employee_group_list_dt.php',$this->var);
		//}
	}
	
	function _load_server_employee_group_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(COMPANY_STRUCTURE);
		$dt->setCustomField();
		$dt->setJoinTable();
		$dt->setJoinFields();
		$dt->setCondition(' type="'.G_Company_Structure::DEPARTMENT.'" AND company_branch_id='.Utilities::decrypt($_GET['h_branch_id']));
		$dt->setColumns('title');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		/*$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"'.url('settings/group_tab?id=id').'\"></ul></div>'));*/
		echo $dt->constructDataTableSub();
	}
	
		
	function _load_group_tab() {
		$this->view->render('settings/employee_group_management/employee_group_tab.php',$this->var);
	}
	
	function _load_group_list_dt() {
		if(!empty($_POST)) {
			$this->var['h_company_structure_id'] = $_POST['h_company_structure_id'];
			$this->view->render('settings/employee_group_management/_group_list_dt.php',$this->var);
		}
	}
	
	function _load_employee_group_trailing() {
		if(!empty($_POST)) {
			$breadcrumb	= new BreadCrumbs();
			$breadcrumb->setId(Utilities::decrypt($_POST['h_company_structure_id']));	
			$breadcrumbs = $breadcrumb->constructEmployeeGroupBreadCrumbs();	
			$this->var['breadcrumbs'] = $breadcrumbs;
			$cs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['h_company_structure_id']));
			if($cs) {
				$this->var['h_company_structure_parent_id'] = Utilities::encrypt($cs->getParentId());
			}
			$this->view->render('settings/employee_group_management/_breadcrumbs.php',$this->var);
		}
	}
	
	function _load_server_group_list_dt() {
        $g = G_Group_Finder::findById(Utilities::decrypt($_GET['h_company_structure_id']));
        $is_parent = $g->isParent();

        if ($is_parent) {
            Utilities::ajaxRequest();
            $dt = new Datatable();
            $c  = $_GET['iDisplayStart'];
            $dt->setPagination(1);
            $dt->setStart(1);
            $dt->setStartIndex(0);
            $dt->setDbTable(COMPANY_STRUCTURE);
            $dt->setCustomField();
            $dt->setJoinTable();
            $dt->setJoinFields();
            $dt->setCondition(" type = '" . G_Company_Structure::DEPARTMENT . "' AND parent_id=".Utilities::decrypt($_GET['h_company_structure_id']) . ' AND is_archive ="' . G_Company_Structure::NO . '"');
            $dt->setColumns('title');
            $dt->setOrder('ASC');
            $dt->setStartIndex(0);
            $dt->setSort(0);
            $dt->setNumCustomColumn(2);
            $dt->setCustomColumn(
                array(
                    //'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_child_group_list(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveGroup(\'e_id\');\"></a></li></ul></div>'));
                    '1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_child_department_list(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveGroup(\'e_id\');\"></a></li></ul></div>'));

            echo $dt->constructDataTable();
        } else {
            Utilities::ajaxRequest();
            $dt = new Datatable();
            $c  = $_GET['iDisplayStart'];
            $dt->setPagination(1);
            $dt->setStart(1);
            $dt->setStartIndex(0);
            $dt->setDbTable(COMPANY_STRUCTURE);
            $dt->setCustomField();
            $dt->setJoinTable();
            $dt->setJoinFields();
            $dt->setCondition(" type = '" . G_Company_Structure::GROUP . "' AND parent_id=".Utilities::decrypt($_GET['h_company_structure_id']) . ' AND is_archive ="' . G_Company_Structure::NO . '"');
            $dt->setColumns('title');
            $dt->setOrder('ASC');
            $dt->setStartIndex(0);
            $dt->setSort(0);
            $dt->setNumCustomColumn(2);
            $dt->setCustomColumn(
                array(
                    '1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_child_group_list(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveGroup(\'e_id\');\"></a></li></ul></div>'));
            echo $dt->constructDataTable();
        }
	}
	
	function _load_employee_list_dt() {
		if(!empty($_POST)) {
			$this->var['h_company_structure_id'] = $_POST['h_company_structure_id'];
			$this->view->render('settings/employee_group_management/_employee_list_dt.php',$this->var);
		}
	}
	
	function _load_server_employee_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_SUBDIVISION_HISTORY);
		$dt->setCustomField(array('employee_name' => 'e.firstname,e.lastname','position_name' => 'gjh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e LEFT JOIN ".G_EMPLOYEE_JOB_HISTORY." gjh ON gjh.employee_id = e.id");
		$dt->setJoinFields(G_EMPLOYEE_SUBDIVISION_HISTORY . ".employee_id = e.id");
		$dt->setCondition(" gjh.end_date = '' AND ".G_EMPLOYEE_SUBDIVISION_HISTORY.".company_structure_id =".Utilities::decrypt($_GET['h_company_structure_id']));
		$dt->setColumns('e.lastname');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteGroupMember(\'e_id\');\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_insert_new_group() {
		if(!empty($_POST)) {
            $company_structure_id = Utilities::decrypt($_POST['company_structure_id_add']);
			$cs = G_Company_Structure_Finder::findById($company_structure_id);

            $g = G_Group_Finder::findById($company_structure_id);
            if ($g->isParent()) {
                $type = G_Company_Structure::DEPARTMENT;
                $message = 'Department has been added';
            } else {
                $type = G_Company_Structure::GROUP;
                $message = 'Group has been added';
            }

			if($cs) {
				$child = new G_Company_Structure;
				$child->setCompanyBranchId($cs->getCompanyBranchId());
				$child->setTitle($_POST['group_name']);
				$child->setType($type);
				$child->setParentId($cs->getId());
				$child->setIsArchive(G_Company_Structure::NO);
				$child->save();
				
				$return['is_saved'] = true;
				$return['message'] 	= $message;
			}
			
			echo json_encode($return);
			
		}
	}
	
	function _load_token() {
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _load_add_employee_togroup() {
		if(!empty($_POST)) {
			$this->var['token'] = $token = Utilities::createFormToken();
			$this->var['h_company_structure'] = $_POST['h_company_structure'];
			$this->view->render('settings/employee_group_management/forms/add_employee.php',$this->var);
		}
	}
	
	function _load_insert_employee_togroup() {
		if(!empty($_POST)) {
			$cs = G_Company_Structure_Finder::findById(Utilities::decrypt($_POST['company_structure_id_employee_add']));
			if($cs) {
                $employees = explode(',', $_POST['h_employee_id']);
                foreach ($employees as $employee) {
                    $subdivision = new G_Employee_Subdivision_History;
                    $subdivision->setEmployeeId(Utilities::decrypt($employee));
                    $subdivision->setCompanyStructureId(Utilities::decrypt($_POST['company_structure_id_employee_add']));
                    $subdivision->setName($cs->getTitle());
                    $subdivision->setType(G_Company_Structure::GROUP);
                    $subdivision->setStartDate($this->c_date);
                    $subdivision->setEndDate();
                    $subdivision->save();
                }

				$return['is_saved'] = true;
				$return['message'] 	= 'Employee has been added to the group';
			}
			echo json_encode($return);
		}
	}
	
	function _load_unlock_payroll_period() {
		if(!empty($_POST)) {			
			$gcp = G_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['e_id']));
			$gcp_weekly = G_Weekly_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($_POST['frequecy'] == 1){
						if($gcp) {	

							$gcp->unLockPayrollPeriod();		
							$json['is_success'] = 1;
							$json['message']    = 'Cutoff period has been unlocked';
						}
			}else{
						if($gcp_weekly) {	
							
							$gcp_weekly->unLockPayrollPeriod();		
							$json['is_success'] = 1;
							$json['message']    = 'Cutoff period has been unlocked';
						}
			}
			


		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function payroll_period_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
				$gcp = G_Cutoff_Period_Finder::findById($value);		
				$d++;
				if($_POST['chkAction'] == 'lock_period'){								
					$gcp->lockPayrollPeriod();
					
					$json['message']    = 'Successfully locked ' . $d . ' record(s)';	
					$json['is_success'] = 1;	
										
				}elseif($_POST['chkAction'] == 'unlock_period'){				
					$gcp->unLockPayrollPeriod();
					
					$json['message']    = 'Successfully unlocked ' . $d . ' archived record(s)';	
					$json['is_success'] = 1;							
				}
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function _load_server_payroll_period_list_dt() 
	{		
		Utilities::ajaxRequest();
		$selected_year = $_GET['selected_year'];
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_CUTOFF_PERIOD);
		$dt->setCondition(' year_tag = "' . $selected_year . '"');
		$dt->setCustomField(array('payroll_period' => 'period_start,"-",period_end'));				
		$dt->setColumns('is_lock');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Lock Period\" id=\"edit\" class=\"ui-icon ui-icon-locked g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:lockPayrollPeriod(\'e_id\');\"></a></li><li><a title=\"Unlock Period\" id=\"edit\" class=\"ui-icon ui-icon-unlocked g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:unlockPayrollPeriod(\'e_id\');\"></a></li></li></ul></div>'));	
		echo $dt->constructDataTable();
	}
	
	function _load_employee_status_list_dt() 
	{
		$estatus = new G_Settings_Employee_Status();
		$estatus->setCompanyStructureid($this->company_structure_id);
		$data 	 = $estatus->getObjectDataByCompanyStructureId();
		if( $data ){			
			$default_ids = $estatus->getDefaultIds();			
			$this->var['default_ids'] = $default_ids;
			$this->var['e_status']    = $data;		
			$this->view->render('settings/options/employee_status/_employee_status_list_dt.php',$this->var);
		}else{
			echo "Object not found";
		}
	}
	
	function _load_requirements_list_dt() 
	{		
		$this->view->render('settings/requirements/_requirements_list_dt.php',$this->var);
	}
	
	function _load_company_benefits_list_dt() 
	{		
		$this->view->render('settings/company_benefits/_company_benefits_list_dt.php',$this->var);
	}
	
	function _load_archive_company_benefits_list_dt() 
	{		
		$this->view->render('settings/company_benefits/_company_benefits_archive_list_dt.php',$this->var);
	}
	
	function _load_archive_requirements_list_dt() 
	{		
		$this->view->render('settings/requirements/_requirements_archive_list_dt.php',$this->var);
	}
	
	function _load_archive_employee_status_list_dt() 
	{		
		$this->view->render('settings/options/employee_status/_employee_status_archive_list_dt.php',$this->var);
	}
	
	function _load_leave_type_list_dt() 
	{		
		$this->view->render('settings/options/leave_type/_leave_type_list_dt.php',$this->var);
	}
	
	function _load_payroll_period_list_dt() 
	{		
		$this->var['selected_year'] = $_POST['selected_year'];
		$this->view->render('settings/payroll_period/_payroll_period_list_dt.php',$this->var);
	}
	
	function _load_payroll_year_list_dt() 
	{		
		$data = G_Cutoff_Period_Finder::findAllDistinctYearTag();
		//$this->var['start_year']   = G_Cutoff_Period::YEAR_START;	
		$this->var['data']		   = $data;	
		$this->var['current_year'] = date("Y");	
		$this->view->render('settings/payroll_period/_payroll_year_list_dt.php',$this->var);
	}
	
	function payroll_period()
	{	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTipsy();
		$this->var['page_title']     = 'Settings';
		$this->var['payroll_period'] = 'selected';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/payroll_period/index.php',$this->var);		
	}
	
	function grace_period()
	{
		Utilities::checkModulePackageAccess('attendance','dtr');	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTipsy();

		Jquery::loadMainTextBoxList();
		Jquery::loadMainTextBoxList();

		$this->var['page_title'] = 'Settings';
		$this->var['grace_period_sb']	= 'selected';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/options/grace_period/grace_period.php',$this->var);		
	}

	function breaktime_schedule()
	{		
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		
		$this->var['page_title'] = 'Break Time Schedules';
		$this->var['breaktime_settings_sb']	= 'selected';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/breaktime_schedules/index.php',$this->var);
	}

	function payroll_settings()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTipsy();		
		$this->var['page_title'] = 'Payroll Settings';
		$this->var['payroll_settings_sb'] = 'selected';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/payroll_settings/index.php',$this->var);		
	}
	
	function save_grace_period()
	{			
		if($_POST['eid']){
			$ggp = G_Settings_Grace_Period_Finder::findById(Utilities::decrypt($_POST['eid']));	
		}else{
			$ggp = new G_Settings_Grace_Period();	
			$ggp->setIsDefault(G_Settings_Grace_Period::NO);					
		}
		
		$ggp->setCompanyStructureId($this->company_structure_id);
		$ggp->setTitle($_POST['grace_title']);
		$ggp->setDescription($_POST['grace_period_description']);
		$ggp->setIsArchive(G_Settings_Grace_Period::NO);		
		$ggp->setNumberMinuteDefault($_POST['number_minute_default']);
		$ggp->save();		
		
		$return['is_success'] = 1;
		$return['message']    = 'Record Saved.';	
		
		echo json_encode($return);
	}
	
	function set_default_grace_period()
	{			
		if($_POST['eid']){
			$ggp = G_Settings_Grace_Period_Finder::findById(Utilities::decrypt($_POST['eid']));	
			if($ggp){
				$ggp->setCompanyStructureId($this->company_structure_id);
				$ggp->set_all_not_default();	
				$ggp->save_default();
				
				$return['is_success'] = 1;
				$return['message']    = 'Selected Grace Period was successfully set as default.';	
			}else{
				$return['is_success'] = 1;
				$return['message']    = 'Error in SQL';	
			}
		}
		
		echo json_encode($return);
	}
	
	function delete_grace_period()
	{			
		if($_POST['eid']){
			$ggp = G_Settings_Grace_Period_Finder::findById(Utilities::decrypt($_POST['eid']));	
			if($ggp){				
				$ggp->delete();
				
				$return['is_success'] = 1;
				$return['message']    = 'Selected Grace Period was successfully deleted.';	
			}else{
				$return['is_success'] = 1;
				$return['message']    = 'Error in SQL';	
			}
		}
		
		echo json_encode($return);
	}
	
	function _load_edit_grace_period()
	{		
		if($_POST['eid']){
			$p = G_Settings_Grace_Period_Finder::findById(Utilities::decrypt($_POST['eid']));
			$this->var['p'] = $p;
			$this->view->render('settings/options/grace_period/forms/edit_grace_period.php',$this->var);
		}
	}
	
	function _load_edit_company_benefit()
	{		
		if($_POST['eid']){
			$b = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($b){
				$this->var['token'] = Utilities::createFormToken();
				$this->var['b']     = $b;
				$this->view->render('settings/company_benefits/forms/edit_company_benefit.php',$this->var);
			}
		}
	}
	
	function _load_add_new_grace_period()
	{		
		$this->view->render('settings/options/grace_period/forms/add_new_grace_period.php',$this->var);
	}
	
	function _load_lock_payroll_period() {
		if(!empty($_POST)) {			

			$gcp = G_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['e_id']));
			$gcp_weekly = G_Weekly_Cutoff_Period_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if(isset($gcp) || isset($gcp_weekly)) 
			{			
				if($_POST['frequecy'] == 1)
				{
					$gcp->lockPayrollPeriod();		
					$json['is_success'] = 1;
					$json['message']    = 'Cutoff period has been locked';
				}
				else
				{
					$gcp_weekly->lockPayrollPeriod();	
					$json['is_success'] = 1;
					$json['message']    = 'Weekly Cutoff period has been locked';
				}														
				
			}
			else
			{
				$json['is_success'] = 0;
			}
		
			echo json_encode($json);
		}
	}
	
	function _load_lock_all_cutoff_period_by_selected_year() {
		if(!empty($_POST['selected_year'])) {
			$gcp = new G_Cutoff_Period();			
			$gcp->lockAllPayrollPeriodBySelectedYear($_POST['selected_year']);		
			$json['is_success'] = 1;
			$json['message']    = 'All cutoff periods of the selected year was successfully locked';	
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function ajax_get_positions_departments_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$positions = G_Job_Finder::searchByTitle($q,$this->company_structure_id);
			
			foreach ($positions as $p) {
				$response[] = array('pos-' . Utilities::encrypt($p->getId()), $p->getTitle(), null);
			}
			
			$departments = G_Company_Structure_Finder::searchByTitle($q,$this->company_structure_id);
			
			foreach ($departments as $d) {
				$response[] = array('dept-' . Utilities::encrypt($d->getId()), $d->getTitle(), null);				
			}
			
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array('emp-' . Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_get_employees_togroup() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		$se = G_Employee_Helper::constructSqlFilterEmployeeAlreadyInTheGroup(Utilities::decrypt($_GET['h_company_structure']));
		if ($q != '') {
			$employees = G_Employee_Finder::searchEmployeeNotInTheSameGroup($q,$se);
			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e->getId()), $e->getFirstName().' ' . $e->getLastName(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function _load_grace_period_list_dt() 
	{		
		$grace_periods = G_Settings_Grace_Period_Finder::findAllByCompanyStructureIsNotArchive($this->company_structure_id);
		$this->var['data'] = $grace_periods;
		$this->view->render('settings/options/grace_period/_grace_period_list_dt.php',$this->var);
	}
	
	function _load_archive_leave_type_list_dt() 
	{		
		$this->view->render('settings/options/leave_type/_leave_type_archive_list_dt.php',$this->var);
	}
	
	function _load_server_employee_status_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_EMPLOYEE_STATUS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archive ="' . G_Settings_Employee_Status::NO . '"');		
		$dt->setColumns('name');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:editEmployeeStatus(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:archiveEmployeeStatus(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_requirements_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_REQUIREMENTS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archive ="' . G_Settings_Requirement::NO . '"');		
		$dt->setColumns('title');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:editRequirement(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:archiveRequirement(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_company_benefits_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_COMPANY_BENEFITS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archived ="' . G_Settings_Company_Benefits::NO . '"');		
		$dt->setColumns('benefit_code,benefit_name');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:editCompanyBenefit(\'e_id\');\"></a></li><li><a title=\"Assign Benefit\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"javascript:assignBenefit(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:archiveBenefit(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_archive_company_benefits_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_COMPANY_BENEFITS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archived ="' . G_Settings_Company_Benefits::YES . '"');		
		$dt->setColumns('benefit_code,benefit_name');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Restore\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreBenefit(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_archive_requirements_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_REQUIREMENTS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archive ="' . G_Settings_Requirement::YES . '"');		
		$dt->setColumns('title');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"e_id\"></li><li><a title=\"Restore Archive\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreRequirement(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_archive_employee_status_list_dt() 
	{		
		//Utilities::ajaxRequest();
		$dt = new Datatable();		
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_SETTINGS_EMPLOYEE_STATUS);	
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND is_archive ="' . G_Settings_Employee_Status::YES . '"');		
		$dt->setColumns('name');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Restore Archive\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreEmployeeStatus(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LEAVE);	
		$dt->setCustomField(array('leave_type' => 'name'));				
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND gl_is_archive ="' . G_Leave::NO . '"');
		$dt->setColumns('id,default_credit,is_paid');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:editLeaveType(\'pkey\');\"></a></li><li class=\"delete-btn\"><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:archiveLeaveType(\'pkey\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_archive_leave_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LEAVE);	
		$dt->setCustomField(array('leave_type' => 'name'));				
		$dt->setCondition('company_structure_id =' . $this->company_structure_id . ' AND gl_is_archive ="' . G_Leave::YES . '"');
		$dt->setColumns('is_paid');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Restore Archive\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreLeaveType(\'e_id\')\"></a></li></ul></div>'));
		
		echo $dt->constructDataTable();
	}
	
	function _load_loan_list_dt() 
	{
		$this->view->render('settings/options/deduction_type/_loan_type_list_dt.php',$this->var);
	}
	
	function _load_archive_loan_list_dt() 
	{
		$this->view->render('settings/options/deduction_type/_archive_loan_type_list_dt.php',$this->var);
	}
	
	function generate_payroll_period() 
	{
		$date         = Tools::getGmtDate('Y-m-d');
		$current_year = date("Y");
		$cycle        = G_Salary_Cycle_Finder::findDefault();
		$current      = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		$payout_date  = Tools::getPayoutDate($date, $cycle->getCutOffs(), $cycle->getPayoutDays());
		G_Cutoff_Period_Manager::savePeriod($current_year,$current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY, $payout_date);			
		$json['message']    = "Current cutoff period was successfully generated";
 		$json['is_success'] = 1;
		echo json_encode($json);
	}
	
	function _load_server_loan_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Type::NO . '" AND company_structure_id="' . $this->company_structure_id . '"');
		$dt->setColumns('loan_type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:editDeductionType(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:archiveLoanType(\'e_id\')\"></ul></div>'));
		/*$dt->setCustomColumn(	
		array(		
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li></ul></div><section class=\"main\"><div class=\"wrapper-demo\"><div id=\"dd\" class=\"wrapper-dropdown-5\" tabindex=\"1\">Action<ul class=\"dropdown\"><li><a href=\"javascript:void(0);\" onclick=\"javascript:editLoanTypeForm(\'e_id\');\"><i class=\"icon-pencil\"></i>Edit</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanType(\'e_id\')\"><i class=\"icon-trash\"></i>Archive</a></li></ul></div></div></section>'));*/
		
		echo $dt->constructDataTable();
	}
	
	function _load_server_archive_loan_type_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_LOAN_TYPE);				
		$dt->setCondition(' is_archive = "' . G_Loan_Type::YES . '" AND company_structure_id="' . $this->company_structure_id . '"');
		$dt->setColumns('loan_type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Restore Archived\" id=\"edit\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:restoreArchiveLoanType(\'e_id\');\"></a></li></ul></div>'));
		/*$dt->setCustomColumn(	
		array(		
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li></ul></div><section class=\"main\"><div class=\"wrapper-demo\"><div id=\"dd\" class=\"wrapper-dropdown-5\" tabindex=\"1\">Action<ul class=\"dropdown\"><li><a href=\"javascript:void(0);\" onclick=\"javascript:editLoanTypeForm(\'e_id\');\"><i class=\"icon-pencil\"></i>Edit</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:archiveLoanType(\'e_id\')\"><i class=\"icon-trash\"></i>Archive</a></li></ul></div></div></section>'));*/
		
		echo $dt->constructDataTable();
	}
	
	function requirements_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gsr = G_Settings_Requirement_Finder::findById(Utilities::decrypt($value));
			if($gsr){				
				if($_POST['requirements_with_selected_action'] == 'archive'){	
					$gsr->archive();					
				}elseif($_POST['requirements_with_selected_action'] == 'restore'){				
					$gsr->restore();												
				}	
			}
			$json['eid']        = $eid;
			$json['is_success'] = 1;
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function company_benefits_with_selected_action() 
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gscb = G_Settings_Company_Benefits_Finder::findById(Utilities::decrypt($value));
			if($gscb){				
				if($_POST['company_benefits_with_selected_action'] == 'archive'){	
					$gscb->archive();					
				}elseif($_POST['company_benefits_with_selected_action'] == 'restore'){				
					$gscb->restore();												
				}	
			}
			$json['eid']        = $eid;
			$json['is_success'] = 1;
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function memo_with_selected_action()
	{
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
			$gsm = G_Settings_Memo_Finder::findById(Utilities::decrypt($value));
			if($gsm){
				if($_POST['memo_with_selected_action'] == 'archive'){
					$gsm->archive();
				}elseif($_POST['memo_with_selected_action'] == 'restore'){
					$gsm->restore();
				}
			}
			$json['eid']        = $eid;
			$json['is_success'] = 1;
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}

	function update_notif_setting() 
	{
		if(!empty($_POST['id'])) {
			$notif_id = $_POST['id'];
			$setting_notif = G_Settings_Notifications_Finder::findById($notif_id);

			if($setting_notif) {
				if($setting_notif->getIsEnable() == 'Yes') {
					$setting_notif->setIsEnable('No');       
				} else {
					$setting_notif->setIsEnable('Yes');
				}

				$saved = $setting_notif->save();
			}
		}
	}
	
	function get_employee_by_employment_status(){
		$enc_employment_status_ids = explode(',', $_GET['employment_status']);
		foreach($enc_employment_status_ids as $key => $value)
		$enc_employment_status_ids[$key] = Utilities::decrypt($enc_employment_status_ids[$key]);
		$employee = G_Employee_Helper::findAllEmployeesByEmploymentStatus($enc_employment_status_ids);
		
		echo json_encode($employee);
	}


	function change_fixed_contribution_settings(){

		$action = $_GET['action'];
		$id = $_GET['eid'];
		$amount = 100;
		$dc = G_Settings_Fixed_Contributions_Finder::findById($id);
		$contri = $dc->getContributionName();
		if($action == 'enabled'){

			$dc->setIsEnabled(1);
			$is_saved = $dc->update();
			if($is_saved){

				if($contri == 'pagibig'){

					$employees = G_Employee_Finder::findAllActiveEmployees();

					foreach($employees as $e){

						$gefc = new G_Employee_Fixed_Contribution();
						$gefc->setEmployeeId($e->getId());    		      
						$gefc->deleteAllByEmployeeId();
								
					    $gefc->setType(G_Employee_Fixed_Contribution::TYPE_HDMF);
					    $gefc->setEEAmount($amount);     
					    $gefc->setERAmount($amount);
					    $gefc->setIsActivated(1);   	       
					    $gefc->save();


					    $ec = G_Employee_Contribution_Finder::findCurrentContribution($e);
					    if($ec) {
							$to_deduct 		= unserialize($ec->getToDeduct());
							$sss 			= $to_deduct['sss'];
							$philhealth 	= $to_deduct['philhealth'];
							$pagibig 		= $to_deduct['pagibig'];

							if($contri == 'sss') {
								$sss = ($sss == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
							}elseif($contri == 'philhealth') {
								$philhealth = ($philhealth == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
							}elseif($contri == 'pagibig') {
								$pagibig = G_Employee_Contribution::NO;
							}

							$to_deduct_arr['sss'] 			= $sss;
					        $to_deduct_arr['philhealth'] 	= $philhealth;
					        $to_deduct_arr['pagibig'] 		= $pagibig;
					        $ec->setToDeduct(serialize($to_deduct_arr));
					        $ec->save();
						}

					}
							

					}

			}
		}
		else{


			$dc->setIsEnabled(0);
			$is_saved = $dc->update();
			if($is_saved){

				if($contri == 'pagibig'){

					$employees = G_Employee_Finder::findAllActiveEmployees();

					foreach($employees as $e){

						$gefc = new G_Employee_Fixed_Contribution();
						$gefc->setEmployeeId($e->getId());    		      
						$gefc->deleteAllByEmployeeId();
								
					    $gefc->setType(G_Employee_Fixed_Contribution::TYPE_HDMF);
					    $gefc->setEEAmount($amount);     
					    $gefc->setERAmount($amount);
					    $gefc->setIsActivated(0);   	       
					    $gefc->save();

					    $ec = G_Employee_Contribution_Finder::findCurrentContribution($e);
					    if($ec) {
							$to_deduct 		= unserialize($ec->getToDeduct());
							$sss 			= $to_deduct['sss'];
							$philhealth 	= $to_deduct['philhealth'];
							$pagibig 		= $to_deduct['pagibig'];

							if($contri == 'sss') {
								$sss = ($sss == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
							}elseif($contri == 'philhealth') {
								$philhealth = ($philhealth == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
							}elseif($contri == 'pagibig') {
								$pagibig =  G_Employee_Contribution::YES;
							}

							$to_deduct_arr['sss'] 			= $sss;
					        $to_deduct_arr['philhealth'] 	= $philhealth;
					        $to_deduct_arr['pagibig'] 		= $pagibig;
					        $ec->setToDeduct(serialize($to_deduct_arr));
					        $ec->save();
						}

					}		

					}

			}

		}

		$json['is_success'] = 1;
		echo json_encode($json);

	}


	function _load_fixed_contribution_settings() 
	{
		$dc_settings = G_Settings_Fixed_Contributions_Finder::findAll();
		$this->var['dc_settings'] = $dc_settings;
		$this->view->render('settings/options/deduction_breakdown/load_pagibig_fixed_contributions_settings.php',$this->var);
	}


	//grace period exemption
	function _load_gp_excempted_employees_list(){
		$record = G_Settings_Grace_Period_Exempted_Finder::findAll();
		//utilities::displayArray($record);exit();
		$this->var['record'] = $record;
		$this->view->render('settings/options/grace_period/_grace_period_exempted_list_dt.php',$this->var);
	}

	function _load_add_new_gp_exempted_employees()
	{	
		$this->view->render('settings/options/grace_period/forms/add_gp_exempted_employees.php',$this->var);
	}

	function add_gp_exempted_employees(){

		$employees = explode(',',$_POST['gp_employee_id']);
		foreach($employees as $eid){

			$id = Utilities::decrypt($eid);
			$hasRecord = G_Settings_Grace_Period_Exempted_Finder::findByEmployeeId($id);
			if(!$hasRecord){
				$gp = new G_Settings_Grace_Period_Exempted;
				$gp->setEmployeeId($id);
				$gp->save();
			}
			
		}

		$json['is_success'] = 1;
		$json['message'] = 'Record was successfully updated';
		echo json_encode($json);

	}


	function delete_grace_period_exempted(){

		$id = Utilities::decrypt($_POST['eid']);
		$rec = G_Settings_Grace_Period_Exempted_Finder::findById($id);

		if($rec){
			$rec->delete();
		 }

		$json['is_success'] = 1;
		$json['message'] = 'Record was successfully updated';
		echo json_encode($json);
	}



	//project site settings
	function load_project_site_dt(){	
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/project_history/_project_type_list_dt.php',$this->var);
	}

	function _load_project_site_leave_type_list_dt(){
		$this->var['results'] = G_PROJECT_SITE::findAllProjectSite();
		$object = json_decode(json_encode($this->var['results']));
		$data = array();

		foreach($object as $row){
			$sub_array = array();
		    $sub_array[] = $row->name;
		    $sub_array[] = $row->location;
		    $sub_array[] = $row->description;
			$sub_array[] = $row->device_id;
		    $sub_array[] = '<div id=edit" style="margin-left:20px"><button type="button" class="btn btn-mini" onclick="javascript:EditProjectSite('.$row->id.')" title="Edit Project Site Info"><i class="icon-pencil" ></i></button> <button type="button" title="Delete Project site" class="btn btn-mini" onclick="javascript:DeleteProjectSite('.$row->id.')"><i class="icon-trash" ></i></button></div>';  

		    $data[] = $sub_array;
		}
        
        $output = array(
			    "iTotalRecords" => count($data),
			    "iTotalDisplayRecords" => count($data),
			    "aaData"=> $data
		);

		echo json_encode($output);
	}


	function _load_add_new_project_type(){
		$this->var['token'] = Utilities::createFormToken();
		$this->var['devices'] = G_Attendance_Log_Finder::getDevices();
		$this->var['project_devices'] = G_Project_Site_Finder::findAll();
		$this->view->render('settings/options/project_history/forms/add_new_project_type.php',$this->var);
	}
	
	function add_new_project_type(){
		Utilities::verifyFormToken($_POST['token']);
		$p = new G_Project_Site_Extends();
		$p->setprojectname(ucwords(strtolower($_POST['project_site_name'])));
		$p->setlocation(ucwords(strtolower($_POST['project_site_address'])));
		$p->setProjectDescription(ucwords(strtolower($_POST['project_site_description'])));
		$p->setDeviceId($_POST['project_site_machine_id']);
		$p->setProjectSite();


		$json['is_success'] = 1;
		$json['message']    = 'Record was successfully updated.';
		
        header('Content-type: application/json');
        echo json_encode($json);

	}

	function project_site_edit_view(){
		$this->var['token'] = Utilities::createFormToken();
		$project_id = Model::safeSql($_POST['id']);
        $this->var['results'] = json_decode(json_encode(G_PROJECT_SITE::findAllProjectSite($project_id)));
		$this->var['devices'] = G_Attendance_Log_Finder::getDevices();
		$this->var['project_devices'] = G_Project_Site_Finder::findAll();
		$this->view->render('settings/options/project_history/forms/edit_project_site.php',$this->var);
	}

	function update_project_site(){
        Utilities::verifyFormToken($_POST['token']);
        $p = new G_Project_Site_Extends();
        $p->setId($_POST['project_id']);
		$p->setprojectname(ucwords(strtolower($_POST['project_site_name'])));
		$p->setlocation(ucwords(strtolower($_POST['project_site_address'])));
		$p->setProjectDescription(ucwords(strtolower($_POST['project_site_description'])));
		$p->setDeviceId($_POST['project_site_machine_id']);
		$p->updateProjectSite();
		$json['is_success'] = 1;
		$json['message']    = 'Project Site was successfully updated.';
		
        header('Content-type: application/json');
        echo json_encode($json);
	}

	function update_activity(){
        Utilities::verifyFormToken($_POST['token']);

        $p = new G_Activity_Skills();
        $p->setId($_POST['activity_id']);
		$p->setActivitySkillsName($_POST['activity_name']);
		$p->setActivitySkillsDescription($_POST['activity_description']);
		$p->setDateStarted($_POST['activity_start']);
		$p->setDateEnded($_POST['activity_end']);
		$p->update();
		$json['is_success'] = 1;
		$json['message']    = 'Activity was successfully updated.';
		
        header('Content-type: application/json');
        echo json_encode($json);
	}

	function _load_delete_project_site_confirmation(){
		$this->view->noTemplate();
		$this->view->render('settings/options/project_history/forms/delete_confirmation',$this->var);

	}

	function delete_project_site(){
		$project_id = Model::safeSql($_GET['id']);
		$projectsiteModel = new G_Employee_Project_Site_Model();
        $projectCountIdIfExist = $projectsiteModel->findProjectSitesIfExist($project_id);
        
	        if ($projectCountIdIfExist > 0) {
	        	echo json_encode(array("status"=>1,"messages" => "The project can't be deleted!"));
	        }
	        else { 
             	$result = $projectsiteModel->deleteProjectSite($project_id);
             	echo json_encode(array("messages" => "Project Site Successfully Deleted!"));
	        }       
	}

	//activity settings
	function load_activity_dt(){	
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/activity_history/_activity_type_list_dt.php',$this->var);
	}
	function _load_activity_leave_type_list_dt(){
		$this->var['results'] = G_Activity_Skills::findAllActivity();
		$object = json_decode(json_encode($this->var['results']));
		$data = array();
		
		foreach($object as $row){
			$sub_array = array();
		    $sub_array[] = $row->activity_skills_name;
		    $sub_array[] = $row->activity_skills_description;
			$sub_array[] = $row->date_started;
			$sub_array[] = $row->date_ended;
		    $sub_array[] = '<div id=edit" style="margin-left:20px"><button type="button" class="btn btn-mini" onclick="javascript:EditActivity('.$row->id.')" title="Edit Project Site Info"><i class="icon-pencil" ></i></button> <button type="button" title="Delete Activity" class="btn btn-mini" onclick="javascript:DeleteActivity('.$row->id.')"><i class="icon-trash" ></i></button></div>';  
		    $data[] = $sub_array;
		}
        
        $output = array(
			    "iTotalRecords" => count($data),
			    "iTotalDisplayRecords" => count($data),
			    "aaData"=> $data
		);

		echo json_encode($output);
	}

	function _load_add_new_activity_type(){
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('settings/options/activity_history/forms/add_new_activity_type.php',$this->var);
	}

	function add_new_activity_type(){

		Utilities::verifyFormToken($_POST['token']);
	
		$activity = G_Activity_Skills_Finder::findByName($_POST['activity_name']);

		if ($activity) {
			$return['message']  = 'Error: Name already exists.';
			$return['is_saved'] = false;
		}
		else {
			$ea = new G_Activity_Skills();  
			$ea->setActivitySkillsName($_POST['activity_name']);
			$ea->setActivitySkillsDescription($_POST['activity_description']);
			$ea->setDateStarted($_POST['activity_date_start']);
			$ea->setDateEnded($_POST['activity_date_end']);
			$ea->setDateCreated(date('d-m-y'));
			$ea->save(); 
		}

		$json['is_success'] = 1;
		$json['message']    = 'Record was successfully updated.';
		
        header('Content-type: application/json');
        echo json_encode($json);

	}
	function activity_edit_view(){
		$this->var['token'] = Utilities::createFormToken();
		$project_id = Model::safeSql($_POST['id']);
        $this->var['results'] = json_decode(json_encode(G_Activity_Skills::findAllActivity($project_id)));
		$this->view->render('settings/options/activity_history/forms/edit_activity.php',$this->var);
	}

	function activity_site(){
        Utilities::verifyFormToken($_POST['token']);

        $p = new G_Project_Site_Extends();
        $p->setId($_POST['project_id']);
		$p->setprojectname(ucwords(strtolower($_POST['project_site_name'])));
		$p->setlocation(ucwords(strtolower($_POST['project_site_address'])));
		$p->setProjectDescription(ucwords(strtolower($_POST['project_site_description'])));
		$p->updateProjectSite();
		$json['is_success'] = 1;
		$json['message']    = 'Project Site was successfully updated.';
		
        header('Content-type: application/json');
        echo json_encode($json);
	}

	function _load_delete_activity_confirmation(){
		$this->view->noTemplate();
		$this->view->render('settings/options/project_history/forms/delete_confirmation',$this->var);

	}

	function delete_activity(){
		$id = Model::safeSql($_GET['id']);
		$activity = G_Activity_Skills_Finder::findById($id);

		if($activity){
			$employee_activities = G_Employee_Activities_Finder::findByActivityId($id);
			
			if ($employee_activities) {
				$json['is_success'] = 0;
				echo json_encode(array("status"=>1,"messages" => "The project can't be deleted!"));
			}
			else {
				$activity->delete();
				$json['is_success'] = 1;			
				echo json_encode(array("messages" => "Project Site Successfully Deleted!"));
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Record not found...';
		}    
	}

	//monthly
	function ajax_edit_monthly_deduction_breakdown() {
		if(!empty($_GET)) {
			$eid = $_GET['h_id'];
			$id  = Utilities::decrypt($eid);
			$deduction = G_Settings_Monthly_Deduction_Breakdown_Finder::findById($id);
			if( $deduction ){
				$yes_no        = $deduction->getOptionsIsTaxable();
				$salary_credit = $deduction->getOptionsSalaryCredit();
				unset($salary_credit[G_Settings_Monthly_Deduction_Breakdown::OPTION_SALARY_CREDIT_NA]); //Remove NA
				
				$this->var['yes_no']        = $yes_no;
				$this->var['salary_credit'] = $salary_credit;
				$this->var['action']	= 'settings/_update_monthly_deduction_breakdown';
				$this->var['deduction'] = $deduction;
				$this->view->render('settings/options/deduction_breakdown/forms/ajax_edit_monthly_deduction_breakdown.php',$this->var);		
			}else{
				echo "Record not found!";
			}
		}else{
			echo "Record not found!";
		}
	}
	
	function _update_monthly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Monthly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setBreakdown($_POST['1st_cutoff'].':'.$_POST['2nd_cutoff']);
				$db->setIsTaxable($_POST['is_taxable']);
				$db->setSalaryCredit($_POST['salary_credit']);
				$db->save();				
				$json['is_saved'] = true;		
			} else {$json['is_saved'] = false;}
		} else {$json['is_saved'] = false;}
		echo json_encode($json);
	}



	function _deactivate_monthly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Monthly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Monthly_Deduction_Breakdown::NO);
				$db->save();
			}
		}
	}
	
	function _activate_monthly_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Monthly_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Monthly_Deduction_Breakdown::YES);
				$db->save();
			}
		}
	}


	//end monthly





}
?>