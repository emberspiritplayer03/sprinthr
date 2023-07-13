<?php
class Settings_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login();		
		Loader::appMainScript('settings.js');
		Loader::appMainScript('settings_base.js');
		//Loader::appMainScript('settings_datatables.js');
		//Loader::appMainUtilities();
		//Loader::appMainLibrary('class_loader');
		
		Loader::appStyle('style.css');
		$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		$this->var['settings'] = 'current';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		//$this->login();
		
	}
	
	function index()
	{
		$this->company();
	}
	
	function company()
	{	
		Jquery::loadMainInlineValidation();	
		Jquery::loadMainModalExetend();
		//Jquery::loadMainTreeView();
		Jquery::loadAsyncTreeView();
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/company/index.php',$this->var);
		
	}

	function _load_add_structure()
	{
		if(!empty($_POST['parent_id'])){
			$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$sst 		 = G_Settings_Subdivision_Type_Finder::findByCompanyStructureId($cstructure->getId());
			$gcb		 = G_Company_Branch_Finder::findByCompanyStructureId($cstructure->getId());
			$this->var['branches'] 		   = $gcb;
			$this->var['subdivision_type'] = $sst;
			$this->var['p_id']             = $_POST['parent_id'];
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_company_structure.php',$this->var);
		}
	}
	
	function _load_add_branch()
	{
		if(!empty($_POST['parent_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
			$this->var['locations'] = $locations;
			$this->var['p_id']      = $_POST['parent_id'];
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_company_branch.php',$this->var);
		}else {
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$locations  = G_Settings_Location_Finder::findByCompanyStructureId($cstructure->getId());
			$this->var['locations'] = $locations;
			
			$this->view->noTemplate();
			$this->view->render('settings/company/forms/add_company_branch.php',$this->var);
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
		$this->var['cstructure'] = Tree_View::buildCompanyStructure();
		$this->view->noTemplate();
		$this->view->render('settings/company/company_structure.php',$this->var);
	}
	
	function _load_delete_confirmation()
	{
		if(!empty($_POST['structure_id'])){
			$c = G_Company_Structure_Finder::findById($_POST['structure_id']);
			if($c){	
				$this->var['structure_name'] = $c->getTitle();
				$this->view->noTemplate();
				$this->view->render('setti ngs/company/delete_confirmation.php',$this->var);
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
			$cinfo->save($cstructure);
			echo 'true';
		}else{echo 'false';}
	}
	
	function update_skill()
	{
		if(!empty($_POST['skill_id'])){		
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);		
			$gss       	= G_Settings_Skills_Finder::findById($_POST['skill_id']);
			$gss->setSkill($_POST['skill']);
			$gss->save($cstructure);
			echo 'true';
		}else{echo 'false';}
	}
	
	function add_skill()
	{
		if(!empty($_POST['skill'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gss = new G_Settings_Skills();
			$gss->setSkill($_POST['skill']);
			$gss->save($cstructure);
			echo 'true';
		}else{echo 'false';}
	}
	
	function add_subdivision_type()
	{	
		if(!empty($_POST['parent_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsst = new G_Settings_Subdivision_Type();
			$gsst->setType($_POST['type']);
			$gsst->save($cstructure);
			echo 'true';
		}else{echo 'false';}
		
	}
	
	function update_subdivision_type()
	{	
		if(!empty($_POST['subdivision_id'])){		
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);		
			$gsst       = G_Settings_Subdivision_Type_Finder::findById($_POST['subdivision_id']);
			$gsst->setType($_POST['type']);
			$gsst->save($cstructure);
			echo 'true';
		}else{echo 'false';}
		
	}
	
	function add_company_branch()
	{	
		if(!empty($_POST['parent_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gcb = new G_Company_Branch();	
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
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$subdivision_type   = G_Settings_Subdivision_Type_Finder::findAll($order_by,$limit);
		$total_records 		=  G_Settings_Subdivision_Type_Helper::countTotalRecords();
		
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
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

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
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

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
	
	function _load_location_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		$location		= G_Settings_Location_Finder::findAll($order_by,$limit);
		$total_records 	=  G_Settings_Location_Helper::countTotalRecords();
		
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
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

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
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

		//$employment_status		= G_Settings_Employment_Status_Finder::findAll($order_by,$limit);
		$csid = $this->company_structure_id;
		$employment_status		= G_Settings_Employment_Status_Finder::findByCompanyStructureId($csid,$order_by,$limit);
		//$total_records 			= G_Settings_Employment_Status_Helper::countTotalRecords();
		$total_records 			= G_Settings_Employment_Status_Finder::findByCompanyStructureId($csid,$order_by);
		
		foreach ($employment_status as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
			
		}
		$terminated['id'] = 0;
		$terminated['company_structure_id'] = 0;
		$terminated['code'] = 'Terminated';
		$terminated['status'] = 'Terminated';
		$array[] = $terminated;
		//print_r($array);
	
		$data = $array;
		$total = count($array);
		$total_records = count($total_records);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";			
	}
	
	function _load_skill_management_dt()
	{
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ?  $_GET['sort'] . ' ' . $_GET['dir']  :  '' ;

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
			$gcs->save();
			echo 'true';
		}else{echo 'false';}		
	}
	
	function add_relationship()
	{	
		if(!empty($_POST['relationship'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsdr       = new G_Settings_Dependent_Relationship();
			$gsdr->setCompanyStructureId($cstructure->getId());
			$gsdr->setRelationship($_POST['relationship']);				
			$gsdr->save();
			echo 'true';
		}else{echo 'false';}		
	}
	
	function add_employment_status()
	{
		if(!empty($_POST['status'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gses       = new G_Settings_Employment_Status();
			$gses->setCode($_POST['code']);
			$gses->setStatus($_POST['status']);
			$gses->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function update_employment_status()
	{
		if(!empty($_POST['employment_status_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gses        = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
			$gses->setCode($_POST['code']);
			$gses->setStatus($_POST['status']);				
			$gses->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function add_license()
	{	
		if(!empty($_POST['license_type'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsdr       = new G_Settings_License();
			$gsdr->setLicenseType($_POST['license_type']);
			$gsdr->setDescription($_POST['description']);				
			$gsdr->save($cstructure);
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
	
	function update_license()
	{	
		if(!empty($_POST['license_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsl        = G_Settings_License_Finder::findById($_POST['license_id']);
			$gsl->setLicenseType($_POST['license_type']);
			$gsl->setDescription($_POST['description']);				
			$gsl->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function add_location()
	{	
		if(!empty($_POST['location'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsl        = new G_Settings_Location();
			$location   = strtoupper($_POST['location']);
			$code		= strtoupper($_POST['code']);
			$gsl->setCode($code);
			$gsl->setLocation($location);				
			$gsl->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
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
	
	function add_membership_type()
	{	
		if(!empty($_POST['type'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsmt       = new G_Settings_Membership_Type();		
			$gsmt->setType($_POST['type']);						
			$gsmt->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function update_membership_type()
	{	
		if(!empty($_POST['membership_type_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsmt       = G_Settings_Membership_Type_Finder::findById($_POST['membership_type_id']);		
			$gsmt->setType($_POST['type']);						
			$gsmt->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function update_location()
	{	
		if(!empty($_POST['location_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsl        = G_Settings_Location_Finder::findById($_POST['location_id']);
			$location   = strtoupper($_POST['location']);
			$code		= strtoupper($_POST['code']);
			$gsl->setCode($code);
			$gsl->setLocation($location);				
			$gsl->save($cstructure);
			echo 'true';
		}else{echo 'false';}		
	}
	
	function update_relationship()
	{	
		if(!empty($_POST['dependent_id'])){
			$cstructure = G_Company_Structure_Finder::findById($this->company_structure_id);	
			$gsdr       = G_Settings_Dependent_Relationship_Finder::findById($_POST['dependent_id']);			
			$gsdr->setRelationship($_POST['relationship']);				
			$gsdr->save();
			echo 'true';
		}else{echo 'false';}
		
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
					$gcs->delete();
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
					echo 1;
				}else{echo 2;}			
		}else{echo 0;}

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
		$this->var['page_title'] = 'Settings';
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
		$this->view->noTemplate();		
		$this->view->render('settings/options/pay_period/forms/add_new_pay_period.php',$this->var);
	}
	
	function _load_add_new_location()
	{		
		$this->view->noTemplate();		
		$this->view->render('settings/options/location/forms/add_new_location.php',$this->var);
	}
	
	function _load_add_new_membership()
	{		
		$this->view->noTemplate();		
		$this->view->render('settings/options/membership_type/forms/add_new_membership.php',$this->var);
	}
	
	function _load_edit_location()
	{		
		if(!empty($_POST['location_id'])){			
			$l = G_Settings_Location_Finder::findById($_POST['location_id']);
			$this->var['l']    = $l;
			$this->view->noTemplate();		
			$this->view->render('settings/options/location/forms/edit_location.php',$this->var);
		}
	}
	
	function _load_edit_membership_type()
	{		
		if(!empty($_POST['membership_type_id'])){			
			$m = G_Settings_Membership_Type_Finder::findById($_POST['membership_type_id']);
			$this->var['m']    = $m;
			$this->view->noTemplate();		
			$this->view->render('settings/options/membership_type/forms/edit_membership_type.php',$this->var);
		}
	}
	
	function _load_edit_employment_status()
	{
		if(!empty($_POST['employment_status_id'])){			
			$es = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
			$this->var['es']    = $es;
			$this->view->noTemplate();		
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
 		$this->view->noTemplate();		
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
		if(!empty($_POST['subdivision_id'])){			
			$s    	    = G_Settings_Subdivision_Type_Finder::findById($_POST['subdivision_id']);
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
		if(!empty($_POST['dependent_id'])){			
			$d          	= G_Settings_Dependent_Relationship_Finder::findById($_POST['dependent_id']);						
			$this->var['d'] = $d;
			$this->view->noTemplate();		
			$this->view->render('settings/options/dependent_relationship/forms/edit_relationship.php',$this->var);
		}
	}
	
	function _load_edit_pay_period()
	{
		if(!empty($_POST['pay_period_id'])){			
			$pp          = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);						
			$this->var['pp'] = $pp;
			$this->view->noTemplate();		
			$this->view->render('settings/options/pay_period/forms/edit_pay_period.php',$this->var);
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
		Jquery::loadMainInlineValidation();
		Jquery::loadMainModalExetend();
		$this->var['page_title'] = 'Settings';
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
		$job           = G_Job_Finder::findByCompanyStructureId($cstructure->getId(), $order_by,$limit); //$cstructure->getId()
		$total_records = G_Job_Helper::countTotalRecordsByCompanyStructureId($cstructure);
		
		//print_r($job);
		foreach ($job as $key=> $object) { 
			$array[] = Tools::objectToArray($object);
		}		
	
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
		$g->setIsActive($_POST['is_active']);
		$g->save();
		// redirect('settings/job_title');
		echo 'true';
		}else{echo 'false';}		
		
	}
	
	function update_job_title()
	{
		if(!empty($_POST)){
		$g = G_Job_Finder::findById($_POST['id']);
		$g->setCompanyStructureId($this->company_structure_id);
		$g->setJobSpecificationId($_POST['job_specification_id']);	
		$g->setTitle($_POST['title']);		
		$g->setIsActive($_POST['is_active']);
		$g->save($g);
		// redirect('settings/job_title');
		echo 'true';
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
			$g->save();
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
	
	function update_eeo_job_category()
	{
		if(!empty($_POST)){
			$g = G_Eeo_Job_Category_Finder::findById($_POST['id']);	
			$g->setCompanyStructureId($this->company_structure_id);
			$g->setCategoryName($_POST['category_name']);	//$_POST['category_name']	
			$g->save($g);
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
	
	function job_salary_rate()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
		
		$this->view->render('settings/job/job_salaray_rate/add_salary_rate.php',$this->var);
	}
	
	function edit_job_salary_rate()
	{
		//$this->var['page_title'] = 'Settings';
		//$this->view->setTemplate('template_settings.php');
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
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
	
	function update_job_salary_rate()
	{
		if(!empty($_POST)){
			$g = G_Job_Salary_Rate_Finder::findById($_POST['id']);
			$g->setCompanyStructureId($this->company_structure_id);
			$g->setJobLevel($_POST['job_level']);	
			$g->setMinimumSalary($_POST['minimum_salary']);	
			$g->setMaximumSalary($_POST['maximum_salary']);	
			$g->setStepSalary($_POST['step_salary']);	
			$g->save($g);
			echo 'true';
		 //redirect('settings/job_specification');
		}else{
			echo 'false';
		}	
	}
		
	
	
	
	function user_management()
	{
		Yui::loadMainDatatable();
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/user_management/index.php',$this->var);
		
	}
	
	function contribution()
	{
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/contribution/index.php',$this->var);
		
	}
	
	function examination_template()
	{
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		$this->var['company_structure_id'] = $this->company_structure_id;
		
		$this->var['page_title'] = 'List of Examination';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/examination/index.php',$this->var);
		
	}
	
	function _json_encode_examination_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' . $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY id asc' ;
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$exam = G_Exam_Helper::findByCompanyStructureId($company->id,$order_by,$limit);
		foreach ($exam as $key=> $object) { 
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
		$x = new G_Exam;
		$x->setCompanyStructureId($_POST['company_structure_id']);
		$x->setTitle($_POST['title']);
		$x->setDescription($_POST['description']);
		$x->setPassingPercentage($_POST['passing_percentage']);
		$x->setCreatedBy($_POST['created_by']);
		$x->setDateCreated($_POST['date_created']);
		$new_id = $x->save();
		echo Utilities::encrypt($new_id);
		
	}
	
	function examination_details()
	{
	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Style::loadMainTableThemes();
		Loader::appMainScript('examination.js');
		
		$examination_id = Utilities::decrypt($_GET['examination_id']);
		
		$this->var['company_structure_id'] = $this->company_structure_id;
		$this->var['details'] = G_Exam_Finder::findById($examination_id);
		$this->var['questions'] = G_Exam_Question_Finder::findByExamId($examination_id);
		$this->var['page_title'] = 'Examination';
		
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/examination/details/index.php',$this->var);
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
		$gsl->setCompanyStructureId($row['company_structure_id']);
		$gsl->setTitle($row['title']);
		$gsl->setDescription($row['description']);	
		$gsl->setPassingPercentage($row['passing_percentage']);
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
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('performance.js');
		$this->var['company_structure_id'] = $this->company_structure_id;
		
		$this->var['page_title'] = 'List of Peformance';
		
		$this->var['job'] = G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/performance/index.php',$this->var);
		
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
		$this->view->noTemplate();
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
	
	function _load_user_management_dt()
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
		$this->view->noTemplate();
		$this->view->render('settings/options/request_approvers/_request_dt.php',$this->var);
	}
	
	function _load_request_approvers_dt()
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
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editRequest(id);\"></a></li><li><a title=\"Copy Settings\" id=\"edit\" class=\"ui-icon ui-icon-copy  g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:copyRequestSettings(id);\"></a></li><li><a title=\"Approvers\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"' . url('settings/approvers?hid=id') . '\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveRequestSettings(id);\"></a></li></ul></div>'));
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
		//Jquery::loadMainInlineValidation();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainModalExetend();	
		Jquery::loadMainTipsy();
		Yui::loadMainDatatable();
		//Jquery::loadMainJqueryDatatable();

		$selected = $_GET['sidebar'];
		if($selected==1 || $selected=='') {
			$this->var['subdivision_type'] = 'selected';
			$render = 'settings/options/subdivision_type/index.php';
		}elseif($selected==2){
			$this->var['dependent_relationship'] = 'selected';
			$render = 'settings/options/dependent_relationship/index.php';
		}elseif($selected==3){
			$this->var['pay_period'] = 'selected';
			$render = 'settings/options/pay_period/index.php';
		}elseif($selected==4){
			$this->var['skill_management'] = 'selected';
			$render = 'settings/options/skill_management/index.php';
		}elseif($selected==5){
			$this->var['license'] = 'selected';
			$render = 'settings/options/license/index.php';
		}elseif($selected==6){
			$this->var['location'] = 'selected';
			$render = 'settings/options/location/index.php';
		}elseif($selected==7){
			$this->var['membership_type'] = 'selected';
			$render = 'settings/options/membership_type/index.php';
		}elseif($selected==8){
			$this->var['employment_status'] = 'selected';
			$render = 'settings/options/employment_status/index.php';
		}elseif($selected==9){
			$this->var['application_status'] = 'selected';
			$render = 'settings/options/application_status/index.php';
		}elseif($selected==10){			
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			$this->var['approvers'] = 'selected';
			$render = 'settings/options/request_approvers/request.php';
		}
		
		
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render($render,$this->var);
		
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
			$this->var['employees']  = unserialize($gsr->getEmployees());
			$this->var['positions']  = unserialize($gsr->getPositions());
			$this->var['departments']= unserialize($gsr->getDepartments());
			$this->var['token']		 = Utilities::createFormToken();
			$this->var['action']	 = 'settings/_load_save_update_settings_request';
			$this->var['page_title'] = 'Settings';		
			$this->view->render('settings/options/request_approvers/forms/ajax_edit_request.php',$this->var);
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
			$approvers = G_Settings_Request_Approver_Finder::findAllBySettingsRequestId($_POST['request_id']);
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
				
				if($_POST['apply_to_all_departments']){
					$dep_ids = Settings_Request::APPLY_TO_ALL;
					$tags .= 'All Departments <br>';
				}else{
					$dep = $this->generateDepartmentArray($_POST['departments'],$_POST['type'],Utilities::decrypt($_POST['req_id']));
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
					$pos = $this->generatePositionArray($_POST['positions'],$_POST['type'],Utilities::decrypt($_POST['req_id']));
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
					$emp = $this->generateEmployeeArray($_POST['employees'],$_POST['type'],Utilities::decrypt($_POST['req_id']));
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
	
	function _load_add_request_approvers()
	{
		$this->login();
		if(Utilities::isFormTokenValid($_POST['token'])) {			
			if($_POST['request_id']){
				$gsr = G_Settings_Request_Finder::findById(Utilities::decrypt($_POST['request_id']));	
				if($gsr){
					if($_POST['apply_to_all_positions']){
						$pos  = Settings_Request::APPLY_TO_ALL;							
						$gsra = new G_Settings_Request_Approver();	
																									
						$gsra->setPositionEmployeeId($pos);
						$gsra->setType(Settings_Request_Approver::POSITION_ID);	
						$gsra->setLevel(0);	
						//$gsra->setOverrideLevel('');
						$gsra->save($gsr);
						
					}else{
						if($_POST['positions']){
							$pos_ids = explode(",",$_POST['positions']);
							foreach($pos_ids as $pos){
								$gsra = new G_Settings_Request_Approver();														
								$gsra->setPositionEmployeeId(Utilities::decrypt($pos));
								$gsra->setType(Settings_Request_Approver::POSITION_ID);	
								$gsra->setLevel(0);	
								//$gsra->setOverrideLevel('');
								$gsra->save($gsr);	
							}
						}
					}
					
					if($_POST['apply_to_all_employees']){
						$emp  = Settings_Request::APPLY_TO_ALL;							
						$gsra = new G_Settings_Request_Approver();
																				
						$gsra->setPositionEmployeeId($emp);
						$gsra->setType(Settings_Request_Approver::EMPLOYEE_ID);	
						$gsra->setLevel(0);	
						$gsra->save($gsr);	
									
					}else{
						if($_POST['employees']){
							$emp_ids = explode(",",$_POST['employees']);
							foreach($emp_ids as $emp){
								$gsra = new G_Settings_Request_Approver();														
								$gsra->setPositionEmployeeId(Utilities::decrypt($emp));
								$gsra->setType(Settings_Request_Approver::EMPLOYEE_ID);	
								$gsra->setLevel(0);	
								$gsra->save($gsr);	
							}
						}
					}
					
					$return['message']    = "Record saved";
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
	
	
	function employee_group_management(){	
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['branch'] = $branch = G_Company_Branch_Finder::findAll();
		
		$this->var['page_title'] = 'Settings';
		$this->view->setTemplate('template_settings.php');
		$this->view->render('settings/employee_group_management/index.php',$this->var);
	}
	
	function _load_department_list_dt() {
		if(!empty($_POST)) {
			$this->var['h_branch_id'] = $_POST['h_branch_id'];
			$this->view->render('settings/employee_group_management/_employee_group_list_dt.php',$this->var);
		}
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
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"'.url('settings/group_tab?id=id').'\"></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function group_tab() {
		if(!empty($_GET)) {
			$eid = $_GET['id'];
			$hash = $_GET['hash'];
			Utilities::verifyHash(Utilities::decrypt($eid),$hash);
			
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			$this->var['branch'] = $branch = G_Company_Branch_Finder::findAll();
			$this->var['h_company_structure_id'] = $_GET['id'];
			
			$this->var['page_title'] = 'Settings';
			$this->view->setTemplate('template_settings.php');
			$this->view->render('settings/employee_group_management/employee_group_tab.php',$this->var);
		}
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
	
	function _load_server_group_list_dt() {
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
		$dt->setCondition(" parent_id=".Utilities::decrypt($_GET['h_company_structure_id']));
		$dt->setColumns('title');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"View Employee Group Structure\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_child_group_list(\'e_id\');\"></a></ul></div>'));
		echo $dt->constructDataTable();
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
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Remove\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"'.url('settings/group_tab?id=id').'\"></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_insert_new_group() {
		if(!empty($_POST)) {
			
		}
	}
	
	function _load_token() {
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	

}
?>