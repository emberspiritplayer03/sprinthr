<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Recruitment_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->login();
		Loader::appMainScript('recruitment.js');
		Loader::appStyle('style.css');
		$this->var['recruitment'] = 'selected';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		
		$this->username 	= $_SESSION['sprint_hr']['username'];
		$this->module 		= 'HR RECRUITMENT';		

		Utilities::checkModulePackageAccess('hr','recruitment');
	}

	function index()
	{
		$this->candidate();	
	}
	
	function job_vacancy()
	{
		Utilities::checkModulePackageAccess('hr','job_vacancy');
		
		$module_access 		= HR;
		$sub_module_access	= array(RECRUITMENT=>"job_vacancy");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		$company_structure_id = $this->company_structure_id;
		
		$this->var['positions'] 			  = $p =  G_Job_Finder::findByCompanyStructureId2($company_structure_id);
		$this->var['company_structure_id'] = Utilities::encrypt($company_structure_id);
		$this->var['page_title'] 			  = 'Job Vacancy';
		
		$this->view->setTemplate('template_recruitment.php');
		$this->view->render('recruitment/job_vacancy/index.php',$this->var);
	}
	
	function ajax_edit_job_vacancy()
	{
		if($_POST['job_id']){
			$jv = G_Job_Vacancy_Finder::findById($_POST['job_id']);			
			if($jv){
				$company_structure_id = $this->company_structure_id;
				$this->var['jv']    = $jv;
				$this->var['token'] = Utilities::createFormToken();
				$this->var['company_structure_id'] = Utilities::encrypt($company_structure_id);
				$this->var['positions'] = 	$p =  G_Job_Finder::findByCompanyStructureId2($company_structure_id);
				$this->view->render('recruitment/job_vacancy/form/ajax_edit_job_vacancy.php',$this->var);
			}
		}
	}
	
	function _autocomplete_load_job_name()
	{

		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$sql = "
				SELECT j.id, CONCAT(j.title) as name
				FROM g_job j
				WHERE j.title LIKE '%{$q}%'
				";
			
			$records = Model::runSql($sql, true);
			
			foreach ($records as $e) {
				$response[] = array($e['id'], $e['name'], null);
			}
		}
		
		if(count($response)==0)
		{
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);	
	}
	
	function _autocomplete_load_employee_name()
	{
		
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
		$sql = "
				SELECT e.id, CONCAT(e.lastname,', ', e.firstname) as name
				FROM g_employee e
				WHERE (e.lastname LIKE '%{$q}%' OR e.firstname LIKE '%{$q}%')
				";
			
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array('id'=>$record['id'],'label'=>$record['name']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	function _autocomplete_load_applicant_name()
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
			if ($q != '') {
				$sql = "
					SELECT a.id, CONCAT(a.lastname, ', ' , a.firstname) as name
					FROM g_applicant a
					WHERE a.lastname LIKE '%{$q}%' OR  a.firstname LIKE '%{$q}%'
					LIMIT 10
					";
				
				$records = Model::runSql($sql, true);
				foreach ($records as $record) {
					$response[] = array($record['id'], $record['name'], null);
				}
			}
			if(count($response)==0)
			{
				$response = '';
			}
			header('Content-type: application/json');
			echo json_encode($response);	
	}
	
	
	function _autocomplete_load_scheduled_by()
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
			if ($q != '') {
				$sql = "
					SELECT a.id, CONCAT(a.lastname, ', ' , a.firstname) as name
					FROM g_employee a
					WHERE a.lastname LIKE '%{$q}%' OR  a.firstname LIKE '%{$q}%'
					";
				
				$records = Model::runSql($sql, true);
				foreach ($records as $record) {
					$response[] = array($record['id'], $record['name'], null);
				}
			}
			if(count($response)==0)
			{
				$response = '';
			}
			header('Content-type: application/json');
			echo json_encode($response);	
	}
	
	function _add_job_vacancy()
	{
		if(!empty($_POST['job_id'])){
			$job = G_Job_Finder::findById($_POST['job_id']);
			if($job){
				$job_title = $job->getTitle();
			}else{
				$job_title = '';
			}
			$gss = new G_Job_Vacancy;
			$gss->setJobId($_POST['job_id']);
			$gss->setJobDescription($_POST['job_description']);
			$gss->setJobTitle($job_title);
			$gss->setHiringManagerId($_POST['hiring_manager_id']);
			$gss->setHiringManagerName($_POST['hiring_manager_name']);
			$gss->setPublicationDate($_POST['publication_date']);
			$gss->setAdvertisementEnd($_POST['advertisement_end']);
			$gss->setIsActive($_POST['is_active']);
			$saved = $gss->save();
			if($saved){
				$this->triggerAudit(1,$this->username,ACTION_INSERT,$this->module.': add job vacancy, id=' . $saved);
				echo 1;	
			}else{
				$this->triggerAudit(0,$this->username,ACTION_INSERT,$this->module.': add job vacancy');
				echo 0;	
			}
		}else {
			$this->triggerAudit(0,$this->username,ACTION_INSERT,$this->module.'add job vacancy');
			echo 0;	
		}
	}
			
	function _update_job_vacancy()
	{
		Utilities::verifyFormToken($_POST['token']);		
		if(!empty($_POST['eid'])){
			$gss = G_Job_Vacancy_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gss){
				$job = G_Job_Finder::findById($_POST['job_id']);
				if($job){
					$job_title = $job->getTitle();
				}else{
					$job_title = '';
				}	
							
				$gss->setJobId($_POST['job_id']);
				$gss->setJobDescription($_POST['job_description']);
				$gss->setJobTitle($job_title);
				$gss->setHiringManagerId($_POST['hiring_manager_id']);
				$gss->setHiringManagerName($_POST['hiring_manager_name']);
				$gss->setPublicationDate($_POST['publication_date']);
				$gss->setAdvertisementEnd($_POST['advertisement_end']);				
				$saved = $gss->save();
				
				$this->triggerAudit(1,$this->username,ACTION_UPDATE,$this->module.': update job vacancy, id=' . Utilities::decrypt($_POST['eid']));
				$json['is_success'] = 1;
				$json['message']    = 'Record was successfully updated.';
				
			}else{
				$this->triggerAudit(0,$this->username,ACTION_UPDATE,$this->module.': update job vacancy, id=' . Utilities::decrypt($_POST['eid']));
				$json['is_success'] = 0;
				$json['message']    = 'Error in data entry.';
			}
		}else {
			$this->triggerAudit(0,$this->username,ACTION_UPDATE,$this->module.': update job vacancy, id=' . Utilities::decrypt($_POST['eid']));
			$json['is_success'] = 0;
			$json['message']    = 'Error in data entry.';
		}
		echo json_encode($json);
	}
	
	function _open_job_vacancy() 
	{
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": change job vacancy status to Open,id=".$_POST['id'],$_SESSION['sprint_hr']['username']);
		$gss = new G_Job_Vacancy($_POST['id']);
		$gss->open();
	}
	
	function _close_job_vacancy() 
	{
		$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": change job vacancy status to Close,id=".$_POST['id'],$_SESSION['sprint_hr']['username']);
		$gss = new G_Job_Vacancy($_POST['id']);
		$gss->close();
	}
	
	function _delete_job_vacancy()
	{
		$this->triggerAudit(1,$this->username,ACTION_DELETE,$this->module.': delete job vacancy, id=' . $_POST['id']);
		$j = new G_Job_Vacancy($_POST['id']);
		$j->delete();
		echo 1;
	}
	
	function _load_delete_job_vacancy_confirmation()
	{
		$jv = G_Job_Vacancy_Finder::findById($_POST['id']);

		$this->var['job_vacancy'] = $jv->getJobTitle();
		$this->view->noTemplate();
		$this->view->render('recruitment/job_vacancy/delete_confirmation.php',$this->var);
	
	}
	
	function _load_add_job_vacancy_form()
	{
		$this->var['positions'] =$p =  G_Job_Finder::findByCompanyStructureId2($this->company_structure_id);
		$this->view->noTemplate();
		$this->view->render('recruitment/job_vacancy/form/add_job_vacancy_form.php',$this->var);	
	}
	
	
	function _ajax_create_job_vacancy_xml() 
	{
		$gjv  = new G_Job_Vacancy();	
		$err  = $gjv->createActiveJobVacancyXMLFile(G_Job_Vacancy::xmlPATH,G_Job_Vacancy::xmlFILENAME);
		if($err == 0){
			$json['message'] = "XML File was successfully created.";
		}else{
			$json['message'] = "Error in creating XML!";
		}
		echo json_encode($json);
	}
	
	function candidate()
	{
		Utilities::checkModulePackageAccess('hr','applicant');
		
		$module_access 		= HR;
		$sub_module_access	= array(RECRUITMENT=>"candidate");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		Yui::loadMainDatatable();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		
		
		$this->var['locations']            = G_Settings_Location_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['company_structure_id'] = $company_structure_id = $this->company_structure_id;
		$this->var['positions'] = G_Job_Finder::findByCompanyStructureId2($company_structure_id);
		if($_SESSION['hr']['applicant_imported']) {
		//	$this->var['imported_button'] = " <a class='gray_button' onclick='javascript:loadImportedApplicants(".$_SESSION['hr']['applicant_imported'].");' href='#'>Load ". $_SESSION['hr']['applicant_imported']." Imported Applicant(s)</a>";		
		}
		
		$this->var['import_action'] = url('recruitment/_import_applicant_excel');
		$this->var['page_title'] = 'Candidate';
		$this->view->setTemplate('template_recruitment2.php');
		
		$this->view->render('recruitment/candidate/index.php',$this->var);
	}
	
	//import timesheet
	function _import_applicant_excel()
	{
		$file = $_FILES['applicant']['tmp_name'];
		$e = new Applicant_Import($file);
		$return = $e->import();	
	
		echo $return;
	}
	
	function _load_imported_applicant() {
		$button = " <a class='gray_button' href='javascript:void(0);' onclick='javascript:load_recently_imported_applicant(".$_SESSION['hr']['applicant_imported'].");'>Load ". $_SESSION['hr']['applicant_imported']." Imported Applicant(s)</a>";	
		echo $button;
	}	
	
	function _load_add_candidate_confirmation()
	{
		$this->var['msg'] = "Successfully Added";
		$this->view->noTemplate();
		$this->view->render('recruitment/candidate/confirmation.php',$this->var);
	}
	
	function _load_delete_applicant_history_confirmation()
	{
		$this->var['msg'] = "Are you sure you want to delete this history";
		$this->view->noTemplate();
		$this->view->render('recruitment/candidate/confirmation.php',$this->var);
	}
	
	function _upload()
	{
		Loader::appMainLibrary('class_main_jquery_file_upload');
	}
	
	function add_candidate()
	{
		if($_POST['job_id']!='') {
			
			$applicant = new G_Applicant;
			$applicant->setLastname($_POST['lastname']);
			$applicant->setFirstname($_POST['firstname']);
			$applicant->setMiddlename($_POST['middlename']);
			$a_id = $applicant->save();
			
			$hash = Utilities::createHash($a_id);
			$gss = new G_Applicant;
			$gss->setId($a_id);
			$gss->setHash($hash);
			
			$gss->setCompanyStructureId($_POST['company_structure_id']);
			$gss->setJobVacancyId('');
			$gss->setApplicationStatusId(APPLICATION_SUBMITTED);

			$gss->setJobId($_POST['job_id']);
			$gss->setLastname($_POST['lastname']);
			$gss->setFirstname($_POST['firstname']);
			$gss->setMiddlename($_POST['middlename']);
			$gss->setExtensionName($_POST['extension_name']);
			$gss->setGender($_POST['gender']);
			$gss->setMaritalStatus($_POST['marital_status']);
			$gss->setBirthdate($_POST['birthdate']);
			$gss->setBirthPlace($_POST['birth_place']);
			$gss->setAddress($_POST['address']);
			$gss->setCity($_POST['city']);
			$gss->setProvince($_POST['province']);
			$gss->setZipCode($_POST['zip_code']);
			$gss->setCountry($_POST['country']);
			$gss->setHomeTelephone($_POST['home_telephone']);
			$gss->setMobile($_POST['mobile']);
			$gss->setEmailAddress($_POST['email_address']);
			$gss->setQualification($_POST['qualification']);
			$gss->setAppliedDateTime($_POST['date_applied']);
			$gss->setSssNumber($_POST['sss_number']);
			$gss->setTinNumber($_POST['tin_number']);
			$gss->setPhilhealthNumber($_POST['philhealth_number']);
			//.$gss->setResumeName('test');
			$gss->setResumePath('test');
			$gss->save();
			
			//Create an Application Event History
			$e = new G_Job_Application_Event;
			$e->setId($row['id']);
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setApplicantid($a_id);
			$e->setDateTimeCreated(date("Y-m-d h:i:s"));
			$e->setCreatedBy($_SESSION['hr']['user_id']);
			$e->setHiringManagerId('');
			$e->setDateTimeEvent($_POST['date_applied']);
			$e->setEventType(APPLICATION_SUBMITTED);
			$e->setApplicationStatusId(APPLICATION_SUBMITTED);
			$e->setNotes($_POST['notes']);
			$e->save();
			
			//Load default requirements
			if($a_id){
				$gar = new G_Applicant_Requirements();
				$gar->setApplicantId($a_id);
				$gar->loadDefaultRequirements();
			}
			//			
			
			echo $applicant_id = Utilities::encrypt($a_id);
			$prefix='resume';
			$resume = Tools::uploadFile($_FILES,$prefix);
			if($resume['is_uploaded']=='true') {
				$row = $_POST;
				$gcb = new G_Applicant_Attachment($row['id']);
				$gcb->setApplicantId($a_id);
				$gcb->setName($_FILES['filename']['name']);
				$gcb->setFilename($resume['filename']);
				$gcb->setDescription($row['description']);
				$gcb->setSize($_FILES['filename']['size']);
				$gcb->setType($_FILES['filename']['type']);
				$gcb->setDateAttached($row['date_attached']);
				$gcb->setAddedBy($row['added_by']);
				$gcb->setScreen($row['screen']);
				$gcb->save();	
			}
		
		}
	}
	
	function _quick_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
			
			$input = $q;
			$array = array('0' => 'application submitted', '1'=>'interview','2'=>'Job Offer','3'=>'Offer Declined', '4'=> 'Rejected', '5'=> 'Hired',);
			$r = Tools::searchInArray($array,$input);
		
			if($r) {
				$x=1;
				foreach($r as $key=>$val) {
					if(count($r)>1) {$str.=(count($r)==$x)?$key:$key.',';}else {$str.= $key;}
					$x++;
				}
				$status = 'OR a.application_status_id IN ('.$str.')';
			}
			
			$sql = "
			SELECT a.id, CONCAT(a.firstname,' ', a.lastname) as name,a.photo, a.hash
			FROM `g_applicant` AS `a`
			Left Join `g_job` AS `j` ON `a`.`job_id` = `j`.`id`

			WHERE a.firstname like '%".$q."%' OR a.lastname like '%".$q."%' OR a.middlename like '%".$q."%' OR a.applied_date_time like '%".$q."%' OR j.title like '%".$q."%' ".$status."  
			GROUP BY
			`a`.`id`
			LIMIT 15
			";

			$records = Model::runSql($sql, true);
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
	
	function _load_applicant_hash() 
	{
		$e =  G_Applicant_Helper::findHashByApplicantId(Utilities::decrypt($_POST['applicant_id']));
		echo $e['hash'];
	}
	
	function _json_encode_job_vacancy_list() 
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? $_GET['sort'] . ' ' . $_GET['dir']  :  'id asc' ;
		
		$job_vacancy = G_Job_Vacancy_Finder::findByJobVacancy();
		foreach ($job_vacancy as $key=> $object) { 
			$data[] = Tools::objectToArray($object);
		}
		
		$count_total =  G_Job_Vacancy_Helper::countTotalRecords();
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _get_total_records()
	{
		Utilities::ajaxRequest();
		
		$colon_count = substr_count($_POST['searched'], ':'); 
		if($colon_count>0) {/* if has a colon*/
			$search = G_Applicant_Helper::getDynamicQueries($_POST['searched']);
		}else {
			if($_POST['searched']) {
				$input = $_POST['searched'];
				$data = array('0' => 'application submitted', '1'=>'interview','2'=>'Job Offer','3'=>'Offer Declined', '4'=> 'Rejected', '5'=> 'Hired',);
				$r = Tools::searchInArray($data,$input);
		
				if($r) {
					$x=1;
					foreach($r as $key=>$val) {
						if(count($r)>1) {
							$str.=(count($r)==$x)? $key  :  $key.',';
						}else {
							$str.= $key;
						}
						$x++;
					}	
					$status = 'OR a.application_status_id IN ('.$str.')';
				}
				//print_r($_POST);
				if($_POST['searched']) {
					$search = " AND (a.firstname like '%". $_POST['searched'] ."%' OR a.lastname like '%". $_POST['searched'] ."%' OR a.middlename like '%". $_POST['searched'] ."%' ";
					$search .= " OR a.applied_date_time like '%". $_POST['searched']."%'  ";
					$search .= "OR j.title like '%".$_POST['searched']."%'  ".$status." ) ";
				}
			}	
		}
			
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);
		if($search) {
			$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);			
			$rec = G_Applicant_Helper::countTotalRecordsBySearch($cstructure->id,$search);
		}
		echo 'Total Record(s): '.count($rec);
		
	}
	
	function _json_encode_view_all_candidate_list()
	{
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/

			$search = G_Applicant_Helper::getDynamicQueries($_GET['search']);
		}else {

			$input = $_GET['search'];
			$array = array('0' => 'application submitted', '1'=>'interview','2'=>'Job Offer','3'=>'Offer Declined', '4'=> 'Rejected', '5'=> 'Hired',);
			$r = Tools::searchInArray($array,$input);
		
			if($r) {
				$x=1;
				foreach($r as $key=>$val) {
					if(count($r)>1) {$str.=(count($r)==$x)?$key:$key.',';}else {$str.= $key;}
					$x++;
				}	
				$status = 'OR a.application_status_id IN ('.$str.')';
			}		
			$search = " AND (a.firstname like '%". $_GET['search'] ."%' OR a.lastname like '%". $_GET['search'] ."%' OR a.middlename like '%". $_GET['search'] ."%' ";
			//$search .= " OR a.applied_date_time like '%". $_GET['search']."%'  ";
			$search .= "OR j.title like '%".$_GET['search']."%'  ".$status." ) ";
		
		}
		

		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort']=='job_name') {
			$_GET['sort'] = 'a.job_id';
		}elseif($_GET['sort']=='id') {
			$_GET['sort'] = 'a.id';
		}elseif($_GET['sort']== 'application_status') {
			$_GET['sort'] = 'a.application_status_id';
		}
	
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' .  $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY a.id desc' ;
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);
		//if($_GET['search']) {
			$applicant   = G_Applicant_Helper::findByCompanyStructureId($cstructure->id,$order_by, $limit,$search);			
			$count_total = G_Applicant_Helper::countTotalRecordsBySearch($cstructure->id,$search);	
		//}
		foreach ($applicant as $key=> $object) { 
			
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$data[$key]['wrapper_id'] = $object['id'];
			if($object['photo'] == ''){
				$aid = G_Applicant_Logs_Finder::findByEmail($object['email_address']);
				if($aid){
					$app = G_Applicant_Profile_Finder::findByApplicantLogId($aid->getId());					
					if($app){
						$photo = BASE_FOLDER .'files/photo/thumb.php?src='. BASE_FOLDER.'files/photo/'.$app->getPhoto().'&w=190&h=190';	
					}else{						
						$photo = BASE_FOLDER.'images/profile_noimage.gif';
					}
				}else{
					$photo = BASE_FOLDER.'images/profile_noimage.gif';
				}
			}else{
				$photo = BASE_FOLDER .'images/thumb.php?src='. BASE_FOLDER.'files/photo/'.$object['photo'].'&w=190&h=190';
			}
			//$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  : BASE_FOLDER .'images/thumb.php?src='. BASE_FOLDER.'files/photo/'.$object['photo'].'&w=190&h=190';
			$data[$key]['photo'] = $photo;
			if($object['application_status_id']==1) {
				$count = G_Job_Application_Event_Helper::countInterview($object['id']);	
			
				$prefix = Tools::getOrdinalSuffix($count,1);
			
			}else {
				$prefix = '';	
			}
			$data[$key]['application_status'] = $prefix . ' ' .  $GLOBALS['hr']['application_status'][$object['application_status_id']];
			$data[$key]['options'] = G_Job_Application_Event_Helper::displayOptions($data[$key]['id'],$object['application_status_id'],$data[$key]['hash']);
			
		}
					
		$count_total = count($count_total);
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_candidate_list()
	{
		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/

			$search = G_Applicant_Helper::getDynamicQueries($_GET['search']);
		}else {

			$input = $_GET['search'];
			$array = array('0' => 'application submitted', '1'=>'interview','2'=>'Job Offer','3'=>'Offer Declined', '4'=> 'Rejected', '5'=> 'Hired',);
			$r = Tools::searchInArray($array,$input);
		
			if($r) {
				$x=1;
				foreach($r as $key=>$val) {
					if(count($r)>1) {$str.=(count($r)==$x)?$key:$key.',';}else {$str.= $key;}
					$x++;
				}	
				$status = 'OR a.application_status_id IN ('.$str.')';
			}		
			$search = " AND (a.firstname like '%". $_GET['search'] ."%' OR a.lastname like '%". $_GET['search'] ."%' OR a.middlename like '%". $_GET['search'] ."%' ";
			$search .= " OR a.applied_date_time like '%". $_GET['search']."%'  ";
			$search .= "OR j.title like '%".$_GET['search']."%'  ".$status." ) ";
		
		}
		

		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort']=='job_name') {
			$_GET['sort'] = 'a.job_id';
		}elseif($_GET['sort']=='id') {
			$_GET['sort'] = 'a.id';
		}elseif($_GET['sort']== 'application_status') {
			$_GET['sort'] = 'a.application_status_id';
		}
	
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' .  $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY a.id desc' ;
		$cstructure  = G_Company_Structure_Finder::findById($this->company_structure_id);
		if($_GET['search']) {
			$applicant = G_Applicant_Helper::findByCompanyStructureId($cstructure->id,$order_by, $limit,$search);
			$count_total = G_Applicant_Helper::countTotalRecordsBySearch($cstructure->id,$search);	
		}
		foreach ($applicant as $key=> $object) { 
			
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$data[$key]['wrapper_id'] = $object['id'];
			$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  : BASE_FOLDER.'files/photo/'.$object['photo'];
			$data[$key]['photo'] = $photo;
			if($object['application_status_id']==1) {
				$count = G_Job_Application_Event_Helper::countInterview($object['id']);	
			
				$prefix = Tools::getOrdinalSuffix($count,1);
			
			}else {
				$prefix = '';	
			}
			$data[$key]['application_status'] = $prefix . ' ' .  $GLOBALS['hr']['application_status'][$object['application_status_id']];
			$data[$key]['options'] = G_Job_Application_Event_Helper::displayOptions($data[$key]['id'],$object['application_status_id'],$data[$key]['hash']);
			
		}
					
		$count_total = count($count_total);
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_candidate_load_imported_applicant()
	{
		$new_imported = $_GET['load_imported'];
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		
		if($_GET['sort']=='job_name') {
			$_GET['sort'] = 'a.job_id';
		}elseif($_GET['sort']=='id') {
			$_GET['sort'] = 'a.id';
		}elseif($_GET['sort']== 'application_status') {
			$_GET['sort'] = 'a.application_status_id';
		}
	
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' .  $_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY a.id desc' ;
		
		$total_imported = $_SESSION['hr']['applicant_imported'];
		
		$cstructure  	= G_Company_Structure_Finder::findById($this->company_structure_id);
		$applicant 		= G_Applicant_Helper::findAllRecentlyImportedByCompanyStructureId($cstructure->id,$total_imported,$order_by, $limit,$search);
		
		foreach ($applicant as $key=> $object) { 
			
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$data[$key]['wrapper_id'] = $object['id'];
			$photo = ($object['photo']=='') ? BASE_FOLDER.'images/profile_noimage.gif?'  : BASE_FOLDER.'files/photo/'.$object['photo'];
			$data[$key]['photo'] = $photo;
			if($object['application_status_id']==1) {
				$count = G_Job_Application_Event_Helper::countInterview($object['id']);	
			
				$prefix = Tools::getOrdinalSuffix($count,1);
			
			}else {
				$prefix = '';	
			}
			$data[$key]['application_status'] = $prefix . ' ' .  $GLOBALS['hr']['application_status'][$object['application_status_id']];
			$data[$key]['options'] = G_Job_Application_Event_Helper::displayOptions($data[$key]['id'],$object['application_status_id'],$data[$key]['hash']);
			
		}
					
		$count_total = count($applicant);
		$total = count($data);
		$total_records =$total_imported;
		
		
			
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function examination() 
	{
		Utilities::checkModulePackageAccess('hr','examination');
		
		$module_access 		= HR;
		$sub_module_access	= array(RECRUITMENT=>"examination");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);
		$this->var['can_manage'] = $_SESSION['sprint_hr']['access_rights']['can_manage'];
	
		$this->var['token'] = Utilities::createFormToken();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		Jquery::loadMainTextBoxList();
		
		$this->var['company_structure_id'] = $this->company_structure_id;
		
		$examination = G_Exam_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['examinations'] = $examination;
		
		$this->var['page_title'] = 'Examination	';
		$this->view->setTemplate('template_recruitment3.php');
		$this->view->render('recruitment/examination/index.php',$this->var);
	}
	
	function _insert_applicant_examination()
	{		
		Utilities::verifyFormToken($_POST['token']);
		
		if($_POST['applicant_id']=='' || $_POST['scheduled_by']==''){
			echo "Please Complete the form";	
		} else {
			$app_ids  = $_POST['applicant_id'];
			$app_ids = explode(",", $app_ids);
			foreach($app_ids as $app_id) {
				$examination_id = $_POST['title'];
				$e = G_Exam_Finder::findById($examination_id);
				$a = G_Applicant_Finder::findById($app_id);
				
				$row 					   = $_POST;
				$exam_code  			= substr(md5(date("Y-m-d H:i:s")),0,7);
				$passing_percentage 	= $e->getPassingPercentage();
				$exam_title				= $e->getTitle();		
				
				$gcb = new G_Applicant_Examination();
				$gcb->setCompanyStructureId($row['company_structure_id']);
				$gcb->setExamCode($exam_code);
				$gcb->setApplicantId($app_id);
				$gcb->setExamId($e->getId());
				$gcb->setTitle($e->getTitle());
				$gcb->setDescription($e->getDescription());
				$gcb->setPassingPercentage($passing_percentage);
				$gcb->setScheduleDate($row['schedule_date']);
				$gcb->setStatus('Pending');
				$gcb->setScheduledBy($row['scheduled_by']);
				$saved = $gcb->save();
				
				if($saved){
					$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": add applicant examination,id=". $saved);	
				}else{
					$this->triggerAuditTrail(0,ACTION_INSERT,$this->module.": add applicant examination:".ERROR_INSERT);
				}
				
				if(APPLICANT_EXAMINATION_SEND_EMAIL == true) {
					$toSend = $a->getEmailAddress();
					$email  = new Sprint_Email();
					$email->setFrom("hr@sprinthr.com");
					$email->setTo($toSend);
					
					$examination_details[1]['code'] 						= $exam_code;
					$examination_details[1]['title']						= $exam_title;
					$examination_details[1]['passing_percentage']	= $passing_percentage;
					$applicant['name'] 									= $a->getLastName() . ', ' . $a->getFirstName();
										
					$email->setSubject("[SprintHR] Applicant Examination");
					$email->applicantExaminationMessageBodyEmail($examination_details,$applicant);						
					$email->applicantExaminationEmail();
				}					
			}	
			echo 1;	
		}
				
	}
	
	function _load_add_applicant_examination_form()
	{
		$this->var['token'] = Utilities::createFormToken();
		$examination = G_Exam_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['examinations'] = $examination;
		
		$this->view->noTemplate();
		$this->view->render('recruitment/examination/form/examination_add.php',$this->var);	
	}
	
	function _load_examination_description()
	{
		$exam = G_Exam_Finder::findById($_POST['examination_id']);
		echo $exam->description;
	}
	
	function _load_examination_percentage()
	{
		$exam = G_Exam_Finder::findById($_POST['examination_id']);
		echo $exam->passing_percentage;

	}
	
	function _examination_checking()
	{
		
		
		$exam = G_Exam_Finder::findById($_POST['exam_id']);
		$applicant = G_Applicant_Examination_Finder::findById($_POST['applicant_examination_id']);
		$question = G_Exam_Question_Finder::findByExamId($exam->getId());
		//echo "<pre>";
		//print_r($exam);
		//print_r($applicant);
			header("Content-Type:text/xml");
			$xml = new Xml;
			$ob->examination->applicant_examination_id = $_POST['applicant_examination_id'];
			$ob->examination->exam_id = $_POST['exam_id'];
			$ob->examination->title = $exam->title;;
			$ob->examination->description = $exam->description;
			$ob->examination->passing_percentage = $exam->passing_percentage;
			$ob->examination->exam_code = $applicant->exam_code;
			$ob->examination->schedule_date = $applicant->schedule_date;
			$ob->examination->status= 'Need to be checked';
			$ob->examination->result = '';
			$e = G_Employee_Finder::findById($applicant->scheduled_by);
			$ob->examination->scheduled_by = $e->lastname. ', ' . $e->firstname ;
			$ctr=1;
			$correct=0;
			$for_checking=0;
			$incorrect=0;
			$total=0;
			foreach($question as $key=>$val) {
				//echo $val;
				//echo "<br>";
				
				$var = 'question_'.$ctr;
				$ob->$var->id=$val->id;
				$ob->$var->question =$val->question;
				$ob->$var->answer=$val->answer;
				$ob->$var->user_answer=stripslashes($_POST['answer_'.$val->id]); 
				$ob->$var->type=$val->type; 
				echo "your answer " . stripslashes($_POST['answer_'.$val->id]);
				echo "<pre>";
				echo "correct answer " . $val->answer;
				
				if($_POST['rechecked_'.$val->id]){
					$ob->$var->result=$_POST['rechecked_'.$val->id];
					if($_POST['rechecked_'.$val->id]=='correct') {
						$correct++;	
					}else if($_POST['rechecked_'.$val->id]=='incorrect') {
						$incorrect++;	
					}
					
				}else {
					if(strtolower($val->answer)==strtolower(stripslashes($_POST['answer_'.$val->id]))) {
						$ob->$var->result="correct"; 
						$correct++;	
					}else {
						$ob->$var->result="incorrect";
						$incorrect++; 		
					}	
				}
				$ctr++;
				$total++;

			}
			//print_r($obj);

			$xml->setNode('questions');
			//----test object----
			$xmlObj =  $xml->toXml($ob);
			//$xmlStr = simplexml_load_string($xmlObj);
			
			//$xml2 = new Xml;
			//$arrXml = $xml2->objectsIntoArray($xmlStr);
			//echo "<pre>";
			//print_r($arrXml);	
			$ini = $correct/$total;
			$grade = $ini * 100;
			$result = $correct .'/'.$total .'('.$grade.'%)';
			$applicant->setQuestions($xmlObj);
			echo "Average: ". $grade;
			echo "Passing Percentage: ".$examination->passing_percentage;
			if($grade>=$exam->passing_percentage) {
				$status = 'Passed';	
			}else {
				$status = 'Failed';	
			}
			$applicant->setStatus($status);
			//$applicant->setStatus('Pending');
			$applicant->setResult($result);
			$applicant->save();
			echo 1;	
	}
	
	function _json_encode_applicant_examination_list()
	{
		if($_GET['date']=='today'){
			$date = 'AND e.schedule_date="'.date("Y-m-d").'"';	
		}else if($_GET['date']=='next_week'){
			$date = 'AND e.schedule_date BETWEEN CURDATE()+ INTERVAL 1 DAY AND CURDATE() + INTERVAL 7 DAY';
			
		}if($_GET['date']=='last_week'){
			 $date = 'AND e.schedule_date>= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
						AND e.schedule_date < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY';
		}if($_GET['date']=='all'){
			$date = '';	
			$order_by = 'ORDER BY e.schedule_date desc' ;
		}else {
			$order_by = 'ORDER BY e.schedule_date asc' ;
		}
		
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		//$order_by = ($_GET['sort'] != '') ? 'ORDER BY '.$_GET['sort'] . ' ' . $_GET['dir']  :  'ORDER BY e.schedule_date asc' ;
		
		
		$company = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$exam = G_Applicant_Examination_Helper::findByCompanyStructureId($company->id,$order_by,$limit,$date);
		foreach($exam as $key=>$value) {
			unset($exam[$key]['questions']);
			$exam[$key]['hash'] = Utilities::encrypt($value['id']);
		}
		$data = $exam;
				
		$data2 =  G_Applicant_Examination_Helper::findByCompanyStructureId($company->id,$order_by,'',$date);
		$total = count($data);
		$total_records =count($data2);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	
	function _load_photo_frame() 
	{
		Utilities::ajaxRequest();
		$applicant_id =  Utilities::decrypt($_POST['applicant_id']);
		$e = G_Applicant_Finder::findById($applicant_id);
		if($e) {
			if($e->getPhoto() == '') {
				$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_POST['applicant_id']));
				if($p_photo){
					$a_profile_photo = $p_photo->getPhoto();
				}else{
					$a_profile_photo = '';					
				}
			} else {
				$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_POST['applicant_id']));
				if($p_photo) {
					if($p_photo->getPhoto() != '') {
						$a_profile_photo = $p_photo->getPhoto();
					}else{
						$a_profile_photo = $e->getPhoto();	
					}
				}else{
					$a_profile_photo = '';				
				}	
			}			
		}else{
			$p_photo = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($_POST['applicant_id']));
			$a_profile_photo = $p_photo->getPhoto();		
		}		
		
		//$a_profile_photo = $e->getPhoto();

		$this->var['applicant'] = $applicant;
		
		$file 			= PHOTO_FOLDER . $a_profile_photo;

		$file_from_recruitment  = RECRUITMENT_BASE_FOLDER . 'files/photo/' .$a_profile_photo;

		if(Tools::isFileExist($file)==1 && $a_profile_photo!='') {
			$this->var['filemtime'] = md5($a_profile_photo).date("His");
			$this->var['filename']  = $file;
			
		}elseif(Tools::isFileExist($file_from_recruitment)==1 && $a_profile_photo!=''){
			$this->var['filemtime'] = md5($a_profile_photo).date("His");
			$this->var['filename']  = $file_from_recruitment;			
		}else{
			$this->var['filename']  = RECRUITMENT_BASE_FOLDER . 'images/profile_noimage.gif';
		}
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/photo/photo_frame.php',$this->var);
	}
	

	
	function _get_photo_filename()
	{
		$applicant_id =  Utilities::decrypt($_POST['applicant_id']);
		$a = G_Applicant_Finder::findById($applicant_id);
		$this->var['filename'] = $a->getPhoto();
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/photo/filename.php',$this->var);
	}
	
	function _load_photo()
	{
		Utilities::ajaxRequest();
		
		
		
		$applicant_id =  Utilities::decrypt($_POST['applicant_id']);	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['applicant'] = $e;
		
		if($e){		
			$epl = G_Applicant_Logs_Finder::findByEmail($e->getEmailAddress());
			if($epl) {
				$e_p = G_Applicant_Profile_Finder::findByApplicantLogId($epl->getId());
				
				$file = PHOTO_FOLDER .$e_p->getPhoto();
				
				if(Tools::isFileExist($file)==true && $e_p->getPhoto()!='') {
					$this->var['filemtime'] = md5($e_p->getPhoto()).date("His");
					$this->var['filename'] = $file;
				}else {
					$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
				}
			}else{
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';	
			}
		}else{
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
		
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/photo/index.php',$this->var);	
	}	
	
	function _load_photoxx()
	{
		Utilities::ajaxRequest();
	
		$applicant_id =  Utilities::decrypt($_POST['applicant_id']);
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['applicant'] = $e;
		
		$file 						= PHOTO_FOLDER.$e->getPhoto();
		$file_from_recruitment  = RECRUITMENT_BASE_FOLDER . 'files/photo/' .$e->getPhoto();

		if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;
		}elseif(Tools::isFileExist($file_from_recruitment)==1 && $e->getPhoto()!=''){
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file_from_recruitment;			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
		
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/photo/index.php',$this->var);	
	}
	
	function _upload_photo()
	{
		$prefix = 'applicant_';
		$applicant_id =  $_POST['applicant_id'];
		
		$em = G_Applicant_Helper::findByApplicantId($applicant_id);
		
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
	         
			  	$e = G_Applicant_Finder::findById($applicant_id);
				$image = $filename . strtolower($extension_name); 
				
				$e->setPhoto($image);
				$e->save();
				
				// saving of profile image in g_applicant_profile			
					$epl = G_Applicant_Logs_Finder::findByEmail($e->getEmailAddress());
					if($epl) {
						$ep = G_Applicant_Profile_Finder::findByApplicantLogId($epl->getId());
						$ep->setPhoto($image);
						$ep->save();
					}
				//end

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
	
	function load_summary_photo()
	{
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
		$e = G_Applicant_Finder::findById($applicant_id);


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
	
	function _load_applicant_summary()
	{
		$applicant_id =  Utilities::decrypt($_POST['applicant_id']);
		$this->var['applicant_details'] = G_Applicant_Helper::findByApplicantId($applicant_id);
		$req = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);
	
		if($req) {
			if($req->is_complete==1) {
				$this->var['requirements'] = 'Complete';		
			}else {
				$this->var['requirements'] = 'Incomplete';
			}
		}else {
			$this->var['requirements'] = '';
		}
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/applicant_summary.php',$this->var);
	}
	
	function errorHandler()
	{
		echo "error";	
	}
	
	
	function profile() 
	{		
		Utilities::checkModulePackageAccess('hr','applicant');
		
		//error_reporting(0);
		$rid = $_GET['rid'];
		$hash = $_GET['hash'];
		$did = Utilities::decrypt($rid);
		
		$applicant = G_Applicant_Finder::findById(Utilities::decrypt($rid));		
		Utilities::verifyObject($applicant);
		
		//Utilities::verifyHash(Utilities::decrypt($rid),$hash);
		//Style::loadMainTableThemes();
		Loader::appMainScript('applicant_profile.js');
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		
		$req = G_Applicant_Requirements_Finder::findByApplicantId(Utilities::decrypt($rid));
		
		//////////////////////
	
		$applicant_id =  Utilities::decrypt($rid);	
		$e = G_Applicant_Finder::findById($applicant_id);
		if($e){		
			$epl = G_Applicant_Logs_Finder::findByEmail($e->getEmailAddress());
			if($epl) {
				$e_p = G_Applicant_Profile_Finder::findByApplicantLogId($epl->getId());			
				$file = PHOTO_FOLDER .$e_p->getPhoto();
				
				if(Tools::isFileExist($file)==true && $e_p->getPhoto()!='') {
					$this->var['filemtime2'] = md5($e_p->getPhoto()).date("His");
					$this->var['filename2'] = $file;
				}else {
					$this->var['filename2'] = BASE_FOLDER. 'images/profile_noimage.gif';
				}
			}else{
				$this->var['filename2'] = BASE_FOLDER. 'images/profile_noimage.gif';	
			}
		}else{
			$this->var['filename2'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
	
		/////////////////////				
		
		if($req) {
			if($req->is_complete==1) {
				$this->var['requirements'] = 'Complete';		
			}else {
				$this->var['requirements'] = 'Incomplete';
			}
		}else {
			$this->var['requirements'] = '';
		}
		//$is_hired = G_Job_Application_Event_Finder::
				
		$this->var['is_hired'] = '';		
		
		$this->var['applicant_id'] = $rid;
		$this->var['applicant_details'] = $details = G_Applicant_Helper::findByApplicantId(Utilities::decrypt($rid));
						
		$this->var['page_title'] = 'Profile	';
		$this->view->setTemplate('template_applicant.php');
		$this->view->render('recruitment/profile/index.php',$this->var);
	}
	
	function _load_personal_details()
	{
		Utilities::ajaxRequest();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
				
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$j = G_Job_Finder::findById($e->job_id);
		if($j){
			$this->var['job_name'] = $j->getTitle();
		}else{
			$this->var['job_name'] = '';
		}
		
		$this->load_summary_photo_in_applicant_profile();
		
		$this->var['locations'] = G_Settings_Location_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['title'] = "Personal Details";
		$this->var['title_personal_details'] = "Personal Details";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/personal_details/index.php',$this->var);
	}
	
	function _load_application_history()
	{
		Utilities::ajaxRequest();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Job_Application_Event_Finder::findByApplicantId($applicant_id);

		$this->var['details'] = $e;
		
		$this->load_summary_photo();
		

		$this->var['title'] = "Application History";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/application_history/index.php',$this->var);	
	}
	
	function _load_application_history_edit_form()
	{
		$this->var['token'] = Utilities::createFormToken();
		
		$application_history_id = (int) $_POST['application_history_id'];
		$history = G_Job_Application_Event_Finder::findById($application_history_id);
		$this->var['a'] = G_Employee_Finder::findById($history->getHiringManagerId());
		if($history->getEventType()==APPLICATION_SUBMITTED) {
			$this->var['title']= 'Application Submitted';
			$views = 'application_submitted/index.php';	
		}elseif($history->getEventType()==INTERVIEW) {
			$duration = 30;
			$start = 600;
			$end = 1700;
			
			$this->var['title']= 'Interview';
			$this->var['time'] = Date::loadTimeWithDuration($duration,$start,$end);
			$views = 'interview/index.php';	
		}elseif($history->getEventType()==JOB_OFFERED) {
			$this->var['title'] = 'Offer Job';
			$views = 'offer_job/index.php';	
		}elseif($history->getEventType()==OFFER_DECLINED) {
			$this->var['title'] = 'Offer Declined';
			$views = 'offer_decline/index.php';	
		}elseif($history->getEventType()==REJECTED) {
			$this->var['title'] = 'Rejected';
			$views = 'reject/index.php';	
		}elseif($history->getEventType()==HIRED) {
			
			$views = 'hire/index.php';	
		}
		
		$this->var['history'] = $history;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/application_history/form/'.$views,$this->var);	
	}
	
	function _delete_application_history()
	{
		Utilities::ajaxRequest();
		$history = G_Job_Application_Event_Finder::findById(Utilities::decrypt($_POST['h_id']));
		$history->delete();
		echo 1;
	}
	
	function _update_application_submission_date()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			$a = G_Job_Application_Event_Finder::findById($_POST['application_history_id']);
				
			$h = new G_Job_Application_Event;
			$h->setId($_POST['application_history_id']);
			$h->setCompanyStructureId($a->getCompanyStructureId());
			$h->setApplicantId($a->getApplicantId());
			$h->setDateTimeCreated(date("Y-m-d"));
			$h->setDateTimeEvent($_POST['date_submitted']);
			$h->setEventType(APPLICATION_SUBMITTED);
			$h->setApplicationStatusId($a->getApplicationStatusId());
			$h->setNotes($_POST['notes']);
			$h->setRemarks($a->getRemarks());
			$h->save();
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant history, id=".$_POST['application_history_id']);		
			echo 1;	
		}
	}
	
	
	function _update_application_interview()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			if($_POST['hiring_manager_id']!='') {
				$application_history_id = (int) $_POST['application_history_id'];
				$a = G_Job_Application_Event_Finder::findById($application_history_id);
						
				$h = new G_Job_Application_Event;
				$h->setId($application_history_id);
				$h->setCompanyStructureId($a->getCompanyStructureId());
				$h->setApplicantId($a->getApplicantId());
				$h->setDateTimeCreated(date("Y-m-d"));
				$h->setDateTimeEvent($_POST['date_time_event']. " " . $_POST['time']);
				$h->setEventType(INTERVIEW);
				$h->setApplicationStatusId($a->getApplicationStatusId());
				$h->setNotes($_POST['notes']);
				$h->setHiringManagerId($_POST['hiring_manager_id']);
				$h->setRemarks($a->getRemarks());
				$h->save();		
				echo 1;
			}else {
				echo "Please Fill Up the Form Completely";	
			}
		}
	}
	
	function _update_applicant_job_offer()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			if($_POST['hiring_manager_id']!='') {
				$application_history_id = (int) $_POST['application_history_id'];
				$a = G_Job_Application_Event_Finder::findById($application_history_id);
					
				$h = new G_Job_Application_Event;
				$h->setId($application_history_id);
				$h->setCompanyStructureId($a->getCompanyStructureId());
				$h->setApplicantId($a->getApplicantId());
				$h->setDateTimeCreated(date("Y-m-d"));
				$h->setDateTimeEvent($_POST['date_time_event']);
				$h->setEventType(JOB_OFFERED);
				$h->setApplicationStatusId($a->getApplicationStatusId());
				$h->setNotes($_POST['notes']);
				$h->setHiringManagerId($_POST['hiring_manager_id']);
				$h->setRemarks($a->getRemarks());
				$h->save();		
				echo 1;
			}else {
				echo "Please Fill Up the Form Completely";	
			}
		}
	}
	
	function _update_application_offer_decline()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			
			if($_POST['hiring_manager_id']!='') {
				$application_history_id = (int) $_POST['application_history_id'];
				$a = G_Job_Application_Event_Finder::findById($application_history_id);
						
					$h = new G_Job_Application_Event;
					$h->setId($application_history_id);
					$h->setCompanyStructureId($a->getCompanyStructureId());
					$h->setApplicantId($a->getApplicantId());
					$h->setDateTimeCreated(date("Y-m-d"));
					$h->setDateTimeEvent($_POST['date_time_event']);
					$h->setEventType(OFFER_DECLINED);
					$h->setApplicationStatusId($a->getApplicationStatusId());
					$h->setNotes($_POST['notes']);
					$h->setHiringManagerId($_POST['hiring_manager_id']);
					$h->setRemarks($a->getRemarks());
					$h->save();		
					echo 1;
			}else {
				echo "Please Fill Up the Form Completely";	
			}
		
		}
	}
	
	function _update_application_rejected()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			
			if($_POST['hiring_manager_id']!='') {
				$application_history_id = (int) $_POST['application_history_id'];
				$a = G_Job_Application_Event_Finder::findById($application_history_id);
						
					$h = new G_Job_Application_Event;
					$h->setId($application_history_id);
					$h->setCompanyStructureId($a->getCompanyStructureId());
					$h->setApplicantId($a->getApplicantId());
					$h->setDateTimeCreated(date("Y-m-d"));
					$h->setDateTimeEvent($_POST['date_time_event']);
					$h->setEventType(REJECTED);
					$h->setApplicationStatusId($a->getApplicationStatusId());
					$h->setNotes($_POST['notes']);
					$h->setHiringManagerId($_POST['hiring_manager_id']);
					$h->setRemarks($a->getRemarks());
					$h->save();		
					echo 1;
			}else {
				echo "Please Fill Up the Form Completely";	
			}
		
		}
	}
	
	function _update_applicant_hired()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
			exit;	
		}else {
			
			if($_POST['hiring_manager_id']!='') {
				$application_history_id = (int) $_POST['application_history_id'];
				$a = G_Job_Application_Event_Finder::findById($application_history_id);
						
					$h = new G_Job_Application_Event;
					$h->setId($application_history_id);
					$h->setCompanyStructureId($a->getCompanyStructureId());
					$h->setApplicantId($a->getApplicantId());
					$h->setDateTimeCreated(date("Y-m-d"));
					$h->setDateTimeEvent($_POST['hired_date']);
					$h->setEventType(HIRED);
					$h->setApplicationStatusId($a->getApplicationStatusId());
					$h->setNotes($_POST['notes']);
					$h->setHiringManagerId($_POST['hiring_manager_id']);
					$h->setRemarks($a->getRemarks());
					$h->save();	
					
					//update the hiring date
					$a = G_Applicant_Finder::findById($a->getApplicantId());
					$a->setHiredDate($_POST['hired_date']);
					$a->save();
					$e = G_Employee_Finder::findById($a->getEmployeeId());
					$e->setHiredDate($_POST['hired_date']);
					$e->save();
					
					echo 1;
			}else {
				echo "Please Fill Up the Form Completely";	
			}
		
		}
	}
	
	function _load_contact_details()
	{
		Utilities::ajaxRequest();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$this->load_summary_photo();

		$this->var['title'] = "Contact Details";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/contact_details/index.php',$this->var);
	}
	
	//requirements	
	
	function _load_requirements() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Requirements_Finder::findByApplicantId(Utilities::decrypt($applicant_id));

	
		$data[] = unserialize($e->requirements);	
		
		//echo "<pre>";
		//print_r($data);
		$this->var['requirements'] = $data;
		
		$this->load_summary_photo();
		
		$this->var['applicant_id'] = $applicant_id;
	
		$this->var['title'] = "Requirements";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/requirements/index.php',$this->var);
	}
	
	function _load_requirements_edit_form()
	{
		$requirement_id = $_POST['requirement_id'];
		$e = G_Applicant_Requirements_Finder::findById($requirement_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/requirements/form/requirements_edit.php',$this->var);
	}
	
	function _add_default_requirements_deprecated()
	{
		
		$applicant_id = Utilities::decrypt($_POST['applicant_id']);
		$req = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);
		
		//requirements from file
		$file = BASE_FOLDER. 'files/xml/requirements.xml';
		
		if(Tools::isFileExist($file)==true) {
			$requirements = Requirements::getDefaultRequirements();	
		}else {
			foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
				$requirements[Tools::friendlyFormName($key)] = '';
			}	
		}
		
		$gss = new G_Applicant_Requirements;
		$gss->setId($req->id);
		$gss->setApplicantId($applicant_id);
		$gss->setRequirements(serialize($requirements));
		$gss->setIsComplete(0);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();
		echo 1;
	}
	
	function _add_default_requirements()
	{
		
		$applicant_id = Utilities::decrypt($_POST['applicant_id']);
		$req			  = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);
		$r = new G_Applicant_Requirements;
		$r->setId($req->id);
		$r->setApplicantId($applicant_id);		
		$r->loadDefaultRequirements();
		
		echo 1;
	}
	
	function _delete_requirements()
	{
		$applicant_id= Utilities::decrypt($_POST['applicant_id']);
		$r = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);
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
		$gss = new G_Applicant_Requirements;
		$gss->setId($r->id);
		$gss->setApplicantId($applicant_id);
		$gss->setRequirements($requirements);
		$gss->setIsComplete($is_complete);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": delete applicant requirements");
		
		echo 1;
	}
	
	
	//end of requirements
	
	function _load_examination()
	{
		Utilities::ajaxRequest();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$examination = G_Applicant_Examination_Finder::findByApplicantId($applicant_id);
		
		$this->var['examination'] = $examination;
	
		$this->load_summary_photo();

		$this->var['title'] = "Examination";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/examination/index.php',$this->var);
	}
	
	function examination_details()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		
		$applicant_examination_id = Utilities::decrypt($_GET['examination']);
		
		$examination = G_Applicant_Examination_Finder::findById($applicant_examination_id);
		if($examination) {
			//echo "<pre>";
			//print_r($examination);
			$this->var['examination'] = $examination;
			$e        = G_Applicant_Finder::findById($examination->applicant_id);
			$question = G_Exam_Question_Finder::findByExamId($examination->exam_id);
			
			$file = PHOTO_FOLDER.$e->getPhoto();

			if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
				$this->var['filemtime'] = md5($e->getPhoto()).date("His");
				$this->var['filename'] = $file;
				
			}else {				
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
				
			}
			
			$this->var['applicant_examination_id'] = $applicant_examination_id;
			$this->var['applicant'] = $e;
			$this->var['exam_id'] = $examination->exam_id;
			$this->var['q'] = $question;
			$this->var['valid'] = true;
			$this->var['exam'] = $examination;
			
			$xmlStr = simplexml_load_string($examination->questions);
			$xml2 = new Xml;
			$this->var['arr_xml'] =$xm =  $xml2->objectsIntoArray($xmlStr);
		
			$this->var['total_questions'] =  G_Exam_Question_Helper::countTotalRecordsByExamId($examination->exam_id);
		}else {
			Utilities::error500();
		}
		
		$this->var['page_title'] = 'Examination	';
		$this->view->setTemplate('template.php');
		$this->view->render('recruitment/examination/details.php',$this->var);
	}
	
	function examination_summary()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		
		$applicant_examination_id = Utilities::decrypt($_GET['examination']);
		
		$examination = G_Applicant_Examination_Finder::findById($applicant_examination_id);
		if($examination) {
			//echo "<pre>";
			//print_r($examination);
			$this->var['examination'] = $examination;
			
			$question = G_Exam_Question_Finder::findByExamId($examination->exam_id);
			$this->var['applicant_examination_id'] = $applicant_examination_id;
			$this->var['applicant'] = G_Applicant_Finder::findById($examination->applicant_id);
			$this->var['exam_id'] = $examination->exam_id;
			$this->var['q'] = $question;
			$this->var['valid'] = true;
			$this->var['exam'] = $examination;
			
			$xmlStr = simplexml_load_string($examination->questions);
			$xml2 = new Xml;
			$this->var['arr_xml'] =$xm =  $xml2->objectsIntoArray($xmlStr);
		
			$this->var['total_questions'] =  G_Exam_Question_Helper::countTotalRecordsByExamId($examination->exam_id);
		}else {
			Utilities::error500();
		}
		
		$this->var['page_title'] = 'Examination	';
		$this->view->setTemplate('template.php');
		$this->view->render('recruitment/examination/summary/index.php',$this->var);
	}
	
	function verify_examination()
	{
		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('examination.js');
		
		$applicant_examination_id = Utilities::decrypt($_GET['examination']);
		
		$examination = G_Applicant_Examination_Finder::findById($applicant_examination_id);
		if($examination) {
			//echo "<pre>";
			//print_r($examination);
			$this->var['examination'] = $examination;
			
			$question = G_Exam_Question_Finder::findByExamId($examination->exam_id);
			$this->var['applicant_examination_id'] = $applicant_examination_id;
			$this->var['applicant'] = G_Applicant_Finder::findById($examination->applicant_id);
			$this->var['exam_id'] = $examination->exam_id;
			$this->var['q'] = $question;
			$this->var['valid'] = true;
			$this->var['exam'] = $examination;
			
			$xmlStr = simplexml_load_string($examination->questions);
			$xml2 = new Xml;
			$this->var['arr_xml'] =$xm =  $xml2->objectsIntoArray($xmlStr);
		
			$this->var['total_questions'] =  G_Exam_Question_Helper::countTotalRecordsByExamId($examination->exam_id);
		}else {
			Utilities::error500();
		}
		
		$this->var['page_title'] = 'Examination	';
		$this->view->setTemplate('template.php');
		$this->view->render('recruitment/examination/form/summary.php',$this->var);
	}

	function _update_applicant_examination()
	{		
		if($_POST['schedule_date']) {
			$e = G_Applicant_Examination_Finder::findById($_POST['applicant_examination_id']);
			$e->setScheduleDate($_POST['schedule_date']);
			$e->save();
		}
	}
	
	function _cancel_examination()
	{		
		if($_POST['examination_id']) {
			$e = G_Applicant_Examination_Finder::findById($_POST['examination_id']);			
			$e->cancel();
		}
	}
	
	function _load_examination_edit_form()
	{
		$examination_id = $_POST['examination_id'];
		$e = G_Applicant_Examination_Finder::findById($examination_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/examination/form/examination_edit.php',$this->var);
	}
	
	function _delete_examination()
	{
		Utilities::ajaxRequest();
		$examination_id = $_POST['examination_id'];
		$e = G_Applicant_Examination_Finder::findById($examination_id);
		$e->delete();
		echo 1;
	}
	
	//end of examination
	
	function _load_interview()
	{
		Utilities::ajaxRequest();
		$this->var['token'] = Utilities::createFormToken();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);

		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$duration = 30;
		$start = 600;
		$end = 1700;
		
		$this->var['time'] = Date::loadTimeWithDuration($duration,$start,$end);

		$this->load_summary_photo();

		$this->var['title'] = "Interview Form";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/interview/index.php',$this->var);
	}

	
	function _load_offer_job()
	{
		Utilities::ajaxRequest();
		$this->var['token'] = Utilities::createFormToken();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$this->load_summary_photo();

		$this->var['title'] = "Offer a Job Form";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/offer_job/index.php',$this->var);
	}
	
	function _load_rejected()
	{
		Utilities::ajaxRequest();
		$this->var['token'] = Utilities::createFormToken();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$this->load_summary_photo();

		$this->var['title'] = "Failed Form";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/reject/index.php',$this->var);
	}
	
	function _load_hired()
	{
		Utilities::ajaxRequest();
		$this->var['token'] = Utilities::createFormToken();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$company_structure_id = $this->company_structure_id;
		
		$cs = G_Company_Structure_Finder::findById($company_structure_id);
		$this->var['branches'] = $branches = G_Company_Branch_Finder::findByCompanyStructureId($cs->getId()); 
		$this->var['locations']  = G_Settings_Location_Finder::findByCompanyStructureId($company_structure_id);
		$this->var['positions'] = G_Job_Finder::findByCompanyStructureId2($company_structure_id);
		$this->var['employement_status'] = G_Settings_Employment_Status_Finder::findByCompanyStructureId($company_structure_id);
		
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['pay_period'] = $pay_period;
		$this->var['rate'] = $rate;
		
		$this->load_summary_photo();

		$this->var['title'] = "Hire Form";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/hire/index.php',$this->var);
	}
	
	function _load_minimum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo $salary->minimum_salary;
	}
	
	function _load_maximum_rate() {
		$salary = G_Job_Salary_Rate_Finder::findById($_POST['job_salary_rate_id']);
		echo $salary->maximum_salary;
	}
	
	function _load_department_dropdown()
	{
		sleep(1);
		$branch_id =  $_POST['branch_id'];
		$this->var['departments'] = G_Company_Structure_Finder::findParentChildByBranchId($branch_id);
		
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/hire/include/department_dropdown.php',$this->var);	
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
		$this->view->render('recruitment/profile/event/form/hire/include/status_dropdown.php',$this->var);
	}
	
	function _load_declined_offer()
	{
		Utilities::ajaxRequest();
		$this->var['token'] = Utilities::createFormToken();
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);
	
		$e = G_Applicant_Finder::findById($applicant_id);
		$this->var['details'] = $e;
		$job = G_Job_Finder::findByCompanyStructureId($e->company_structure_id);
		$this->var['job'] = $job;
		
		$this->load_summary_photo();

		$this->var['title'] = "Decline Offer";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/event/form/offer_decline/index.php',$this->var);
	}
	
	function _update_applicant_event()
	{
		if(Utilities::isFormTokenValid($_POST['token'])!=1) {
			echo "Form Expired. Please Refresh";
		}else if($_POST['hiring_manager_id']==''){
			echo "Please Complete the form";	
		}else {
			$applicant_id = $_POST['applicant_id'];
			$es = G_Applicant_Finder::findById(Utilities::decrypt($applicant_id));
			$hash = Utilities::createHash(Utilities::decrypt($applicant_id));
			// Update applicant status
			$gss = new G_Applicant;
			$gss->setId($es->getId());
			$gss->setHash($hash);
			$gss->setCompanyStructureId($this->company_structure_id);
			$gss->setJobVacancyId($es->job_vacancy_id);
			$gss->setApplicationStatusId($_POST['application_status']);
			$gss->setPhoto($es->photo);
			$gss->setJobId($es->job_id);
			$gss->setLastname($es->lastname);
			$gss->setFirstname($es->firstname);
			$gss->setMiddlename($es->middlename);
			$gss->setExtensionName($es->extenstion_name);
			$gss->setGender($es->gender);
			$gss->setMaritalStatus($es->marital_status);
			$gss->setBirthdate($es->birthdate);
			$gss->setBirthPlace($es->birth_place);
			$gss->setAddress($es->address);
			$gss->setCity($es->city);
			$gss->setProvince($es->province);
			$gss->setZipCode($es->zip_code);
			$gss->setCountry($es->country);
			$gss->setHomeTelephone($es->home_telephone);
			$gss->setMobile($es->mobile);
			$gss->setEmailAddress($es->email_address);
			$gss->setQualification($es->qualification);
			$gss->setSssNumber($es->sss_number);
			$gss->setTinNumber($es->tin_number);
			$gss->setPagibigNumber($es->pagibig_number);
			$gss->setAppliedDateTime($es->applied_date_time);
			$gss->setResumeName($es->resume_name);
			$gss->setResumePath($es->resume_path);
			$gss->save();
			
			if($_POST['application_status']==INTERVIEW) {
				$h = G_Employee_Finder::findById($_POST['hiring_manager_id']);
				$_POST['notes'] =  $_POST['notes'];
				$date_time_event = $_POST['date_time_event'] . ' '. $_POST['time'];
				
				
				$details = G_Job_Application_Event_Finder::findByApplicantId($es->getId());
				$prefix = '1st';
				 $count=0;
				   foreach($details as $key=>$e) { 
					
					$count++;
					   if($e->event_type==INTERVIEW) {
							$prefix = Tools::getSubOrdinalSuffix($count);
					   }
				   }
				   $remarks = $prefix . ' Interview';	   
			}
			
			if($_POST['application_status']==JOB_OFFERED) {
				$h = G_Employee_Finder::findById($_POST['hiring_manager_id']);
				$_POST['notes'] =  $_POST['notes'];
				$date_time_event = date("Y-m-d");
				$remarks = 'Job Offered';
			}
			
			if($_POST['application_status']==OFFER_DECLINED) {
				$h = G_Employee_Finder::findById($_POST['hiring_manager_id']);
				$_POST['notes'] = $_POST['notes'];
				$date_time_event = date("Y-m-d");
				$remarks = 'Offer Declined';
			}
			
			if($_POST['application_status']==REJECTED) {
				$h = G_Employee_Finder::findById($_POST['hiring_manager_id']);
				$_POST['notes'] =  $_POST['notes'];
				$date_time_event = date("Y-m-d");
				$remarks = 'Rejected';
			}
			
			if($_POST['application_status']==HIRED) {
				$h = G_Employee_Finder::findById($_POST['hiring_manager_id']);
				$_POST['notes'] = $_POST['notes'];
				$date_time_event = date("Y-m-d");
				$remarks = 'Hired';
				
				//insert into employee
				$e = new G_Employee;
				$e->setFirstname($es->firstname);
				$e->setLastname($es->lastname);
				$e->setMiddlename($es->middlename);
				$e->setExtensionName($es->extension_name);
				//$e->setNickname($row['nickname']);
				$e->setBirthdate($es->birthdate);
				$e->setGender($es->gender);
				$e->setMaritalStatus($es->marital_status);
				$e->setNationality($es->nationality);
				$e->setSssNumber($es->sss_number);
				$e->setTinNumber($es->tin_number);
				$e->setPagibigNumber($es->pagibig_number);
				$e->setPhilhealthNumber($es->philhealth_number);
				$e->setPhoto($es->photo);
				$e->setHiredDate($_POST['hired_date']);
				$e->setIsArchive(G_Employee::NO);

				$employee_id = $e->save();
				
				//create hash
				$e = Employee_Factory::get($employee_id);
				$hash = Utilities::createHash($employee_id);
				$e->addHash($hash);
				
				//insert job
				$p = G_Job_Finder::findById($_POST['position_id']);
				$p->saveToEmployee($e, date("Y-m-d") );
				//add employee into company
				$c = G_Company_Structure_Finder::findById($this->company_structure_id);
				$c->addEmployee($e);
				//add to department			
				$c = G_Company_Structure_Finder::findById($_POST['department_id']);
				$c->addEmployeeToSubdivision($e,date("Y-m-d"));
				//add to that branch
				$b = G_Company_Branch_Finder::findById($_POST['branch_id']);
				$b->addEmployee($e, date("Y-m-d"));
				
				//add requirements
				$a = G_Applicant_Requirements_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				$gss = new G_Employee_Requirements;
				$gss->setEmployeeId($employee_id);
				$gss->setRequirements($a->requirements);
				$gss->setIsComplete($a->is_complete);
				$gss->setDateUpdated($a->date_updated);
				$gss->save();
				//add work experience
				$a = G_Applicant_Work_Experience_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
						$we = new G_Employee_Work_Experience;
						$we->setEmployeeId($employee_id);
						$we->setCompany($w->company);
						$we->setAddress($w->address);
						$we->setJobTitle($w->job_title);
						$we->setFromDate($w->from_date);
						$we->setToDate($w->to_date);
						$we->setComment($w->comment);
						$we->save();	
				}
				//add education
				$a = G_Applicant_Education_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
						$ed = new G_Employee_Education;
						$ed->setEmployeeId($employee_id);
						$ed->setInstitute($w->institute);
						$ed->setCourse($w->course);
						$ed->setYear($w->year);
						$ed->setStartDate($w->start_date);
						$ed->setEndDate($w->end_date);
						$ed->setGpaScore($w->gpa_score);
						$ed->setAttainment($w->attainment);
						$ed->save();
				}	
				//add training
				$a = G_Applicant_Training_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
						$tr = new G_Employee_Training;
						$tr->setEmployeeId($employee_id);
						$tr->setFromDate($w->from_date);
						$tr->setToDate($w->to_date);
						$tr->setDescription($w->description);
						$tr->setProvider($w->provider);
						$tr->setLocation($w->location);
						$tr->setCost($w->cost);
						$tr->setRenewalDate($w->renewal_date);
						$tr->save();
				}	
				
				//add skills
				$a = G_Applicant_Skills_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
						$es = new G_Employee_Skills;
						$es->setEmployeeId($employee_id);
						$es->setSkill($w->skill);
						$es->setYearsExperience($w->years_experience);
						$es->setComments($w->comments);
						$es->save();
				}	
				
				// add license
				$a = G_Applicant_License_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
							$el = new G_Employee_License;
							$el->setEmployeeId($employee_id);
							$el->setLicenseType($w->license_type);
							$el->setLicenseNumber($w->license_number);
							$el->setIssuedDate($w->issued_date);
							$el->setExpiryDate($w->expiry_date);	
							$el->save();
				}	
				
				//add language
				$a = G_Applicant_Language_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
				foreach($a as $key=>$w) {
					$lang = new G_Employee_Language;
					$lang->setEmployeeId($employee_id);
					$lang->setLanguage($w->language);
					$lang->setFluency($w->fluency);
					$lang->setCompetency($w->competency);
					$lang->setComments($w->comments);
					$lang->save();
				}	
				
				//add status
				$position =  G_Job_Finder::findById($_POST['position_id']);

				$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
				if($total_status>0) {
					
					//load job employment status
					//for dynamic status
					/*	$status = G_Job_Employment_Status_Finder::findById($_POST['employment_status_id']);
					$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
					$employee_job->setEmploymentStatus($status->getEmploymentStatus());
					$employee_job->save();*/
					// for future features 	
					
					$status = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
				
					$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
					$employee_job->setEmploymentStatus($status->getStatus());
					$employee_job->save();
								
				}else {
					//load settings employment status
					$status = G_Settings_Employment_Status_Finder::findById($_POST['employment_status_id']);
				
					$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
					$employee_job->setEmploymentStatus($status->getStatus());
					$employee_job->save();
				}
				
				$employee = G_Employee_Finder::findById($employee_id);
				if($employee) {
					$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);	
					
					if($salary) {
					
						$salary->setEmployeeId($employee->id);
						$salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
						$salary->setBasicSalary($_POST['basic_salary']);
						$salary->setType($_POST['type']);
						$salary->setPayPeriodId($_POST['pay_period_id']);
						$salary->save();
					}else {
						$employee_salary = new G_Employee_Basic_Salary_History;	
						
						$employee_salary->setEmployeeId($employee->id);
						$employee_salary->setJobSalaryRateId($_POST['job_salary_rate_id']);
						$employee_salary->setBasicSalary($_POST['basic_salary']);
						$employee_salary->setType($_POST['type']);
						$employee_salary->setPayPeriodId($_POST['pay_period_id']);
						$employee_salary->setStartDate($_POST['hired_date']);
						$employee_salary->save();
					}
				}
				
				$es = G_Applicant_Finder::findById(Utilities::decrypt($_POST['applicant_id']));
				$hash = Utilities::createHash(Utilities::decrypt($_POST['applicant_id']));
				// Update Hired Date
				$gss = new G_Applicant;
				$gss->setId($es->getId());
				$gss->setHash($hash);
				$gss->setEmployeeId($employee_id);
				$gss->setCompanyStructureId($this->company_structure_id);
				$gss->setJobVacancyId($es->job_vacancy_id);
				$gss->setApplicationStatusId($_POST['application_status']);
				$gss->setPhoto($es->photo);
				$gss->setJobId($es->job_id);
				$gss->setLastname($es->lastname);
				$gss->setFirstname($es->firstname);
				$gss->setMiddlename($es->middlename);
				$gss->setGender($es->gender);
				$gss->setMaritalStatus($es->marital_status);
				$gss->setBirthdate($es->birthdate);
				$gss->setBirthPlace($es->birth_place);
				$gss->setAddress($es->address);
				$gss->setCity($es->city);
				$gss->setProvince($es->province);
				$gss->setZipCode($es->zip_code);
				$gss->setCountry($es->country);
				$gss->setHomeTelephone($es->home_telephone);
				$gss->setMobile($es->mobile);
				$gss->setEmailAddress($es->email_address);
				$gss->setQualification($es->qualification);
				$gss->setAppliedDateTime($es->applied_date_time);
				$gss->setResumeName($es->resume_name);
				$gss->setResumePath($es->resume_path);
				$gss->setHiredDate($_POST['hired_date']);
				$gss->save();	
			}
			
			$e = new G_Job_Application_Event;
			$e->setId($row['id']);
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setApplicantid(Utilities::decrypt($_POST['applicant_id']));
			$e->setDateTimeCreated(date("Y-m-d h:i:s"));
			$e->setCreatedBy($_SESSION['hr']['user_id']);
			$e->setHiringManagerId($_POST['hiring_manager_id']);
			$e->setDateTimeEvent($date_time_event);
			$e->setEventType($_POST['application_status']);
			$e->setApplicationStatusId($_POST['application_status']);
			$e->setNotes($_POST['notes']);
			$e->setRemarks($remarks);
			$e->save();	
			
			if(APPLICANT_EVENT_SEND_EMAIL == true){
				$toSend = $es->email_address;
				$email = new Sprint_Email();
				$email->setFrom("hr@sprinthr.com");
				$email->setTo($toSend);
				
				$details['name']    = $es->lastname . ", " . $es->firstname;
				$details['content'] = $_POST['notes'];				
				
				if($_POST['application_status']==INTERVIEW) {	
					$details['interviewer_id']    = $_POST['hiring_manager_id'];
					$details['date_of_interview'] = $_POST['date_time_event'];	
					$details['time']					= $_POST['time'];	
					$email->setSubject("[SprintHR] " . $remarks);
					$email->eventInterviewBodyMessage($details);				
				} elseif($_POST['application_status']==JOB_OFFERED) {					
					$email->setSubject("[SprintHR] " . 'Job Offered');
					$email->eventJobOfferedBodyMessage($details);				
				} elseif($_POST['application_status']==OFFER_DECLINED) {				
					$email->setSubject("[SprintHR] " . 'Offer Declined');
					$email->eventOfferDeclinedBodyMessage($details);				
				} elseif($_POST['application_status']==REJECTED) {				
					$email->setSubject("[SprintHR] " . 'Rejected');
					$email->eventRejectedBodyMessage($details);				
				} elseif($_POST['application_status']==HIRED) {							
					$email->setSubject("[SprintHR] " .'Hired');
					$email->eventHiredBodyMessage($details);					
				} else {				
					$email->setSubject("[SprintHR]");
					$email->eventInterviewBodyMessage($details);				
				}
				
				$email->applicationEventEmail();	
			}
			$a_id = Utilities::decrypt($applicant_id);
			if($_POST['application_status']==INTERVIEW) {
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event ('For Interview'),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			} elseif($_POST['application_status']==JOB_OFFERED) {					
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event ('Job Offered'),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			} elseif($_POST['application_status']==OFFER_DECLINED) {				
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event ('Offer Declined'),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			} elseif($_POST['application_status']==REJECTED) {				
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event ('Rejected'),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			} elseif($_POST['application_status']==HIRED) {							
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event (" . HIRED . "),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			}else{
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant event ('Hired'),applicant id=" . $a_id,$_SESSION['sprint_hr']['username']);
			}
 			
			echo 1;	
		}
							
	}
	
	function _load_attachment()
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Attachment_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
		
		$this->var['attachment'] = $e;
		
		$applicant = G_Applicant_Finder::findById(Utilities::decrypt($applicant_id));		
		if(!empty($applicant)) {			
			$app_log_finder = G_Applicant_Logs_Finder::findByEmail($applicant->getEmailAddress());
			if(!empty($app_log_finder)){
				$app_resume 	 = G_Applicant_Profile_Finder::findByApplicantLogId($app_log_finder->getId());
				if(!empty($app_resume)) {
					if($app_resume->getResumeName() != '') {
						$file = BASE_FOLDER . 'files/applicant/resume/' . $app_resume->getResumeName();
						if(Tools::isFileExist($file)==1) {
							$this->var['attached_resume_exist'] = 1;
							$this->var['attached_resume'] 		= $app_resume->getResumeName();
						} else {
							$this->var['attached_resume_exist'] = 0;	
						}
					}else{
						$this->var['attached_resume_exist'] = 0;
					}
				}else{
					$this->var['attached_resume_exist'] 	= 0;
					$this->var['attached_resume'] 			= '';							
				}
			}else{
				$this->var['attached_resume_exist'] 	= 0;
				$this->var['attached_resume'] 			= '';			
			} 		
		}else{
			$this->var['attached_resume_exist'] 	= 0;
			$this->var['attached_resume'] 			= '';
		}
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
	
		$this->var['title'] = "Attachment";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/attachment/index.php',$this->var);
	}
	
	
	
	
	function _load_attachment_edit_form()
	{
		$attachment_id = $_POST['attachment_id'];
		$e = G_Applicant_Attachment_Finder::findById($attachment_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/personal_information/attachment/form/attachment_edit.php',$this->var);
	}
	
	function _delete_attachment()
	{
		Utilities::ajaxRequest();
		$attachment_id = $_POST['attachment_id'];
		$e = G_Applicant_Attachment_Finder::findById($attachment_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": delete applicant attachment, id=".$attachment_id);		
		
		echo 1;
	}
	
	//work experience

	function _load_work_experience() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Work_Experience_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
		
		$this->var['work_experience'] = $e;
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
	
		$this->var['title_work_experience'] = "Work Experience";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/work_experience/index.php',$this->var);
	}
	
	function _load_work_experience_edit_form()
	{
		$work_experience_id = $_POST['work_experience_id'];
		$e = G_Applicant_Work_Experience_Finder::findById($work_experience_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/work_experience/form/work_experience_edit.php',$this->var);
	}
	
	function _delete_work_experience()
	{
		Utilities::ajaxRequest();
		$work_experience_id = $_POST['work_experience_id'];
		$e = G_Applicant_Work_Experience_Finder::findById($work_experience_id);
		$d = $e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_work_ex_delete'] .",id=". $work_experience_id,$this->username);
		
		echo 1;
	}
	
	//work experience
	
	//education
	
	function _load_education() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Education_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
	
		$this->var['education'] = $e;
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
	
		$this->var['title_education'] = "Education";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/education/index.php',$this->var);
	}
	
	function _load_education_edit_form()
	{
		$education_id = $_POST['education_id'];
		$e = G_Applicant_Education_Finder::findById($education_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/education/form/education_edit.php',$this->var);
	}
	
	function _delete_education()
	{
		Utilities::ajaxRequest();
		$education_id = $_POST['education_id'];
		$e = G_Applicant_Education_Finder::findById($education_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_education_delete'] .",id=". $education_id,$this->username);	
		
		echo 1;
	}
	
	//training
	
	function _load_training() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Training_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
	
		$this->var['training'] = $e;
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
	
		$this->var['title_training'] = "Training";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/training/index.php',$this->var);
	}
	
	function _load_training_edit_form()
	{
		$training_id = $_POST['training_id'];
		$e = G_Applicant_Training_Finder::findById($training_id);

		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/training/form/training_edit.php',$this->var);
	}
	
	function _delete_training()
	{
		Utilities::ajaxRequest();
		$training_id = $_POST['training_id'];
		$e = G_Applicant_Training_Finder::findById($training_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_training_delete'] .",id=". $training_id,$this->username);     
		
		echo 1;
	}
	
	// end of training
	
	//skills
	function _load_skills() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Skills_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
		
		$this->var['skills'] = $e;
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
		$this->var['g_skills']     = G_Settings_Skills_Finder::findByCompanyStructureId($this->company_structure_id);
  		$this->var['title_skills'] = "Skills";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/skill/index.php',$this->var);
	}
	
	function _load_skill_edit_form()
	{
		$skill_id = $_POST['skill_id'];
		$e = G_Applicant_Skills_Finder::findById($skill_id);
		$this->var['g_skills']= G_Settings_Skills_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/skill/form/skill_edit.php',$this->var);
	}
	
	function _delete_skill()
	{
		Utilities::ajaxRequest();
		$skill_id = $_POST['skill_id'];
		$e = G_Applicant_Skills_Finder::findById($skill_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_skills_delete'] .",id=". $skill_id,$this->username);     		
		
		echo 1;
	}
	//end of skills
	
	
	//language
	function _load_language() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_Language_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
		
		$this->var['languages'] = $e;
		
		$this->load_summary_photo();
		$this->var['g_languages']  = G_Settings_Language_Finder::findAllByCompanyStructureId($this->company_structure_id);
		$this->var['applicant_id'] = $applicant_id;		
		$this->var['title_language'] = "Language";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/language/index.php',$this->var);
	}
	
	function _load_language_edit_form()
	{
		$language_id = $_POST['language_id'];
		$e = G_Applicant_Language_Finder::findById($language_id);
		$this->var['g_languages']    = G_Settings_Language_Finder::findAllByCompanyStructureId($this->company_structure_id);
		$this->var['details'] = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/language/form/language_edit.php',$this->var);
	}
	
	function _delete_language()
	{
		Utilities::ajaxRequest();
		$language_id = $_POST['language_id'];
		$e = G_Applicant_Language_Finder::findById($language_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_language_delete'] .",id=". $language_id,$this->username);		
		
		echo 1;
	}
	//end of language
	
	//license
	function _load_license() 
	{
		Utilities::ajaxRequest();
		
		$applicant_id =  $_GET['applicant_id'];
		$e = G_Applicant_License_Finder::findByApplicantId(Utilities::decrypt($applicant_id));
		
		$this->var['licenses'] = $e;
		
		$this->load_summary_photo();
		$this->var['applicant_id'] = $applicant_id;
		$this->var['g_licenses']   = G_Settings_License_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['title_license'] = "License";
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/license/index.php',$this->var);
	}
	
	function _load_license_edit_form()
	{
		$license_id = $_POST['license_id'];
		$e = G_Applicant_License_Finder::findById($license_id);
		$this->var['g_licenses'] = G_Settings_License_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['details']    = $e;
		$this->view->noTemplate();
		$this->view->render('recruitment/profile/qualification/license/form/license_edit.php',$this->var);
	}
	
	function _delete_license()
	{
		Utilities::ajaxRequest();
		$license_id = $_POST['license_id'];
		$e = G_Applicant_License_Finder::findById($license_id);
		$e->delete();
		
		$this->triggerAuditTrail(1,ACTION_DELETE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_license_delete'] .",id=". $license_id,$this->username);    
		echo 1;
	}
	//end of license
	
	
	function _update_personal_details()
	{
			//print_r($_POST);
			
			$es = G_Applicant_Finder::findById(Utilities::decrypt($_POST['applicant_id']));
			$hash = Utilities::createHash(Utilities::decrypt($_POST['applicant_id']));
			
			$gss = new G_Applicant;
			$gss->setId($es->getId());
			$gss->setHash($hash);
			$gss->setCompanyStructureId(1);
			$gss->setJobVacancyId('');
			$gss->setApplicationStatusId(APPLICATION_SUBMITTED);
			$gss->setPhoto($_POST['photo']);
			$gss->setJobId($_POST['job_id']);
			$gss->setLastname($_POST['lastname']);
			$gss->setFirstname($_POST['firstname']);
			$gss->setMiddlename($_POST['middlename']);
			$gss->setExtensionName($_POST['extension_name']);
			$gss->setGender($_POST['gender']);
			$gss->setMaritalStatus($_POST['marital_status']);
			$gss->setBirthdate($_POST['birthdate']);
			$gss->setBirthPlace($_POST['birth_place']);
			$gss->setAddress($_POST['address']);
			$gss->setCity($_POST['city']);
			$gss->setProvince($_POST['province']);
			$gss->setZipCode($_POST['zip_code']);
			$gss->setCountry($_POST['country']);
			$gss->setHomeTelephone($_POST['home_telephone']);
			$gss->setMobile($_POST['mobile']);
			$gss->setEmailAddress($_POST['email_address']);
			$gss->setQualification($_POST['qualification']);
			$gss->setSssNumber($_POST['sss_number']);
			$gss->setPagibigNumber($_POST['pagibig_number']);
			$gss->setTinNumber($_POST['tin_number']);
			$gss->setPhilhealthNumber($_POST['philhealth_number']);
			$gss->setAppliedDateTime($_POST['applied_date_time']);
		//	$gss->setResumeName('test');
		//	$gss->setResumePath('test');
			$gss->save();
			
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant personal details,id=".$es->getId());			
			
			echo 1;
	}
	
	function _update_attachment()
	{
			$prefix='attachment';
			$file = Tools::uploadFile($_FILES,$prefix);
			if($file['is_uploaded']=='true') {
				$row = $_POST;
				$gcb = new G_Applicant_Attachment($row['id']);
				$gcb->setApplicantId(Utilities::decrypt($row['applicant_id']));
				$gcb->setName($row['name']);
				$gcb->setFilename($file['filename']);
				$gcb->setDescription($row['description']);
				$gcb->setSize($_FILES['filename']['size']);
				$gcb->setType($_FILES['filename']['type']);
				$gcb->setDateAttached($row['date_attached']);
				$gcb->setAddedBy($row['added_by']);
				$gcb->setScreen($row['screen']);
				$saved = $gcb->save();
				
				$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": add applicant attachment, id=".$saved);
							
				$return = 1;
			}else{
				$row = $_POST;
				$gcb = G_Applicant_Attachment_Finder::findById($row['id']);				
				$gcb->setApplicantId($gcb->getApplicantId());
				$gcb->setName($row['name']);
				$gcb->setFilename($gcb->getFilename());
				$gcb->setDescription($row['description']);
				$gcb->setSize($gcb->getSize());
				$gcb->setType($gcb->getType());
				$gcb->setDateAttached($row['date_attached']);
				$gcb->setAddedBy($gcb->getAddedBy());
				$gcb->setScreen($gcb->getScreen());
				$gcb->save();
		
				$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant attachment, id=".$row['id']);
				
				$return = 1;
			}
			
			
		echo $return;

	}
	
	function _update_training()
	{
		$row = $_POST;	
		$e = new G_Applicant_Training;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setDescription($row['description']);
		$e->setProvider($row['provider']);
		$e->setLocation($row['location']);
		$e->setCost($row['cost']);
		$e->setRenewalDate($row['renewal_date']);
		$e->save();
		
		$app_id = Utilities::decrypt($row['applicant_id']);
		if($row['id']){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_training_update'] .",applicant id=". $app_id,$this->username);     			
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_training_add'] .",applicant id=". $app_id,$this->username);     
		}
		
		echo 1;	
	}
	
	function _update_contact_details()
	{
			//print_r($_POST);
			$es = G_Applicant_Finder::findById(Utilities::decrypt($_POST['applicant_id']));
			$hash = Utilities::createHash(Utilities::decrypt($_POST['applicant_id']));
			//print_r($es);
			$gss = new G_Applicant;
			$gss->setId($es->getId());
			$gss->setHash($hash);
			$gss->setCompanyStructureId($this->company_structure_id);
			$gss->setJobVacancyId($es->job_vacancy_id);
			$gss->setApplicationStatusId($es->application_status_id);
			$gss->setPhoto($es->photo);
			$gss->setJobId($es->job_id);
			$gss->setLastname($es->lastname);
			$gss->setFirstname($es->firstname);
			$gss->setMiddlename($es->middlename);
			$gss->setGender($es->gender);
			$gss->setMaritalStatus($es->marital_status);
			$gss->setBirthdate($es->birthdate);
			$gss->setBirthPlace($es->birth_place);
			$gss->setAddress($es->address);
			$gss->setCity($es->city);
			$gss->setProvince($es->province);
			$gss->setZipCode($es->zip_code);
			$gss->setCountry($es->country);
			$gss->setHomeTelephone($_POST['home_telephone']);
			$gss->setMobile($_POST['mobile']);
			$gss->setEmailAddress($_POST['email_address']);
			$gss->setQualification($_POST['qualification']);
			$gss->setAppliedDateTime($es->applied_date_time);
			$gss->setResumeName($es->resume_name);
			$gss->setResumePath($es->resume_path);
			$saved = $gss->save();
			
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant contact details,id=".$es->getId());
			echo 1;
	}
	
	function _add_requirements()
	{
		$applicant_id = Utilities::decrypt($_POST['applicant_id']);
		$requirements = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);
		
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
			
			$gss = new G_Applicant_Requirements;
			$gss->setId($requirements->id);
			$gss->setApplicantId($applicant_id);
			$gss->setRequirements(serialize($req));
			$gss->setIsComplete($is_complete);
			$gss->setDateUpdated(date("Y-m-d"));
			$gss->save();
			
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant requirements,id=".$requirements->id);
			
		}else {
			//insert<
			
			$req[Tools::friendlyFormName($_POST['name'])] = '';
			
			$new_req = serialize($req);
			$gss = new G_Applicant_Requirements;
			$gss->setId($r->id);
			$gss->setApplicantId($applicant_id);
			$gss->setRequirements($new_req);
			$gss->setIsComplete(0);
			$gss->setDateUpdated(date("Y-m-d"));
			$saved = $gss->save();
			
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": update applicant requirements,id=".$saved);
		}
		echo 1;
		
	}
	
	function _update_education()
	{
		$row = $_POST;	
		$e = new G_Applicant_Education;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setInstitute($row['institute']);
		$e->setCourse($row['course']);
		$e->setYear($row['year']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);
		$e->setGpaScore($row['gpa_score']);
		$e->setAttainment($row['attainment']);
		$e->save();
		
		$app_id = Utilities::decrypt($row['applicant_id']); 
		
		if($row['id']){ 
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_education_update'] .",applicant id=". $app_id,$this->username);	
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_education_add'] .",applicant id=". $app_id,$this->username);
		}
		
		echo 1;	
	}
	
	function _update_requirements()
	{
		//print_r($_POST);	
		$form = $_POST;
		$applicant_id= Utilities::decrypt($_POST['applicant_id']);
		$r = G_Applicant_Requirements_Finder::findByApplicantId($applicant_id);

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
		$gss = new G_Applicant_Requirements;
		$gss->setId($r->id);
		$gss->setApplicantId($applicant_id);
		$gss->setRequirements($requirements);
		$gss->setIsComplete($is_complete);
		$gss->setDateUpdated(date("Y-m-d"));
		$gss->save();
		echo 1;
		
	}
	
	function _update_work_experience()
	{
		$row = $_POST;	
		$e = new G_Applicant_Work_Experience;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setCompany($row['company']);
		$e->setAddress($row['address']);
		$e->setJobTitle($row['job_title']);
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setComment($row['comment']);
		$e->save();
		
		$app_id = Utilities::decrypt($row['applicant_id']);
		if(!empty($row['id'])){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_work_ex_update'] .",applicant id=". $app_id,$this->username);
		} else {
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['recruitment']['qualification_work_ex_add'] .",applicant id=".$app_id,$this->username);
		}
		echo 1;	
	}
	
	function _update_skill()
	{
		$row = $_POST;	
		$e = new G_Applicant_Skills;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setSkill($row['skill']);
		$e->setYearsExperience($row['years_experience']);
		$e->setComments($row['comments']);
		$e->save();
		
		$app_id = Utilities::decrypt($row['applicant_id']);
		if($row['id']){
			$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_skills_update'] .",applicant id=". $app_id,$this->username);
		}else{
			$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_skills_add'] .",applicant id=". $app_id,$this->username);
		}
		
		echo 1;	
	}
	
	function _update_language()
	{
		$row = $_POST;	
		$e = new G_Applicant_Language;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setLanguage($row['language']);
		$e->setFluency($row['fluency']);
		$e->setCompetency($row['competency']);
		$e->setComments($row['comments']);	
		$e->save();
		
        $app_id = Utilities::decrypt($row['applicant_id']);
        if($row['id']){
        	$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_language_update'] .",applicant id=". $app_id,$this->username);
        }else{
        	$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_language_add'] .",applicant id=". $app_id,$this->username);
        }
		
		echo 1;	
	}
	
	function _update_license()
	{
		$row = $_POST;	
		$e = new G_Applicant_License;
		$e->setId($row['id']);
		$e->setApplicantId(Utilities::decrypt($row['applicant_id']));
		$e->setLicenseType($row['license_type']);
		$e->setLicenseNumber($row['license_number']);
		$e->setIssuedDate($row['issued_date']);
		$e->setExpiryDate($row['expiry_date']);	
		$e->save();
		
        $app_id = Utilities::decrypt($row['applicant_id']);
        if($row['id']){
        	$this->triggerAuditTrail(1,ACTION_UPDATE,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_license_update'] .",applicant id=". $app_id,$this->username);
        }else{
        	$this->triggerAuditTrail(1,ACTION_INSERT,$this->module.": ". $GLOBALS['hr']['audit_trail']['recruitment']['qualification_license_add'] .",applicant id=". $app_id,$this->username);
        }		
		
		echo 1;	
	}
		
	function html_show_import_applicant_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('recruitment/candidate/html/html_show_import_applicant_format.php', $this->var);	
	}
	
	function load_summary_photo_in_applicant_profile()
	{
		$applicant_id =  Utilities::decrypt($_GET['applicant_id']);	
		$e = G_Applicant_Finder::findById($applicant_id);
		
		if($e){		
			$epl = G_Applicant_Logs_Finder::findByEmail($e->getEmailAddress());
			if($epl) {
				$e_p = G_Applicant_Profile_Finder::findByApplicantLogId($epl->getId());
				
				$file = PHOTO_FOLDER .$e_p->getPhoto();
				
				if(Tools::isFileExist($file)==true && $e_p->getPhoto()!='') {
					$this->var['filemtime'] = md5($e_p->getPhoto()).date("His");
					$this->var['filename'] = $file;
				}else {
					$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
				}
			}else{
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';	
			}
		}else{
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
	}	
}
?>