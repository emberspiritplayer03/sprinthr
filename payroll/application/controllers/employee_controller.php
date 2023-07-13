<?php
class Employee_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		
		Loader::appMainScript('employee.js');

		Loader::appStyle('style.css');
		$this->var['employee'] = 'selected';
		
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];	

	}

	function index()
	{
		$this->employee();		
	}
	
	function employee()
	{
		$this->var['page_title'] = 'Employee';
		$this->var['token'] = Utilities::createFormToken();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
	
		$company_structure_id = $this->company_structure_id;
		
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId()); 
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByCompanyStructureId($company_structure_id);
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		$this->var['positions'] = $p= G_Job_Finder::findByCompanyStructureId2($company_structure_id);

		$this->var['employement_status'] = G_Settings_Employment_Status_Finder::findByCompanyStructureId($company_structure_id);
		
		$this->var['add_new_branch_action'] = url('employee/_insert_company_branch');
		$this->var['add_position_action'] =  url('employee/_insert_new_position');
		$this->var['add_status_action'] =  url('employee/_insert_new_status');
		
		$this->var['import_action'] = url('employee/_import_employee_excel');
		
		$this->var['page_title'] = 'Employee';
		$this->view->setTemplate('template_employee.php');
		$this->view->render('employee/employee/index.php',$this->var);
	}

	//import
	function _import_employee_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['employee']['tmp_name'];
		$e = new Employee_Import($file);
		$return = $e->import();
	}	
	
	function _import_error()
	{ 
		
		$this->var['error'] = $_SESSION['hr']['error'];
		$this->view->noTemplate();
		$this->view->render('employee/employee/form/import_error.php',$this->var);	
	}
	
	
	function _insert_new_employee()
	{
		Utilities::verifyFormToken($_POST['token']);
		if($_POST['branch_id']=='' || $_POST['department_id']=='' || $_POST['position_id']=='' || $_POST['employment_status_id']=='') {
			echo 0;	
		}else {
			$e = new G_Employee;
			$e->setEmployeeCode($_POST['employee_code']);
			$e->setFirstname($_POST['firstname']);
			$e->setLastname($_POST['lastname']);
			$e->setHiredDate($_POST['hired_date']);
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
		}
	}
	
	function _load_employee_hash() 
	{
		$e =  G_Employee_Helper::findHashByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		echo $e['hash'];
	}
	
	
	function _insert_company_branch() 
	{
		sleep(3);
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
		$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);
		
		$this->view->noTemplate();
		$this->view->render('employee/employee/includes/department_dropdown.php',$this->var);	
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
		sleep(3);
		$department = new G_Company_Structure;
		$department->setParentId($this->company_structure_id);
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
		sleep(3);
		$job = new G_Job;
		$job->setCompanyStructureId($this->company_structure_id);
		$job->setJobSpecificationId($_POST['job_specification_id']);
		$job->setTitle($_POST['job_title']);
		$job->setIsActive(1);
		$job->save();		
	}
	
	function _insert_new_status()
	{
		sleep(3);
	
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

		$colon_count = substr_count($_POST['searched'], ':'); 
		if($colon_count>0) {/* if has a colon*/	

				$search = G_Employee_Helper::getDynamicQueries($_POST['searched']);
		}else {
				if($_POST['searched']) {
					$search = " AND (e.firstname like '%". $_POST['searched'] ."%' OR e.lastname like '%". $_POST['searched'] ."%' ";
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
		
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search = G_Employee_Helper::getDynamicQueries($_GET['search']);
		}else {
			//no colon
			if($_GET['search']) {
				$search = " AND (e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				$search .= "OR j.name like '%".$_GET['search']."%' OR d.name like '%".$_GET['search']."%' ";
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
		$eid = $_GET['eid'];
		$hash = $_GET['hash'];
		Utilities::verifyHash(Utilities::decrypt($eid),$hash);
		Loader::appMainScript('employee_profile.js');
		Jquery::loadMainJqueryFormSubmit();
		//Style::loadMainTableThemes();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		
		$this->var['employee_id'] = $eid;
		$e =  G_Employee_Helper::findByEmployeeId(Utilities::decrypt($eid));
		if($e) {
			$this->var['employee_details'] = $e;	
		}else {
			$link[] = "Goto Page: ";
			display_error('',$link);
		}
		

		$this->var['title'] = $title;
		$this->var['page_title'] = 'Employee';
		$this->var['page_subtitle'] = '<span>Manage employee list</span>';
		$this->view->setTemplate('template_employee2.php');
		$this->view->render('employee/profile/index.php',$this->var);
	}
	
	function _load_employee_summary()
	{
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$this->var['employee_details'] = G_Employee_Helper::findByEmployeeId($employee_id);
		$this->view->noTemplate();
		$this->view->render('employee/profile/employee_summary.php',$this->var);
	}
	
	function _load_photo_frame()
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
		$e = G_Employee_Finder::findById($employee_id);
		$this->view->noTemplate();
		$this->var['filename'] = $e->getPhoto();
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
	
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			$records = G_Employee_Helper::findByLastnameFirstname($q);
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
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array($e->getId(), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}	
	
	function _load_personal_details() 
	{
		Utilities::ajaxRequest();
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
	
		$e = G_Employee_Finder::findById($employee_id);
		$this->var['details'] = $e;
		
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$e);
		$this->load_summary_photo();

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
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
	
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['details'] = $e;

		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
		$this->var['title_contact_details'] = "Contact Details";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/contact_details/index.php',$this->var);
	}
	
	function _load_emergency_contact() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Emergency_Contact_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['contacts'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
	
	function _delete_emergency_contact()
	{
		Utilities::ajaxRequest();
		$emergency_contact_id = $_POST['emergency_contact_id'];
		$e = G_Employee_Emergency_Contact_Finder::findById($emergency_contact_id);
		$e->delete();
		echo 1;
	}
	
	function _load_dependents() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Dependent_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['dependents'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
		$this->var['title_dependent'] = "Dependents";
		$this->view->noTemplate();
		$this->view->render('employee/profile/personal_information/dependent/index.php',$this->var);
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
		$e->delete();
		echo 1;
	}
	
	
	//
	function _load_bank() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Direct_Deposit_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['banks'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		echo 1;
	}
	
	//job history
	
	function _load_job_history() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Job_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$job = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['job'] = $job;
		$this->var['job_history'] = $history;
		$this->var['status'] = $status;
		//$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		echo 1;
	}
	
	//end of job history
	
	//subdivision history
	
	function _load_subdivision_history() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Subdivision_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
	
		
		$this->var['department'] = $department;
		$this->var['subdivision_history'] = $history;

		//$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
		$this->var['title_subdivision_history'] = "Subdivision History";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/subdivision_history/index.php',$this->var);
	}
	
	function _load_subdivision_history_edit_form()
	{
		
		$subdivision_history_id = $_POST['subdivision_history_id'];
		$e = G_Employee_Subdivision_History_Finder::findById($subdivision_history_id);
		$department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
		
		
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
		echo 1;
	}
	
	//end of subdivision history
	
	
	//employment status
	
	function _load_employment_status() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];

		$d = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$branch = G_Company_Branch_Finder::findByCompanyStructureId($d['company_structure_id']);
		$department = G_Company_Structure_finder::findByCompanyBranchId($d['branch_id']);
		$job = G_Job_Finder::findByCompanyStructureId($d['company_structure_id']);
		$job_category = G_Eeo_Job_Category_finder::findByCompanyStructureId($d['company_structure_id']);
	
		
		$employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));
		//echo "<pre>";
		//print_r($employee);
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
		$this->var['status'] = $status;
		$this->var['status_type'] = $status_type;
		$this->var['employment_status'] = $employee['employment_status'];
		

		$this->var['branch'] = $branch;
		$this->var['d'] = $d;
		$this->var['department'] = $department;
		$this->var['job'] = $job;
		$this->var['job_category'] = $job_category;
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
		$e = G_Employee_Extend_Contract_Finder::findByEmployeeId($employee_id);
		
		$this->var['durations'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = Utilities::encrypt($employee_id);
	
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
		echo 1;
	}
	//end extend contract
	
	//end of employment status
	
	function _load_compensation() 
	{
		Utilities::ajaxRequest();
		
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
		$this->var['title_compensation'] = "Compensation";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/compensation/index.php',$this->var);
	}
	
	//compensation history
	
	function _load_compensation_history() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['compensation_history'] = $history;
		$this->var['pay_period'] = $pay_period;
		$this->var['rate'] = $rate;
		$this->var['employee_id'] = $employee_id;
	
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
		echo 1;
	}
	
	//end of compensation history
	
	function _load_minimum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo $salary->minimum_salary;
	}
	
	function _load_maximum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo $salary->maximum_salary;
	}
	
	//requirements
	function _load_requirements() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Requirements_Finder::findByEmployeeId(Utilities::decrypt($employee_id));	
		$data[] = unserialize($e->requirements);	

		$this->var['requirements'] = $data;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		
		if(Tools::isFileExist($file)==true) {
			$requirements = Requirements::getDefaultRequirements();	
		}else {
			foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
				$requirements[Tools::friendlyFormName($key)] = '';
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
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Training_Finder::findByEmployeeId(Utilities::decrypt($employee_id));

		$this->var['training'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		echo 1;
	}
	
	// end of training

	//memo notes
	
	function _load_memo_notes() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Memo_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		
		$this->var['memo'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
		$this->var['title_memo'] = "Memo";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/memo/index.php',$this->var);
	}
	
	function _load_memo_edit_form()
	{
		$memo_id = $_POST['memo_id'];
		$e = G_Employee_Memo_Finder::findById($memo_id);

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
		$work_experience_id = $_POST['work_experience_id'];
		$e = G_Employee_Work_Experience_Finder::findById($work_experience_id);
		$e->delete();
		echo 1;
	}
	
	//work experience
	
	
	//contribution
	
	function _load_contribution() 
	{
		Utilities::ajaxRequest();
		
		$employee_id =  $_GET['employee_id'];
		$e = G_Employee_Contribution_Finder::findByEmployeeId(Utilities::decrypt($employee_id));

		$this->var['c'] = $e;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
	
		$this->var['title_skills'] = "Skills";
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/skill/index.php',$this->var);
	}
	
	function _load_skill_edit_form()
	{
		$skill_id = $_POST['skill_id'];
		$e = G_Employee_Skills_Finder::findById($skill_id);

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
	
		$this->var['title_language'] = "Language";
		$this->view->noTemplate();
		$this->view->render('employee/profile/qualification/language/index.php',$this->var);
	}
	
	function _load_language_edit_form()
	{
		$language_id = $_POST['language_id'];
		$e = G_Employee_Language_Finder::findById($language_id);

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
		
		$employee_id =  $_GET['employee_id'];
		$subordinate = G_Employee_Supervisor_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$supervisor = G_Employee_Supervisor_Finder::findBySupervisorId(Utilities::decrypt($employee_id));
		
		$this->var['subordinate'] = $subordinate;
		$this->var['supervisor'] = $supervisor;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
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
		
		$employee_id =  $_GET['employee_id'];
		$availables = G_Employee_Leave_Available_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$request = G_Employee_Leave_Request_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$gcs = G_Company_Structure_Finder::findById($this->company_structure_id);
		$leaves = G_Leave_Finder::findByCompanyStructureId($gcs);

		$this->var['leaves'] = $leaves;
		$this->var['request'] = $request;
		$this->var['availables'] = $availables;
		
		$this->load_summary_photo();
		$this->var['employee_id'] = $employee_id;
	
		$this->var['title_leave_available'] = "Leave Available";
		$this->var['title_request'] = "Leave Request";
		$this->view->noTemplate();
		$this->view->render('employee/profile/employment_information/leave/index.php',$this->var);
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
		$e = G_Employee_Finder::findById($employee_id);
		$file = PHOTO_FOLDER.$e->getPhoto();
		
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

		
		$hash = $em['hash'];
		$len = strlen($_FILES['filename']['name']);
		$pos = strpos($_FILES['filename']['name'],'.');
		$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
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
				$gcb->setAddedBy($row['added_by']);
				$gcb->setScreen($row['screen']);
				$gcb->save();

	           $handle->clean();
			   $return = true;
			 
	       } else {	          
			  $return =  $handle->error;
	       }
	   }else {
			$return =  $handle->error;   
	   }	
			
		echo $return;
			//echo 1;
	}
	
	
	function _update_personal_details()
	{
		//print_r($_POST);
		$es = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		$hash = Utilities::createHash(Utilities::decrypt($_POST['employee_id']));
		$e = new G_Employee;
		$e->setId(Utilities::decrypt($_POST['employee_id']));
		$e->setHash($hash);
		$e->setPhoto($_POST['photo']);
		$e->setEmployeeCode($_POST['employee_code']);
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
		$e->setNumberDependent($_POST['number_dependent']);
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
		
		echo 1;
			
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
		
		echo 1;
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
		$e->save();
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
		$e->save();
		echo 1;	
	}
	
	function _update_duration()
	{
	
		$prefix = 'contract';
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$em = G_Employee_Helper::findByEmployeeId($employee_id);
		
		$hash = $em['hash'];
		$len = strlen($_FILES['filename']['name']);
		$pos = strpos($_FILES['filename']['name'],'.');
		$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
		$handle = new upload($_FILES['filename']);
		$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;

	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;
		
		   $handle->process($path);
		   if ($handle->processed) {
			 
			   $file_docs =  $filename . strtolower($extension_name);
			   $row = $_POST;	
				$e = new G_Employee_Extend_Contract;
				$e->setId($row['id']);
				$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$e->setStartDate($row['start_date']);
				$e->setEndDate($row['end_date']);
				$e->setAttachment($file_docs);
				$e->setRemarks($row['remarks']);
				$e->setIsDone($row['is_done']);
				$e->save(); 								
			   $handle->clean();
			   $return = true;			 
		   } else {	    		      
		   		$row = $_POST;	
				$e = G_Employee_Extend_Contract_Finder::findById($row['id']);
				$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$e->setStartDate($row['start_date']);
				$e->setEndDate($row['end_date']);
				$e->setRemarks($row['remarks']);
				$e->setIsDone($row['is_done']);
				$e->save();
			  $return =  $handle->error;

		   }
	   }else {
			$row = $_POST;	
			$e = G_Employee_Extend_Contract_Finder::findById($row['id']);
			$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
			$e->setStartDate($row['start_date']);
			$e->setEndDate($row['end_date']);
			$e->setRemarks($row['remarks']);
			$e->setIsDone($row['is_done']);
			$e->save();
			$return =  $handle->error;   
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
		echo 1;	
	}
	
	function _update_employment_status()
	{
		
		$employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		$obj_employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		$company_subdivision = G_Company_Structure_Finder::findById($_POST['department_id']);
		$company_branch = G_Company_Branch_Finder::findById($_POST['branch_id']);
		
		$terminated_date = ($_POST['employment_status_id']=='0')? $_POST['terminated_date']: '';
		if($_POST['branch_id']!=$empoyee['branch_id']) {
			$employee_branch  = G_Employee_Branch_History_Finder::findCurrentBranch($obj_employee);
			$employee_branch->setCompanyBranchId($company_branch->getId());
			$employee_branch->setBranchName($company_branch->getName());
			$employee_branch->save();
		}
		
		if($_POST['department_id']!=$employee['department_id']) {
			$employee_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($obj_employee);
			
			if($employee_subdivision) {//has current subdivision
				
				$employee_subdivision->setCompanyStructureId($company_subdivision->getId());
				$employee_subdivision->setName($company_subdivision->getTitle());
				$employee_subdivision->save();	
			}else {//has no current subdivision
				$total_subdivision_history = G_Employee_Subdivision_History_Helper::countTotalHistoryByEmployeeId($obj_employee->getId());
				
				if($total_subdivision_history>0) {// update the recent subdivision
					$recent_history = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($obj_employee->getId());					
					if($recent_history) {
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
			
			$employment_status = ($_POST['employment_status_id']=='0')? 'Terminated': $_POST['employment_status_id'];
			$employee_position = G_Employee_Job_History_Finder::findCurrentJob($obj_employee);
			$company_job = G_Job_Finder::findById($_POST['job_id']);
		
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

		$obj_employee->setHiredDate($_POST['hired_date']);
		$obj_employee->setEeoJobCategoryId($_POST['job_category_id']);
		$obj_employee->setTerminatedDate($terminated_date);
		$obj_employee->save();
		
		if($_POST['employment_status_id']=='0') {
			$e = new G_Employee_Memo;
			$e->setId($row['id']);
			$e->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$e->setTitle('Terminated');
			$e->setMemo($_POST['memo']);
			$e->setDateCreated(date("Y-m-d"));
			$employee = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['hr']['employee_id']));
			$e->setCreatedBy($employee->lastname. ' ' . $employee->firstname);
			$e->save();	
			
			//terminate the basic salary
			$current_salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($obj_employee);
			if($current_salary) {
				$current_salary->setEndDate($terminated_date);
				$current_salary->save();	
			}
			//terminate the subdivision
			$current_subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($obj_employee);
			if($current_subdivision) {
				$current_subdivision->setEndDate($terminated_date);
				$current_subdivision->save();	
			}	
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
		}
		echo 1;
	}
	
	function _insert_compensation_history() 
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
	
	function _update_compensation_history() 
	{
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_POST['employee_id']));
		if($employee) {
				$employee_salary = new G_Employee_Basic_Salary_History;	
				$employee_salary->setId($_POST['compensation_history_id']);
				$employee_salary->setEmployeeId($employee->id);
				$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
				$employee_salary->setBasicSalary($_POST['basic_salary_history']);
				$employee_salary->setType($_POST['type']);
				$employee_salary->setPayPeriodId($_POST['pay_period_id']);
				$employee_salary->setStartDate($_POST['compensation_history_from']);
				$end_date = ($_POST['present'])? '' : $_POST['compensation_history_to'] ;
				$employee_salary->setEndDate($end_date);
				$employee_salary->save();	
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
		$e->save();
		echo 1;	
	}
	
	function _update_job_history()
	{
		$row = $_POST;
		//print_r($_POST);
		$employment_status = ($row['employment_status']=='0') ? 'Terminated'  : $row['employment_status'] ;
		
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
		$prefix = 'memo';
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$em = G_Employee_Helper::findByEmployeeId($employee_id);

		
		$hash = $em['hash'];
		$len = strlen($_FILES['filename']['name']);
		$pos = strpos($_FILES['filename']['name'],'.');
		$extension_name =  substr($_FILES['filename']['name'],$pos, 5);
		$handle = new upload($_FILES['filename']);
		$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;
	
	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;
		
	       $handle->process($path);
	       if ($handle->processed) {
	         
				$memo =  $filename . strtolower($extension_name); 
				$row = $_POST;
				$e = new G_Employee_Memo;
				$e->setId($row['id']);
				$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
				$e->setTitle($row['title']);
				$e->setMemo($row['memo']);
				$e->setAttachment($memo);
				$e->setDateCreated($row['date_created']);
				$e->setCreatedBy($row['created_by']);
				$e->save();

	           $handle->clean();
			   $return = true;
			 
	       } else {	
		   		 
			  $return =  $handle->error;
	       }
	   }else {
		   
		   $row = $_POST;
			$e = new G_Employee_Memo;
			$e->setId($row['id']);
			$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
			$e->setTitle($row['title']);
			$e->setMemo($row['memo']);
			//$e->setAttachment($memo);
			$e->setDateCreated($row['date_created']);
			$e->setCreatedBy($row['created_by']);
			$e->save();
			$return =  $handle->error;   
	   }	
			
		echo 1;
	}
	
	function _update_leave_available()
	{
		$row = $_POST;
		$e = new G_Employee_Leave_Available;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setLeaveId($row['leave_id']);
		$e->setNoOfDaysAlloted($row['no_of_days_alloted']);
		$e->setNoOfDaysAvailable($row['no_of_days_available']);	
		$e->save();
		echo 1;
	}
	
	function _update_subdivision_history()
	{
		
		$row = $_POST;
		$subdivision = G_Company_Structure_Finder::findById($row['subdivision_id']);
		
		$e = new G_Employee_Subdivision_History;
		$e->setId($row['id']);
		$e->setEmployeeId(Utilities::decrypt($row['employee_id']));
		$e->setCompanyStructureId($row['subdivision_id']);
		$e->setName($subdivision->title);
		$e->setStartDate($row['subdivision_start_date']);
		$e->setEndDate($row['subdivision_end_date']);
		$e->save();
		echo 1;	
	}
	
	function _update_leave_request()
	{
		$row = $_POST;
		$e = new G_Employee_Leave_Request;
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
		echo 1;
	}
	
	function account()
	{
	
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
		$this->var['token'] = Utilities::createFormToken();
		$this->var['page_title'] = "Account";
		$this->view->setTemplate('template_account.php');
		$this->view->render('employee/account/index.php',$this->var);
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
	
	function test()
	{
		echo $test = 'ppOyiN99rlsXV2QCdkOSMNt3xM16hx4k1hYuVK9DSJE='; //$_GET['eid'];
		echo Utilities::encrypt(7);	
		echo "<br>";
		echo Utilities::decrypt($test);
	}
	
	function xml() {
			
	}
	
	
	
}
?>