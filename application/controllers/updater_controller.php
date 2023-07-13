<?php
class Updater_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appUtilities();	
		Loader::appScript('startup.js');
		Loader::appStyle('style.css');
		Loader::appScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appStyle('jquerytimepicker/jquery.timepicker.css');
		$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		$this->var['settings'] = 'current';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}

	function checkIsFileExists()
	{
		$session_id     = session_id();		
		$user_info_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $session_id;			
		//echo $user_info_file;
		if( Tools::isFileExistDirectPath($user_info_file) == 1 ) {	
			unlink($user_info_file);
			echo "File exists!";			
		}else{			
			echo "File does not exists!";
		}	
	}

	function processAttendanceLogById()
	{
		$attendance_log_pkid = 3;
		$at = G_Attendance_Log_Finder::findById($attendance_log_pkid);
		if( $at ){
			Utilities::displayArray($at);
			//Create timesheet data	
			$process = false;	
			if( $at->getType() == G_Attendance_Log::TYPE_IN ){
				$index_inout = "in";
				$process     = true;
			}elseif( $at->getType() == G_Attendance_Log::TYPE_OUT  ){
				$index_inout = "out";
				$process     = true;
			}    		

			if( $process ){				
				$raw_timesheet[$at->getEmployeeId()][$index_inout][$at->getDate()] = array($at->getTime() => $at->getDate());				
				$r = new G_Timesheet_Raw_Logger($raw_timesheet);
				$r->logTimesheet();

				$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
				$tr->filterAndUpdateAttendance();
			}
		}
		echo 5;
	}

	function sprintLogin()
	{
		//HR only
		$username = "test_user";
		$password = "abc123";

		//Payroll only
		//$username = "payroll";
		//$password = "abc123";

		//Super Admin
		//$username = G_Employee_User::OVERRIDE_USERNAME;
		//$password = G_Employee_User::OVERRIDE_PASSWORD;

		$u = new G_Employee_User();
		$u->setUserName($username);
		$u->setPassword($password);
		$json = $u->login();

		Utilities::displayArray($json);
	}

	function sprintModules()
	{
		echo "<pre>";

		$parent_module = 'earnings_deductions';
		$m = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
		$modules = $m->getSubModules($parent_module);

		print_r($modules);
	}

	function getSprintModules()
	{
		$mp = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
		$mod_payroll = $mp->getModuleList();

		echo "<pre>";
		print_r($mod_payroll);
		echo "</pre>";
	}

	function sprintModuleProperties()
	{
		echo "<pre>";

		$parent_index = 'employees';
		$child_index  = 'personal_details'; //optional - if set will get parent child property else will return parent properties

		$m = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$modules = $m->getModuleProperties($parent_index, $child_index);

		print_r($modules);
	}

	function sprintModuleActions()
	{
		echo "<pre>";
		
		$parent_index = 'employees';
		$child_index  = 'personal_details'; //optional - if set will return parent child property else will return parent properties

		$m = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$actions = $m->getModuleActions($parent_index, $child_index);

		print_r($actions);
	}

	function index()
	{	
		Loader::appScript('startup.js');
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadTipsy();
		Yui::loadDatatable();
		Jquery::loadJTags();
		Jquery::loadTextBoxList();
		Jquery::loadJqueryDatatable();
		
		$this->var['page_title'] 			= 'Startup';
		$this->var['company_structure_sb']	= 'selected';
		$this->var['module_title']			= 'Company Structure';
		$this->view->setTemplate('template.php'); //template_settings
		$this->view->render('startup/company/index.php',$this->var);
		
	}
		
	function _load_company_info()
	{   
	    $check_structure = G_Company_Structure_Finder::findByParentID(0);
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		$this->var['cs'] = $c_structure;
		$this->var['ci'] = $c_info;	
		$this->var['check_structure'] = $check_structure;
		$this->view->noTemplate();
		$this->view->render('startup/company/company_info.php',$this->var);
	}
	
	function xmlToArray()
	{
		
		$arr = Sprint_Tables::loadDefaultPagibigTable();		
		echo '<pre>';
		print_r($arr);	
		foreach($arr as $key => $value){
			foreach($value as $keysub => $subvalue){				
				echo $subvalue['salary_base'];
				/*switch ($subvalue['name']) {
					case "apple":
						echo "i is apple";
						break;
					case "bar":
						echo "i is bar";
						break;
					case "cake":
						echo "i is cake";
						break;
				}*/
			}
		}

	}
	
	function testWriteXml()
	{
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/startup.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2   = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);				
			echo $arrXml['startup'];			
			
			$obj = $xmlStr->xpath('//Settings');
			$obj[0]->startup = 'disable';						
			$xmlStr->asXml($xmlUrl);
			
		}else {
			echo 'No file exist';
		}
	}
	
	function _load_edit_company_info()
	{
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		$this->var['c']     = $c_structure;
		$this->var['cinfo'] = $c_info;
		$this->view->noTemplate();
		$this->view->render('startup/company/forms/edit_company_info.php',$this->var);
	}
	
	
	function add_company_structure()
	{	
		if(!empty($_POST)){
			$name = $_POST['title'];
			$gcs = new G_Company_Structure();	
			$gcs->setCompanyBranchId(0);
			$gcs->setTitle($name);	
			$gcs->setDescription("Company");				
			$gcs->setParentId(0);
			$gcs->setType();
			$gcs->setIsArchive();			
			$csid = $gcs->save();
			
			$cstructure = G_Company_Structure_Finder::findById($csid);
			$cstructure->setCompanyBranchId($csid);
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
			$cinfo->save($cstructure);

			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';			
		}else{
			$return['is_success'] = 0;
			$return['message']    = 'Error in SQL';
		}		
		
		echo json_encode($return);
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
			$cinfo->save($cstructure);
			
			$json['is_succes']= 1;
			$json['message']  = 'Record was successfully saved.' . $err;
			
		}else{
			$json['is_succes']= 0;
			$json['message']  = $err;
		}
		echo json_encode($json);
	}
	//department	
	
	function _load_department_startup()
	{   
	    $check_structure = G_Company_Structure_Finder::findByParentID(0);
		
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId()); 
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByCompanyStructureId($company_structure_id);
		
		$this->var['cs'] = $c_structure;
		$this->var['ci'] = $c_info;	
		$this->var['check_structure'] = $check_structure;
		$this->view->noTemplate();
		$this->view->render('startup/department/index.php',$this->var);
	}
	
	function _load_branch_dropdown_startup() 
	{
		sleep(1);
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId(),'id');
		$this->view->noTemplate();
		$this->view->render('startup/department/includes/branch_dropdown.php',$this->var);
	}
	
	function _load_department_dropdown_startup()
	{
		sleep(1);
		$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);
		
		$this->view->noTemplate();
		$this->view->render('startup/department/includes/department_dropdown.php',$this->var);	
	}
	
	function _load_department_startup_dt()
	{
		sleep(1);
		$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);
		$this->view->noTemplate();
		$this->view->render('startup/department/includes/department_dt.php',$this->var);	
	}
	
	function _load_add_branch_form_startup()
	{
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		$this->var['add_new_branch_action'] = url('startup/add_company_branch');
		$this->view->noTemplate();
		$this->view->render('startup/department/forms/add_new_branch.php',$this->var);
	}
	
	function _load_add_department_form_startup()
	{
		$branch_id =  $_POST['branch_id'];
		$this->var['branch'] = $b = G_Company_Branch_Finder::findById($branch_id);
		$this->var['branch_id'] = $b->getId();
		$this->var['branch_name'] = $b->getName();		
		$this->var['department_form_action'] = url('startup/_insert_new_department');
		$this->view->noTemplate();
		$this->view->render('startup/department/forms/add_department.php',$this->var);
	}
	
	function add_company_branch()
	{	
		if(!empty($this->company_structure_id)){
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
			$return['is_success'] = 1;
			$return['message']    = 'Record Saved.';
		}else{
			$return['is_success'] = 2;
			$return['message']    = 'Error in SQL';
		}
		echo json_encode($return);		
	}
	
	//department end
	function _load_employee()
	{
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		//exit;
		$this->var['page_title'] = 'Employee';
		$this->var['token'] = Utilities::createFormToken();
		//Jquery::loadMainTagBox();
		
	//	Jquery::loadJqueryFormSubmit();
//		Jquery::loadJTags();
//		Yui::loadDatatable();
//		Jquery::loadInlineValidation2();
//		Jquery::loadTipsy();
//		Jquery::loadTextBoxList();

		$company_structure_id = $this->company_structure_id;
		
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId()); 
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByCompanyStructureId($company_structure_id);
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		$this->var['positions'] = $p= G_Job_Finder::findByCompanyStructureId2($company_structure_id);

		$this->var['employement_status'] = G_Settings_Employment_Status_Finder::findByCompanyStructureId($company_structure_id);
		
		$this->var['add_new_branch_action'] = url('startup/_insert_company_branch');
		$this->var['add_position_action'] =  url('startup/_insert_new_position');
		$this->var['add_status_action'] =  url('startup/_insert_new_status');
		
		$this->var['import_action'] = url('startup/_import_employee_excel');
		
		$this->var['page_title'] = 'Employee';
		//$this->view->setTemplate('template_employee.php');
		$this->view->noTemplate();
		$this->view->render('startup/employee/index.php',$this->var);
	}
	
	//branch employee
	function _load_branch_dropdown() 
	{
		sleep(1);
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId(),'id'); 
		
		$this->view->noTemplate();
		$this->view->render('startup/employee/includes/branch_dropdown.php',$this->var);
	}
	
	function _load_add_branch_form()
	{
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		
		$this->view->noTemplate();
		$this->view->render('startup/employee/form/add_new_branch.php',$this->var);
	}
	//department employee
	function _load_add_department_form()
	{
		$branch_id =  $_POST['branch_id'];
		$this->var['branch'] =$b= G_Company_Branch_Finder::findById($branch_id);
		$this->var['department_form_action'] = url('startup/_insert_new_department');
		$this->view->noTemplate();
		$this->view->render('startup/employee/form/add_department.php',$this->var);
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
		$department->setType("Department");
		$department->save();
		echo 1;
	}
	
	//position
	function _load_add_position_form() 
	{
		$this->var['add_position_action'] =  url('startup/_insert_new_position');
		$this->view->noTemplate();
		$this->view->render('startup/employee/form/add_position.php',$this->var);
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
	
	function _load_position_dropdown()
	{
		sleep(1);

		$this->var['positions'] = G_Job_Finder::findByCompanyStructureId2($this->company_structure_id);
		
		$this->view->noTemplate();
		$this->view->render('startup/employee/includes/position_dropdown.php',$this->var);
	}
	
	//employee status
	function _load_add_status_form()
	{
		$this->var['position_id'] = $_POST['position_id'];
		$this->var['add_status_action'] =  url('startup/_insert_new_status');
		$this->view->noTemplate();
		$this->view->render('startup/employee/form/add_employment_status.php',$this->var);
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
		$this->view->render('startup/employee/includes/status_dropdown.php',$this->var);
	}
	
	//import
	function _import_employee_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['employee']['tmp_name'];
		$e = new Employee_Main_Import($file);
		$return = $e->import();
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
	
	function _load_employee_hash() 
	{
		$e =  G_Employee_Helper::findHashByEmployeeId(Utilities::decrypt($_POST['employee_id']));
		echo $e['hash'];
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
	
	function _load_department_dropdown()
	{
		sleep(1);
		$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);
		
		$this->view->noTemplate();
		$this->view->render('startup/employee/includes/department_dropdown.php',$this->var);	
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
		$this->view->render('startup/employee/confirmation.php',$this->var);
	}
	
	function profile()
	{
		$eid = $_GET['eid'];
		$hash = $_GET['hash'];
		Utilities::verifyHash(Utilities::decrypt($eid),$hash);
		//Loader::appScript('employee_profile.js');
		Loader::appScript('employee_profile_base.js');
		Loader::appScript('employee_loan.js');
		Loader::appScript('employee_loan_base.js');
		Jquery::loadJqueryDatatable();
		Jquery::loadJqueryFormSubmit();
		//Style::loadMainTableThemes();
		Jquery::loadInlineValidation2();
		Jquery::loadJTags();
		Jquery::loadTextBoxList();
		Jquery::loadTipsy();
		
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
		
		$this->var['title'] = $title;
		$this->var['page_title'] = 'Employee';
		$this->var['page_subtitle'] = '<span>Manage employee list</span>';
		$this->view->setTemplate('template_employee2.php');
		$this->view->render('startup/employee/profile/index.php',$this->var);
	}
	
	function _load_personal_details() 
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		$employee_id =  Utilities::decrypt($_GET['employee_id']);
	
		$e = G_Employee_Finder::findById($employee_id);
		$this->var['details'] = $e;
		
		$this->var['field'] = G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$e);
		$this->load_summary_photo();
		
		//tags
		$this->var['t'] = $t = G_Employee_Tags_Finder::findByEmployeeId($e->getId());

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
		$this->view->render('startup/employee/profile/personal_information/index.php',$this->var);

	}
	
	function _get_photo_filename()
	{
		$employee_id =  Utilities::decrypt($_POST['employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		$this->view->noTemplate();
		$this->var['filename'] = $e->getPhoto();
		$this->view->noTemplate();
		$this->view->render('startup/employee/profile/personal_information/photo/filename.php',$this->var);
		
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
	
	function _load_photo_frame()
	{
		Utilities::ajaxRequest();
		
		$module_access 		= HR;
		$sub_module_access	= array(EMPLOYEE_MODULE=>"employee_management");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt($this->h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
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
		$this->view->render('startup/employee/profile/personal_information/photo/photo_frame.php',$this->var);
	}
	
	function _insert_new_employee()
	{
		Utilities::verifyFormToken($_POST['token']);
		$is_exist = G_User_Helper::isUsernameExist($_POST['username']);
		if($_POST['branch_id']=='' || $_POST['department_id']=='' || $_POST['position_id']=='' || $_POST['employment_status_id']=='') {
			echo 0;	
		}else if($is_exist) {
			echo 2;	
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
	
			// user access 
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
						//echo 1;	
					}else {
						echo "Employee is already terminated. <br>Registration Failed";	
					}
					
				}
			}
			echo Utilities::encrypt($employee_id);
		}
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
	
	
	//default Schedule	
	function _load_schedule_startup()
	{   
		Loader::appScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appStyle('jquerytimepicker/jquery.timepicker.css');
		//Loader::appMainScript('jquerytimepicker/base.js');
		//Loader::appMainStyle('jquerytimepicker/base.css');		
	
	
	    $check_structure = G_Company_Structure_Finder::findByParentID(0);
		$company_structure_id = $this->company_structure_id;
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$c_structure = G_Company_Structure_Finder::findById($this->company_structure_id);	
		$c_info	     = G_Company_Info_Finder::findByCompanyStructureId($c_structure->getId());
		$this->var['cs'] = $c_structure;
		$this->var['ci'] = $c_info;	
		$this->var['grace_period'] = G_Settings_Grace_Period_Finder::findAllCompanyStructureIdActive($this->company_structure_id);
		$this->var['action'] = url('startup/show_employee');	
		$this->var['schedules'] = G_Schedule_Finder::findAll();		
		$this->var['check_structure'] = $check_structure;
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/index.php',$this->var);
	}
	 
	function _load_grace_period_dropdown_startup() 
	{ 
		sleep(1);
		$this->var['grace_period'] = G_Settings_Grace_Period_Finder::findAllCompanyStructureIdActive($this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/includes/grace_period_dropdown.php',$this->var);
	}
	
	function _load_add_grace_period_form_startup()
	{	 
		$this->var['grace_period_form_action'] = url('startup/save_grace_period');
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/forms/add_new_grace_period.php',$this->var);
	}
	
	function save_grace_period()
	{
		sleep(3);
		  
		$grace_period = G_Settings_Grace_Period_Finder::findAllCompanyStructureIdActive($this->company_structure_id);
		if($grace_period){ foreach($grace_period as $content):
				$grace_time =  G_Settings_Grace_Period_Finder::findById($content->getId());	
				$e->setIsDefault(0);
				$grace_time->save_not_default();
				echo 2;
		  endforeach; }
		
		$e = new G_Settings_Grace_Period;
		$e->setCompanyStructureId($this->company_structure_id);
		$e->setTitle($_POST['grace_title']);
		$e->setDescription($_POST['grace_period_description']);
		$e->setIsArchive($_POST['is_archive']);
		$e->setIsDefault($_POST['is_default']);
		$e->setNumberMinuteDefault($_POST['number_minute_default']);
		$e->save();
		echo 1;
	}
	
	function save_grace_period_default()
	{
		sleep(3);
		$grace_period = G_Settings_Grace_Period_Finder::findAllCompanyStructureIdActive($this->company_structure_id);
		if($grace_period){ foreach($grace_period as $content):
				$grace_time =  G_Settings_Grace_Period_Finder::findById($content->getId());	
				$grace_time->save_not_default();
				echo 2;
		  endforeach; }
		
				$grace_time_set =  G_Settings_Grace_Period_Finder::findById($_POST['grace_period_id']);	
				if($grace_time_set){
				$grace_time_set->save_default();
				}
		echo 1;
	}
	
	function _load_default_leave_startup() 
	{ 
		sleep(1);
		$this->var['company_structure'] = $this->company_structure_id;
		$this->var['default_leave'] = G_Leave_Finder::findAllByCompanyStructureIdIsNotArchive($this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/includes/default_leave_dt.php',$this->var);
	}
	
	function _load_add_default_leave_form_startup()
	{	 
		$this->var['default_leave_form_action'] = url('startup/save_default_leave');
		$this->var['leave_id'] = $_POST['lid'];
		$this->var['leave'] = G_Leave_Finder::findById($_POST['lid'],$this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/forms/add_leave_default.php',$this->var);
	}
	
	function _load_edit_default_leave_form_startup()
	{	 
		$this->var['default_leave_form_action'] = url('startup/save_default_leave');
		$this->var['leave_id'] = $_POST['lid'];
		$this->var['leave'] = G_Leave_Finder::findById($_POST['lid'],$this->company_structure_id);
		$this->var['leave_c'] = G_Settings_Default_Leave_Finder::findByLeaveTypeId($_POST['lid'],$this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('startup/default_schedule/forms/edit_leave_default.php',$this->var);
	}
	
	function save_default_leave()
	{
		sleep(3);
		$company_structure_id = $this->company_structure_id;
		$default_leave = G_Settings_Default_Leave_Finder::findByLeaveTypeId($_POST['leave_id'],$this->company_structure_id);
		if($default_leave){
			$gsd = G_Settings_Default_Leave_Finder::findByLeaveTypeId($_POST['leave_id'],$this->company_structure_id);
		}else{
			$gsd = new G_Settings_Default_Leave;
		}
		$gsd->setCompanyStructureId($company_structure_id);
		$gsd->setLeaveTypeId($_POST['leave_id']);
		$gsd->setNumberOfDaysDefault($_POST['number_of_days_default']);
		$gsd->save();
		echo 1;
	}
	
	//schedule let
	
	function ajax_show_weekly_schedule_list() {
		//$s = G_Schedule_Finder::findAll();
		//$this->var['schedules'] = G_Schedule_Helper::mergeByName($s);
		$this->var['schedule_groups'] = $s = G_Schedule_Group_Finder::findAll();
		$this->view->noTemplate();
		$this->view->render('startup/schedule/ajax_weekly_schedule_list.php',$this->var);
	}	
	
	function ajax_import_schedule() {
		$this->var['action'] = url('startup/_import_schedule');
		$this->view->render('startup/schedule/forms/ajax_import_schedule.php', $this->var);	
	}
	
	function ajax_import_schedule_specific() {
		$this->var['action'] = url('startup/_import_schedule_specific');
		$this->view->render('startup/schedule/forms/ajax_import_schedule_specific.php', $this->var);	
	}
	
	function ajax_add_weekly_schedule_form() {
		$this->var['action'] = url('startup/_add_weekly_schedule');
		$this->view->noTemplate();
		$this->view->render('startup/schedule/forms/ajax_add_weekly_schedule_form.php',$this->var);
	}
	
	function ajax_add_specific_schedule() {
		$this->var['action'] = url('startup/_add_specific_schedule');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->view->render('startup/schedule/forms/ajax_add_specific_schedule_form.php', $this->var);
	}
	
	function ajax_edit_weekly_schedule_form() {
		$this->var['action'] = url('startup/_edit_weekly_schedule');
		$this->var['public_id'] = $public_id = $_GET['public_id'];
		$group = G_Schedule_Group_Finder::findByPublicId($public_id);
		$this->var['group_name'] = $group->getName();
		$this->var['is_default'] = $group->isDefault();
		$effect_date = $group->getEffectivityDate();
		if (!strtotime($effect_date)) {
			$effect_date = date('Y-m-d');	
		}
		$this->var['effectivity_date'] = $effect_date;
		$this->var['schedules'] = G_Schedule_Finder::findAllByScheduleGroup($group);
		$this->view->noTemplate();
		$this->view->render('startup/schedule/forms/ajax_edit_weekly_schedule_form.php',$this->var);
	}
	
	function html_show_import_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('startup/schedule/html/html_show_import_format.php', $this->var);	
	}
	
	function html_import_changed_schedule() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('startup/schedule/html/html_import_changed_schedule.php', $this->var);	
	}	
	
	function _import_schedule() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$date_start = $_POST['date_start'];
		
		Loader::appLibrary('php_excel/PHPExcel/Shared/ZipStreamWrapper');
		Loader::appLibrary('php_excel/PHPExcel/Shared/String');
		Loader::appLibrary('php_excel/PHPExcel/Reader/IReader');
		Loader::appLibrary('php_excel/PHPExcel/Reader/IReadFilter');
		Loader::appLibrary('php_excel/PHPExcel/Reader/DefaultReadFilter');
		Loader::appLibrary('php_excel/PHPExcel/Reader/Excel2007');
		Loader::appLibrary('php_excel/PHPExcel/ReferenceHelper');
		Loader::appLibrary('php_excel/PHPExcel/Shared/File');
		Loader::appLibrary('php_excel/PHPExcel');
		Loader::appLibrary('php_excel/PHPExcel/IComparable');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorageFactory');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/PageSetup');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/PageMargins');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/HeaderFooter');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/SheetView');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/Protection');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/RowDimension');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/ColumnDimension');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/RowIterator');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/Row');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/CellIterator');		
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/ICache');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/CacheBase');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/Memory');
		Loader::appLibrary('php_excel/PHPExcel/DocumentProperties');
		Loader::appLibrary('php_excel/PHPExcel/DocumentSecurity');
		Loader::appLibrary('php_excel/PHPExcel/Style/Font');
		Loader::appLibrary('php_excel/PHPExcel/Style/Color');
		Loader::appLibrary('php_excel/PHPExcel/Style/Fill');
		Loader::appLibrary('php_excel/PHPExcel/Style/Border');
		Loader::appLibrary('php_excel/PHPExcel/Style/Borders');
		Loader::appLibrary('php_excel/PHPExcel/Style/Alignment');
		Loader::appLibrary('php_excel/PHPExcel/Style/NumberFormat');
		Loader::appLibrary('php_excel/PHPExcel/Style/Protection');
		Loader::appLibrary('php_excel/PHPExcel/Reader/Excel2007/Theme');
		Loader::appLibrary('php_excel/PHPExcel/Shared/Date');		
		Loader::appLibrary('php_excel/PHPExcel/Cell');
		Loader::appLibrary('php_excel/PHPExcel/Cell/DataType');
		Loader::appLibrary('php_excel/PHPExcel/Calculation');
		Loader::appLibrary('php_excel/PHPExcel/Style');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet');
		Loader::appLibrary('php_excel/PHPExcel/IOFactory');
		Loader::appLibrary('php_excel/PHPExcel/Autoloader');
		
				
		if (!strtotime($date_start)) {
			$date_start = date('Y-m-d');	
		}		
		$file = $_FILES['import_schedule_file']['tmp_name'];

		$g = new G_Schedule_Import_Weekly($file);	
		$g->setEffectivityDate($date_start);
			
		if ($g->import()) {
			$es = $g->getEmployees();
			$c = G_Cutoff_Period_Finder::findByDate($date_start);
			$start_date = $date_start;//$c->getStartDate();
			$end_date = $c->getEndDate();
			
			foreach ($es as $employee_code) {
				$e = G_Employee_Finder::findByEmployeeCode($employee_code);
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
				}				
			}
			
			$return['message'] = 'Schedule has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}
		
	//	$return['message'] = 'There was an error while importing. Please contact the administrator';
