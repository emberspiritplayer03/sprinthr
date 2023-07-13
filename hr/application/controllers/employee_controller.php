<?php
class Employee_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainScript('employee.js');
		

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'employees');
		
		$employee = G_Employee_Finder::findById(Utilities::decrypt($this->global_user_eid));
		if($employee) {
			$position = G_Employee_Job_History_Finder::findCurrentJob($employee);
			if($position){
				$this->h_job_position_id 	= Utilities::encrypt($position->getJobId());
			}else{
				$this->h_job_position_id = 0;
			}
		}
		
		
		$this->h_employee_id   			= $this->global_user_eid; //employee
		$this->h_company_structure_id 	= $this->global_user_ecompany_structure_id;
		$this->c_date  					= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->default_method   		= 'index';
		
		$this->var['h_employee_id']  	     = $this->h_employee_id;
		$this->var['h_job_position_id'] 	 = $this->h_job_position_id;	
		$this->var['h_company_structure_id'] = $this->h_company_structure_id;

		Loader::appStyle('style.css');
		$this->var['employee'] 		= 'selected';
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);			
		$this->username 	= $this->global_user_username;
		$this->module 		= 'HR EMPLOYEE';		

		$this->validatePermission(G_Sprint_Modules::HR,'employees','');
	}

	function index()
	{
		$this->employee();		
	}
	
	function employee()
	{
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		
		$this->var['page_title'] = 'Employees';
		$this->var['token'] = Utilities::createFormToken();
		
		Jquery::loadMainJTags();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();

		$company_structure_id = $this->company_structure_id;

		$cs = G_Company_Structure_Finder::findById($company_structure_id);

		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId()); 
		//$this->var['departments'] = G_Company_Structure_Finder::findParentChildByCompanyStructureId($company_structure_id);
		$b = G_Company_Branch_Finder::findMain();
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByBranchIdAndParentId($b->getId(),$company_structure_id);
		$sections = G_Company_Structure_Helper::sqlAllSectionIsNotArchiveByBranchIdAndParentId($b->getId(),$company_structure_id);

		$this->var['sections'] = $sections;
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		$this->var['positions'] = $p= G_Job_Finder::findByCompanyStructureId2($company_structure_id);
		$this->var['frequencies'] = G_Frequency_Finder::findAll();
		$this->var['employement_status'] = G_Settings_Employment_Status_Finder::findByCompanyStructureId($company_structure_id);
		
		$this->var['add_new_branch_action'] = url('employee/_insert_company_branch');
		$this->var['add_position_action'] 	= url('employee/_insert_new_position');
		$this->var['add_status_action'] 	= url('employee/_insert_new_status');
		
		$this->var['import_action'] = url('employee/_import_employee_excel');
		$this->var['import_salary_action'] = url('employee/_import_employee_salary_excel');
		$this->var['import_training_action'] = url('employee/_import_employee_training_excel');

		$btn_import_employee_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_management',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importEmployee();',
    		'id' 					=> '',
    		'class' 				=> 'gray_button',
    		'icon' 					=> '<i class="icon-user"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import Employee'
    		); 

		$btn_import_employee_training_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_management',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importEmployeeTraining();',
    		'id' 					=> '',
    		'class' 				=> 'gray_button',
    		'icon' 					=> '<i class="icon-file"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import Training'
    		); 			

		$btn_import_salary_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_management',
    		'href' 					=> 'javascript:void(0);',    		
    		'id' 					=> '',
    		'class' 				=> 'gray_button btn-import-salary',
    		'icon' 					=> '<i class="icon-file"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import Salary'
    		); 	

		$btn_add_employee_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_management',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:load_add_employee();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Employee</b>'
    		); 

		$sv = new G_Sprint_Variables();
		$working_days_options = $sv->optionsWorkingDays();

		$count_employee_notifications = G_Notifications_Helper::countTotalEmployeeNotifications();


		$this->var['projects'] = G_PROJECT_SITE::findAllProjectSite();

		$this->var['is_enable_popup_notification']  = true;
		$this->var['count_employee_notifications']  = $count_employee_notifications;  		
    	
    	$this->var['working_days_options']      = $working_days_options;
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_management');
    	$this->var['btn_import_employee'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_employee_config);
    	$this->var['btn_import_employee_training'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_employee_training_config);
    	$this->var['btn_import_salary'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_salary_config);
    	$this->var['btn_add_employee'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employee_config);
		
		$this->var['page_title'] = 'Employees';
		$this->view->setTemplate('template_employee.php');
		$this->view->render('employee/employee/index.php',$this->var);
	}

	//import
	function _import_employee_excel()
	{
		ini_set("memory_limit", "999M");
		ini_set('upload_max_filesize', '20M');
		ini_set('post_max_size', '20M');
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['employee']['tmp_name'];
		//$e = new Employee_Import($file);
        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        }

        $e = new G_Employee_Import($file);
        $e->setDateCreated($this->c_date);
        $e->setCompanyStructureId(Utilities::decrypt($this->h_company_structure_id));
		$is_imported = $e->import();

        if ($is_imported) {
            echo "<b>IMPORT RESULT:</b>";
            echo '<br>';

            $total_import = $e->getTotalSuccessfulImport();
            echo "<b>Successful import: ". $total_import ."</b>";
            echo '<br>';

            $duplicate_codes = array_filter($e->getDuplicateEmployeeCodes());
            if ($duplicate_codes) {
                echo "<b>Duplicate employee codes: ". count($duplicate_codes) ."</b><br>";
                echo implode('<br>', $duplicate_codes);
                echo '<br>';
            }
            $empty_fields = array_filter($e->getEmptyRequiredFields());            
            if ($empty_fields) {
                echo '<b>Empty required fields: '. count($empty_fields) .'</b><br>';
                echo implode('<br>', $empty_fields);
                echo '<br>';
            }
            $exceed_fields = array_filter($e->getExceedEmployeeCodes());            
            if ($exceed_fields) {
                echo '<b>Exceed employee codes: '. count($exceed_fields) .'</b><br>';
                echo implode('<br>', $exceed_fields);
                echo '<br>';
            }
        }
	}

	function _import_employee_training_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);

		$file = $_FILES['employee']['tmp_name'];

        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        } else {
			$path = $_FILES['employee']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if($ext != 'xls' && $ext != 'xlsx')
			{
				echo "Invalid file. Allowed file types are (.xls) and (.xlsx).";
				exit;
			}
		}

        $e = new G_Employee_Import($file);
        $e->setDateCreated($this->c_date);

		$is_imported = $e->importTraining();

        if ($is_imported) {
            echo "<b>IMPORT RESULT:</b>";
            echo '<br>';

            $total_import = $e->getTotalSuccessfulImport();
            echo "<b>Successful import: ". $total_import ."</b>";
            echo '<br>';

            $empty_fields = $e->getEmptyRequiredFields();
            if ($empty_fields) {
                echo '<b>Empty required fields: '. count($empty_fields) .'</b><br>';
                echo implode('<br>', $empty_fields);
                echo '<br>';
            }
			$duplicate_training = $e->getDuplicateTraining();
			if($duplicate_training)
			{
				echo '<b> Duplicate training(s) exist(s): '. count($duplicate_training) .'</b><br>';
				echo 'Row(s): <br>';
				echo implode('<br>', $duplicate_training);
				echo '<br>';
			}

			$missing_ec = $e->getMissingEmployeeCode();
			if ($missing_ec)
			{
				echo '<b> Unrecognized employee code(s): '. count($missing_ec) .'</b><br>';
				echo 'Row(s): <br>';
				echo implode('<br>', $missing_ec);
				echo '<br>';
			}
        }
	}	

	//import
	function _import_employee_salary_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		
		$file = $_FILES['employee']['tmp_name'];
		//$e = new Employee_Import($file);
        if (!is_file($file)) {
            echo "Please select a file";
            exit;
        }

        $e = new G_Employee_Import($file);
        $e->setDateCreated($this->c_date);
        $e->setCompanyStructureId(Utilities::decrypt($this->h_company_structure_id));
		$is_imported = $e->importSalary();

        if ($is_imported) {
            echo "<b>IMPORT RESULT:</b>";
            echo '<br>';

            $total_import = $e->getTotalSuccessfulImport();
            echo "<b>Successful import: ". $total_import ."</b>";
            echo '<br>';
           
            $empty_fields = $e->getEmptyRequiredFields();            
            if ($empty_fields) {
                echo '<b>Empty required fields: '. count($empty_fields) .'</b><br>';
                echo implode('<br>', $empty_fields);
                echo '<br>';
            }
        }
	}	
	
	function _import_error()
	{ 
		
		$this->var['error'] = $_SESSION['hr']['error'];
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/import_error.php',$this->var);	
	}

    function _insert_new_employee() {
		//Utilities::verifyFormToken($_POST['token']);

		$employees = G_Employee_Finder::findAllActiveEmployees();
		$current_total_employees = count($employees);

		if ($current_total_employees >= G_Employee::MAX_EMPLOYEES) {
			echo 1;	
		}
		else {
			$department_id = (int) $_POST['department_id'];
			$position_id = (int) $_POST['position_id'];
			$employment_status_id = (int) $_POST['employment_status_id'];
	
			$dept = G_Company_Structure_Finder::findById($department_id);
			$job = G_Job_Finder::findById($position_id);
			$emp = G_Settings_Employment_Status_Finder::findById($employment_status_id);
	
			$sv = new G_Sprint_Variables();
	
			$week_working_days = $_POST['week_working_days'];
			$num_days = $sv->getWorkingDaysDescriptionNumberOfDays($week_working_days);
			$employment_status = $emp->getStatus();
			$position = $job->getTitle();
			$department = $dept->getTitle();
			$hired_date = $_POST['hired_date'];
			$salary_amount = (float) $_POST['salary_amount'];
			$salary_type = $_POST['salary_type'];
			$frequency_id = (int) $_POST['frequency_type'];
			$tags = $_POST['tags'];
			$employee_code = $_POST['employee_code'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$middlename = $_POST['middlename'];
			$extension_name = $_POST['extension_name'];
			$birthdate = $_POST['birthdate'];
			$gender = $_POST['gender'];
			$marital_status = $_POST['marital_status'];
			$number_of_dependents = (int) $_POST['number_of_dependents'];
			$is_confidential = (int) $_POST['is_confidential'];
			$cost_center = $_POST['cost_center'];
			$nationality = '';
			$employee_status = '';
			$section = '';

			$project_site = $_POST['project_site_id'];


			if($_POST['section_id'] != "add" || $_POST['section_id'] != "") {
				$sect = G_Company_Structure_Finder::findById($_POST['section_id']);
				if($sect) {
					$section = $sect->getTitle();	
				}
				
			}
		 
			$cs = G_Company_Structure_Finder::findByMainParent();
	
			$e = $cs->hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status,
				$number_of_dependents, $hired_date, $department, $position, $employment_status, $salary_amount, $salary_type, $frequency_id,
				$sss_number, $tin_number, $pagibig_number, $philhealth_number, $extension_name, $nickname, $section, $is_confidential, $week_working_days, $num_days,$nationality,$employee_status,$cost_center, $project_site);
			// var_dump($e);
	
			//Utilities::displayArray($e);
			if( !empty($e) ){        	
				$employee_id = $e->getId();
				
				 //Tags
				$t = new G_Employee_Tags();
				$t->setCompanyStructureId($this->company_structure_id);
				$t->setTags($tags);
				$t->setIsArchive(G_Employee_Tags::NO);
				$t->setDateCreated($this->c_date);
				$t->save($e);
	
				echo Utilities::encrypt($employee_id);
			}  
		}     


        if($employee_id){
            $this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_add_new'] .",id=". $employee_id,$this->username);
        }else{
            $this->triggerAuditTrail(0,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_add_new'] .",id=". $employee_id,$this->username);
        }

        //general reports / shr audit trail
        if($employee_id){
        	$emp_name = $firstname.' '.$lastname;
            $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' New Employee ', $emp_name, '', '', 1, $position, $department);
        }else{
            $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' New Employee ', $emp_name, '', '', 0, $position, $department);
        }

    }
	
	function _insert_new_employee_OLD()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST['branch_id']=='' || $_POST['department_id']=='' || $_POST['position_id']=='' || $_POST['employment_status_id']=='') {
			echo 0;	
		}else {
			$e = new G_Employee;
			$e->setEmployeeCode($_POST['employee_code']);
			$e->setFirstname($_POST['firstname']);
			$e->setMiddlename($_POST['middlename']);
			$e->setLastname($_POST['lastname']);
			$e->setGender($_POST['gender']);
			$e->setMaritalStatus($_POST['marital_status']);
			$e->setExtensionName($_POST['extension_name']);
			$e->setHiredDate($_POST['hired_date']);
			$e->setIsArchive(G_Employee::NO);
			$employee_id = $e->save();

			
			$e = Employee_Factory::get($employee_id);
			$hash = Utilities::createHash($employee_id);
			$e->addHash($hash);
			$p = G_Job_Finder::findById($_POST['position_id']);
			$p->saveToEmployee($e, date("Y-m-d") );
			
			$c = G_Company_Structure_Finder::findById($this->company_structure_id);
			$c->addEmployee($e);
			
			$c = G_Company_Structure_Finder::findById($_POST['department_id']);
			$c->addEmployeeToSubdivision($e,date("Y-m-d"));
				
			$b = G_Company_Branch_Finder::findById($_POST['branch_id']);
			$b->addEmployee($e, date("Y-m-d"));
			
			//Tags
			$t = new G_Employee_Tags();
			$t->setCompanyStructureId($this->company_structure_id);			
			$t->setTags($_POST['tags']);
			$t->setIsArchive(G_Employee_Tags::NO);					
			$t->setDateCreated($this->c_date);	
			$t->save($e);		
			
			$position =  G_Job_Finder::findById($_POST['position_id']);
			
			//add supervisor
			
			$s = new G_Employee_Supervisor;
			$s->setEmployeeId($employee_id);
			$s->setSupervisorId($_POST['supervisor_id']);
			$s->save();
			
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
			
			if($total_status>0) {
				
				//load job employmetn status
				$status = G_Job_Employment_Status_Finder::findById($_POST['employment_status_id']);
				$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
				$employee_job->setEmploymentStatus($status->getEmploymentStatus());
				$employee_job->save();
				
			}else {
				//load settings employment status
				$status = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
				$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
				$employee_job->setEmploymentStatus($status->getStatus());
				$employee_job->save();
			}
			echo Utilities::encrypt($employee_id);
			
			if($employee_id){
				$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_add_new'] .",id=". $employee_id,$this->username);
			}else{
				$this->triggerAuditTrail(0,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_add_new'] .",id=". $employee_id,$this->username);
			}
		}
	}
	
	function _load_employee_hash() 
	{
		$e =  G_Employee_Helper::findHashByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		echo $e['hash'];
	}
	
	
	function _insert_company_branch() 
	{
		sleep(1);
		$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$gcb = new G_Company_Branch();	
		$gcb->setName($_POST['branch_name']);
		$gcb->setProvince($_POST['province']);	
		$gcb->setCity($_POST['city']);				
		$gcb->setAddress($_POST['address']);
		$gcb->setZipCode($_POST['zip_code']);
		$gcb->setPhone($_POST['phone']);
		$gcb->setFax($_POST['fax']);
		$gcb->setLocationId($_POST['location_id']);
		$gcb->save($cstructure);	
		
	}
	
	function _load_add_branch_form()
	{
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/add_new_branch.php',$this->var);
	}

	function _load_branch_dropdown() 
	{
		sleep(1);
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId(),'id'); 
		
		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/branch_dropdown.php',$this->var);
	}
	
	function _load_department_dropdown()
	{
		sleep(1);
		/*$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);*/
		
		$company_structure_id = $this->company_structure_id;
		$b = G_Company_Branch_Finder::findMain();
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByBranchIdAndParentId($b->getId(),$company_structure_id);
		
		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/department_dropdown.php',$this->var);	
	}

	function _load_section_dropdown() {
		sleep(1);

		$this->var['sections'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentIdAndType($_POST['did'], G_Company_Structure::SECTION);

		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/section_dropdown.php',$this->var);
	}
	
	function _load_add_section_form()
	{
		$dept = G_Company_Structure_Finder::findById($_POST['department_id']);
		if($dept) {
			$branch_id =  $_POST['branch_id'];
			$this->var['branch'] =$b= G_Company_Branch_Finder::findById($branch_id);
			$this->var['department_id'] = $_POST['department_id'];
			$this->var['dept_name'] = $dept->getTitle();
			$this->var['section_form_action'] = url('employee/_insert_new_section');
			$this->view->noTemplate();
			$this->view->render('employee/employee/form/add_section.php',$this->var);
		}else{
			echo "<div class='alert alert-error'> No record found.</div>";
		}
	}

	function _insert_new_section()
	{
		//print_r($_POST);
		sleep(1);
		$department = new G_Company_Structure;
		$department->setParentId($_POST['dep_department_id']);
		$department->setCompanyBranchId($_POST['dep_branch_id']);
		$department->setTitle($_POST['dep_branch_name']);
		$department->setDescription($_POST['dep_description']);
		$department->setType(G_Company_Structure::SECTION);
		$department->save();
		echo 1;
	}
	
	function _load_add_department_form()
	{
		$branch_id =  $_POST['branch_id'];
		$this->var['branch'] =$b= G_Company_Branch_Finder::findById($branch_id);
		$this->var['department_form_action'] = url('employee/_insert_new_department');
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/add_department.php',$this->var);
	}
		
	function _insert_new_department()
	{
		//print_r($_POST);
		sleep(1);
		$department = new G_Company_Structure;
		$department->setParentId($this->company_structure_id);
		$department->setType(G_Company_Structure::DEPARTMENT);
		$department->setCompanyBranchId($_POST['dep_branch_id']);
		$department->setTitle($_POST['dep_branch_name']);
		$department->setDescription($_POST['dep_description']);
		$department->save();
		echo 1;
	}
	
	function _load_add_position_form() 
	{
		$this->var['add_position_action'] =  url('employee/_insert_new_position');
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/add_position.php',$this->var);
	}
	
	function _load_position_dropdown()
	{
		sleep(1);

		$this->var['positions'] = G_Job_Finder::findByCompanyStructureId2($this->company_structure_id);
		
		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/position_dropdown.php',$this->var);
	}
	
	function _insert_new_position() 
	{
		sleep(1);
		$job = new G_Job;
		$job->setCompanyStructureId($this->company_structure_id);
		$job->setJobSpecificationId($_POST['job_specification_id']);
		$job->setTitle($_POST['job_title']);
		$job->setIsActive(1);
		$job->save();		
	}
	
	function _insert_new_status()
	{
		sleep(1);
	
		$position_id =  ($_POST['position_id']=='')?0:$_POST['position_id'];
	
		$position =  G_Job_Finder::findById($position_id);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		if($total_status==0) {

			$status = new G_Settings_Employment_Status;
			$status->setCompanyStructureId($this->company_structure_id);
			$status->setCode($_POST['code']);
			$status->setStatus($_POST['status']);
			$company_structure = G_Company_Structure_Finder::findById($this->company_structure_id);
			$status->save($company_structure);	
		}else {

			$j = new G_Job_Employment_Status();
			$j->setCompanyStructureId($this->company_structure_id);
			$j->setJobId($_POST['position_id']);
			$j->setEmploymentStatus($_POST['status']);	
			$j->save();	
		}
		
	}
	
	function _load_add_status_form()
	{
		$this->var['position_id'] = $_POST['position_id'];
		$this->var['add_status_action'] =  url('employee/_insert_new_status');
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/add_employment_status.php',$this->var);
	}
	
	function _load_add_job_status_form()
	{
		$this->var['add_status_action'] =  url('employee/_insert_new_status');
		$this->var['position_id'] = $_POST['position_id'];	
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/add_employment_status.php',$this->var);
	}
	
	
	function _load_employee_status_dropdown()
	{
		sleep(2);
		
		$position_id =  ($_POST['position_id']>0 || $_POST['position_id']=='')?$_POST['position_id']: 0;
		
		$position =  G_Job_Finder::findById($position_id);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		
		if($total_status>0){
			$status = G_Job_Employment_Status_Finder::findByJobId($position->getId());
			$status_type = 1; // status by position
		}else {
			$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
			$status_type =0; // default status
		}
		
		$this->var['status'] = $status;
		$this->var['status_type'] = $status_type;
		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/status_dropdown.php',$this->var);
	}
	
	

	/*function _json_query_help()
	{
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			$query = array('department','age','branch','firstname','lastname','employee_id','address','birthdate');
			
			
			//foreach ($records as $record) {	
				$response[] = array('label'=>'Age:');
				$response[] = array('label'=>'Address:');
				$response[] = array('label'=>'Birthdate:');
			//}
		}
		if(count($response)==0)
		{
			$response = '';
		}

		header('Content-type: application/json');
		echo json_encode($response);	
	}*/
	
	function _get_total_result()
	{
		Utilities::ajaxRequest();

		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$search = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$search = " AND (e.is_confidential = 1) ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$search = " AND (e.is_confidential = 0) ";
		}else{
			$search = "";
		}

		$colon_count = substr_count($_POST['searched'], ':'); 
		if($colon_count>0) {/* if has a colon*/	

				$search .= G_Employee_Helper::getDynamicQueries($_POST['searched']);
		}else {
				if($_POST['searched']) {
					$search  = " AND (e.e_is_archive = '" . G_Employee::NO . "')";
					$search .= " AND (e.firstname like '%". $_POST['searched'] ."%' OR e.lastname like '%". $_POST['searched'] ."%' ";
					$search .= " OR e.middlename like '%". $_POST['searched'] ."%' ";
					$search .= "OR j.name like '%".$_POST['searched']."%' OR d.name like '%".$_POST['searched']."%' ";
					$search .= " OR e.employee_code like '%". $_POST['searched'] ."%' OR j.employment_status like  '%". $_POST['searched'] ."%'  )";	
					
				}
				
				$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
				$dir = $_GET['dir'];
				if($_GET['sort']=='branch_name') {
					$_GET['sort'] = 'e.id';
				}elseif($_GET['sort']=='department') {
					$_GET['sort'] = 'd.name';
				}elseif($_GET['sort']=='employee_name') {
					$_GET['sort'] = 'e.lastname';
				}elseif($_GET['sort']=='employee_code') {
					$_GET['sort'] = 'e.employee_code';
				}
		}
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);
		if($search) {

			$emp = 	 G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
			$count_total =count($emp);
		}	
		echo 'Total Record(s): '.$count_total;	
	}
	
	function _get_total_records()
	{
		Utilities::ajaxRequest();
		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$search = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$search = " AND is_confidential = 1 ";
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$search = " AND is_confidential = 0 ";
		}else{
			$search = "";
		}

		$count_total = G_Employee_Helper::countTotalRecordsIsNotArchiveByCompanyStructureId($this->company_structure_id, $search);		
		echo 'Total Record(s): '.$count_total;	
	}
	
	function _get_total_records_is_archive()
	{
		Utilities::ajaxRequest();
		$count_total = G_Employee_Helper::countTotalRecordsIsArchiveByCompanyStructureId($this->company_structure_id);		
		echo 'Total Record(s): '.$count_total;	
	}
	
	function _json_encode_employee_account_list()
	{
		Utilities::ajaxRequest();
		
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		$dir = $_GET['dir'];
					
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $_GET['sort'] . ' ' . $dir  :  ' ORDER BY id asc' ;
		
		$data = G_User_Helper::findAll($order_by, $limit);

		$total_records = G_User_Helper::findAll();
		
		$count_total =count($total_records);
		$total = count($employee);
		$total_records = $count_total;
		
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_employee_list()
	{
		
		Utilities::ajaxRequest();
		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$search = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$search = " AND (e.is_confidential = 1) ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$search = " AND (e.is_confidential = 0) ";
		}else{
			$search = "";
		}
		
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search .= G_Employee_Helper::getDynamicQueries($_GET['search']);
		}else {
			//no colon
			if($_GET['search']) {
				$search  .= " AND (e.e_is_archive = '" . G_Employee::NO . "')";
				$search .= " AND(e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				$search .= " OR j.name like '%".$_GET['search']."%' OR d.name like '%".$_GET['search']."%' ";
				$search .= " OR e.employee_code like '%". $_GET['search'] ."%' OR j.employment_status like  '%". $_GET['search'] ."%'  )";	
			}
		}
		
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		$dir = $_GET['dir'];
			
		$sort = $_GET['sort'];
		$sort = G_Employee_Helper::getSortValue($sort);
					
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $sort . ' ' . $dir  :  ' ORDER BY e.id asc' ;
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
		if($search) {
			$employee      = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, $limit,$search);
			$total_records = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
		}

		$has_permission = 'false';
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_management');
		if($permission_action == Sprint_Modules::PERMISSION_02){
			$has_permission = 'true';
		}

		$child_index_arr = array(
			"personal_details",
			"contact_details",
			"emergency_contacts",
			"dependents",
			"bank",
			"employment_status",
			"compensation",
			"contribution",
			"training",
			"memo",
			"requirements",
			"supervisor",
			"employees_leave",
			"work_experience",
			"educations",
			"skills",
			"language",
			"license",
			"attachment"
			);
		//Utilities::displayArray($data);
		foreach($child_index_arr as $key => $value) {
			$r = $this->validatePermission(G_Sprint_Modules::HR,'employees',$value,false);
			if($r != '') {
				$default_module = $value;
				break;
			}
		}
		
		foreach ($employee as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['e_id'] = Utilities::encrypt($object['id']);
			
			$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  :BASE_FOLDER .'images/thumb.php?src='. BASE_FOLDER.'files/photo/'.$object['photo'].'&w=190&h=190' ;
			$data[$key]['photo'] = $photo;
			$data[$key]['has_permission'] = $has_permission;
			$data[$key]['default_module'] = $default_module;
		}
		//BASE_FOLDER.'files/photo/'.$object['photo']
	
		$count_total =count($total_records);
		$total = count($employee);
		$total_records = $count_total;
		
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_view_all_employee_list()
	{
		Utilities::ajaxRequest();
		
		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		if($employee_access == Sprint_Modules::PERMISSION_05) {
			$search = "";
		}elseif($employee_access == Sprint_Modules::PERMISSION_06) {
			$search = " AND (e.is_confidential = 1) ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07) {
			$search = " AND (e.is_confidential = 0) ";
		}else{
			$search = "";
		}

		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search .= G_Employee_Helper::getDynamicQueries($_GET['search']);
		}else {
			//no colon
			if($_GET['search']) {
				$search .= " AND (e.e_is_archive = '" . G_Employee::NO . "')";
				$search .= " OR (e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				$search .= " OR j.name like '%".$_GET['search']."%' OR d.name like '%".$_GET['search']."%' ";
				$search .= " OR e.employee_code like '%". $_GET['search'] ."%' OR j.employment_status like  '%". $_GET['search'] ."%'  )";	
			}
		}
		
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		$dir = $_GET['dir'];
			
		$sort = $_GET['sort'];
		$sort = G_Employee_Helper::getSortValue($sort);
					
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $sort . ' ' . $dir  :  ' ORDER BY e.id asc' ;
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
		if($search) {			
			$employee      = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, $limit,$search);
			$total_records = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
		}else{			

			$employee      = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, $limit,$search);
			$total_records = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
		}
		
		$has_permission = 'false';
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_management');
		if($permission_action == Sprint_Modules::PERMISSION_02){
			$has_permission = 'true';
		}

		$child_index_arr = array(
			"personal_details",
			"contact_details",
			"emergency_contacts",
			"dependents",
			"bank",
			"employment_status",
			"compensation",
			"contribution",
			"training",
			"memo",
			"requirements",
			"supervisor",
			"employees_leave",
			"work_experience",
			"educations",
			"skills",
			"language",
			"license",
			"attachment"
			);

		foreach($child_index_arr as $key => $value) {
			$r = $this->validatePermission(G_Sprint_Modules::HR,'employees',$value,false);
			if($r != '') {
				$default_module = $value;
				break;
			}
		}

		 //exit;

		foreach ($employee as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['e_id'] = Utilities::encrypt($object['id']);
			
			$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  : BASE_FOLDER .'images/thumb.php?src='. BASE_FOLDER.'files/photo/'.$object['photo'].'&w=190&h=190';
			$data[$key]['photo'] = $photo;
			$data[$key]['has_permission'] = $has_permission;
			$data[$key]['default_module'] = $default_module;
		}
		//BASE_FOLDER.'files/photo/'.$object['photo']
	
		
		$count_total =count($total_records);
		$total = count($employee);
		$total_records = $count_total;
		
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_view_all_archive_employee_list()
	{
		
		Utilities::ajaxRequest();
		
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search = G_Employee_Helper::getDynamicQueries($_GET['search']);
		}else {
			//no colon
			if($_GET['search']) {
				$search  = " AND (e.e_is_archive = '" . G_Employee::YES . "')";
				//$search .= " AND (e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				//$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				//$search .= " OR j.name like '%".$_GET['search']."%' OR d.name like '%".$_GET['search']."%' ";
				//$search .= " OR e.employee_code like '%". $_GET['search'] ."%' OR j.employment_status like  '%". $_GET['search'] ."%'  )";	
			}
		}
		
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		$dir = $_GET['dir'];
			
		$sort = $_GET['sort'];
		$sort = G_Employee_Helper::getSortValue($sort);
					
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $sort . ' ' . $dir  :  ' ORDER BY e.id asc' ;
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
		if($search) {			
			$employee = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, $limit,$search);
			$total_records = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
		}else{
			echo 2;
			$employee = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, $limit,$search);
			$total_records = G_Employee_Helper::findByCompanyStructureId($cstructure,$order_by, '',$search);
		}
		
		foreach ($employee as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['e_id'] = Utilities::encrypt($object['id']);
			
			$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  : BASE_FOLDER.'files/photo/'.$object['photo'];
			$data[$key]['photo'] = $photo;
		}
		
		$count_total =count($total_records);
		$total = count($employee);
		$total_records = $count_total;
		
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}

	
	function _load_add_employee_confirmation()
	{
		$this->var['hash'] = Utilities::createHash($_POST['employee_id']);
		$this->var['msg'] = "Successfully Added";
		$this->view->noTemplate();
		$this->view->render('employee/employee/confirmation.php',$this->var);
	}
	
	function schedule()
	{
		$this->var['page_title'] = 'Schedule';
		$this->view->setTemplate('template.php');
		$this->view->render('employee/schedule/index.php',$this->var);
	}
	
	function profile()
	{
		Utilities::checkModulePackageAccess('hr','employee');
	    $eid = $_GET['eid'];
	    $hash = $_GET['hash'];
		Utilities::verifyHash(Utilities::decrypt($eid),$hash);
		Loader::appMainScript('employee_profile.js');
		Loader::appMainScript('employee_profile_base.js');
		Loader::appMainScript('employee_loan.js');
		Loader::appMainScript('employee_loan_base.js');
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainJqueryFormSubmit();
		//Style::loadMainTableThemes();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJTags();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		$this->var['employee_id'] = $eid;
		$e =  G_Employee_Helper::findByEmployeeId(Utilities::decrypt($eid));
		if($e) {
			$this->var['t']				   = G_Employee_Tags_Finder::findByEmployeeId($e['id']);
			$this->var['employee_details'] = $e;	
		}else {
			$link[] = "Goto Page: ";
			display_error('',$link);
		}		

		$btn_personal_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'personal_details',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#personal_details',
    		'onclick' 				=> 'javascript:hashClick("#personal_details");',
    		'wrapper_start'			=> '<li id="personal_details_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Personal Details'
    		);

		$btn_contact_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contact_details',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#contact_details',
    		'onclick' 				=> 'javascript:hashClick("#contact_details");',
    		'wrapper_start'			=> '<li id="contact_details_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Contact Details'
    		);

		$btn_emergency_contacts_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'emergency_contacts',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#emergency_contacts',
    		'onclick' 				=> 'javascript:hashClick("#emergency_contacts");',
    		'wrapper_start'			=> '<li id="emergency_contacts_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Emergency Contacts'
    		);

		$btn_dependents_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'dependents',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#dependents',
    		'onclick' 				=> 'javascript:hashClick("#dependents");',
    		'wrapper_start'			=> '<li id="dependents_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Dependents'
    		);

		$btn_bank_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'bank',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#bank',
    		'onclick' 				=> 'javascript:hashClick("#bank");',
    		'wrapper_start'			=> '<li id="bank_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Bank'
    		);

		$btn_employment_status_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employment_status',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#employment_status',
    		'onclick' 				=> 'javascript:hashClick("#employment_status");',
    		'wrapper_start'			=> '<li id="employment_status_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Employment Status'
    		);

		$btn_compensation_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'compensation',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#compensation',
    		'onclick' 				=> 'javascript:hashClick("#compensation");',
    		'wrapper_start'			=> '<li id="compensation_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Compensation'
    		);

		$btn_benefits_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'benefits',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#benefits',
    		'onclick' 				=> 'javascript:hashClick("#benefits");',
    		'wrapper_start'			=> '<li id="benefits_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Benefits'
    		);

		$btn_contract_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contract',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#duration',
    		'onclick' 				=> 'javascript:hashClick("#duration");',
    		'wrapper_start'			=> '<li id="duration_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Contract'
    		);

		$btn_contribution_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contribution',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#contribution',
    		'onclick' 				=> 'javascript:hashClick("#contribution");',
    		'wrapper_start'			=> '<li id="contribution_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Contribution & Tax'
    		);

		$btn_training_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'training',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#training',
    		'onclick' 				=> 'javascript:hashClick("#training");',
    		'wrapper_start'			=> '<li id="training_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Training'
    		);

		$btn_memo_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'memo',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#memo_notes',
    		'onclick' 				=> 'javascript:hashClick("#memo_notes");',
    		'wrapper_start'			=> '<li id="memo_notes_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Memo'
    		);

		$btn_requirements_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'requirements',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#requirements',
    		'onclick' 				=> 'javascript:hashClick("#requirements");',
    		'wrapper_start'			=> '<li id="requirements_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Requirements'
    		);

		$btn_supervisor_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'supervisor',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#supervisor',
    		'onclick' 				=> 'javascript:hashClick("#supervisor");',
    		'wrapper_start'			=> '<li id="supervisor_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Supervisor'
    		);

		$btn_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employees_leave',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#leave',
    		'onclick' 				=> 'javascript:hashClick("#leave");',
    		'wrapper_start'			=> '<li id="leave_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Leave'
    		);

		$btn_work_experience_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'work_experience',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#work_experience',
    		'onclick' 				=> 'javascript:hashClick("#work_experience");',
    		'wrapper_start'			=> '<li id="work_experience_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Work Experience'
    		);

		$btn_education_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'educations',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#education',
    		'onclick' 				=> 'javascript:hashClick("#education");',
    		'wrapper_start'			=> '<li id="education_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Education'
    		);

		$btn_skills_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'skills',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#skills',
    		'onclick' 				=> 'javascript:hashClick("#skills");',
    		'wrapper_start'			=> '<li id="skills_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Skills'
    		);

		$btn_language_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'language',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#language',
    		'onclick' 				=> 'javascript:hashClick("#language");',
    		'wrapper_start'			=> '<li id="language_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Language'
    		);

		$btn_license_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'license',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#license',
    		'onclick' 				=> 'javascript:hashClick("#license");',
    		'wrapper_start'			=> '<li id="license_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'License'
    		);

		$btn_attachment_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'attachment',
    		'required_permission'	=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#attachment',
    		'onclick' 				=> 'javascript:hashClick("#attachment");',
    		'wrapper_start'			=> '<li id="attachment_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Attachment'
    		);
		
		$this->var['btn_personal'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_personal_config);
		$this->var['btn_contact_details'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_contact_details_config);
		$this->var['btn_emergency_contacts'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_emergency_contacts_config);
		$this->var['btn_dependents'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_dependents_config);
		$this->var['btn_bank'] 						= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_bank_config);

		$this->var['btn_employment_status'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_employment_status_config);
		$this->var['btn_compensation'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_compensation_config);
		$this->var['btn_benefits'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_benefits_config);

		$this->var['btn_contract'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_contract_config);
		$this->var['btn_contribution'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_contribution_config);
		$this->var['btn_training'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_training_config);

		$this->var['btn_memo'] 						= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_memo_config);
		$this->var['btn_requirements'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_requirements_config);
		$this->var['btn_supervisor'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_supervisor_config);
		$this->var['btn_leave'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_leave_config);
		$this->var['btn_work_experience'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_work_experience_config);

		$this->var['btn_education'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_education_config);
		$this->var['btn_skills'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_skills_config);
		$this->var['btn_language'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_language_config);
		$this->var['btn_license'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_license_config);
		$this->var['btn_attachment'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_attachment_config);

		$this->var['employee_access'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');
		
		$this->var['title'] = $title;
		$this->var['page_title'] = 'Employee Information';
		$this->var['page_subtitle'] = '<span>Manage employee list</span>';
		$this->view->setTemplate('template_employee2.php');
		$this->view->render('employee/profile/index.php',$this->var);
	}
	
	function _load_employee_summary()
	{
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$this->var['employee_details'] = G_Employee_Helper::findByEmployeeId($employee_id);
		$this->view->noTemplate();
		$this->view->render('employee/profile/employee_summary.php',$this->var);
	}
	
	function _load_photo_frame()
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$e = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id);  //findById($employee_id);
		$this->var['employee'] = $e;
		
		if( $e ){
			$file = PHOTO_FOLDER.$e->getPhoto();
		}else{
			$e = new G_Employee();
			$file = $e->getValidEmployeeImage();
		}

		if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;
			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
			
		}
		
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_management');
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/photo/photo_frame.php',$this->var);
	}
	
	function _load_photo()
	{
		Utilities::ajaxRequest();
	
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		$this->var['employee'] = $e;
		
		$file = PHOTO_FOLDER.$e->getPhoto();

		if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/photo/index.php',$this->var);	
	}
	
	
	function _get_photo_filename()
	{
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$e = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id); //findById($employee_id);
		if( $e ){
			$photo = $e->getPhoto();
		}else{
			$e = new G_Employee();
			$photo = $e->getValidEmployeeImage();
		}
		$this->view->noTemplate();
		$this->var['filename'] = $photo;
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/photo/filename.php',$this->var);
		
	}
	
	function _upload_photo()
	{
		$prefix = 'employee_';
		$employee_id =  $_POST['employee_id'];
		$em = G_Employee_Helper::findByEmployeeId($employee_id);
		$hash = $em['hash'];
		$len = strlen($_FILES['fileField']['name']);
		$pos = strpos($_FILES['fileField']['name'],'.');
		$extension_name =  substr($_FILES['fileField']['name'],$pos, 5);
		$handle = new upload($_FILES['fileField']);
		$path = $_SERVER['DOCUMENT_ROOT'] . PHOTO_FOLDER;
	
	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.$hash;
			$handle->file_overwrite 	  = true;
			$handle->image_resize         = true;
			$handle->image_x              = 300;
			$handle->image_ratio_y        = true;
			$handle->allowed = array('image/*');
	       $handle->process($path);
	       if ($handle->processed) {
	         
			  	$e = G_Employee_Finder::findById($employee_id);
				$image =  $filename . strtolower($extension_name); 
				
				$e->setPhoto($image);
				$e->save();
				
				//Tools::showArray($e);

	           $handle->clean();
			   $return = true;
			 
	       } else {	          
			  $return =  $handle->error;
	       }
	   }else {
			$return =  $handle->error;   
	   }	
			
		echo $return;
	}
	
	function _quick_autocomplete() {
		$employee_access = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_access');

		if($employee_access == Sprint_Modules::PERMISSION_06) { 
				$additional_qry .= " AND u.is_confidential = 1  ";	
		}elseif($employee_access == Sprint_Modules::PERMISSION_07){
			$additional_qry .= " AND u.is_confidential = 0  ";
		}else{
			$additional_qry .= "";
		}

		if($this->username == G_Employee_User::OVERRIDE_USERNAME && $this->h_employee_id==''){
			$additional_qry = "";


		}

		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			$records = G_Employee_Helper::findByLastnameFirstname($q,$additional_qry);
			foreach ($records as $record) {	
				$response[] = array('id'=>Utilities::encrypt($record['id']),'label'=>$record['name'],'hash'=>$record['hash']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}

		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	function _quick_autocomplete_search_by_employee_code() {
	
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			$records = G_Employee_Helper::findByEmployeeCode($q);
			foreach ($records as $record) {	
				$response[] = array('id'=>Utilities::encrypt($record['id']),'label'=>$record['name'],'hash'=>$record['hash']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}

		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	
	function ajax_get_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$fields    = array('e.id','e.employee_code','e.firstname','e.lastname','e.middlename');
			$employees = G_Employee_Finder::searchByFirstnameMiddlenameLastnameEmployeeCodeDefinedFields($q,$fields);

			foreach ($employees as $e) {
				$response[] = array($e->getId(), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}		
		echo json_encode($response);		
	}	
	
	function ajax_get_employees_superpervisor_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		$eid = Utilities::decrypt($_GET['eid']);
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameMiddlenameLastnameEmployeeCode($q);
			foreach ($employees as $e) {
				if($eid != $e->getId()){
					$response[] = array($e->getId(), $e->getFullname(), null);
				}
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}	
	

	function _load_edit_personal_details()
	{
		Utilities::ajaxRequest();
		
		$employee_id =  Utilities::decrypt($_GET['eid']);
	
		$e = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id); //findById($employee_id);
		if( $e ){
			$dynamic_fields = $e->getDynamicFields();
		}

		$this->var['dynamic_fields'] = $dynamic_fields;
		$this->var['details'] 		 = $e;
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$e);
		$this->load_summary_photo();
		
		//tags
		$this->var['t'] = $t = G_Employee_Tags_Finder::findByEmployeeId($e->getId());
    	
    	$sv = new G_Sprint_Variables();
		$working_days_options = $sv->optionsWorkingDays();

		$this->var['other_details_counter']  = 1;
		$this->var['working_days_options']   = $working_days_options;
		$this->var['title_personal_details'] = "Personal Details";

		//contact details		
		$cc = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);	
		$this->var['title_contact_details'] = "Contact Details";
		$this->var['contact_field'] = G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['cc'] = $cc;
		
		//emergency contact
		$this->var['title_emergency_contact'] = "Emergency Contacts";
		$ec = G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);
		$this->var['ec'] = $ec;
		
		//dependent
		$this->var['title_dependent'] = "Dependents";
		$dd = G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
		$this->var['dd'] = $dd;



		//get project history : LIST
		$eps = new G_Employee_Project_Site_History();

		$eps->setEmployeeId($employee_id);
        
		$employeeProjects = $eps->getCurrentProject();
        

		$this->var['project_tag'] = $employeeProjects[0]['project_id'];

        $this->var['project_site'] = G_Project_Site::findProjectSites(); 


		
		//bank
		$this->var['title_bank'] = "Bank Account";
		$bb = G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);			
		$this->var['banks'] = $bb;	

		$this->var['title'] = "Personal Information";
		//$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/personal_details/form/personal_details_edit.php',$this->var);
	}
	
	function _load_personal_details() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
	
		$e = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id); //findById($employee_id);

		if( $e ){
			$dynamic_fields = $e->getDynamicFields();
		}

		$this->var['dynamic_fields'] = $dynamic_fields;
		$this->var['details'] 		 = $e;
		
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$e);
		$this->load_summary_photo();


		//get project history : LIST//
		$eps = new G_Employee_Project_Site_History();

		$eps->setEmployeeId($employee_id);
        
		$employeeProjects = $eps->getCurrentProject();

		$this->var['project_tag'] = $employeeProjects[0]['project_name'];

		
		//tags
		$this->var['t'] = $t = G_Employee_Tags_Finder::findByEmployeeId($e->getId());

		$btn_edit_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'personal_details',
    		'href' 					=> '#personal_details',
    		'onclick' 				=> 'javascript:loadPersonalDetailsForm();',
    		'id' 					=> '',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Edit Details'
    		); 

		$btn_add_history_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'personal_details',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:_addEmployeeHistoryDialog("'.Utilities::encrypt($e->getId()).'","'.ucfirst($e->firstname) . ' ' . ucfirst($e->lastname).'");',
    		'id' 					=> '',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong> Add History'
    		); 


    	
    	$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','personal_details');
    	$this->var['permission_action_on_photo'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_management');
    	$this->var['btn_edit_details'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_details_config);
    	$this->var['btn_add_history'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_history_config);

    	$sv = new G_Sprint_Variables();
		$working_days_options = $sv->optionsWorkingDays();

		$this->var['other_details_counter']  = 1;
		$this->var['working_days_options']   = $working_days_options;
		$this->var['title_personal_details'] = "Personal Details";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/personal_details/index.php',$this->var);
		
		//contact details
		
		$cc = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);	
		$this->var['title_contact_details'] = "Contact Details";
		$this->var['contact_field'] = G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['cc'] = $cc;
		
		//emergency contact
		$this->var['title_emergency_contact'] = "Emergency Contacts";
		$ec = G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);
		$this->var['ec'] = $ec;
		
		//dependent
		$this->var['title_dependent'] = "Dependents";
		$dd = G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
		$this->var['dd'] = $dd;
		
		//bank
		$this->var['title_bank'] = "Bank Account";
		$bb = G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);			
		$this->var['banks'] = $bb;	

		$this->var['title'] = "Personal Information";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/index.php',$this->var);

	}
	
	function _load_contact_details()
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
	
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);

		$btn_edit_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contact_details',
    		'href' 					=> '#contact_details_wrapper',
    		'onclick' 				=> 'javascript:loadContactDetailsForm();',
    		'id' 					=> '',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Edit Details'
    		); 
    	
    	$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','contact_details');
    	$this->var['btn_edit_details'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_details_config);
	
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['details'] = $e;
		$this->var['g_countries'] = G_Settings_Location_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		$this->var['title_contact_details'] = "Contact Details";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/contact_details/index.php',$this->var);
	}
	
	function _load_emergency_contact() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Emergency_Contact_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['contacts'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_emergency_contacts_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'emergency_contacts',
    		'href' 					=> 'javascript:loadEmergencyContactAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'emergency_contact_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Emergency Contacts</b>'
    		); 
    	
    	$this->var['permission_action'] 			= $this->validatePermission(G_Sprint_Modules::HR,'employees','emergency_contacts');
    	$this->var['btn_add_emergency_contacts'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_emergency_contacts_config);
	
		$this->var['title_emergency_contact'] = "Emergency Contacts";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/emergency_contacts/index.php',$this->var);
	}
	
	function _load_emergency_contacts_edit_form()
	{
		$emergency_contact_id = $_POST['emergency_contact_id'];
		$e = G_Employee_Emergency_Contact_Finder::findById($emergency_contact_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/emergency_contacts/form/emergency_contacts_edit.php',$this->var);
	}

	function _load_employee_add_benefit_form()
	{
		$eid = $_GET['eid'];
		if( !empty($eid) ){
			$employee_id = Utilities::decrypt($eid);
			$b = new G_Settings_Employee_Benefit();
			$benefits = $b->getEmployeeUnregisteredBenefits($employee_id);

			foreach($benefits as $key => $value){
				foreach( $value as $subkey => $subvalue ){
					if( $subkey == "id" ){
						$benefits[$key][$subkey] = Utilities::encrypt($subvalue);
					}
				}				
			}

			if( !empty($benefits) ){
				$description = "<p>Select benefit(s) to enroll employee</p>";
			}else{
				$description ="<p>No benefit(s) to enroll</p>";
			}
			
			$this->var['description'] = $description;
			$this->var['token']       = Utilities::createFormToken();
			$this->var['benefits']    = $benefits;
			$this->var['eid']         = $eid;			
			$this->view->noTemplate();
			$this->view->render('employee/profile/employment_information/benefits/form/add_benefit.php',$this->var);
		}else{
			echo "Record not found";
		}
	}
	
	function _delete_emergency_contact()
	{
		Utilities::ajaxRequest();
		$emergency_contact_id = $_POST['emergency_contact_id'];
		$e = G_Employee_Emergency_Contact_Finder::findById($emergency_contact_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_delete_emergency_contact'] .",id=". $emergency_contact_id,$this->username);
		
		echo 1;
	}
	
	function _load_dependents() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Dependent_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['dependents'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_dependent_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'dependents',
    		'href' 					=> 'javascript:loadDependentAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'dependent_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Dependent</b>'
    		); 
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'employees','dependents');
    	$this->var['btn_add_dependent'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_dependent_config);
	
		$this->var['title_dependent'] = "Dependents";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/dependent/index.php',$this->var);
	}
	
	function _load_employee_benefits() 
	{
		Utilities::ajaxRequest();
		$eid = $_GET['employee_id'];		
		$this->load_summary_photo();

		$btn_add_benefit_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'benefits',
    		'href' 					=> 'javascript:loadAddBenefitForm("' . $eid . '");',
    		'onclick' 				=> '',
    		'id' 					=> 'benefits_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Benefits</b>'
    		); 
				
		$this->var['eid']       		= $eid;
		$this->var['btn_add_benefit']   = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_benefit_config);
		$this->var['title_dependent']   = "Employee Benefits";
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/benefits/index.php',$this->var);
	}

	function _dt_employee_benefits_list_depre()
	{
		if( !empty($_GET['eid']) ){
			$eid         = $_GET['eid'];
			$employee_id = Utilities::decrypt($eid);

			$b = new G_Employee_Benefits_Main();
			$b->setCompanyStructureId($this->company_structure_id);
			$b->setEmployeeDepartmentId($employee_id);
			$benefits = $b->getAllEmployeeBenefits();
			$benefits = Utilities::encryptArrayIds("id",$benefits);


			$this->var['all_employees']     = Employee_Benefits_Main::ALL_EMPLOYEE;	
			$this->var['benefits']          = $benefits;
			$this->var['employee_id']       = $employee_id;
			$this->view->noTemplate();
			$this->view->render('employee/profile/employment_information/benefits/_employee_benefits.php',$this->var);
		}else{
			echo "Employee has no benefit(s) enrolled";
		}
	}

	function _dt_employee_benefits_list()
	{
		if( !empty($_GET['eid']) ){
			$eid         = $_GET['eid'];
			$employee_id = Utilities::decrypt($eid);
			$e = G_Employee_Finder::findById($employee_id);
			$benefits = array();

			if( $e ){
				$b = new G_Employee_Benefits_Main();
				$applied_to = $b->validAppliedToOptions();
	        	$criteria   = G_Employee_Benefits_Main::NO_CRITERIA;	
	        	$cutoff     = 0;        	
	        	//$benefits   = $b->setCriteria($criteria)->getEmployeeBenefits($e, $applied_to, $cutoff);
	        	$benefits   = $b->getEmployeeEnrolledBenefits($e);
	        	//$a_benefits = $benefits->a_employee_benefits;        	
			}			
			$this->var['all_employees']     = Employee_Benefits_Main::ALL_EMPLOYEE;	
			//$this->var['benefits']          = $a_benefits;
			$this->var['benefits']          = $benefits;
 			$this->var['employee_id']       = $employee_id;
			$this->view->noTemplate();
			$this->view->render('employee/profile/employment_information/benefits/_employee_benefits.php',$this->var);
		}else{
			echo "Employee has no benefit(s) enrolled";
		}
	}

	function _load_employee_benefits_deprecated() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);	
		$employee_id = $_GET['employee_id'];
		$benefits = G_Employee_Benefit_Helper::getAllEmployeeBenefits(Utilities::decrypt($employee_id));
		
		$this->load_summary_photo();
				
		$this->var['benefits']        = $benefits;
		$this->var['employee_id']     = $employee_id;
		$this->var['can_manage']      = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		$this->var['title_dependent'] = "Employee Benefits";
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/benefits/index.php',$this->var);
	}
	
	function _load_dependent_edit_form()
	{
		$dependent_id = $_POST['dependent_id'];
		$e = G_Employee_Dependent_Finder::findById($dependent_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/dependent/form/dependent_edit.php',$this->var);
	}
	
	function _delete_dependent()
	{
		Utilities::ajaxRequest();
		$dependent_id = $_POST['dependent_id'];
		$e = G_Employee_Dependent_Finder::findById($dependent_id);
		if( $e ){
			$e->delete();
			$e->updateEmployeeDataTotalDependents();
		}
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_delete_dependents'] .",id=". $dependent_id,$this->username);
		echo 1;
	}
	
	
	//
	function _load_bank() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Direct_Deposit_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['banks'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_account_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'bank',
    		'href' 					=> 'javascript:loadDirectDepositAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'direct_deposit_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Account</b>'
    		); 
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'employees','bank');
    	$this->var['btn_add_account'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_account_config);
	
		$this->var['title_bank'] = "Bank Account";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/bank/index.php',$this->var);
	}
	
	function _load_direct_deposit_edit_form()
	{
		$direct_deposit_id = $_POST['direct_deposit_id'];
		$e = G_Employee_Direct_Deposit_Finder::findById($direct_deposit_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/bank/form/bank_edit.php',$this->var);
	}
	
	function _delete_direct_deposit()
	{
		Utilities::ajaxRequest();
		$direct_deposit_id = $_POST['direct_deposit_id'];
		$e = G_Employee_Direct_Deposit_Finder::findById($direct_deposit_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_delete_bank_account'] .",employee id=". $direct_deposit_id,$this->username);
		echo 1;
	}
	
	function _archive_employee_history()
	{
		if($_POST['eid']){
			$geh = G_Employee_Details_History_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($geh){
				$geh->archive();
				$this->triggerAuditTrail(1,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_archive_history'] .",id=". $geh->getId(),$this->username);
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully deleted.';
			}else{
				$this->triggerAuditTrail(0,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_archive_history'] .",id=". $geh->getId(),$this->username);
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$this->triggerAuditTrail(0,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_archive_history'] .",id=". $geh->getId(),$this->username);
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	//job history
	
	function _load_job_history() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];

		//code for updating history date order
		$present_hist = G_Employee_Job_History_Finder::findPresentHistoryById(Utilities::decrypt($employee_id));
			$changed = false;
		$emp_his = G_Employee_Job_History_Finder::findAllById(Utilities::decrypt($employee_id));
		//checked if all have same end dates
		foreach ($emp_his as $key => $value) {
			if($value->end_date != '' ){
					if($value->end_date==$present_hist->start_date){
				$changed = true;
					}else{
				$changed = false;
				break;
					}
			}
		}//end change
		if($changed){
						$history_ids = array();
			$start_dates = array();
			$counter = 1;
			$startDateCounter = 0;
			foreach ($emp_his as $key => $value) {
			 array_push($start_dates, $value->start_date);					
				array_push($history_ids, $value->id);
				if($value->end_date != ''){				
					$j = new G_Employee_Job_History();
			  $j->setEmployeeId(Utilities::decrypt($_GET['employee_id']));
			  $j->updateJobHistoryEndDate($history_ids[$counter],$start_dates[$startDateCounter]);
					$counter++;
					$startDateCounter++;
				}
			
			}
		}
		//code for updating history date order

		$history = G_Employee_Job_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$job = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['job'] = $job;
		$this->var['job_history'] = $history;
		$this->var['status'] = $status;
		//$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_job_history_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employment_status',
    		'href' 					=> 'javascript:loadJobHistoryAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'job_history_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Job History</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','employment_status');
    	$this->var['btn_add_job_history'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_job_history_config);
	
		$this->var['title_job_history'] = "Job History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/job_history/index.php',$this->var);
	}
	
	function _load_job_history_edit_form()
	{
		
		$job_history_id = $_POST['job_history_id'];
		$e = G_Employee_Job_History_Finder::findById($job_history_id);
		$job = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['job'] = $job;
		$this->var['details'] = $e;
		$this->var['status'] = $status;
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/job_history/form/job_history_edit.php',$this->var);
	}
	
	function _delete_job_history()
	{
		Utilities::ajaxRequest();
		$job_history_id = $_POST['job_history_id'];
		$e = G_Employee_Job_History_Finder::findById($job_history_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_job_history_delete'] .",id=". $job_history_id,$this->username);		
		echo 1;
	}
	
	//end of job history
	
	//subdivision history
	
	function _load_subdivision_history() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Subdivision_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		//$department = G_Company_Structure_Finder::findParentChildByBranchIdAndIsNotArchive($this->company_structure_id);
		$department = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);


		//code for updating history by order
		//checking if end dates are same
		$changed_dept = false;
		$present_dept = G_Employee_Subdivision_History_Finder::findPresentByEmployeeId(Utilities::decrypt($employee_id));
	
		foreach ($history as $key => $value) {
			if($value->end_date != ''){
					if($value->end_date == $present_dept->start_date){
						$changed_dept = true;
					}
					else{
						$changed_dept = false;
					}
			}
		}
		//end checking if end dates are same

		if($changed_dept){

			$department_ids = array();
			$department_start_dates = array();

			$dept_counter = 1;
			$dept_start_dates = 0;
			foreach ($history as $key => $value) {
				# code...
				array_push($department_start_dates, $value->start_date);					
				array_push($department_ids, $value->id);

				if($value->end_date != ''){
			
					$sh = new G_Employee_Subdivision_History;
					$sh->setEmployeeId(Utilities::decrypt($employee_id));
					//$sh->resetEmployeePresentSubdivisionBySubdivisionHistory($history_id);
					$sh->updateSubdivisionBySubdivisionHistoryEndDate($department_ids[$dept_counter],$department_start_dates[$dept_start_dates]);
											
					$dept_counter++;
					$dept_start_dates++;
					
				}

			



			}

			//var_dump($department_ids[0]);
		}
		//end for updating history by order
	

	
		$this->var['department'] = $department;
		$this->var['subdivision_history'] = $history;

		//$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_department_history_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employment_status',
    		'href' 					=> 'javascript:loadSubdivisionHistoryAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'subdivision_history_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Department History</b>'
    		); 
    	
    	$this->var['permission_action'] 			= $this->validatePermission(G_Sprint_Modules::HR,'employees','employment_status');
    	$this->var['btn_add_department_history'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_history_config);
	
		$this->var['title_subdivision_history'] = "Department History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/subdivision_history/index.php',$this->var);
	}
	
	function _load_subdivision_history_edit_form()
	{
		
		$subdivision_history_id = $_POST['subdivision_history_id'];
		$e = G_Employee_Subdivision_History_Finder::findById($subdivision_history_id);
		//$department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
		$department = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		
		
		$this->var['details'] = $e;
 		$this->var['department'] = $department;
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/subdivision_history/form/subdivision_history_edit.php',$this->var);
	}
	
	function _delete_subdivision_history()
	{
		Utilities::ajaxRequest();
		$subdivision_history_id = $_POST['subdivision_history_id'];
		$e = G_Employee_Subdivision_History_Finder::findById($subdivision_history_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_subd_history_delete'] .",id=". $subdivision_history_id,$this->username);	
		echo 1;

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId($e->employee_id);
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, 'Subdivision History of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

	}
	
	//end of subdivision history
	
	
	//employment status
	
	function _load_employment_status() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Job_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$job = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$employee_id =  $_GET['employee_id'];

		$d = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($employee_id));
		$current_active_status_details = G_Employee_Status_History_Finder::findCurrentEmployeeStatusWithStatusId(Utilities::decrypt($employee_id), 1);

		$this->var['current_employee_active_date'] = "";
		if($current_active_status_details) {
			$this->var['current_employee_active_date'] = $current_active_status_details->getStartDate();
		}
		
		$branch = G_Company_Branch_Finder::findByCompanyStructureId($d['company_structure_id']);
		$department = G_Company_Structure_finder::findAllDepartmentsIsNotArchive();//G_Company_Structure_finder::findByCompanyBranchId($d['branch_id']);
		$job = G_Job_Finder::findByCompanyStructureId($d['company_structure_id']);
		$job_category = G_Eeo_Job_Category_finder::findByCompanyStructureId($d['company_structure_id']);
	
		
		$employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));		
		$position =  G_Job_Finder::findById($d['job_id']);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		
		if($total_status>0){
			$status = G_Job_Employment_Status_Finder::findByJobId($position->getId());
			$status_type = 1; // status by position
		}else {
			$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
			$status_type =0; // default status
		}
		if($employee['employment_status']=='Terminated') {
			
			$memo = G_Employee_Memo_Finder::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));	
			
			foreach($memo as $key=> $val) {
				if($val->title=='Terminated') {
					$this->var['terminated_memo'] = $val->memo;
				}
			}
		}
		
		$employee_status = G_Settings_Employee_Status_Finder::findAllIsNotArchiveByCompanyStructureId($this->company_structure_id);
		$estatus		 = G_Settings_Employee_Status_Finder::findById($d['employee_status_id']);
		if($estatus){
			$estatus_title = $estatus->getName();
			$estatus_id    = $estatus->getId();
		}else{
			$estatus_title = "";
			$estatus_id    = "";
		}

		$section = G_Company_Structure_Finder::findById($d['section_id']);
		if($section) {
			$section_name = $section->getTitle();			
		}
		
		$sections = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentIdAndType($d['department_id'], G_Company_Structure::SECTION);		
		if(empty($sections)) {
			$sections = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByType(G_Company_Structure::SECTION);		
		}

		$this->var['sections'] = $sections;		
		$this->var['section_name']      = $section_name;
		$this->var['estatus_id']        = $estatus_id;
		$this->var['estatus_title']	  = $estatus_title;
		$this->var['employee_status']   = $employee_status;
		$this->var['employee_status_id']= $d['employee_status_id'];
		$this->var['status'] 		     = $status;
		$this->var['status_type'] 		  = $status_type;
		$this->var['employment_status'] = $employee['employment_status'];
		
		$this->var['branch'] 	   = $branch;
		$this->var['d'] 			   = $d;
		$this->var['department']   = $department;
		$this->var['job'] 		   = $job;
		$this->var['job_category'] = $job_category;
		$this->load_summary_photo();
		$this->var['employee_id']  = $employee_id;	

		$btn_edit_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employment_status',
    		'href' 					=> '#employment_status',
    		'onclick' 				=> 'javascript:loadEmploymentStatusEditForm();',
    		'id' 					=> '',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Edit Details'
    		); 
		$this->var['sections']              = $sections;
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','employment_status');
    	$this->var['btn_edit_details'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_details_config);

		$this->var['title_employment_status'] = "Employment Status";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/employment_status/index.php',$this->var);
	}
	
	
	function _load_profile_status_dropdown()
	{
	

		$position_id =  ($_GET['pid']>0 || $_GET['pid']=='')?$_GET['pid']: 0;
		$employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));
		
		$position =  G_Job_Finder::findById($position_id);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		
		if($total_status>0){
			$status = G_Job_Employment_Status_Finder::findByJobId($position->getId());
			$status_type = 1; // status by position
		}else {
			$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
			$status_type =0; // default status
		}
		
		$this->var['status'] = $status;
		$this->var['status_type'] = $status_type;
		$this->var['employment_status'] = $employee['employment_status'];
		
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/includes/employment_status_dropdown.php',$this->var);
	}
	
	
	function _delete_load_employment_status()
	{
		Utilities::ajaxRequest();
		$direct_deposit_id = $_POST['direct_deposit_id'];
		$e = G_Employee_Direct_Deposit_Finder::findById($direct_deposit_id);
		$e->delete();
		echo 1;
	}
	
	function _load_job_description()
	{
		Utilities::ajaxRequest();
		$job_id = (int) $_POST['job_id'];
		$job = G_Job_Finder::findById($job_id);
		$job_specification = G_Job_Specification_Finder::findById($job->getJobSpecificationId());
		$this->var['job'] = $job_specification;
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/includes/job_description.php',$this->var);
	}
	
	function _load_job_duties()
	{
		Utilities::ajaxRequest();
		$job_id = (int) $_POST['job_id'];
		$job = G_Job_Finder::findById($job_id);
		$job_specification = G_Job_Specification_Finder::findById($job->getJobSpecificationId());
		$this->var['job'] = $job_specification;
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/includes/job_duties.php',$this->var);
	}
	
	// extend contract
	function _load_duration() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Extend_Contract_Finder::findByEmployeeId($employee_id);
		
		$this->var['durations'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = Utilities::encrypt($employee_id);

		$btn_add_duration_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contract',
    		'href' 					=> 'javascript:loadDurationAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'duration_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Duration</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','contract');
    	$this->var['btn_add_duration'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_duration_config);
	
		$this->var['title_duration'] = "Contracts";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/employment_duration/index.php',$this->var);
	}
	
	function _load_duration_edit_form()
	{
		$duration_id = $_POST['duration_id'];
		$e = G_Employee_Extend_Contract_Finder::findById($duration_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/employment_duration/form/duration_edit.php',$this->var);
	}
	
	function _delete_duration()
	{
		Utilities::ajaxRequest();
		$duration_id = $_POST['duration_id'];
		$e = G_Employee_Extend_Contract_Finder::findById($duration_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_contract_delete'] .",id=". $duration_id,$this->username);
		echo 1;
	}
	//end extend contract
	
	//end of employment status
	
	function _load_compensation() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_GET['employee_id']));
		$employee_salary =  G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);
	
		$employee_rate = G_Job_Salary_Rate_Finder::findById( $employee_salary->job_salary_rate_id);
		$employee_pay_period = G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);
		
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['employee_id'] = Utilities::encrypt($employee->id);
		$this->var['employee_salary'] = $employee_salary;
		$this->var['employee_rate'] = $employee_rate;
		$this->var['employee_pay_period'] = $employee_pay_period;
		$this->var['pay_period'] = $pay_period;
		$this->var['rate'] = $rate;

		$btn_edit_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'compensation',
    		'href' 					=> '#compensation',
    		'onclick' 				=> 'javascript:loadCompensationForm();',
    		'id' 					=> 'subdivision_history_add_button_wrapper',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Edit Details'
    		); 
    	
    	$this->var['permission_action'] 			= $this->validatePermission(G_Sprint_Modules::HR,'employees','compensation');
    	$this->var['btn_edit_details'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_details_config);

		$this->var['title_compensation'] = "Compensation";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/compensation/index.php',$this->var);
	}
	
	//compensation history
	
	function _load_compensation_history() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['compensation_history'] = $history;
		$this->var['pay_period'] = $pay_period;
		$this->var['rate'] = $rate;
		$this->var['employee_id'] = $employee_id;

		$btn_add_compensation_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'compensation',
    		'href' 					=> 'javascript:loadCompensationHistoryAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'compensation_history_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Compensation</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','compensation');
    	$this->var['btn_add_compensation'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_compensation_config);
	
		$this->var['title_compensation_history'] = "Compensation History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/compensation_history/index.php',$this->var);
	}
	
	function _load_compensation_history_edit_form()
	{
		
		$compensation_history_id = (int) $_POST['compensation_history_id'];

		$e = G_Employee_Basic_Salary_History_Finder::findById($compensation_history_id);

		$employee = G_Employee_Finder::findById($e->employee_id);
		$employee_salary =  G_Employee_Basic_Salary_History_Finder::findById($e->id);
			
		$employee_rate = G_Job_Salary_Rate_Finder::findById( $employee_salary->job_salary_rate_id);
		$employee_pay_period = G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);

		$this->var['frequency_id'] = $employee_salary->getFrequencyId();

		$this->var['compensation_history_id'] = $compensation_history_id;
		$this->var['employee_id'] = Utilities::encrypt($employee->id);
		$this->var['employee_salary'] = $employee_salary;
		$this->var['employee_rate'] = $employee_rate;
		$this->var['employee_pay_period'] = $employee_pay_period;
		
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['pay_period'] = $pay_period;
		$this->var['rate'] = $rate;

		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/compensation_history/form/compensation_history_edit.php',$this->var);
	}
	
	function _delete_compensation_history()
	{
		Utilities::ajaxRequest();

		$compensation_history_id = $_POST['compensation_history_id'];
		$e = G_Employee_Basic_Salary_History_Finder::findById($compensation_history_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_compensation_delete'] .",id=". $compensation_history_id,$this->username);
		echo 1;

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId($e->employee_id);
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	    $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_DELETE, 'Employment Compensation Details of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);
	}
	
	//end of compensation history
	
	function _load_minimum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo number_format($salary->minimum_salary,2);
	}
	
	function _load_maximum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo number_format($salary->maximum_salary,2);
	}
	
	//requirements
	function _load_requirements() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Requirements_Finder::findByEmployeeId(Utilities::decrypt($employee_id));	
		$data[] = unserialize($e->requirements);	

		$this->var['requirements'] = $data;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_requirements_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'requirements',
    		'href' 					=> 'javascript:loadRequirementsAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'requirements_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Requirements</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','requirements');
    	$this->var['btn_add_requirements'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_requirements_config);
	
		$this->var['title'] = "Requirements";
		$this->var['title_requirements'] = "Requirements";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/requirements/index.php',$this->var);
	}
	
	function _load_requirements_edit_form()
	{
		$requirement_id = $_POST['requirement_id'];
		$e = G_Employee_Requirements_Finder::findById($requirement_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/requirements/form/requirements_edit.php',$this->var);
	}
	
	function _add_default_requirements()
	{
		
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$req = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
		
		//requirements from file
		$file = BASE_FOLDER. 'files/xml/requirements.xml';
		$obj_requirements = G_Settings_Requirement_Finder::findAllIsNotArchiveByCompanyStructureId();		
		if( !empty($obj_requirements) ){
			foreach( $obj_requirements as $r ){				
				$requirements[Tools::friendlyFormName($r->getName())] = $r->getId();
			}
		}else{
			if(Tools::isFileExist($file)==true) {
				$requirements = Requirements::getDefaultRequirements();	
			}else {
				foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
					$requirements[Tools::friendlyFormName($key)] = '';
				}	
			}
		}
		
		$gss = new G_Employee_Requirements;
		$gss->setId($req->id);
		$gss->setEmployeeId($employee_id);
		$gss->setRequirements(serialize($requirements));
		$gss->setIsComplete(0);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();
		echo 1;
	}
	
	function _update_requirements()
	{
		//print_r($_POST);	
		$form = $_POST;
		$employee_id= Utilities::decrypt($_POST['employee_id']);
		$r = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);

		$req = unserialize($r->requirements);
		unset($form['applicant_id']);

		foreach($req as $key=>$value) {
			$req[$key] = $form[$key];
		}
		
		$is_complete = 1;
			foreach($req as $key=>$val) {
				
				if($val=='') {
					$is_complete=0;	
				}
			}

		$requirements = serialize($req);
		$gss = new G_Employee_Requirements;
		$gss->setId($r->id);
		$gss->setEmployeeId($employee_id);
		$gss->setRequirements($requirements);
		$gss->setIsComplete($is_complete);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();
		echo 1;
		
	}
	
	function _add_requirements()
	{

		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$requirements = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
	
		if($requirements) {
			//update
			$req = unserialize($requirements->requirements);
			$req[Tools::friendlyFormName($_POST['name'])] = '';

			$is_complete = 1;
			foreach($req as $key=>$val) {
				
				if($val=='') {
					$is_complete=0;	
				}
			}
			
			$gss = new G_Employee_Requirements;
			$gss->setId($requirements->id);
			$gss->setEmployeeId($employee_id);
			$gss->setRequirements(serialize($req));
			$gss->setIsComplete($is_complete);
			$gss->setDateUpdated(date("Y-m-d"));
			$gss->save();
		}else {
			//insert<
			//print_r($_POST);
			$req[Tools::friendlyFormName($_POST['name'])] = '';
			//print_r($req);
			$new_req = serialize($req);
			
			$gss = new G_Employee_Requirements;
			$gss->setId($r->id);
			$gss->setEmployeeId($employee_id);
			$gss->setRequirements($new_req);
			$gss->setIsComplete(0);
			$gss->setDateUpdated(date("Y-m-d"));
			$gss->save();
		}
		echo 1;
		
	}
	
	function _delete_requirements()
	{
		$employee_id= Utilities::decrypt($_POST['employee_id']);
		$r = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
		$req = unserialize($r->requirements);
		//print_r($req);
		foreach($req as $key=>$value) {
			if($_POST['requirement_id']!=$key) {
				$new[$key] = $value; 	
			}
			
		}
		
		$is_complete = 1;
		foreach($new as $key=>$val) {
				
				if($val=='') {
					$is_complete=0;	
				}
		}
		if(count($new)==0) {
			$is_complete='no requirements';	
		}

		$requirements = serialize($new);
		$gss = new G_Employee_Requirements;
		$gss->setId($r->id);
		$gss->setEmployeeId($employee_id);
		$gss->setRequirements($requirements);
		$gss->setIsComplete($is_complete);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();

		echo 1;
	}
	

	
	// performance
	
	function _load_performance() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Performance_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		//echo "<pre>";
		//print_r($e);
		$this->var['performance'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
		$this->var['title_performance'] = "Performance";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/performance/index.php',$this->var);
	}
	
	function _load_performance_edit_form()
	{
		$performance_id= $_POST['performance_id'];
		$e = G_Employee_Performance_Finder::findById($performance_id);
		if($e){
			$employee = G_Employee_Finder::findById($e->employee_id);
			$this->var['employee_name'] = 	$employee->lastname . ", ". $employee->firstname;
			$reviewer = G_Employee_Finder::findById($e->reviewer_id);
			$this->var['reviewer_name'] = 	$reviewer->lastname . ", ". $reviewer->firstname;
			$c = G_Employee_Finder::findById($e->created_by);
			$this->var['created_by'] = $c->lastname. ", " . $c->firstname;
			$xmlUrl = $e->kpi;
			$xmlObj = simplexml_load_string($xmlUrl);
			$xml = new Xml;
			$kpi = $xml->objectsIntoArray($xmlObj);
			$this->var['kpi'] = $kpi['kpi'];
		}
		
			$ob->kpi->id=0;
   			$ob->kpi->rate=10;
    		$ob->kpi->comment="textB";
			$arr[] = $ob;
			
			$ob->kpi->id=1;
   			$ob->kpi->rate=2;
    		$ob->kpi->comment="textC";
			$arr[] = $ob;
			//----test object----
			echo "<pre>";
			print_r($arr);
			//header("Content-Type:text/xml");
			$xml = new Xml;
			

			$xml->setNode('xml');
			echo $xml->toXml($arr);
			//echo $xmlStr = $xml->toXml($ob);
		
		
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/performance/form/performance_edit.php',$this->var);
	}
	
	function _delete_performance()
	{
		Utilities::ajaxRequest();
		$performance_id = $_POST['performance_id'];
		$e = G_Employee_Dependent_Finder::findById($performance_id);
		$e->delete();
		echo 1;
	}
	
	//end performance
	
	function _load_employee_performance() {
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/performance/index.xml',$this->var);
	}
	
	//training
	
	function _load_training() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Training_Finder::findByEmployeeId(Utilities::decrypt($employee_id));

		$this->var['training'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_training_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'training',
    		'href' 					=> 'javascript:loadTrainingAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'training_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Training</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','training');
    	$this->var['btn_add_training'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_training_config);
	
		$this->var['title_training'] = "Training";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/training/index.php',$this->var);
	}
	
	function _load_training_edit_form()
	{
		$training_id = $_POST['training_id'];
		$e = G_Employee_Training_Finder::findById($training_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/training/form/training_edit.php',$this->var);
	}
	
	function _delete_training()
	{
		Utilities::ajaxRequest();
		$training_id = $_POST['training_id'];
		$e = G_Employee_Training_Finder::findById($training_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_training_delete'] .",id=". $training_id,$this->username);
		echo 1;
	}
	
	// end of training

	//memo notes
	
	function _load_memo_notes() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Memo_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['memo'] = $e;
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		$this->var['memo_template'] = G_Settings_Memo_Finder::findAll();
		$this->var['title_memo'] = "Memo";

		$btn_add_memo_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'memo',
    		'href' 					=> 'javascript:loadMemoAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'memo_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Memo</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','memo');
    	$this->var['btn_add_memo'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_memo_config);

		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/memo/index.php',$this->var);
	}
	
	function _load_memo_edit_form()
	{
		$memo_id = $_POST['memo_id'];
		$e = G_Employee_Memo_Finder::findById($memo_id);

		$this->var['memo_template'] = G_Settings_Memo_Finder::findAll();
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/memo/form/memo_edit.php',$this->var);
	}
	
	function _delete_memo()
	{
		Utilities::ajaxRequest();
		$memo_id = $_POST['memo_id'];
		$e = G_Employee_Memo_Finder::findById($memo_id);
		$e->delete();
		echo 1;
	}
	
	//end memo notes
	
	//work experience
	
	function _load_work_experience() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Work_Experience_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['work_experience'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_work_experience_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'work_experience',
    		'href' 					=> 'javascript:loadWorkExperienceAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'work_experience_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Work Experience</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','work_experience');
    	$this->var['btn_add_work_experience'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_work_experience_config);
	
		$this->var['title_work_experience'] = "Work Experience";
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/work_experience/index.php',$this->var);
	}
	
	function _load_work_experience_edit_form()
	{
		$work_experience_id = $_POST['work_experience_id'];
		$e = G_Employee_Work_Experience_Finder::findById($work_experience_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/work_experience/form/work_experience_edit.php',$this->var);
	}
	
	function _delete_work_experience()
	{
		Utilities::ajaxRequest();
		$e = G_Employee_Work_Experience_Finder::findById(Utilities::decrypt($_POST['h_id']));
		$e->delete();
		echo 1;
	}
	
	//work experience
	
	//Set employee fixed contri
	function _load_employee_fixed_contributions()
	{
		Utilities::ajaxRequest();
		$employee_id =  $_GET['employee_id'];

		$fixed_contri = new G_Employee_Fixed_Contribution();
		$employee_fixed_contri = $fixed_contri->getEmployeeFixedContributions(Utilities::decrypt($employee_id));
		$fixed_contri_types    = $fixed_contri->getFixedContributionTypes();		
		$new_fixed_contri_types = array();
		foreach( $fixed_contri_types as $type ){
			foreach( $employee_fixed_contri as $efc ){				
				if( $efc['type'] == $type ){
					$new_fixed_contri_types[$efc['type']] = array(
						'is_activated' => $efc['is_activated'],
						'ee_amount' => $efc['ee_amount'],
						'er_amount' => $efc['er_amount']
					);
				}
			}
		}
		
		$this->var['employee_id']  = $employee_id;
		$this->var['fixed_contri'] = $fixed_contri;
		$this->var['employee_fixed_contri'] = $new_fixed_contri_types;
		$this->var['fixed_contri_types']    = $fixed_contri_types;
 		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/contribution/form/set_employee_fixed_contributions.php',$this->var);


	}
	
	//contribution
	
	function _load_contribution() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$emp = G_Employee_Finder::findById(Utilities::decrypt($employee_id));
		if($emp){
			$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($emp);	
			if($salary){
				if($salary->isDaily()){
				$basic_salary = ($salary->getBasicSalary()*$emp->getYearWorkingDays())/12;
				}else
				$basic_salary = $salary->getBasicSalary();
				G_Employee_Contribution_Helper::updateContribution($emp->getId(), $basic_salary);
			}
		}
		$e = G_Employee_Contribution_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$fixed_contri = new G_Employee_Fixed_Contribution();
		$fixed_contri_data = $fixed_contri->getEmployeeFixedContributions(Utilities::decrypt($employee_id));

		if($emp) {
			$this->var['is_exempted'] = $emp->getIsTaxExempted();
		}

		$this->var['c'] = $e;
		$this->var['to_deduct'] = unserialize($e->to_deduct);
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_edit_details_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contribution',
    		'href' 					=> 'javascript:loadContributionForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'contribution_button_wrapper',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Edit Details'
    		); 

		$btn_refresh_contribution_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contribution',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> '',
    		'id' 					=> 'btn-refresh-contri',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Refresh Contribution'
    		);

    	$btn_fixed_contribution_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'contribution',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> '',
    		'id' 					=> 'btn-set-fixed-contri',
    		'class' 				=> 'edit_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Set employee fixed contributions'
    		); 
    	
    	$this->var['fixed_contri_data']				= $fixed_contri_data;
    	$this->var['permission_action'] 			= $this->validatePermission(G_Sprint_Modules::HR,'employees','contribution');
    	$this->var['btn_edit_details'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_details_config);
    	$this->var['btn_refresh_contribution'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_refresh_contribution_config);
    	$this->var['btn_fixed_contribution'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_fixed_contribution_config);
	
		$this->var['title_contribution'] = "Contribution";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/contribution/index.php',$this->var);
	}
	
	function _delete_contribution()
	{
		Utilities::ajaxRequest();
		$work_experience_id = $_POST['work_experience_id'];
		$e = G_Employee_Work_Experience_Finder::findById($work_experience_id);
		$e->delete();
		echo 1;
	}
	
	
	//education
	
	function _load_education() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Education_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['education'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_education_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'educations',
    		'href' 					=> 'javascript:loadEducationAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'education_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Education</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','educations');
    	$this->var['btn_add_education'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_education_config);
	
		$this->var['title_education'] = "Education";
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/education/index.php',$this->var);
	}
	
	function _load_education_edit_form()
	{
		$education_id = $_POST['education_id'];
		$e = G_Employee_Education_Finder::findById($education_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/education/form/education_edit.php',$this->var);
	}
	
	function _delete_education()
	{
		Utilities::ajaxRequest();
		$education_id = $_POST['education_id'];
		$e = G_Employee_Education_Finder::findById($education_id);
		$e->delete();
		echo 1;
	}
	
	// end of education
	
	//skills
	function _load_skills() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Skills_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['skills'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		$this->var['g_skills']    = G_Settings_Skills_Finder::findByCompanyStructureId($this->company_structure_id);
 		$this->var['title_skills'] = "Skills";

 		$btn_add_skill_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'skills',
    		'href' 					=> 'javascript:loadSkillAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'skill_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Skill</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','skills');
    	$this->var['btn_add_skill'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_skill_config);

		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/skill/index.php',$this->var);
	}
	
	function _load_skill_edit_form()
	{
		$skill_id = $_POST['skill_id'];
		$e = G_Employee_Skills_Finder::findById($skill_id);
		$this->var['g_skills']    = G_Settings_Skills_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/skill/form/skill_edit.php',$this->var);
	}
	
	function _delete_skill()
	{
		Utilities::ajaxRequest();
		$skill_id = $_POST['skill_id'];
		$e = G_Employee_Skills_Finder::findById($skill_id);
		$e->delete();
		echo 1;
	}
	//end of skills
	
	
	//language
	function _load_language() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Language_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['languages'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		$this->var['g_language']  = G_Settings_Language_Finder::findAllByCompanyStructureId($this->company_structure_id);
		$this->var['title_language'] = "Language";

		$btn_add_language_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'language',
    		'href' 					=> 'javascript:loadLanguageAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'language_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Language</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','language');
    	$this->var['btn_add_language'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_language_config);

		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/language/index.php',$this->var);
	}
	
	function _load_language_edit_form()
	{
		$language_id = $_POST['language_id'];
		$e = G_Employee_Language_Finder::findById($language_id);
		$this->var['g_language']  = G_Settings_Language_Finder::findAllByCompanyStructureId($this->company_structure_id);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/language/form/language_edit.php',$this->var);
	}
	
	function _delete_language()
	{
		Utilities::ajaxRequest();
		$language_id = $_POST['language_id'];
		$e = G_Employee_Language_Finder::findById($language_id);
		$e->delete();
		echo 1;
	}
	//end of language
	
	//license
	function _load_license() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_License_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['licenses'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_license_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'license',
    		'href' 					=> 'javascript:loadLicenseAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'license_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add License</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','license');
    	$this->var['btn_add_license'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_license_config);
	
		$this->var['title_license'] = "License";
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/license/index.php',$this->var);
	}
	
	function _load_license_edit_form()
	{
		$license_id = $_POST['license_id'];
		$e = G_Employee_License_Finder::findById($license_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/license/form/license_edit.php',$this->var);
	}
	
	function _delete_license()
	{
		Utilities::ajaxRequest();
		$license_id = $_POST['license_id'];
		$e = G_Employee_License_Finder::findById($license_id);
		$e->delete();
		echo 1;
	}
	//end of license
	
	
	
	// supervisor
	
	function _load_supervisor() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];
		$subordinate = G_Employee_Supervisor_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$supervisor = G_Employee_Supervisor_Finder::findBySupervisorId(Utilities::decrypt($employee_id));
		
		$this->var['subordinate'] = $subordinate;
		$this->var['supervisor'] = $supervisor;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_supervisor_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'supervisor',
    		'href' 					=> 'javascript:loadSupervisorAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'supervisor_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Supervisor / Subordinates</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','supervisor');
    	$this->var['btn_add_supervisor'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_supervisor_config);
	
		$this->var['title_supervisor'] = "Supervisor / Subordinates";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/supervisor/index.php',$this->var);
	}
	
	function _load_supervisor_edit_form()
	{
		$id = $_POST['id'];
		$e = G_Employee_Supervisor_Finder::findById($id);

		$s = G_Employee_Finder::findById($e->supervisor_id);
		
		$this->var['details'] = $e;
		$this->var['subordinate'] = $s;	
		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/supervisor/form/supervisor_edit.php',$this->var);
	}
	
	function _load_subordinates_edit_form()
	{
		$id = $_POST['id'];
		$e = G_Employee_Supervisor_Finder::findById($id);

		$s = G_Employee_Finder::findById($e->employee_id);
		//echo "<pre>";
		//print_r($s);
		$this->var['details'] = $s;
		$this->var['supervisor'] = $e;		
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/supervisor/form/subordinates_edit.php',$this->var);
	}

	function _delete_employee_benefit()
	{
		$return = array();
		if($_POST['eid']){
			$id = Utilities::decrypt($_POST['eid']); 
			$b = G_Employee_Benefits_Main_Finder::findById($id);
			if( $b ){				
				$return = $b->deleteEnrollee();
				$return['eid'] = Utilities::encrypt($b->getEmployeeDepartmentId());
			}else{
				$return['message']    = "Record not found";
				$return['is_success'] = false;
			}
		}		
		echo json_encode($return);
	}

	function _add_benefit_to_employee()
	{
		Utilities::verifyFormToken($_POST['token']);	

		$data = $_POST;	
		$json = array();
		
		if( !empty($data) ){			
			$e = G_Employee_Finder::findById(Utilities::decrypt($data['eid']));			
			if( $e ){				
				$company_structure_id = $this->company_structure_id;
				$benefits             = $data['benefits'];
				$json = $e->enrollToBenefits($benefits, $company_structure_id);
			}else{
				$json['is_success'] = false;
				$json['message']    = "Cannot save record";
			}		 

		}else{
			$json['is_success'] = false;
			$json['message']    = "Cannot save record";
		}

		$json['eid']   = $data['eid'];
		$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
		
	}
	
	function _delete_supervisor()
	{
		
		Utilities::ajaxRequest();
		$id = $_POST['id'];
		$e = G_Employee_Supervisor_Finder::findById($id);
		$e->delete();
		echo 1;
	}
	
	function _delete_subordinates()
	{
		Utilities::ajaxRequest();
		$id = $_POST['id'];
		$e = G_Employee_Supervisor_Finder::findById($id);
		$e->delete();
		echo 1;
	}
	
	// end of supervisor
	
	//leave available
	
	function _load_leave() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id  =  $_GET['employee_id'];
		$covered_year = date("Y"); 
		$availables = G_Employee_Leave_Available_Finder::findByEmployeeIdNew(Utilities::decrypt($employee_id), $covered_year);
		$request = G_Employee_Leave_Request_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$gcs = G_Company_Structure_Finder::findById($this->company_structure_id);
		$leaves = G_Leave_Finder::findByCompanyStructureId($gcs);

		$this->var['leaves'] = $leaves;
		$this->var['request'] = $request;
		$this->var['availables'] = $availables;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		
		$btn_add_leave_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employees_leave',
    		'href' 					=> 'javascript:loadLeaveAvailableAddForm("' . $employee_id . '");',
    		'onclick' 				=> '',
    		'id' 					=> 'leave_available_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Leave</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','employees_leave');
    	$this->var['btn_add_leave'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_leave_config);
	
	
		$this->var['title_leave_available'] = "Leave Available";
		$this->var['title_request'] = "Leave Request";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/leave/index.php',$this->var);
	}
	
	function _load_deductions() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  $_GET['employee_id'];		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;	
		
		$this->var['title_request'] = "Add Deduction";
		$this->var['title_deduction_available'] = "Loan / Deduction History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/loan/index.php',$this->var);
	}

	function _load_add_leave_available_form() 
	{
		$eid = $_GET['eid'];
		if( !empty($eid) ){
			
			$id = Utilities::decrypt($eid);
			$la = new G_Employee_Leave_Available();
			$la->setEmployeeId($id);
			$data = $la->employeeLeaveTypeAvailableByEmployeeId();

			$this->var['employee_id'] = $eid;
			$this->var['leaves']      = $data;
			$this->view->noTemplate();
			$this->view->render('employee/profile/employment_information/leave/form/leave_available_add.php',$this->var);
		}else{
			echo "<div class=\"label label-warning\" style=\"margin-top:4px;width:auto;padding-right:9px;\"><i class=\"icon-remove icon-white\"></i> Employee record does not exists!</div>";
		}
	}
	
	function ajax_edit_loan() 
	{
		sleep(1);
		$this->var['gel']			 = G_Employee_Loan_Finder::findById(Utilities::decrypt($_POST['e_id']));
		$this->var['loan_type']      = G_Loan_Type_Finder::findAllIsNotArchive();
		$this->var['deduction_type'] = G_Loan_Deduction_Type_Finder::findAllIsNotArchive();
		$this->var['e']			     = $e;	
		$this->var['token']		     = Utilities::createFormToken();		
		$this->var['page_title']     = 'Edit Loan';		
		$this->view->render('employee/profile/employment_information/loan/form/edit_loan.php',$this->var);
	}
	
	function _load_loan_list_dt() 
	{		
		$this->var['eid'] = $_POST['eid'];
		$this->view->render('employee/profile/employment_information/loan/_loan_list_dt.php',$this->var);
	}
	
	function _load_server_loan_list_dt() 
	{		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LOAN);		
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LOAN . ".employee_id = e.id LEFT JOIN " . G_LOAN_TYPE . " gl ON " . G_EMPLOYEE_LOAN . ".type_of_loan_id = gl.id LEFT JOIN " . G_LOAN_DEDUCTION_TYPE . " gld ON " . G_EMPLOYEE_LOAN .".type_of_deduction_id = gld.id");
		$dt->setCondition(G_EMPLOYEE_LOAN .'.is_archive = "' . G_Employee_Loan::NO . '" AND ' . G_EMPLOYEE_LOAN. '.company_structure_id=' . $this->company_structure_id . " AND employee_id=" . Utilities::decrypt($_GET['eid']));
		$dt->setColumns('loan_type,balance,loan_amount,deduction_type,no_of_installment');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLoan(\'e_id\');\"></a></li><li><a title=\"View Details\" id=\"edit\" class=\"ui-icon ui-icon-zoomin g_icon\" href=\"' . url('loan/details?hid=id') . '\"></a></li><li><a title=\"Download\" id=\"edit\" class=\"ui-icon ui-icon-print g_icon\" href=\"' . url('reports/download_loan?hid=id') . '\"></a></li></ul></div>'));	
		echo $dt->constructDataTable();
	}
	
	function _load_leave_available_edit_form()
	{
		$leave_available_id = $_POST['leave_available_id'];
		$e = G_Employee_Leave_Available_Finder::findById($leave_available_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/leave/form/leave_available_edit.php',$this->var);
	}
	
	function _delete_leave_available()
	{
		Utilities::ajaxRequest();
		$leave_available_id = $_POST['leave_available_id'];
		$e = G_Employee_Leave_Available_Finder::findById($leave_available_id);
		$e->delete();
		echo 1;
	}	
	
	
	function _load_leave_request_edit_form()
	{
		$leave_request_id = $_POST['leave_request_id'];
		$e = G_Employee_Leave_Request_Finder::findById($leave_request_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/leave/form/leave_request_edit.php',$this->var);
	}
	
	function _delete_leave_request()
	{
		Utilities::ajaxRequest();
		$leave_request_id = $_POST['leave_request_id'];
		$e = G_Employee_Leave_Request_Finder::findById($leave_request_id);
		$e->delete();
		echo 1;
	}	
	
	// end of leave available
	
	function _load_work_schedule() 
	{
		Utilities::ajaxRequest();
		$this->var['employee_id'] = $employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		//$this->var['schedule'] = $schedule = G_Schedule_Finder::findByEmployeeAndDate($e,date("Y-m-d"));
		
		$schedules = G_Schedule_Helper::getCurrentEmployeeSchedule($e);
		$this->var['schedule'] = G_Schedule_Helper::showSchedules($schedules);
		
		$specifics = G_Schedule_Specific_Helper::getEmployeeLastMonthUntilNextMonthSchedules($e);
		$this->var['specific'] = G_Schedule_Specific_Helper::showSchedules($specifics);		
		
		$this->var['title_work_schedule'] = "Work Schedule";
		$this->view->noTemplate();
		$this->view->render('employee/profile/schedule/schedule/index.php',$this->var);
	}
	
	function _load_attachment()
	{
		Utilities::ajaxRequest();

		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Attachment_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['attachment'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_attachment_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'attachment',
    		'href' 					=> 'javascript:loadAttachmentAddForm();',
    		'onclick' 				=> '',
    		'id' 					=> 'attachment_add_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Attachment</b>'
    		); 
    	
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','attachment');
    	$this->var['btn_add_attachment'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_attachment_config);
	
		$this->var['title_attachment'] = "Attachment";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/attachment/index.php',$this->var);
	}
	
	
	
	
	function _load_attachment_edit_form()
	{
		$attachment_id = $_POST['attachment_id'];
		$e = G_Employee_Attachment_Finder::findById($attachment_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/attachment/form/attachment_edit.php',$this->var);
	}
	
	function _delete_attachment()
	{
		Utilities::ajaxRequest();
		$attachment_id = $_POST['attachment_id'];
		$e = G_Employee_Attachment_Finder::findById($attachment_id);
		$e->delete();
		echo 1;
	}
	
	
	function load_summary_photo()
	{
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id); //findById($employee_id);
		if( $e ){
			$file = PHOTO_FOLDER.$e->getPhoto();
		}else{
			$e = new G_Employee();
			$file = $e->getValidEmployeeImage();
		}
		
		if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;
			//echo "exist";
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
			//echo "not exist";
		}	
		
	}
	
	function _update_attachment()
	{
		$prefix = 'employee_';
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$em = G_Employee_Helper::findByEmployeeId($employee_id);


		//added alex
		$file2 = $_FILES['filename']['name'];
		$extension_name = ".".pathinfo($file2, PATHINFO_EXTENSION);
		//var_dump($ext);exit();


		$hash = $em['hash'];
		//$len = strlen($_FILES['filename']['name']);
		//$pos = strpos($_FILES['filename']['name'],'.');
		//$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
		$handle = new upload($_FILES['filename']);
		$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;

	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;

	       $handle->process($path);
	       if ($handle->processed) {
	         
				$image =  $filename . strtolower($extension_name); 
				
				//print_r($_FILES);
				$row = $_POST;
		
				$gcb = new G_Employee_Attachment($row['id']);
				$gcb->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$gcb->setFilename($image);
				$gcb->setDescription($row['description']);
				$gcb->setSize($_FILES['filename']['size']);
				$gcb->setType($_FILES['filename']['type']);
				$gcb->setDateAttached($row['date_attached']);
				//$gcb->setAddedBy($row['added_by']);
				$gcb->setAddedBy($em['employee_name']);
				$gcb->setScreen($row['screen']);
				$gcb->save();

	           $handle->clean();
			   $return = true;
			 
	       } else {	   		   		
			  $return =  $handle->error;
	       }
	   }else {
			//$return =  $handle->error;   
			$row = $_POST;
			$gcb = G_Employee_Attachment_Finder::findById($row['id']);
				//$gcb->setEmployeeId($gcb->getEmployeeId());				
				$gcb->setDescription($row['description']);
				//$gcb->setSize($gcb->getSize());
				//$gcb->setType($gcb->getType());
				$gcb->setDateAttached($row['date_attached']);
				$gcb->setAddedBy($em['employee_name']);
				//$gcb->setAddedBy($gcb->getAddedBy());
				//$gcb->setScreen($gcb->getScreen());
				$gcb->save();   
				$return = true;    
	   }	
			
		echo $return;
			//echo 1;
	}
	
	
	function _update_personal_details()
	{
		//print_r($_POST);
		$es = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		$hash = Utilities::createHash(Utilities::decrypt($_POST['employee_id']));

		$week_working_days = $_POST['week_working_days'];
		$sv 			   = new G_Sprint_Variables();
		$num_days 		   = $sv->getWorkingDaysDescriptionNumberOfDays($week_working_days);



		//adding project site in 201 
		  $projectId = $_POST['project_site'];
        Model::open('G_Employee_Project_Site_Status_Model');
        $a = new G_Employee_Project_Site_Status_Model();
        $a->update_employee_project_site_history($es->id,$projectId);


			$p = new G_Employee_Project_Site_History();
			$p->setEmployeeId($es->id);
			$p->setStartDate(date('Y-m-d'));
			$p->setEndDate('');
			$p->setProjectId($projectId);
			$p->removeCurrentProject();
			$p->setProject();



		/*$e = new G_Employee;
		$e->setId(Utilities::decrypt($_POST['employee_id']));
		$e->setHash($hash);
		$e->setPhoto($_POST['photo']);
		$e->setEmployeeCode($_POST['employee_code']);
		$e->setEmployeeDeviceId($_POST['employee_device_id']);
		$e->setFirstname(ucfirst($_POST['firstname']));
		$e->setLastname(ucfirst($_POST['lastname']));
		$e->setExtensionName(ucfirst($_POST['extension_name']));
		$e->setNickname(ucfirst($_POST['nickname']));
		$e->setMiddlename(ucfirst($_POST['middlename']));
		$e->setGender($_POST['gender']);
		$e->setMaritalStatus($_POST['marital_status']);
		$e->setSssNumber($_POST['sss_number']);
		$e->setTinNumber($_POST['tin_number']);
		$e->setPagibigNumber($_POST['pagibig_number']);
		$e->setPhilhealthNumber($_POST['philhealth_number']);
		$e->setBirthdate($_POST['birthdate']);
		$e->setSalutation($_POST['salutation']);
		$e->setNationality($_POST['nationality']);
		$e->setWeekWorkingDays($week_working_days);
		$e->setYearWorkingDays($num_days);
		$e->setIsConfidential($_POST['is_confidential']);
		//$e->setNumberDependent($_POST['number_dependent']);
		$e->setIsArchive(G_Employee::NO);
		$save_details = $e->save();	*/

		if( $es ){
			$es->setId(Utilities::decrypt($_POST['employee_id']));
			$es->setHash($hash);
			$es->setPhoto($_POST['photo']);
			$es->setEmployeeCode($_POST['employee_code']);
			$es->setEmployeeDeviceId($_POST['employee_device_id']);
			$es->setFirstname(ucfirst($_POST['firstname']));
			$es->setLastname(ucfirst($_POST['lastname']));
			$es->setExtensionName(ucfirst($_POST['extension_name']));
			$es->setNickname(ucfirst($_POST['nickname']));
			$es->setMiddlename(ucfirst($_POST['middlename']));
			$es->setGender($_POST['gender']);
			$es->setMaritalStatus($_POST['marital_status']);
			$es->setSssNumber($_POST['sss_number']);
			$es->setTinNumber($_POST['tin_number']);
			$es->setPagibigNumber($_POST['pagibig_number']);
			$es->setPhilhealthNumber($_POST['philhealth_number']);
			$es->setBirthdate($_POST['birthdate']);
			$es->setSalutation($_POST['salutation']);
			$es->setNationality($_POST['nationality']);
			$es->setWeekWorkingDays($week_working_days);
			$es->setYearWorkingDays($num_days);
			$es->setIsConfidential($_POST['is_confidential']);

			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($es->getId());
			
			if($shr_emp['sss_number'] != $_POST['sss_number']){
				$details_name = 'SSS Number';

				if($shr_emp['sss_number'] == ''){
					$details_from = 0;
				}
				else{
					$details_from = $shr_emp['sss_number'];
				}
				
				$details_to = $_POST['sss_number'];
			}
			elseif($shr_emp['tin_number'] != $_POST['tin_number']){
				$details_name = 'Tin Number';

				if($shr_emp['tin_number'] == ''){
					$details_from = 0;
				}
				else{
					$details_from = $shr_emp['tin_number'];
				}
				
				$details_to = $_POST['tin_number'];
			}
			elseif($shr_emp['pagibig_number'] != $_POST['pagibig_number']){
				$details_name = 'Pag-ibig Number';

				if($shr_emp['pagibig_number'] == ''){
					$details_from = 0;
				}
				else{
					$details_from = $shr_emp['pagibig_number'];
				}

				$details_to = $_POST['pagibig_number'];
			}
			elseif($shr_emp['philhealth_number'] != $_POST['philhealth_number']){
				$details_name = 'Philhealth Number';

				if($shr_emp['philhealth_number'] == ''){
					$details_from = 0;
				}
				else{
					$details_from = $shr_emp['philhealth_number'];
				}

				$details_to = $_POST['philhealth_number'];
			}

			//$e->setNumberDependent($_POST['number_dependent']);
			$es->setIsArchive(G_Employee::NO);
			$es->setCostCenter($_POST['cost_center']);
			//project site
			$es->setProjectSiteId($projectId);
			$save_details = $es->save();	
			
			//Other Details
			$other_details = $_POST['other_details'];		
			foreach( $other_details as $key => $details ){			
				$data_other_details[$es->getId()][] = $details;		
			}						
			$es->createDynamicField($data_other_details);
			//var_dump($data_other_details);
			//echo $details['other_details_label'];
			foreach ($data_other_details as $value) {
				foreach ($value as $key => $values) {
					//echo $values['other_details_label'].'<br>';
				}
			}
			
			//Employee Tags		
			$t = G_Employee_Tags_Finder::findByEmployeeId($es->getId());
			if($t){
				$t->setTags($_POST['tags']);
			}else{
				$t = new G_Employee_Tags();
				$t->setCompanyStructureId($this->company_structure_id);			
				$t->setTags($_POST['tags']);
				$t->setIsArchive(G_Employee_Tags::NO);					
				$t->setDateCreated($this->c_date);					
			}
			$t->save($es);
			
			//if came from settings or employee record already
			foreach($_POST as $key=>$value) {
				$k = explode("_", $key);

				if($k[0]=='s') {
					
					$settings_employee_field_id = $k[1];
					$s = G_Settings_Employee_Field_Finder::findById($settings_employee_field_id);					
					$d = new G_Employee_Dynamic_Field;
					$d->setSettingsEmployeeFieldId($settings_employee_field_id);
					$d->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
					$d->setTitle($s->title);
					$d->setValue($value);
					$d->setScreen('#personal_details');
					$d->save();
					
				}
				
				if($k[0]=='e') {
					$employee_dynamic_field_id= $k[1];
					$ed = G_Employee_Dynamic_Field_Finder::findById($employee_dynamic_field_id);
					$d = new G_Employee_Dynamic_Field;
					$d->setId($employee_dynamic_field_id);
					$d->setSettingsEmployeeFieldId($ed->settings_employee_field_id);
					$d->setEmployeeId($ed->employee_id);
					$d->setTitle($ed->title);
					$d->setValue($value);
					$d->setScreen('#personal_details');
					$d->save();
				}
			
			}	
		}
		
		
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_edit'] .",id=". $es->getId(),$this->username);
		echo 1;
		//General Reports / Shr Audit Trail
		$emp_name = $_POST['firstname'].' '.$_POST['lastname'];
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, $details_name.' Details of ', $emp_name, $details_from, $details_to, 1, $shr_emp['position'], $shr_emp['department']);
			
	}
	
	function _update_contact_details()
	{
		$row = $_POST;	
		$e = new G_Employee_Contact_Details;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$e->setAddress($row['address']);
		$e->setCity($row['city']);
		$e->setProvince($row['province']);
		$e->setZipCode($row['zip_code']);
		$e->setCountry($row['country']);
		$e->setHomeTelephone($row['home_telephone']);
		$e->setMobile($row['mobile']);
		$e->setWorkTelephone($row['work_telephone']);
		$e->setWorkEmail($row['work_email']);
		$e->setOtherEmail($row['other_email']);	
		$e->save();	
		
		//if came from settings or employee record already
		foreach($_POST as $key=>$value) {
			$k = explode("_", $key);

			if($k[0]=='s') {
				
				$settings_employee_field_id = $k[1];
				$s = G_Settings_Employee_Field_Finder::findById($settings_employee_field_id);
				
				$d = new G_Employee_Dynamic_Field;
				$d->setSettingsEmployeeFieldId($settings_employee_field_id);
				$d->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
				$d->setTitle($s->title);
				$d->setValue($value);
				$d->setScreen('#contact_details');
				$d->save();
			}
			
			if($k[0]=='e') {
				$employee_dynamic_field_id= $k[1];
				$ed = G_Employee_Dynamic_Field_Finder::findById($employee_dynamic_field_id);
				$d = new G_Employee_Dynamic_Field;
				$d->setId($employee_dynamic_field_id);
				$d->setSettingsEmployeeFieldId($ed->settings_employee_field_id);
				$d->setEmployeeId($ed->employee_id);
				$d->setTitle($ed->title);
				$d->setValue($value);
				$d->setScreen('#contact_details');
				$d->save();
			}
		
		}
		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_edit_contact_detail'] .",employee id=". $emp_id,$this->username);
		echo 1;

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' Contact Details of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);
	}
	
	function _update_emergency_contacts()
	{

		$row = $_POST;	
		$e = new G_Employee_Emergency_Contact;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setPerson($row['person']);
		$e->setRelationship($row['relationship']);
		$e->setHomeTelephone($row['home_telephone']);
		$e->setMobile($row['mobile']);
		$e->setWorkTelephone($row['work_telephone']);
		$e->setAddress($row['address']);
		$saved = $e->save();
		
		$emp_id = Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_edit_emergency_contact'] .",employee id=". $emp_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_emergency_contact'] .",employee id=". $saved,$this->username);
		}

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($row['employee_id']));
		if(!empty($row['id'])){
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, 'Emergency Contacts of ', $emp_name, 'New', $row['person'], 1, $shr_emp['position'], $shr_emp['department']);	
		}else{
			//General Reports / Shr Audit Trail
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' Emergency Contacts to ', $emp_name, 'None', $row['person'], 1, $shr_emp['position'], $shr_emp['department']);
		}
		
		echo 1;


	}
	
	function _update_dependent()
	{
		$row = $_POST;	
		$e = new G_Employee_Dependent;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setName($row['name']);
		$e->setRelationship($row['relationship']);
		$e->setBirthdate($row['birthdate']);
		$saved = $e->save();
		$e->updateEmployeeDataTotalDependents();
		
		$emp_id	= Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_edit_dependents'] .",employee id=". $emp_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_dependents'] .",employee id=". $emp_id,$this->username);
		}	

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($row['employee_id']));
		if(!empty($row['id'])){
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, 'Dependents of ', $emp_name, 'None', $row['name'], 1, $shr_emp['position'], $shr_emp['department']);	
		}else{
			//General Reports / Shr Audit Trail
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' Dependents to ', $emp_name, 'none', $row['name'], 1, $shr_emp['position'], $shr_emp['department']);
		}
		
		echo 1;	
	}
	
	function _update_duration()
	{
		$row = $_POST;
	
		$prefix = 'contract';
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$em = G_Employee_Helper::findByEmployeeId($employee_id);
		
		$hash = $em['hash'];
		$len = strlen($_FILES['filename']['name']);
		$pos = strpos($_FILES['filename']['name'],'.');
		$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
		
		$handle = new upload($_FILES['filename']);
		$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;
		$file_docs =  $filename . strtolower($extension_name);
		
		if($_FILES['filename']['size'] > 0 && $_FILES['filename']['error'] == 0) {
			//$fname = $file_docs;			
			$fname = $_FILES['filename']['name'];
			//echo $fname . ' -  ' . $_FILES['filename']['name'];
			//exit;
		} else {
			$fname = $row['attachment'];
		}

	    //Tools::showArray($_FILES);
	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;
		
		   $handle->process($path);
		   if ($handle->processed) {
			    $image =  $filename . strtolower($extension_name); 
				
				$e = new G_Employee_Extend_Contract;
				$e->setId($row['id']);
				$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$e->setStartDate($row['start_date']);
				$e->setEndDate($row['end_date']);
				$e->setAttachment($image);
				$e->setRemarks($row['remarks']);
				$e->setIsDone($row['is_done']);
				$e->save(); 								
			   $handle->clean();
			   $return = true;			 
		   } else {	    		      		   		
				//$e = new G_Employee_Extend_Contract;
				$e = G_Employee_Extend_Contract_Finder::findById($row['id']);								
				$e->setStartDate($row['start_date']);
				$e->setEndDate($row['end_date']);				
				$e->setRemarks($row['remarks']);
				$e->setIsDone($row['is_done']);
				$e->save();
				
			  $return =  $handle->error;

		   }
	   } else {
			
			$e = new G_Employee_Extend_Contract;
			$e->setId($row['id']);
			$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
			$e->setStartDate($row['start_date']);
			$e->setEndDate($row['end_date']);
			$e->setAttachment($fname);
			$e->setRemarks($row['remarks']);
			$e->setIsDone($row['is_done']);
			$e->save();
			$return =  $handle->error;   
	   }
	   
	   if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_contract_update'] .",employee id=". $employee_id,$this->username);
	   }else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_contract_add'] .",employee id=". $employee_id,$this->username);
	   }
	    
		echo 1;	
	}
	
	function _update_direct_deposit()
	{
		$row = $_POST;	
		$e = new G_Employee_Direct_Deposit;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setBankName($row['bank_name']);
		$e->setAccount($row['account']);
		$e->setAccountType($row['account_type']);
		$e->save();
		
		$emp_id = Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_edit_bank_account'] .",employee id=". $emp_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_bank_account'] .",employee id=". $emp_id,$this->username);	
		}

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($row['employee_id']));
		if(!empty($row['id'])){
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, 'Employee Bank Details of ', $emp_name, 'None', $row['bank_name'], 1, $shr_emp['position'], $shr_emp['department']);	
		}else{
			//General Reports / Shr Audit Trail
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' Employee Bank Account to ', $emp_name, 'None', $row['bank_name'], 1, $shr_emp['position'], $shr_emp['department']);
		}
		
		echo 1;	
	}
	
	function _update_employment_status_depre()
	{
		
		$employee 	  	     = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$obj_employee        = G_Employee_Finder::findByIdBothArchiveAndNot(Utilities::decrypt($_POST['employee_id']));
		
		$company_subdivision = G_Company_Structure_Finder::findById($_POST['department_id']);
		$company_branch      = G_Company_Branch_Finder::findById($_POST['branch_id']);
				
		//Update Employee Status		
		$obj_employee->setEmployeeStatusId($_POST['employee_status_id']);
		$obj_employee->updateEmployeeStatus();		
		//		

		//Update Employee section id
		$emp = G_Employee_Finder::findById($employee['id']);

		if($emp) {
			$emp->updateSectionId($_POST['section_id']);
			$emp->save();
		}
		
		//Employee Status 
		$data['memo'] = $_POST['memo'];
			if($_POST['employee_status_id'] == G_Settings_Employee_Status::TERMINATED){
				//Terminated
				$data['terminated_date'] 	    = $date = $_POST['terminated_date'];
				$data_attachment['description'] = "Termination : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
				
				//$obj_employee->activeToTerminated($data);
                $obj_employee->terminate($date);
				$obj_employee->employeeAttachFile($_FILES,$data_attachment);
				
			}elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::RESIGNED){
				//Resigned
				$data['resigned_date'] 			= $date = $_POST['resignation_date'];
				$data_attachment['description'] = "Resignation : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
				
				//$obj_employee->activeToResigned($data);
                $obj_employee->resign($date);
				$obj_employee->employeeAttachFile($_FILES,$data_attachment);
				
			}elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::ENDO){
				//Endo
				$data['endo_date'] 			    = $date = $_POST['endo_date'];
				$data_attachment['description'] = "ENDO : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
				
				//$obj_employee->activeToEndo($data);
                $obj_employee->endo($date);
				$obj_employee->employeeAttachFile($_FILES,$data_attachment);
			}
		//
		
		//$terminated_date = ($_POST['employment_status_id']=='0')? $_POST['terminated_date']: '';
		if($_POST['branch_id']!= $employee['branch_id']) {
			$employee_branch = G_Employee_Branch_History_Finder::findCurrentBranch($obj_employee);
            if ($employee_branch) {
                $employee_branch->setCompanyBranchId($company_branch->getId());
                $employee_branch->setBranchName($company_branch->getName());
                $employee_branch->save();
            }
		}
		
		if($_POST['department_id']!=$employee['department_id']) {
			$employee_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($obj_employee);
			
			if($employee_subdivision && $company_subdivision) {//has current subdivision
				
				$employee_subdivision->setCompanyStructureId($company_subdivision->getId());
				$employee_subdivision->setName($company_subdivision->getTitle());
				$employee_subdivision->save();	
			}else {//has no current subdivision
				$total_subdivision_history = G_Employee_Subdivision_History_Helper::countTotalHistoryByEmployeeId($obj_employee->getId());
				
				if($total_subdivision_history>0) {// update the recent subdivision
					$recent_history = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($obj_employee->getId());
					if($recent_history && $company_subdivision) {
						$recent_history->setCompanyStructureId($company_subdivision->getId());
						$recent_history->setName($company_subdivision->getTitle());
						$recent_history->setEndDate($terminated_date);
						$recent_history->save();
					}
				}else {
					$dep = new G_Employee_Subdivision_History;
					$dep->setId($row['id']);
					$dep->setEmployeeId(Utilities::decrypt($row['employee_id']));
					$dep->setCompanyStructureId($company_subdivision->getId());
					$dep->setName($company_subdivision->getTitle());
					$dep->setStartDate(date("Y-m-d"));
					$dep->save();	
				}	
			}			
		}
		
		if(($_POST['job_id']!=$employee['job_id']) || ($_POST['employment_status_id']!=$employee['employment_status'])) {
			$employment_status   = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
			$s_employment_status = '';
			if( $employment_status ){
				$s_employment_status = $employment_status->getStatus();
			}
			
			$employment_status   = ($_POST['employment_status_id']=='0')? 'Terminated': $s_employment_status;
			$employee_position   = G_Employee_Job_History_Finder::findCurrentJob($obj_employee);
			$company_job 	     = G_Job_Finder::findById($_POST['job_id']);
		
			if($employee_position) {
				$employee_position->setJobId($company_job->getId());
				$employee_position->setName($company_job->getTitle());
				$employee_position->setEmploymentStatus($employment_status);
				$employee_position->setEndDate($terminated_date);
				$employee_position->save();	
			}else {
				$employee_terminated_status = G_Employee_Job_History_Finder::findByTerminatedJob($obj_employee);
				if($employee_terminated_status) {
					$employee_terminated_status->setJobId($company_job->id);
					$employee_terminated_status->setName($company_job->getTitle());
					$employee_terminated_status->setEmploymentStatus($employment_status);
					$employee_terminated_status->setStartDate($employee['hired_date']);
					$employee_terminated_status->setEndDate($terminated_date);
					$employee_terminated_status->save();		
				}else {
					$employee_position = new G_Employee_Job_History;
					$employee_position->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
					$employee_position->setJobId($company_job->id);
					$employee_position->setName($company_job->getTitle());
					$employee_position->setEmploymentStatus($employment_status);
					$employee_position->setStartDate($employee['hired_date']);
					$employee_position->setEndDate($terminated_date);
					$employee_position->save();		
				}			
			}	
		}

		$obj_employee->setEmploymentStatusId($_POST['employment_status_id']);
		$obj_employee->setHiredDate($_POST['hired_date']);
		$obj_employee->setEeoJobCategoryId($_POST['job_category_id']);
		//$obj_employee->setTerminatedDate($terminated_date);
		$obj_employee->save();
		
		if($_POST['employment_status_id']=='0') {
			//Add Memo
			/*$e = new G_Employee_Memo;
			$e->setId($row['id']);
			$e->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$e->setTitle('Terminated');
			$e->setMemo($_POST['memo']);
			$e->setDateCreated(date("Y-m-d"));
			$employee = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['hr']['employee_id']));
			$e->setCreatedBy($employee->lastname. ' ' . $employee->firstname);
			$e->save();	*/
			
			//terminate the basic salary
			/*$current_salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($obj_employee);
			if($current_salary) {
				$current_salary->setEndDate($terminated_date);
				$current_salary->save();	
			}*/
			//terminate the subdivision
			/*$current_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($obj_employee);
			if($current_subdivision) {
				$current_subdivision->setEndDate($terminated_date);
				$current_subdivision->save();	
			}	*/
		}else {
			// if the status is not terminated it will update the recent salary into present
			$total_salary_history = G_Employee_Basic_Salary_History_Helper::countTotalHistoryByEmployeeId($obj_employee->getId());
				
			if($total_salary_history>0) {// update the recent salary
				$recent_history = G_Employee_Basic_Salary_History_Finder::findRecentHistoryByEmployeeId($obj_employee->getId());					
				if($recent_history) {
					$recent_history->setEndDate($terminated_date);
					$recent_history->save();
				}
			}
		}
		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_update'] .",employee id=". $emp_id,$this->username);
		echo 1;
	}

	function _update_employment_status()
	{
		$employee 	  	     = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$obj_employee        = G_Employee_Finder::findByIdBothArchiveAndNot(Utilities::decrypt($_POST['employee_id']));
		
		$company_subdivision = G_Company_Structure_Finder::findById($_POST['department_id']);
		$company_branch      = G_Company_Branch_Finder::findById($_POST['branch_id']);

		//Updates Employee Status History - Start

		$current_emp_status = $_POST['current_employee_status_id'];
		$emp_status         = $_POST['employee_status_id'];
		$employee_id        = Utilities::decrypt($_POST['employee_id']);

		if($current_emp_status == $emp_status) {
			
			$current_emp_status_history = G_Employee_Status_History_Finder::findCurrentEmployeeStatus($employee_id);
			
			if(!$current_emp_status_history) {

				if($current_emp_status == G_Settings_Employee_Status::ACTIVE) {
					$status_text = "Active";
					$start_date  = !empty($_POST['active_date']) ? $_POST['active_date'] : $_POST['hired_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::RESIGNED) {
					$status_text = "Resigned";
					$start_date  = $_POST['resignation_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::TERMINATED) {
					$status_text = "Terminated";
					$start_date  = $_POST['terminated_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::INACTIVE) {
					$status_text = "Inactive";	
					$start_date  = $_POST['inactive_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::ENDO) {
					$status_text = "Endo";
					$start_date  = $_POST['endo_date'];
				}

				elseif($current_emp_status == G_Settings_Employee_Status::AWOL) {
					$status_text = "AWOL";
					$start_date  = $_POST['awol_date'];
				}


		        $esh = new G_Employee_Status_History();
		        $esh->setEmployeeId($employee_id);
		        $esh->setEmployeeStatusId($current_emp_status);
		        $esh->setStatus($status_text);
		        $esh->setStartDate($start_date);
		        $return = $esh->save(); 

		       	//General Reports / Shr Audit Trail
		        $shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		        $emp_cur_status = $shr_emp['employee_status_id'];

		        if($emp_cur_status == G_Settings_Employee_Status::ACTIVE){
		        	$cur_status_text = 'Active';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::RESIGNED){
		        	$cur_status_text = 'Resigned';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::TERMINATED){
		        	$cur_status_text = 'Terminated';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::INACTIVE){
		        	$cur_status_text = 'Inactive';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::ENDO){
		        	$cur_status_text = 'Endo';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::AWOL){
		        	$cur_status_text = 'AWOL';
		        }

				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' Employment Status of ', $emp_name, $cur_status_text, $status_text, 1, $shr_emp['position'], $shr_emp['department']);	

			} else {

				if($current_emp_status == G_Settings_Employee_Status::ACTIVE) {
					//$start_date  = $_POST['hired_date'];
					$start_date  = !empty($_POST['active_date']) ? $_POST['active_date'] : $_POST['hired_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::RESIGNED) {
					$start_date  = $_POST['resignation_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::TERMINATED) {
					$start_date  = $_POST['terminated_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::INACTIVE) {
					$start_date  = $_POST['inactive_date'];
				}elseif($current_emp_status == G_Settings_Employee_Status::ENDO) {
					$start_date  = $_POST['endo_date'];
				}
				elseif($current_emp_status == G_Settings_Employee_Status::AWOL) {
					$start_date  = $_POST['awol_date'];
				}

				$end_date = Tools::getCurrentDateTime('Y-m-d','Asia/Manila');
				$current_emp_status_history->setStartDate($start_date);
				$current_emp_status_history->save();				
			

				 //General Reports / Shr Audit Trail
		        $shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		        $emp_cur_status = $shr_emp['employee_status_id'];

		        if($emp_cur_status == G_Settings_Employee_Status::ACTIVE){
		        	$cur_status_text = 'Active';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::RESIGNED){
		        	$cur_status_text = 'Resigned';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::TERMINATED){
		        	$cur_status_text = 'Terminated';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::INACTIVE){
		        	$cur_status_text = 'Inactive';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::ENDO){
		        	$cur_status_text = 'Endo';
		        }
		        elseif($emp_cur_status == G_Settings_Employee_Status::AWOL){
		        	$cur_status_text = 'AWOL';
		        }

		        //echo 'Current Status = '.$cur_status_text.' New Status = '.$status_text;
		        
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' Employment Status of ', $emp_name, $cur_status_text, $cur_status_text, 1, $shr_emp['position'], $shr_emp['department']);

			}

		}

		if($current_emp_status != $emp_status) {
			//end the previous history - start
			$current_history = G_Employee_Status_History_Finder::findCurrentEmployeeStatusWithStatusId($employee_id, $current_emp_status);
			if($current_history) {
				$end_date = Tools::getCurrentDateTime('Y-m-d','Asia/Manila');
				$current_history->setEndDate($end_date);
				$current_history->save();
			}
			//end the previous history - end

			if($emp_status == G_Settings_Employee_Status::ACTIVE) {
				$status_text = "Active";
				$end_date = !empty($_POST['active_date']) ? $_POST['active_date'] : Tools::getCurrentDateTime('Y-m-d','Asia/Manila');
				$start_date  = !empty($_POST['active_date']) ? $_POST['active_date'] : $end_date; //$_POST['hired_date'];
			}elseif($emp_status == G_Settings_Employee_Status::RESIGNED) {
				$status_text = "Resigned";
				$start_date  = $_POST['resignation_date'];
			}elseif($emp_status == G_Settings_Employee_Status::TERMINATED) {
				$status_text = "Terminated";
				$start_date  = $_POST['terminated_date'];
			}elseif($emp_status == G_Settings_Employee_Status::INACTIVE) {
				$status_text = "Inactive";	
				$start_date  = $_POST['inactive_date'];
			}elseif($emp_status == G_Settings_Employee_Status::ENDO) {
				$status_text = "Endo";
				$start_date  = $_POST['endo_date'];
			}	
			elseif($emp_status == G_Settings_Employee_Status::AWOL) {
				$status_text = "AWOL";
				$start_date  = $_POST['awol_date'];
			}			

	        $esh_n = new G_Employee_Status_History();
	        $esh_n->setEmployeeId($employee_id);
	        $esh_n->setEmployeeStatusId($emp_status);
	        $esh_n->setStatus($status_text);
	        $esh_n->setStartDate($start_date);
	        $return = $esh_n->save();


	        //General Reports / Shr Audit Trail
	        $shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
	        $emp_cur_status = $shr_emp['employee_status_id'];
	        
	        if($emp_cur_status == G_Settings_Employee_Status::ACTIVE){
	        	$cur_status_text = 'Active';
	        }
	        elseif($emp_cur_status == G_Settings_Employee_Status::RESIGNED){
	        	$cur_status_text = 'Resigned';
	        }
	        elseif($emp_cur_status == G_Settings_Employee_Status::TERMINATED){
	        	$cur_status_text = 'Terminated';
	        }
	        elseif($emp_cur_status == G_Settings_Employee_Status::INACTIVE){
	        	$cur_status_text = 'Inactive';
	        }
	        elseif($emp_cur_status == G_Settings_Employee_Status::ENDO){
	        	$cur_status_text = 'Endo';
	        }
	        elseif($emp_cur_status == G_Settings_Employee_Status::AWOL){
	        	$cur_status_text = 'AWOL';
	        }

	        $emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' Employment Status of ', $emp_name, $cur_status_text, $status_text, 1, $shr_emp['position'], $shr_emp['department']);	

		}

		//echo '<pre>';
		//print_r($current_emp_status_history);
		//echo '</pre>';

		//Updates Employee Status History - End

		//exit;
				
		//Update Employee Status		
		$reset_endo_date = true;
		if( $_POST['employee_status_id'] == 1 ){
			$reset_endo_date = false;
		}
		$obj_employee->setEmployeeStatusId($_POST['employee_status_id']);		
		$obj_employee->updateEmployeeStatus();		
		//		

		//Update section
		$obj_employee->setSectionId($_POST['section_id']);		
		$obj_employee->updateSection($_POST['section_id']);

		$obj_employee->setEndoDate('0000-00-00');
		$obj_employee->setHiredDate($_POST['hired_date']);
		$obj_employee->save();
		
		//Employee Status Memo / Attachment
		$data['memo'] = $_POST['memo'];
		if($_POST['employee_status_id'] == G_Settings_Employee_Status::TERMINATED){
			//Terminated
			$data['terminated_date'] 	    = $date = $_POST['terminated_date'];
			$data_attachment['description'] = "Termination : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
			
			//$obj_employee->activeToTerminated($data);
            $obj_employee->terminate($date);
			$obj_employee->employeeAttachFile($_FILES,$data_attachment);
			
		}elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::RESIGNED){
			//Resigned
			$data['resigned_date'] 			= $date = $_POST['resignation_date'];
			$data_attachment['description'] = "Resignation : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
			
			//$obj_employee->activeToResigned($data);
            $obj_employee->resign($date);
			$obj_employee->employeeAttachFile($_FILES,$data_attachment);
			
		}elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::ENDO){
			//Endo
			$data['endo_date'] 			    = $date = $_POST['endo_date'];
			$data_attachment['description'] = "ENDO : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
			
			//$obj_employee->activeToEndo($data);
            $obj_employee->endo($date);
			$obj_employee->employeeAttachFile($_FILES,$data_attachment);
		}elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::INACTIVE){
			//Inactive
			$data['inactive_date'] 			= $date = $_POST['inactive_date'];
			$data_attachment['description'] = "INACTIVE : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
			
            $obj_employee->inactive($date);
			$obj_employee->employeeAttachFile($_FILES,$data_attachment);

        }

        elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::AWOL){
            //Inactive
            $data['inactive_date']             = $date = $_POST['awol_date'];
            $data_attachment['description'] = "INACTIVE : " . $obj_employee->getLastname() . ", " . $obj_employee->getFirstname();
            
            $obj_employee->inactive($date);
            $obj_employee->employeeAttachFile($_FILES,$data_attachment);
        }
        elseif($_POST['employee_status_id'] == G_Settings_Employee_Status::ACTIVE){

			 $obj_employee->resetActive('');
		}
		//

		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_update'] .",employee id=". $emp_id,$this->username);
		echo 1;

		
	}
	
	function _update_compensation() 
	{
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		if($employee) {
			$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);	
			if($salary) {
				$salary->setEmployeeId($employee->id);
				$salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$salary->setBasicSalary($_POST['basic_salary']);
				$salary->setType($_POST['type']);
				$salary->setPayPeriodId($_POST['pay_period_id']);
				//$salary->setStartDate(date('Y-m-d'));
				$salary->save();
				
			}else {
				$employee_salary = new G_Employee_Basic_Salary_History;	
				$employee_salary->setId($salary->id);
				$employee_salary->setEmployeeId($employee->id);
				$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$employee_salary->setType($_POST['type']);
				$employee_salary->setBasicSalary($_POST['basic_salary']);
				$employee_salary->setPayPeriodId($_POST['pay_period_id']);
				$employee_salary->setStartDate(date('Y-m-d'));
				$employee_salary->save();
			}
				
			//Update Employee Contribution
			$employee->addContribution($_POST['basic_salary']);
			
		}
		
		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_compensation_update'] .",employee id=". $emp_id,$this->username);
		

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	    $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, ' Employment Compensation Details of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

	    echo 1;	
		
	}
	
	function _insert_compensation_history_old() 
	{
		print_r($_POST);
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
	/*	if($employee) {
			$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);	
			if($salary) {
				$salary->setEmployeeId($employee->id);
				$salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$salary->setBasicSalary($_POST['basic_salary']);
				$salary->setType($_POST['type']);
				$salary->setPayPeriodId($_POST['pay_period_id']);
				$salary->setStartDate(date('Y-m-d'));
				$salary->save();
			}else {
				$employee_salary = new G_Employee_Basic_Salary_History;	
				$employee_salary->setId($salary->id);
				$employee_salary->setEmployeeId($employee->id);
				$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$employee_salary->setType($_POST['type']);
				$employee_salary->setBasicSalary($_POST['basic_salary']);
				$employee_salary->setPayPeriodId($_POST['pay_period_id']);
				$employee_salary->setStartDate(date('Y-m-d'));
				$employee_salary->save();
			}
		}*/
		echo 1;
	}
	
	function _insert_compensation_history() 
	{
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		if($employee) {
				if($_POST['present']){
					//Offset present salary
						$count = G_Employee_Basic_Salary_History_Helper::countTotalHistoryByEmployeeId($employee->id);
						if($count > 0){
							$salary = new G_Employee_Basic_Salary_History();
							$salary->setEmployeeId($employee->id);
							$salary->setEndDate($_POST['start_date']);
							$salary->resetEmployeePresentSalary();
							$employee->setFrequencyId($_POST['pay_period_id']);
							$employee->save();
						}
					//
				}
				$employee_salary = new G_Employee_Basic_Salary_History();	
				$employee_salary->setId($_POST['compensation_history_id']);
				$employee_salary->setEmployeeId($employee->id);
				$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$employee_salary->setBasicSalary($_POST['basic_salary_add']);
				$employee_salary->setType($_POST['type']);
				$employee_salary->setPayPeriodId($_POST['pay_period_id']);
				$employee_salary->setFrequencyId($_POST['pay_period_id']);
				$employee_salary->setStartDate($_POST['start_date']);
				
				$end_date = ($_POST['present'])? '' : $_POST['end_date'] ;
				
				$employee_salary->setEndDate($end_date);
				$employee_salary->save();	

				if($_POST['present']){
					//Update Employee Contribution
					$employee->addContribution($_POST['basic_salary_add']);
				}
		}
		
		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_compensation_add'] .",employee id=". $emp_id,$this->username);

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	    $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' New Employment Compensation to ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

		echo 1;
	}
	
	function _update_compensation_history() 
	{
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		if($employee) {
				if($_POST['present']){
					//Offset present salary
						$count = G_Employee_Basic_Salary_History_Helper::countTotalHistoryByEmployeeId($employee->id);
						if($count > 0){
							$salary = new G_Employee_Basic_Salary_History();
							$salary->setEmployeeId($employee->id);
							$salary->setEndDate(date('Y-m-d'));
							$salary->resetEmployeePresentSalary();
							$employee->setFrequencyId($_POST['pay_period_id']);
							$employee->save();
						}
					//
				}

				$employee_salary = new G_Employee_Basic_Salary_History;	
				$employee_salary->setId($_POST['compensation_history_id']);
				$employee_salary->setEmployeeId($employee->id);
				$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$employee_salary->setBasicSalary($_POST['basic_salary_history']);
				$employee_salary->setType($_POST['type']);
				$employee_salary->setPayPeriodId($_POST['pay_period_id']);
				$employee_salary->setFrequencyId($_POST['pay_period_id']);
				$employee_salary->setStartDate($_POST['compensation_history_from']);
				$end_date = ($_POST['present'])? '' : $_POST['compensation_history_to'] ;
				$employee_salary->setEndDate($end_date);
				$employee_salary->save();	

				if($_POST['present']){
					//Update Employee Contribution
					$employee->addContribution($_POST['basic_salary_history']);
				}
		}
		
		$emp_id = Utilities::decrypt($_POST['employee_id']);
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_compensation_update'] .",employee id=". $emp_id,$this->username);

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
	    $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, 'Employment Compensation Details of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);

		echo 1;
	}

	function _refresh_employee_contribution() {	
		$return['is_success'] = false;

		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		if($employee) {
			$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);	
			if($salary) {
				if($salary->isDaily()){
					$basic_salary = ($salary->getBasicSalary()*$employee->getYearWorkingDays())/12;
				}else
				$basic_salary = $salary->getBasicSalary();
				G_Employee_Contribution_Helper::updateContribution($employee->getId(), $basic_salary);
			}else{
				$basic_salary = 0;
				$employee->addContribution($basic_salary);
			}
			
			$return['is_success'] = true;
		}

		echo json_encode($return);
	}

	function update_employee_contribution_to_deduct() {
		if(!empty($_POST)) {
			$ec = G_Employee_Contribution_Finder::findById(Utilities::decrypt($_POST['ec_id']));
			if($ec) {
				$to_deduct 		= unserialize($ec->getToDeduct());
				$sss 			= $to_deduct['sss'];
				$philhealth 	= $to_deduct['philhealth'];
				$pagibig 		= $to_deduct['pagibig'];

				if($_POST['ec_type'] == 'sss') {
					$sss = ($sss == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
				}elseif($_POST['ec_type'] == 'philhealth') {
					$philhealth = ($philhealth == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
				}elseif($_POST['ec_type'] == 'pagibig') {
					$pagibig = ($pagibig == G_Employee_Contribution::YES ? G_Employee_Contribution::NO : G_Employee_Contribution::YES);
				}

				$to_deduct_arr['sss'] 			= $sss;
		        $to_deduct_arr['philhealth'] 	= $philhealth;
		        $to_deduct_arr['pagibig'] 		= $pagibig;
		        $ec->setToDeduct(serialize($to_deduct_arr));
		        $ec->save();
			}
		}
		echo json_encode($json['return'] = true);
	}

	function update_is_tax_exempted() {
		if(!empty($_POST)) {
			$e = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
			if($e) {
				$is_exempted = ($e->getIsTaxExempted() == G_Employee::NO ? G_Employee::YES : G_Employee::NO);

				$e->setIsTaxExempted($is_exempted);
				$e->updateIsTaxExempted();
			}
		}
		echo json_encode($json['return'] = true);
	}

	function update_employee_fixed_contributions()
	{
		$row = $_POST;				
		$employee_id = Utilities::decrypt($row['employee_id']);			          
		$gefc = new G_Employee_Fixed_Contribution();
		$gefc->setEmployeeId($employee_id);    		      
		$gefc->deleteAllByEmployeeId();
		foreach( $row['fixed_contri'] as $key => $contri ){			
	        $gefc->setType($key);
	        $gefc->setEEAmount($contri['ee']);     
	        $gefc->setERAmount($contri['er']);
	        $gefc->setIsActivated($contri['is_activated']);   	       
	        $gefc->save(); 
		}
		
		echo 1;	
	}
	
	function _update_training()
	{
		$row = $_POST;	
		$e = new G_Employee_Training;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setDescription($row['description']);
		$e->setProvider($row['provider']);
		$e->setLocation($row['location']);
		$e->setCost($row['cost']);
		$e->setRenewalDate($row['renewal_date']);
		$saved = $e->save();
		
		$emp_id = Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_training_update'] .",id=". $emp_id,$this->username);
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_training_add'] .",id=". $emp_id,$this->username);
		}
		
		echo 1;	
	}
	
	function _update_job_history()
	{
		$row = $_POST;
		//print_r($_POST);
		$employment_status = ($row['employment_status']=='0') ? 'Terminated'  : $row['employment_status'] ;
		if($row['end_date'] == ''){
			//Offset default job history

			 $emp_his = G_Employee_Job_History_Finder::findAllById(Utilities::decrypt($row['employee_id']));
			
			 foreach($emp_his as $value) {
			 	if($value->end_date == ""){
			 		$history_id = $value->id;
			 	}
			 }

			 

				$j = new G_Employee_Job_History();
				$j->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$j->setEndDate(date($row['start_date']));
				//$j->resetEmployeeDefaultJob();
				$j->resetEmployeeByJobHistoryId($history_id);

				$e  = G_Employee_Finder::findById(Utilities::decrypt($row['employee_id']));
				$es = G_Settings_Employment_Status_Finder::findByStatus($row['employment_status']);
				if( $e && $es ){					
					$e->setEmploymentStatusId($es->getId());
					$e->save();
				}
			//
		}


		
		$job = G_Job_Finder::findById($row['job_id']);
		$e = new G_Employee_Job_History;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setJobId($row['job_id']);
		$e->setName($job->title);
		$e->setEmploymentStatus($employment_status);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);
		$e->save();
		
		$emp_id = Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_job_history_update'] .",employee id=". $emp_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_job_history_add'] .",employee id=". $emp_id,$this->username);	
		}
		
		echo 1;	
	}
	
	function _update_work_experience()
	{
		$row = $_POST;	
		$e = new G_Employee_Work_Experience;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setCompany($row['company']);
		$e->setAddress($row['address']);
		$e->setJobTitle($row['job_title']);
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setComment($row['comment']);
		$e->save();
		echo 1;	
	}
	
	function _update_contribution()
	{
		$row = $_POST;	
		$e = new G_Employee_Contribution;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setSssEe($row['sss_ee']);
		$e->setPagibigEe($row['pagibig_ee']);
		$e->setPhilhealthEe($row['philhealth_ee']);
		$e->setSssEr($row['sss_er']);
		$e->setPagibigEr($row['pagibig_er']);
		$e->setPhilhealthEr($row['philhealth_er']);
		$e->save();
		
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_contribution_update'] .",id=". $row['id'],$this->username);	
		}
		echo 1;	
	}
	
	function _update_education()
	{
		$row = $_POST;	
		$e = new G_Employee_Education;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setInstitute($row['institute']);
		$e->setCourse($row['course']);
		$e->setYear($row['year']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);
		$e->setGpaScore($row['gpa_score']);
		$e->setAttainment($row['attainment']);
		$e->save();
		echo 1;	
	}
	
	function _update_supervisor()
	{		
		$row = $_POST;	
		if($row['select']==1) {
			$e = new G_Employee_Supervisor;
			$e->setId($row['id']);
			$e->setEmployeeId($row['e_id']);
			$e->setSupervisorId(Utilities::decrypt($row['employee_id']));
		
			$e->save();	
		}else {
			$e = new G_Employee_Supervisor;
			$e->setId($row['id']);
			$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
			$e->setSupervisorId($row['e_id']);
		
			$e->save();	
		}
		
		echo 1;	
	}
	
	function _update_skill()
	{
		$row = $_POST;	
		$e = new G_Employee_Skills;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setSkill($row['skill']);
		$e->setYearsExperience($row['years_experience']);
		$e->setComments($row['comments']);
		$e->save();
		echo 1;	
	}
	
	function _update_language()
	{
		$row = $_POST;	
		$e = new G_Employee_Language;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setLanguage($row['language']);
		$e->setFluency($row['fluency']);
		$e->setCompetency($row['competency']);
		$e->setComments($row['comments']);	
		$e->save();
		echo 1;	
	}
	
	function _update_license()
	{
		$row = $_POST;	
		$e = new G_Employee_License;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setLicenseType($row['license_type']);
		$e->setLicenseNumber($row['license_number']);
		$e->setIssuedDate($row['issued_date']);
		$e->setExpiryDate($row['expiry_date']);	
		$e->save();
		echo 1;	
	}
	
	function _update_membership()
	{
		$row = $_POST;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setMembershipTypeId($row['membership_type_id']);
		$e->setMembershipId($row['membership_id']);
		$e->setSubscriptionOwnership($row['subscription_ownership']);
		$e->setSubscriptionAmount($row['subscription_amount']);
		$e->setCommenceDate($row['commence_date']);
		$e->setRenewalDate($row['renewal_date']);	
	}
	
	
	function _update_memo()
	{
		$row = $_POST;
		if(!empty($row['memo_id'])){		
			$date_create = Tools::getCurrentDateTime('Y-m-d','Asia/Manila');
			$prefix = 'memo';
			$employee_id =  Utilities::decrypt($_POST['employee_id']);
			$em = G_Employee_Helper::findByEmployeeId($employee_id);
			
			$created_by = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($this->h_employee_id));
			
			$hash = $em['hash'];
			$len = strlen($_FILES['filename']['name']);
			$pos = strpos($_FILES['filename']['name'],'.');
			$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
			$handle = new upload($_FILES['filename']);
			$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;
						
			if($_FILES['filename']['size'] > 0 && $_FILES['filename']['error'] == 0) {
				$fname = $_FILES['filename']['name'];
			} else {
				$fname = $_POST['attachment'];
			}
			
		   if ($handle->uploaded) {		   
				$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
				$handle->file_overwrite 	  = true;
			
		       $handle->process($path);
		       if ($handle->processed) {
		         $memo_temp = G_Settings_Memo_Finder::findById($_POST['memo_id']);
					$memo =  $filename . strtolower($extension_name); 				
					$row = $_POST;
					$e = new G_Employee_Memo;
					$e->setId($row['id']);
					$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
					$e->setMemoId($row['memo_id']);
					$e->setTitle($memo_temp->getTitle());
					$e->setMemo($row['memo']);
					$e->setAttachment($memo);
					$e->setDateOfOffense($row['date_of_offense']);
					$e->setOffenseDescription($row['offense_description']);
					$e->setRemarks($row['remarks']);
					$e->setDateCreated($memo_temp->getDateCreated());
					$e->setCreatedBy($memo_temp->getCreatedBy());
					$saved = $e->save();
					
					/*if($saved) {*/
						$handle->clean();
						$emp_id = Utilities::decrypt($row['employee_id']);
						if(!empty($row['id'])){
							$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_update'] .",employee id=". $emp_id,$this->username);		
						}else{
							$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_add'] .",employee id=". $emp_id,$this->username);	
						}
						echo 1;
				/*	}else{
						$this->triggerAuditTrail(0,ACTION_INSERT . '/' . ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_add'] .",employee id=". $emp_id,$this->username);	
						echo 'Error adding/updating Memo or 1' . '<br />';
					}*/
				 
		       } else {
		       	echo $return = $handle->error;
		       }
		   }else{
			   $memo_temp = G_Settings_Memo_Finder::findById($_POST['memo_id']);
			   $row = $_POST;
				$e = new G_Employee_Memo;
				$e->setId($row['id']);
				$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$e->setMemoId($row['memo_id']);
				$e->setTitle($memo_temp->getTitle());
				$e->setMemo($memo_temp->getContent());
				$e->setAttachment($fname);
				$e->setDateOfOffense($row['date_of_offense']);
				$e->setOffenseDescription($row['offense_description']);
				$e->setRemarks($row['remarks']);
				$e->setDateCreated($date_create);
				$e->setCreatedBy($created_by['lastname'] . ' ' . $created_by['firstname']);
				$saved = $e->save();
				
				/*if($saved) {*/
					$emp_id = Utilities::decrypt($row['employee_id']);
					if(!empty($row['id'])){
						$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_update'] .",employee id=". $emp_id,$this->username);		
					}else{
						$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_add'] .",employee id=". $emp_id,$this->username);	
					}
					echo 1;
				/*}else{
					$this->triggerAuditTrail(0,ACTION_INSERT . '/' . ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_memo_add'] .",employee id=". $emp_id,$this->username);	
					echo 'Error adding/updating Memo or 2' . '<br />';
					echo $return =  $handle->error;	
				}*/
		   }
		}else{
			echo 'No Memo Template Selected';
		}	
	}
	
	function _update_leave_available()
	{	
		$row 	= $_POST;

		$year 	= $this->year;     
		$e 		= new G_Employee_Leave_Available;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setLeaveId($row['leave_id']);
		$e->setNoOfDaysAlloted($row['no_of_days_alloted']);
		$e->setNoOfDaysAvailable($row['no_of_days_available']);	
		$e->setCoveredYear(date("Y"));
		$json = $e->saveEmployeeLeaveCredits();

		if( $json['is_success'] ) {
			if( empty($row['id']) ) {
				//add also on employee leave credit histor
				$h = new G_Employee_Leave_Credit_History();
				$h->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$h->setLeaveId($row['leave_id']);
				$h->setCreditsAdded($row['no_of_days_alloted']);
				$h->addToHistory();
			}
		}
		
		echo json_encode($json);
	}
	
	function _update_subdivision_history()
	{
		
		$row = $_POST;
		$subdivision = G_Company_Structure_Finder::findById($row['subdivision_id']);
		$history = G_Employee_Subdivision_History_Finder::findByEmployeeId(Utilities::decrypt($row['employee_id']));
			foreach ($history as  $value) {
				if($value->end_date == ''){
					$history_id = $value->id;
				}
		}

		if($row['subdivision_end_date'] == ''){		
			//Offset other present subdivision
				$sh = new G_Employee_Subdivision_History;
				////$sh->setCompanyStructureId($row['subdivision_id']);
				$sh->setEmployeeId(Utilities::decrypt($row['employee_id']));
				//$sh->setEndDate(date("Y-m-d"));
				//$sh->resetEmployeePresentSubdivision();
				$sh->setEndDate($row['subdivision_start_date']);
				$sh->resetEmployeePresentSubdivisionBySubdivisionHistory($history_id);

			//
		}
		
		$e = new G_Employee_Subdivision_History;		
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setCompanyStructureId($row['subdivision_id']);
		$e->setName($subdivision->title);
		$e->setType(G_Employee_Subdivision_History::DEPARTMENT);
		$e->setStartDate($row['subdivision_start_date']);
		$e->setEndDate($row['subdivision_end_date']);
		$e->save();

		if($row['subdivision_end_date'] == '') {
			$new_sid = $row['subdivision_id'];
			$obj_employee        = G_Employee_Finder::findByIdBothArchiveAndNot(Utilities::decrypt($row['employee_id']));
			G_Employee_Manager::updateEmployeeDepartmentId($obj_employee, $new_sid);
		}
		
		$emp_id = Utilities::decrypt($row['employee_id']);
		if(!empty($row['id'])) {
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_subd_history_update'] .",employee id=". $emp_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_subd_history_add'] .",employee id=". $emp_id,$this->username);	
		}

		//General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($row['employee_id']));
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
		if(!empty($row['id'])) {
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_NEW_UPDATE, 'Department History Details of ', $emp_name, '', '', 1, $shr_emp['position'], $shr_emp['department']);
    	}
    	else{
    		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'HR', ACTION_ADD, ' New Department History Details of ', $emp_name, '', '', 0, $shr_emp['position'], $shr_emp['department']);
    	}

				
		echo 1;	
	}
	
	function _update_leave_request()
	{
		$row = $_POST;

		$e = new G_Employee_Leave_Request;
		$r = G_Employee_Leave_Request_Finder::findById($row['id']);

		if($r) {
			$is_approved =  $r->getIsApproved();
			$leave_id = $r->getLeaveId();
			$employee_id = $r->getEmployeeId();
		}
			$e->setId($row['id']);
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
			$e->setLeaveId($row['leave_id']);
			$e->setDateApplied($row['date_applied']);
			$e->setDateStart($row['date_start']);
			$e->setDateEnd($row['date_end']);
			$e->setLeaveComments($row['leave_comments']);
			$e->setIsApproved($row['is_approved']);	
			$e->save();
			
	
		 $d =  Date::get_day_diff($r->date_start,$r->date_end);
		$number_of_days =  $d['days'] +=1;
		if(strtolower($is_approved)=='pending' && strtolower($row['is_approved'])=='approved') {

			$available = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($employee_id,$leave_id);
			if($available) {
				$available->lessLeaveAvailable($number_of_days);
			}		
		}
		
		echo 1;
	}
	
	function account()
	{
		Utilities::checkModulePackageAccess('hr','user_account');
		
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
		$this->var['token'] = Utilities::createFormToken();
		$this->var['page_title'] = "Account";
		$this->view->setTemplate('template_account.php');
		$this->view->render('employee/account/index.php',$this->var);
	}
	
	function import_account()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['earning_file']['tmp_name'];
		$account = new G_Account_Import($file);
		$account->setCompanyStructureId($this->company_structure_id);
		
		$is_imported = $account->import();		
		
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'Account has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing account. Please contact the administrator.';
		}
		echo json_encode($return);		
	}
	
	function html_import_account() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('employee/account/html/html_import_account.php',$this->var);		
	}
	
	function ajax_import_account() 
	{	
		$this->var['action']	 = url('employee/import_account');			
		$this->view->render('employee/account/form/ajax_import_account.php',$this->var);		
	}
	
	function ajax_validate_username()
	{
		$response[] = 'user';
		$response[] = false;
	
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	function _insert_new_acount()
	{
		Utilities::verifyFormToken($_POST['token']);
		
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$company_structure_id = $this->company_structure_id;
		$e = G_Employee_Finder::findById($employee_id);
		
		$j = G_Employee_Job_History_Finder::findCurrentJob($e);
		
		$is_user_exist = G_User_Finder::findByEmployeeId($employee_id);
		if($is_user_exist) {
			echo "Employee: ".$e->firstname . " " . $e->lastname . " is already registered.";
		}else {
			$is_exist = G_User_Helper::isUsernameExist($_POST['username']);
			if($is_exist) {
				echo "Username is already taken";				
			}else {
				if($j) {
					$u = new G_User;
					$u->setCompanyStructureId($company_structure_id);
					$u->setEmployeeId($employee_id);
					$u->setEmploymentStatus($j->getEmploymentStatus());
					$u->setHash($e->getHash());
					$u->setUsername($_POST['username']);
					$u->setPassword(Utilities::encryptPassword($_POST['password']));
					$u->setModule($_POST['module']);
					$u->setDateEntered(date("Y-m-d"));
					$u->save();
					echo 1;	
				}else {
					echo "Employee is already terminated. <br>Registration Failed";	
				}
				
			}
		}
	}
	
	function _generate_username()
	{
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		$username = $e->getLastname().date("Ym");
		$is_exist = G_User_Helper::isUsernameExist($username);

		if($is_exist) {
			$username = 1;				
		}else {
			$username = $username;	
		}
		echo strtolower($username);
	}
	
	function _load_edit_account()
	{
		$user = G_User_Finder::findById($_POST['user_id']);
		$e = G_Employee_Finder::findById($user->getEmployeeId());
		if($e) {
			$employee_id =  $e->getId();	
		
		
			$e = G_Employee_Finder::findById($employee_id);
			$this->var['employee'] = $e;
			
			$file = PHOTO_FOLDER.$e->getPhoto();
	
			if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
				$this->var['filemtime'] = md5($e->getPhoto()).date("His");
				$this->var['filename'] = $file;
			}else {
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';	
			}
			$mod = explode(',',$user->getModule());
			//print_r($mod);
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
		$this->var['current_module'] = $current_module;
		$this->var['employee'] = $e;
		$this->var['user'] = $user;
		$this->view->noTemplate();
		$this->view->render('employee/account/form/edit_account.php',$this->var);	
	}
	
	function _generate_password()
	{
		echo substr(Tools::createRandomPassword(),2,8);
		//echo Utilities::decrypt($_POST['employee_id']);
	}
	
	function _update_account()
	{
		$user = G_User_Finder::findById($_POST['user_id']);
		
		$e = G_Employee_Finder::findById($user->getEmployeeId());
		$j = G_Employee_Job_History_Finder::findCurrentJob($e);
		if($user) {
			$user->setUsername($_POST['username_update']);
			if($_POST['password_update']=='') {
				$password = $user->getPassword();	
			}else {
				$password = Utilities::encryptPassword($_POST['password_update']);
				$password_update = 1;
			}
			$user->setPassword($password);
			$user->setModule($_POST['update_module']);
			$user->setHash($e->getHash());
			$user->setDateModified(date("Y-m-d"));
			$user->setEmploymentStatus($j->getEmploymentStatus());
			$user->save();
			echo 1;
		}else {
			echo "Update Account Failed.";	
		}
		
	}
	
	function _check_username()
	{
		$username = $_POST['username_update'];
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
	
	function _load_print_employee_details_options() {
		if(!empty($_POST)) {
			$this->var['h_employee_id'] = $_POST['h_id'];
			$this->view->render('employee/profile/print/forms/print_employee_details_options.php',$this->var);
		}
	}
	
	function download_employee_details() {
		if(!empty($_POST)) {
			$employee_id = Utilities::decrypt($_POST['h_employee_id']);
			$this->var['data'] = $data = $_POST;
			
			$this->print_personal_details($employee_id);
			$this->print_contact_details($employee_id);
			$this->print_emergency_contacts($employee_id);
			$this->print_dependents($employee_id);
			$this->print_banks($employee_id);
			$this->print_employement_information($employee_id);
			$this->print_compensation($employee_id);
			$this->print_contract($employee_id);
			$this->print_contribution($employee_id);
			$this->print_performance($employee_id);
			$this->print_training($employee_id);
			$this->print_memo($employee_id);
			$this->print_requirements($employee_id);
			$this->print_supervisor($employee_id);
			$this->print_leave($employee_id);
			$this->print_deduction($employee_id);
			$this->print_work_experience($employee_id);
			$this->print_education($employee_id);
			$this->print_skills($employee_id);
			$this->print_language($employee_id);
			$this->print_license($employee_id);
			$e = G_Employee_Finder::findById($employee_id);
			
			$this->var['filename'] = $e->getLastName()."_details.xls";
			$this->view->render('employee/profile/print/print.html.php',$this->var);
		}
	}
	
	function print_personal_details($employee_id) {
		//$employee_id = 1;
		
		$this->load_employee_photo($employee_id);
		
		$this->var['personal_details'] 	= $details = G_Employee_Finder::findById($employee_id);
		$this->var['field'] 			= G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$details);
		
		$this->var['title_personal_details'] = "Personal Details";
		//$this->view->render('benchmark_leo/personal_details.php',$this->var);
	}
	
	function print_contact_details($employee_id) {
		//$employee_id = 1;
		
		$this->var['contact_details'] = $details  = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
		$this->load_employee_photo($employee_id);
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['field'] 			= G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_contact_details'] = "Contact Details";
		
		//$this->view->render('benchmark_leo/contact_details.php',$this->var);
	}
	
	 function print_emergency_contacts($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['contacts'] 			= G_Employee_Emergency_Contact_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_emergency_contacts'] = "Emergency Contacts";
		
		//$this->view->render('benchmark_leo/emergency_contacts.php',$this->var);
	}
	
	function print_dependents($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['dependents'] 		= G_Employee_Dependent_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_dependent'] 	= "Dependents";
		
		//$this->view->render('benchmark_leo/dependents.php',$this->var);
	}
	
	function print_banks($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['banks'] 			= $banks = G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);
		
		//Tools::showArray($banks);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_banks'] = "Banks";
		
		//$this->view->render('benchmark_leo/emergency_contacts.php',$this->var);
	}
	
	function print_employement_information($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$d = G_Employee_Helper::findByEmployeeId($employee_id);
		
		$branch 		= G_Company_Branch_Finder::findByCompanyStructureId($d['company_structure_id']);
		$department 	= G_Company_Structure_finder::findByCompanyBranchId($d['branch_id']);
		$job 			= G_Job_Finder::findByCompanyStructureId($d['company_structure_id']);
		$job_category 	= G_Eeo_Job_Category_finder::findByCompanyStructureId($d['company_structure_id']);
		
		$employee = G_Employee_Helper::findByEmployeeId($employee_id);
		$position =  G_Job_Finder::findById($d['job_id']);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		
		if($total_status>0){
			$status = G_Job_Employment_Status_Finder::findByJobId($position->getId());
			$status_type = 1; // status by position
		}else {
			$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
			$status_type =0; // default status
		}
		if($employee['employment_status']=='Terminated') {
			
			$memo = G_Employee_Memo_Finder::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));	
			
			foreach($memo as $key=> $val) {
				if($val->title=='Terminated') {
					$this->var['terminated_memo'] = $val->memo;
				}
			}
		}
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->var['status'] = $status;
		$this->var['status_type'] = $status_type;
		$this->var['employment_status'] = $employee['employment_status'];
		
		$this->var['branch'] 		= $branch;
		$this->var['d'] 			= $d;
		$this->var['department'] 	= $department;
		$this->var['job'] 			= $job;
		$this->var['job_category'] 	= $job_category;
		
		## Subdivision History ##
		$this->var['subdivision_history'] 	= $history = G_Employee_Subdivision_History_Finder::findByEmployeeId($employee_id);
		$this->var['department'] 			= $department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
		
		## Job History ##
		
		$history 	= G_Employee_Job_History_Finder::findByEmployeeId($employee_id);
		$job 		= G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status 	= G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['job'] = $job;
		$this->var['job_history'] = $history;
		$this->var['status'] = $status;
	
		//$this->view->render('benchmark_leo/employment_status.php',$this->var);
	}
	
	function print_compensation($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$employee 			= G_Employee_Finder::findById($employee_id);
		$employee_salary 	= G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);
	
		$employee_rate 			= G_Job_Salary_Rate_Finder::findById($employee_salary->job_salary_rate_id);
		$employee_pay_period 	= G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);
	
		## Compensation ##
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['employee_id'] 			= Utilities::encrypt($employee->id);
		$this->var['employee_salary'] 		= $employee_salary;
		$this->var['employee_rate'] 		= $employee_rate;
		$this->var['employee_pay_period'] 	= $employee_pay_period;
		$this->var['pay_period'] 			= $pay_period;
		$this->var['rate'] 					= $rate;
		
		## Compensation History ##
		$history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId($employee_id);

		$this->var['compensation_history'] = $history;
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		
		//$this->view->render('benchmark_leo/compensation.php',$this->var);
	}
	
	function print_contract($employee_id) {
	//	$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['durations'] 		= G_Employee_Extend_Contract_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/contract.php',$this->var);
	}
	
	function print_contribution($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['c']					= G_Employee_Contribution_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/contribution.php',$this->var);
	}
	
	function print_performance($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['performance']  		= G_Employee_Performance_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/performance.php',$this->var);
	}
	
	function print_training($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['training'] 			= G_Employee_Training_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/training.php',$this->var);
	}
	
	function print_memo($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['memo'] 				= G_Employee_Memo_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/memo_notes.php',$this->var);
	}
	
	function print_requirements($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$e = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
		$data[] = unserialize($e->requirements);	
		$this->var['requirements'] = $data;
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/requirements.php',$this->var);
	}
	
	function print_supervisor($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['subordinate'] 	= G_Employee_Supervisor_Finder::findByEmployeeId($employee_id);
		$this->var['supervisor'] 	= G_Employee_Supervisor_Finder::findBySupervisorId($employee_id);
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/supervisor.php',$this->var);
	}
	
	function print_leave($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$availables = G_Employee_Leave_Available_Finder::findByEmployeeId($employee_id);
		$request 	= G_Employee_Leave_Request_Finder::findByEmployeeId($employee_id);
		$gcs 		= G_Company_Structure_Finder::findById($this->company_structure_id);
		$leaves 	= G_Leave_Finder::findByCompanyStructureId($gcs);

		$this->var['leaves'] = $leaves;
		$this->var['request'] = $request;
		$this->var['availables'] = $availables;
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/leave.php',$this->var);
	}
	
	## DEDUCTION, not yet finish ##
	function print_deduction($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		//$this->var['loans'] 			= $loans = G_Employee_Loan_Helper::getAllLoansByCompanyStructureIdAndEmployeeId($this->company_structure_id,$employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/leave.php',$this->var);
	}
	
	function print_work_experience($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['work_experience'] 	= G_Employee_Work_Experience_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/work_experience.php',$this->var);
	}
	
	function print_education($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['education'] 		= G_Employee_Education_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/education.php',$this->var);
	}
	
	function print_skills($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['skills'] 			= G_Employee_Skills_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/skills.php',$this->var);
	}
	
	function print_language($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['languages'] 		= G_Employee_Language_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/language.php',$this->var);
	}
	
	function print_license($employee_id) {
		//$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['license'] 			= G_Employee_License_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/language.php',$this->var);
	}
	
	function load_employee_photo($employee_id) {
		$employee = G_Employee_Finder::findById($employee_id);
		//$file = PHOTO_FOLDER.$employee->getPhoto();
		$excel_path = "http://".$_SERVER['HTTP_HOST'].BASE_FOLDER."hr/";
		$file = $excel_path."files/photo".$employee->getPhoto();
		if(Tools::isFileExist($file)==true && $employee->getPhoto()!='') {
			$this->var['filemtime'] = $file = md5($employee->getPhoto()).date("His");
		}else {
			$this->var['filename'] = $excel_path. 'images/profile_noimage.gif';
		}
		
	}
	
	function test()
	{
		//Tools::showArray($_SERVER);
		//echo PHOTO_FOLDER;
		$excel_path = "http://".$_SERVER['HTTP_HOST']."/".BASE_FOLDER."/hr/";
		$file = $excel_path."files/photo/tsadfs";
		echo $excel_path . ' - ' . $file;
		exit;
		echo $test = 'ppOyiN99rlsXV2QCdkOSMNt3xM16hx4k1hYuVK9DSJE='; //$_GET['eid'];
		echo Utilities::encrypt(7);	
		echo "<br>";
		echo Utilities::decrypt($test);
	}
	
	function xml() {
			
	}
	
	
	function _load_employee_history_form() {
		if(!empty($_POST)) {
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('employee/profile/personal_information/personal_details/form/add_history.php',$this->var);
		}
	}
	
	function _insert_update_history() {
		if(!empty($_POST)) {
			$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {
				$history = G_Employee_Details_History_Finder::findById(Utilities::decrypt($_POST['h_id']));
				$json['message']  = "Successfully Updated Employee History!";
				if(!$history) {
					$history = new G_Employee_Details_History;
					$json['message']  = "Successfully Added Employee History!";
					$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_history'] .",employee id=". $employee->getId(),$this->username);
				} else {
					$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_update_history'] .",employee id=". $employee->getId(),$this->username);
				}
				
				$history->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
				$history->setEmployeeCode($employee->getEmployeeCode());
				$history->setModifiedBy(Utilities::decrypt($this->h_employee_id));
				$history->setRemarks($_POST['remarks']);
				$history->setIsArchive(G_Employee_Details_History::NO);
				$history->setHistoryDate($_POST['history_date']);
				$history->setDateModified($this->c_date);	
				$history->save();
				$json['is_saved'] = true;
			} else {
				$this->triggerAuditTrail(0,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_history'] .",employee id=". $employee->getId(),$this->username);
				$json['is_saved'] = false;
				$json['message']  = "Error : Cannot find " . $employee->getName() . " in employee list";	
			}
		} else {
			$this->triggerAuditTrail(0,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_information_personal_add_history'] .",employee id=". $employee->getId(),$this->username);
			$json['is_saved'] = false;
			$json['message']  = "Error : There's an error occured! Please try again.";	
		}
		
		echo json_encode($json);
	}
	
	function _load_employee_history_list_dt() {
		$this->var['permission_action']	= $this->validatePermission(G_Sprint_Modules::HR,'employees','personal_details');
		$this->view->render('employee/profile/personal_information/personal_details/_employee_history_list_dt.php',$this->var);
	}
	
	function _load_server_employee_history_list_dt() {

		$permission_action	= $this->validatePermission(G_Sprint_Modules::HR,'employees','personal_details');

		Utilities::ajaxRequest();
		
		$employee_id = Utilities::decrypt($_GET['h_employee_id']);
		$condition	 = ' employee_id = ' . Model::safeSql($employee_id) . ' AND is_archive = ' . Model::safeSql(G_Employee_Details_History::NO);
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_DETAILS_HISTORY);
		$dt->setCustomField();
		$dt->setJoinTable();			
		$dt->setJoinFields();
		//$dt->setCondition(' employee_id = ' . Model::safeSql($employee_id));
		$dt->setCondition($condition);
		$dt->setColumns('remarks,history_date');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02)	{	
			$dt->setNumCustomColumn(2);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editEmployeeHistoryDialog(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteEmployeeHistory(\'e_id\')\"></a></li></ul></div>'));
		}else{
			$dt->setNumCustomColumn(2);
		}
		echo $dt->constructDataTable();
	}
	
	function _load_edit_history_form() {
		if(!empty($_POST)) {
			$this->var['history'] = $history = G_Employee_Details_History_Finder::findById(Utilities::decrypt($_POST['h_id']));
			$this->view->render('employee/profile/personal_information/personal_details/form/edit_history.php',$this->var);
		}
	}
	
	function _delete_employee_account()
	{
		if($_POST['id']){
			$gu = G_User_Finder::findById($_POST['id']);
			if($gu){
				$gu->delete();
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully deleted.';
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _archive_employee()
	{
		if($_POST['eid']){
			$ge = G_Employee_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($ge){
				$ge->archive();
				$this->triggerAuditTrail(1,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_archive'] .",id=". $ge->getId(),$this->username);
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully sent to archive.';
			}else{
				$this->triggerAuditTrail(0,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_archive'] .",id=". $ge->getId(),$this->username);
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$this->triggerAuditTrail(0,ACTION_ARCHIVE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_archive'] .",id=". $ge->getId(),$this->username);
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _permanent_delete_employee()
	{
		if($_POST['eid']){
			$ge = G_Employee_Finder::findById2(Utilities::decrypt($_POST['eid']));
			if($ge){
				$ge->delete();
				//To do: Delete all employee records on the tables

				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully deleted.';
			}else{
				$json['is_success'] = 0;			
				$json['message']    = 'Record not found...';				
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';			
		}				
		echo json_encode($json);
	}
	
	function _restore_employee()
	{
		if($_POST['eid']){
			$ge = G_Employee_Finder::findByIdIsArchive(Utilities::decrypt($_POST['eid']));
			if($ge){
				$ge->restore();
				$this->triggerAuditTrail(1,ACTION_RESTORE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_restore'] .",id=". $ge->getId(),$this->username);
				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully restored.';
			}else{
				$this->triggerAuditTrail(0,ACTION_RESTORE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_restore'] .",id=". $ge->getId(),$this->username);
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$this->triggerAuditTrail(1,ACTION_RESTORE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employee_restore'] .",id=". $ge->getId(),$this->username);
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function html_show_import_employee_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('employee/employee/html/html_show_import_employee_format.php', $this->var);	
	}
	
	function _get_p_memo_content() {
		if(!empty($_POST['memo_id'])){
			$m = G_Employee_Memo_Finder::findById($_POST['memo_id']);
			if($m) {
				$this->var['memo_content'] = $m;
				$this->view->setTemplate('template_blank.php');
				$this->view->render('employee/profile/employment_information/memo/form/p_memo_content.php', $this->var);
			}else{
				echo "<p>NO RECORDS FOUND</p>";	
			}
		}		
	}
	
	function _get_memo_content() {
		if(!empty($_POST['memo_id'])){
			$m = G_Settings_Memo_Finder::findById($_POST['memo_id']);
			if($m) {
				$this->var['memo_content'] = $m;
				$this->view->setTemplate('template_blank.php');
				$this->view->render('employee/profile/employment_information/memo/form/memo_content.php', $this->var);
			}else{
				echo "<p>NO RECORDS FOUND</p>";	
			}
		}
	}



	//=====================================================================



	//project_site_history
	function _load_project_site_history()
	{

		Utilities::ajaxRequest();

		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];

		$employee_id =  $_GET['employee_id'];

		$employee_status = G_Settings_Employee_Status_Finder::findAllIsNotArchiveByCompanyStructureId($this->company_structure_id);

		$eps = new G_Employee_Project_Site_History();

		$eps->setEmployeeId(Utilities::decrypt($employee_id));

		//get project history : LIST
		$projects = $eps->getProjectSites();

		$employeeProjects = $eps->_load_employee_projects();
		
		$this->var['max_date'] = $employeeProjects[0]['end_date'];

		$this->var['projects'] = $projects;
		$this->var['project_history'] =$employeeProjects;
		$this->var['employee_status'] =$employee_status;


		$this->var['status'] = $status;
		//$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;

		$btn_add_project_site_history_config = array(
				'module'				=> 'hr',
				'parent_index'			=> 'employees',
				'child_index'			=> 'employment_status',
				'href' 					=> 'javascript:loadProjectSiteHistoryAddForm();',
				'onclick' 				=> '',
				'id' 					=> 'project_site_history_add_button_wrapper',
				'class' 				=> 'add_button',
				'icon' 					=> '',
				'additional_attribute' 	=> '',
				'caption' 				=> '<strong>+</strong><b>Add Project Site History</b>'
				);

			$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'employees','employment_status');
			$this->var['btn_add_project_site_history'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_project_site_history_config);

		$this->var['title_project_site_history'] = "Project Site History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/project_site_history/index.php',$this->var);
	}



	function _set_project_site()
	{
		# code...
		$row = $_POST;
		$row['employee_status'] = '';
		$row['status_date'] = '';

		$p = new G_Employee_Project_Site_History();

		$p->setEmployeeId(Utilities::decrypt($row['employee_id']));

		$p->setStartDate(date($row['start_date']));
		$p->setEndDate(date($row['end_date']));
		$p->setProjectId($row['project_site_id']);
		$p->setEmployeeStatus($row['employee_status']);
		$p->setStatusDate($row['status_date']);
                                                                                                                                                                                                
        Model::open('G_Employee_Project_Site_Status_Model');
        $a = new G_Employee_Project_Site_Status_Model();

       

        if($p->getEndDate() == ""){ 
        	 $a->update_employee_project_site_history(Utilities::decrypt($row['employee_id']),$row['project_site_id']);
        }
        

		if ($p->setProject()) {
			echo 1;
		}
	}

	function _update_project(){
		$row = $_POST;

		$row['employee_status'] = '';
		$row['status_date'] = '';

		$p = new G_Employee_Project_Site_History();

		$p->setEmployeeId(Utilities::decrypt($row['employee_id']));

		$p->setStartDate(date($row['start_date']));

		$p->setProjectId($row['project_site_id']);
        
        $p->setEmployeeStatus($row['employee_status']);
        $p->setStatusDate($row['status_date']);
		$site_id = $row['site_id'];
		$psh_id = $row['project_site_id'];
		$inp_s_date = date($row['start_date']);
		$inp_e_date = date($row['end_date']);

	
			if ($row['end_date'] == '') {
					//end date ng current project
					$current_end_date = date('Y-m-d');
					$p->setEndDate(date($current_end_date));

					if ($p->removeCurrentProject()) {
					 	//echo 1;
					}
				    Model::open('G_Employee_Project_Site_Status_Model');
                  
				   // $p->setEmployeeId(Utilities::decrypt($row['employee_id']));
				   // $p->setProjectId($site_id);
				   // $p->setStartDate(date($row['start_date']));
		     //       $p->setProjectId($row['site_id']);
				   $a = new G_Employee_Project_Site_Status_Model();
                   $a->update_employee_project_site_history(Utilities::decrypt($row['employee_id']),$row['site_id']);
                   $p->updateHistoryStartDate($inp_s_date , $psh_id);
                   $p->updateHistoryEndDate($inp_e_date , $psh_id,$row['employee_status'],$row['status_date']);
					echo 1;
					


			}else{
				$p->setEndDate(date($row['end_date']));
				$p->setSiteId($row['site_id']);
				if ($p->updateThisProjectSite()) {
					echo 1;
				}
					
			}


		


				// $p->updateHistoryProject($site_id , $psh_id);
				// $p->updateHistoryStartDate($inp_s_date , $psh_id);
				// $p->updateHistoryEndDate($inp_e_date , $psh_id);
		

	}

	public function remove_project()
	{
		// code...
		$project_id = $_POST['project_site_history_id'];

		$p = new G_Employee_Project_Site_History();
		$p->setProjectId($project_id);
		if ($p->remove_project()) {
			//????
			$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['employee']['employment_status_project_site_history_delete'] .",id=". $project_id,$this->username);
			return 1;
		}

	}

	//edit form
	function _load_project_history_edit_form()
	{

		$project_history_id = $_POST['project_history_id'];

		$eps = new G_Employee_Project_Site_History();
		$eps->setProjectId($project_history_id);

		$j = $eps->getProjectSites();

		$a = $eps->getCurrentProjectById();

		$employee_status = G_Settings_Employee_Status_Finder::findAllIsNotArchiveByCompanyStructureId($this->company_structure_id);

		$this->var['project_lists'] = $j;
		$this->var['selected_project_site'] = $a;
		$this->var['employee_status'] = $employee_status;

		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/project_site_history/form/project_site_history_edit.php',$this->var);
	}

//=======================================================

	
}
?>