//		$return['is_imported'] = false;
		echo json_encode($return);
	}
	
	function _import_schedule_specific() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['import_schedule_specific_file']['tmp_name'];
		//$file = BASE_PATH . 'files/sample_import_files/import_schedule_weekly.xlsx';
		
		Loader::appLibrary('php_excel/PHPExcel/Shared/ZipStreamWrapper');
		Loader::appLibrary('php_excel/PHPExcel/Shared/String');
		Loader::appLibrary('php_excel/PHPExcel/Reader/IReader');
		Loader::appLibrary('php_excel/PHPExcel/Reader/IReadFilter');
		Loader::appLibrary('php_excel/PHPExcel/Reader/DefaultReadFilter');
		Loader::appLibrary('php_excel/PHPExcel/Reader/Excel2007');
		Loader::appLibrary('php_excel/PHPExcel/ReferenceHelper');
		Loader::appLibrary('php_excel/PHPExcel/Shared/File');
		Loader::appLibrary('php_excel/PHPExcel');
		Loader::appLibrary('php_excel/PHPExcel/IComparable');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorageFactory');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/PageSetup');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/PageMargins');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/HeaderFooter');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/SheetView');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/Protection');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/RowDimension');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/ColumnDimension');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/RowIterator');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/Row');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet/CellIterator');		
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/ICache');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/CacheBase');
		Loader::appLibrary('php_excel/PHPExcel/CachedObjectStorage/Memory');
		Loader::appLibrary('php_excel/PHPExcel/DocumentProperties');
		Loader::appLibrary('php_excel/PHPExcel/DocumentSecurity');
		Loader::appLibrary('php_excel/PHPExcel/Style/Font');
		Loader::appLibrary('php_excel/PHPExcel/Style/Color');
		Loader::appLibrary('php_excel/PHPExcel/Style/Fill');
		Loader::appLibrary('php_excel/PHPExcel/Style/Border');
		Loader::appLibrary('php_excel/PHPExcel/Style/Borders');
		Loader::appLibrary('php_excel/PHPExcel/Style/Alignment');
		Loader::appLibrary('php_excel/PHPExcel/Style/NumberFormat');
		Loader::appLibrary('php_excel/PHPExcel/Style/Protection');
		Loader::appLibrary('php_excel/PHPExcel/Reader/Excel2007/Theme');
		Loader::appLibrary('php_excel/PHPExcel/Shared/Date');		
		Loader::appLibrary('php_excel/PHPExcel/Cell');
		Loader::appLibrary('php_excel/PHPExcel/Cell/DataType');
		Loader::appLibrary('php_excel/PHPExcel/Calculation');
		Loader::appLibrary('php_excel/PHPExcel/Style');
		Loader::appLibrary('php_excel/PHPExcel/Worksheet');
		Loader::appLibrary('php_excel/PHPExcel/IOFactory');
		Loader::appLibrary('php_excel/PHPExcel/Autoloader');
		
		$g = new G_Schedule_Import_Dates($file);	
		if ($g->import()) {
			$return['message'] = 'Schedule has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}
		echo json_encode($return);
	}
	
	function _add_weekly_schedule() {
		$this->_edit_weekly_schedule();
	}
	
	function _edit_weekly_schedule() {
		$name = $_POST['name'];
		$public_id = $_POST['id'];
		$effectivity_date = $_POST['effectivity_date'];
		$is_changed = $_POST['is_changed'];
//		$schedule['time_in'] = array(
//			'mon' => '8:00 am', 
//			'tue' => '8:00 am', 
//			'wed' => '8:00 am', 
//			'thu' => '8:00 am', 
//			'fri' => '8:00 am', 
//			'sat' => '8:00 am', 
//			'sun' => '8:00 am'
//		);		
//		$schedule['time_out'] = array(
//			'mon' => '5:00 pm', 
//			'tue' => '5:00 pm', 
//			'wed' => '5:00 pm', 
//			'thu' => '5:00 pm', 
//			'fri' => '5:00 pm', 
//			'sat' => '6:00 pm', 
//			'sun' => '6:00 pm'
//		);
		//print_r($schedule);
		$schedule = $_POST;
		$merged_days = array();
		foreach ($schedule['time_in'] as $day => $schedule_time) {		
			if (strtotime($schedule_time)) {
				$schedule_time_in = $schedule_time;
				$schedule_time_out = $schedule['time_out'][$day];
				$merged_days[$schedule_time_in .'-'. $schedule_time_out][] = $day;
			}
		}
		if (count($merged_days) > 0) {
			$group = G_Schedule_Group_Finder::findByPublicId($public_id);
			if (!$group) {
				$group = new G_Schedule_Group;	
			}
			$group->setEffectivityDate($effectivity_date);
			$group->setName($name);			
			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();				
			} else {
				$group_id = $group->save();
			}
			$group = G_Schedule_Group_Finder::findById($group_id);
			
			G_Schedule_Group_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
						
			$s = G_Schedule_Finder::findAllByScheduleGroup($group);
			foreach ($s as $ss) {
				$old_time[] = $ss->getTimeIn() .'-'. $ss->getTimeOut();	
			}
			foreach ($merged_days as $time => $days) {
				list($time_in, $time_out) = explode('-', $time);
				$day = implode(',', $days);
				$time_in = date('H:i:s', strtotime($time_in));
				$time_out = date('H:i:s', strtotime($time_out));			
				$updated_time[] = $time_in .'-'. $time_out;
				$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);
				if (!$d) {
					$d = new G_Schedule;				
				}
				$d->setName($name);
				$d->setWorkingDays($day);
				$d->setTimeIn($time_in);
				$d->setTimeOut($time_out);
				if ($d->getId() > 0) {
					$schedule_id = $d->getId();
					$d->save();				
				} else {
					$schedule_id = $d->save();
				}
				$sched = G_Schedule_Finder::findById($schedule_id);
				$sched->saveToScheduleGroup($group);
			}
			$to_be_deleted = array_diff($old_time, $updated_time);
			foreach ($to_be_deleted as $to_delete) {
				list($time_in, $time_out) = explode('-', $to_delete);
				$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);
				if ($d) {
					$d->deleteSchedule();	
				}
			}
			$all_schedules = G_Schedule_Finder::findAllByScheduleGroup($group);
			foreach ($all_schedules as $all_schedule) {
				$schedule_string .=  '<div>'. Tools::timeFormat($all_schedule->getTimeIn()) .' - '. Tools::timeFormat($all_schedule->getTimeOut()) .' - '. $all_schedule->getWorkingDays().' </div>';		
			}
			
			
			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date;//$c->getStartDate();
				$end_date = $c->getEndDate();
							
				$employees = G_Employee_Finder::findByScheduleGroup($group);	
				foreach ($employees as $e) {
					if ($e) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);	
					}
				}
			}

			$return['is_saved'] = true;
			$return['message'] = 'Schedule has been saved';
			$return['title_string'] = $group->getName();
			$return['schedule_string'] = $schedule_string;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;
		}						
		echo json_encode($return);	
	}
	
	function _add_specific_schedule() {
		$employee_id = (int) $_POST['employee_id'];
		$start_date = $_POST['schedule_date'];
		$end_date = $_POST['schedule_end_date'];
		$time_in = $_POST['schedule_time_in'];
		$time_out = $_POST['schedule_time_out'];
		
		if (Tools::isValidDate($start_date) && Tools::isValidTime($time_in) && Tools::isValidTime($time_out)) {
			$start_date = date('Y-m-d', strtotime($start_date));
			$time_in = date('H:i:s', strtotime($time_in));
			$time_out = date('H:i:s', strtotime($time_out));
			
			$e = G_Employee_Finder::findById($employee_id);
			if ($e) {
				if (Tools::isValidDate($end_date)) {
					$end_date = date('Y-m-d', strtotime($end_date));
				} else {
					$end_date = $start_date;	
				}
				
				if (strtotime($end_date) >= strtotime($start_date)) {																	
					$s = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $start_date, $end_date);
					if (!$s) {
						$s = new G_Schedule_Specific;
					}				
					$s->setDateStart($start_date);
					$s->setDateEnd($end_date);
					$s->setTimeIn($time_in);
					$s->setTimeOut($time_out);
					$s->setEmployeeId($e->getId());
					$is_saved = $s->save();
					
					if ($is_saved) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
						$return['is_saved'] = true;
						$return['message'] = 'Schedule has been saved';												
					} else {
						$return['is_saved'] = false;
						$return['message'] = 'There was a problem saving the schedule. Please contact the administrator';	
					}					
				} else {
					$return['is_saved'] = false;
					$return['message'] = 'Start Date must not greater than End Date';
				}				
			} else {
				$return['is_saved'] = false;
				$return['message'] = 'Employee was not found';
			}
		} else {
			$return['is_saved'] = false;
			$return['message'] = 'Schedule has not been saved. Invalid time or date format.';
		}
		
		echo json_encode($return);	
	}
	
	function _delete_specific_schedule() {
		$schedule_id = (int) $_POST['schedule_id'];		
		$s = G_Schedule_Specific_Finder::findById($schedule_id);
		if ($s) {
			$start_date = $s->getDateStart();
			$end_date = $s->getDateEnd();
			$employee_id = $s->getEmployeeId();
			if ($s->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Schedule has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Schedule has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Schedule was not found.';
		}
		echo json_encode($return);
	}	
	
	function _assign_group_schedule_to_employee() {
		$employee_id = $_POST['employee_id'];
		$group_id = (int) $_POST['schedule_group_id'];
		$e = G_Employee_Finder::findById($employee_id);
		$is_assigned = false;
		if ($e) {
			$group = G_Schedule_Group_Finder::findById($group_id);
			if ($group) {
				$new_id = $group->assignToEmployee($e, Tools::getGmtDate('Y-m-d'));
				if ($new_id > 0) {
					$is_assigned = true;	
				}
			}					
		}
		if (!$is_assigned) {
			$return['message'] = 'Schedule has not been assigned';	
		} else {
			$return['message'] = 'Schedule has been assigned';
		}
		$return['is_assigned'] = $is_assigned;
		echo json_encode($return);
	}
	
	function show_employee() {	
		Jquery::loadTextBoxList();
		Jquery::loadInlineValidation2();
		Jquery::loadJqueryFormSubmit();
		Jquery::loadTipsy();
		
		Loader::appScript('restday_base.js');
		Loader::appScript('restday.js');
				
		$this->var['query'] = $query = $_GET['query'];
		$this->var['action'] = url('startup/show_employee');
		if ($query != '') {
			$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
		}
		$this->view->setTemplate('template.php');
		$this->view->render('startup/schedule/show_employee.php',$this->var);		
	}
	
	function show_employee_result() {	
		Jquery::loadTextBoxList();
		Jquery::loadInlineValidation2();
		Jquery::loadJqueryFormSubmit();
		Jquery::loadTipsy();
		
		Loader::appScript('restday_base.js');
		Loader::appScript('restday.js');
				
		$this->var['query'] = $query = $_GET['query'];
		$this->var['action'] = url('startup/show_employee');
		if ($query != '') {
			$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
		}
		//$this->view->setTemplate('template.php');
		$this->view->noTemplate();
		$this->view->render('startup/schedule/show_employee_result.php',$this->var);		
	}
	
	// end schedule
	
	// start settings 
	function _load_payroll_settings() {	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadTipsy();
		Yui::loadDatatable();
		$check_structure = G_Company_Structure_Finder::findByParentID(0);
		$this->var['check_structure'] = $check_structure;
		//$this->view->setTemplate('template.php');
		$this->view->noTemplate();
		$this->view->render('startup/payroll_settings/index.php',$this->var);		
	}
	
	function _load_deduction_breakdown() {	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadTipsy();
		Yui::loadDatatable();
		Jquery::loadJqueryFormSubmit();
		$this->var['approvers'] 			= 'selected';
		$this->var['deduction_breakdown_sb']= 'selected';			
		$this->view->noTemplate();
		$this->view->render('startup/payroll_settings/deduction_breakdown/index.php',$this->var);		
	}
	
	function _load_deduction_breakdown_list() {
		$this->var['deductions'] 	= $deductions = G_Settings_Deduction_Breakdown_Finder::findAll();
		$this->view->render('startup/payroll_settings/deduction_breakdown/deduction_breakdown_list.php',$this->var);
	}
	
	function ajax_edit_deduction_breakdown() {
		if(!empty($_POST)) {
			$this->var['action']	= 'startup/_update_deduction_breakdown';
			$this->var['deduction'] = $deduction = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			$this->view->render('startup/payroll_settings/deduction_breakdown/forms/ajax_edit_deduction_breakdown.php',$this->var);	
		}
	}
	
	function _update_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setBreakdown($_POST['1st_cutoff'].':'.$_POST['2nd_cutoff']);
				$db->save();
				$json['is_saved'] = true;
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
	
	function _activate_deduction_breakdown() {
		if(!empty($_POST)) {
			$db = G_Settings_Deduction_Breakdown_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($db) {
				$db->setIsActive(G_Settings_Deduction_Breakdown::YES);
				$db->save();
			}
		}
	}
	
		
	function _load_pay_period() {	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadTipsy();
		Yui::loadDatatable();
		Jquery::loadJqueryFormSubmit();		
		$this->var['module_title']	= 'Pay Period';
		$this->view->noTemplate();
		$this->view->render('startup/payroll_settings/pay_period/index.php',$this->var);		
	}
	
	function _load_pay_period_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$location		= G_Settings_Pay_Period_Finder::findAll($order_by,$limit);
		$total_records 	=  G_Settings_Pay_Period_Helper::countTotalRecords();
		
		foreach ($location as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}
	
		$data = $array;
		$total = count($array);
		$total_records = $total_records;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_add_new_pay_period()
	{
		$this->var['action_pay_period'] = url('startup/add_pay_period');
		$this->view->noTemplate();		
		$this->view->render('startup/payroll_settings/pay_period/forms/add_new_pay_period.php',$this->var);
	}
		
	function add_pay_period()
	{
		if(!empty($_POST['pay_period_name'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gspp       = new G_Settings_Pay_Period();
			$gspp->setPayPeriodCode($_POST['pay_period_code']);
			$gspp->setPayPeriodName($_POST['pay_period_name']);	
			$gspp->setCutOff($_POST['cut_off']);	
			$gspp->setIsDefault($_POST['is_default']);	
			$gspp->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}	
	
	function update_pay_period()
	{	
		if(!empty($_POST['pay_period_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gspp       = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
			$gspp->setPayPeriodCode($_POST['pay_period_code']);
			$gspp->setPayPeriodName($_POST['pay_period_name']);	
			$gspp->setCutOff($_POST['cut_off']);	
			$gspp->setIsDefault($_POST['is_default']);	
			$gspp->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function _load_edit_pay_period()
	{
		if(!empty($_POST['pay_period_id'])){			
			$pp          = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);		
			$this->var['action_pay_period'] = url('startup/update_pay_period');				
			$this->var['pp'] = $pp;
			$this->view->noTemplate();		
			$this->view->render('startup/payroll_settings/pay_period/forms/edit_pay_period.php',$this->var);
		}
	}
	
	function _load_delete_pay_period_confirmation()
	{
		if(!empty($_POST['pay_period_id'])){
			$pp = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
			if($pp){	
				$this->var['pay_period'] = $pp->getPayPeriodName();
				$this->view->noTemplate();
				$this->view->render('startup/payroll_settings/pay_period/delete_confirmation.php',$this->var);
			}
		}
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
	// end settings 
	
	
}
?>