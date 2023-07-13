<?php
class Applicant_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');
		
		$this->c_date  			    = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');		
	
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];		
		if($this->applicant_id){
			$count = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
												
			$this->a_has_applicant_info = $count;			
			$this->is_profile_exist 	 = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));			 
			$this->ahid 				    =  Utilities::createHash($this->applicant_id);			
			$this->aeid 				    =  Utilities::encrypt($this->applicant_id);			
		}
				
	}

	function index()
	{
		$this->applicant_login();
				
		$al = G_Applicant_Logs_Finder::findById(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		if($al->getIsPasswordChange() == G_Applicant_Logs::IS_YES) {
			redirectMain("job_vacancy");
		} else {
			$this->change_password();
		}
 		
	}
	
	/*function _load_cancel_application_confirmation()
	{
		if(!empty($_POST['application_id'])){			
			$a = G_Applicant_Finder::findById(Utilities::decrypt($_POST['application_id']));
			
			if($a){	
				$j = G_Job_Vacancy_Finder::findByJobIdAndIsActive($a->getJobId());
				$this->var['job_title'] = $j->getJobTitle();
				$this->view->noTemplate();
				$this->view->render('applicant/front/delete_confirmation.php',$this->var);
			}
		}
	}*/
	
	function dashboard() {
		$this->applicant_login();
		
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTipsy();
		
		Loader::appMainScript('applicant.js');
				
		$this->var['page_title']	= 'Dashboard';
		$this->var['module_title'] = 'Application History';
		$this->view->setTemplate('template.php');
		$this->view->render('applicant/front/dashboard.php',$this->var);	
	}
	
	function application_details() {
		$aid = Utilities::decrypt($_GET['aid']);
		
		Loader::appMainScript('applicant.js');
		
		$application_details = G_Applicant_Helper::findApplicationDetails($aid);
		$this->var['application_details'] = $application_details;
		$this->view->setTemplate('template_applicant_blank.php');
		$this->view->render('applicant/front/application_details.php',$this->var);	
	}
	
	function change_password()
	{
		$this->applicant_login();
		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['token'] 		= Utilities::createFormToken();
		$this->var['action'] 	= url('applicant/save_password');

		$this->var['page_title']= 'Change Password';
		$this->view->setTemplate('template_applicant_nosidebar.php');
		$this->view->render('applicant/front/forms/change_password.php',$this->var);	
	}
		
	function save_password()
	{
		if(Utilities::isFormTokenValid($_POST['token'])) {

			$al = G_Applicant_Logs_Finder::findById(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
			
			if($al) {
				$old_password = Utilities::encrypt($_POST['old_password']);
				$new_password = Utilities::encrypt($_POST['new_password']);
				$image        = BASE_FOLDER . 'themes/' . THEME . '/themes-images/login/error.png';	
				
				if($al->getPassword() == $old_password) {
					
					// save new password					
					$al->setPassword($new_password);
					$al->setIsPasswordChange(G_Applicant_Logs::IS_YES);
					$al->save();

					//Send Email
						$email = new Sprint_Email();
						$email->setFrom("hr@sprinthr.com");
						$email->setTo($al->getEmail());
						$email->setSubject("[SprintHR]Change Password: Your new password");
						$email->applicantChangePassword($al,$_POST['new_password']);
						$email->sendEmail();	
					//					
					
						$return['token']			= Utilities::createFormToken();
						$return['is_success'] 	= 1;
						$return['message'] 		= '
						<div class="alert alert-info fade in">
							<i class="icon-ok-circle"></i>						
							Password was successfully changed. You may check also your email for your new password.
						</div>
						';	
					
				} else {
					$return['token']		 = Utilities::createFormToken();
					$return['is_success'] = 0;
					$return['message']    = '
							<div class="alert alert-error fade in">
								<i class="icon-remove-circle"></i>			
								Incorrect old password entry
							</div>';
					
				}

			} else {
				$return['token']		 = Utilities::createFormToken();
				$return['is_success'] = 0;
				$return['message']    = '
						<div class="alert alert-error fade in">
							<i class="icon-remove-circle"></i>			
							Error Changing Password
						</div>';			
			}
			
		}
		echo json_encode($return);		
	}
	
	function job_vacancy_list() 
	{
		$this->applicant_login();
		if($this->applicant_id){			
			$jxml   = new G_Job_Vacancy();
			$xmlUrl = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . 'files/xml/job_vacancy/';		
			$data   = $jxml->readActiveJobVacancyXMLFile($xmlUrl,G_Job_Vacancy::xmlFILENAME);
			
			$this->var['data'] 		= $data;		
			$this->var['ahid'] 		= $this->ahid;
			$this->var['aeid']		= $this->ehid;
			$this->var['page_title']= 'Job Vacancy List';
			$this->view->setTemplate('template_applicant_nosidebar.php');
			$this->view->render('jobs/front/job_vacancy_list.php',$this->var);			
		}
	}
	
	function apply()
	{	
		$this->applicant_login();
		
		if(!empty($_GET['jeid']) && !empty($_GET['jhid'])){
			$jeid = $_GET['jeid'];
			$jhid = $_GET['jhid'];
		
			Utilities::verifyHash(Utilities::decrypt($jeid),$jhid);
							
			$jdid = Utilities::decrypt($jeid);			
	
			$j = G_Job_Vacancy_Finder::findByJobIdAndIsActive($jdid);
			if($j){			
				Jquery::loadMainInlineValidation2();
				//Jquery::loadMainTextBoxList();
				Jquery::loadMainJqueryFormSubmit();
				
				$applicant_id = Utilities::decrypt($this->applicant_id);	
				
				$this->var['eid']  = $this->applicant_id;
				$this->var['j']    = $j;					
				$this->var['token']= Utilities::createFormToken();
				$this->view->setTemplate('template_applicant_nosidebar.php');
				
				//echo $this->a_has_applicant_info;
				
				if($this->a_has_applicant_info > 0){
					$a = G_Applicant_Profile_Finder::findByApplicantLogId($applicant_id);					
					//$a = G_Applicant_Finder::findSpecificEmailAddress($_SESSION['sprint_applicant']['username']);					
					if($a){
						$this->var['a']			= $a;
						$this->var['page_title']= 'Job Application';
						$this->view->render('applicant/front/forms/short_registration_form.php',$this->var);
					}else{												
						$this->var['page_title']= 'Job Application Error';
						$this->view->render('applicant/front/err/applicant_record_error.php',$this->var);
					}
					
				}else{							
					$a = G_Applicant_Logs_Finder::findById($applicant_id);
					
					if($a){		
						Jquery::loadMainUploadify();			
						$this->var['a']			= $a;
						$this->var['page_title']= 'Job Application';
						$this->view->render('applicant/front/forms/registration_form.php',$this->var);
					}else{
						$this->var['page_title']= 'Job Application Error';
						$this->view->render('applicant/front/err/applicant_record_error.php',$this->var);
					}
				}
			}else{				
				$this->var['page_title']= 'Job Application';
				$this->view->render('jobs/front/err/job_no_longer_available.php',$this->var);
			}	
		}	
	}
	
	function _save_applicant_info(){
		if(Utilities::isFormTokenValid($_POST['token'])) {
			$hash = Utilities::createHash($a_id);
			if($this->applicant_id){				
				$is_id_exist = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
				if($is_id_exist == 0){
					$gcb = new G_Applicant_Profile();
					$gcb->setCompanyStructureId($this->company_structure_id);
					$gcb->setApplicantLogId(Utilities::decrypt($this->applicant_id));
					$gcb->setLastname(ucfirst($_POST['lastname']));
					$gcb->setFirstname(ucfirst($_POST['firstname']));
					$gcb->setMiddlename(ucfirst($_POST['middlename']));
					$gcb->setExtensionName($_POST['extension_name']);
					$gcb->setBirthdate($_POST['birthdate']);
					$gcb->setGender($_POST['gender']);
					$gcb->setMaritalStatus($_POST['marital_status']);
					$gcb->setHomeTelephone($_POST['home_telephone']);
					$gcb->setMobile($_POST['mobile']);
					$gcb->setBirthPlace($_POST['birth_place']);
					$gcb->setAddress($_POST['address']);
					$gcb->setCity($_POST['city']);
					$gcb->setProvince($_POST['province']);
					$gcb->setZipCode($_POST['zip_code']);
					$gcb->setSssNumber($_POST['sss_number']);
					$gcb->setTinNumber($_POST['tin_number']);
					$gcb->setPhilhealthNumber($_POST['philhealth_number']);
					$gcb->setPagibigNumber($_POST['pagibig_number']);
					//$gcb->setResumeName($row['resume_name']);
					//$gcb->setResumePath($row['resume_path']);				
					$gcb->save();
				}else{
					$gcb = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($this->applicant_id));
				}
				
				$other_details['job_id'] 	    = Utilities::decrypt($_POST['job_id']);
				$other_details['date_applied'] = $_POST['date_applied'];		
				
				$a_id = $gcb->copyApplicantProfile($other_details);
												
				// add requirements
				$xml_file = BASE_FOLDER . 'files/xml/requirements.xml';
				$req = new G_Applicant_Requirements();			
				$req->loadDefaultApplicantRequirements($a_id,$xml_file);
				//				
				
				//Create an Application Event History
				$aeh = new G_Job_Application_Event();
				$aeh->loadDefaultApplicationEventHistory($a_id);
				//
				
				if(!empty($_POST['directory_name'])) {
					$att = new G_Applicant_Attachment;
					$att->setApplicantId($a_id);
					$att->setName($_POST['upload_filename']);
					$att->setFileName($_POST['directory_name']);
					$att->setDateAttached($this->c_date);
					$att->setAddedBy(Utilities::decrypt($this->eid));
					$att->setScreen();
					$att->save();
				}
		
				//Send Examination link to email
					$applicant_details['name'] = $_POST['lastname'] . ", " . $_POST['firstname'];
					$e = new G_Exam();
					$exam_details = $e->sendExaminationToApplicant($_POST['job_id'],$a_id);
					$sprint_email = new Sprint_Email();
					$sprint_email->applicantExaminationMessageBodyEmailForApplicantMember($exam_details,$applicant_details);
					$sprint_email->setFrom("hr@sprinthr.com");
					$sprint_email->setTo($_SESSION['sprint_applicant']['username']);
					$sprint_email->setSubject("[SprintHR]Applicant Examination");
					$sprint_email->sendEmail();
				//			
				
				$eid = Utilities::encrypt($a_id);
				$hid = Utilities::createHash($a_id);			
				
				//$json['url']					= url("applicant/application_completed?eid={$eid}&hid={$hid}");
				$json['url']					= url("applicant/application_completed");
				$json['eid']					= $eid;
				$json['hid']					= $hid;
				$json['token']   			   = Utilities::createFormToken();
				$json['h_application_id'] 	= $eid;
				$json['hash']				   = $hid;
				$json['is_saved'] 			= true;
			}else{
				$json['token']    = Utilities::createFormToken();
				$json['is_saved'] = false;
			}
		}else {
			$json['token']    = Utilities::createFormToken();
			$json['is_saved'] = false;
		}
		
		echo json_encode($json);
	}
	
	function application_completed()
	{
		$eid = $_GET['eid'];
		$hid = $_GET['hid'];
		
		Utilities::verifyHash(Utilities::decrypt($eid),$hid);
		$this->var['page_title']= 'Job Application';
		$this->view->setTemplate('template_applicant_nosidebar.php');
		$this->view->render('applicant/front/application_completed.php',$this->var);	
	}
	
	function _load_application_list_dt()
	{
		$this->view->render('applicant/front/_load_application_list_dt.php',$this->var);	
	}	
	
	function _load_server_application_list_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(APPLICANT);
		$dt->setCustomField();
		$dt->setJoinTable("LEFT JOIN " . G_JOB_VACANCY . " j");
		$dt->setJoinFields("j.job_id = " . APPLICANT . ".job_id");
		$dt->setCondition(" " . APPLICANT . ".email_address = '" . $this->username . "'");
		$dt->setColumns('job_title,applied_date_time,application_status_id');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);			
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Cancel Application\" id=\"cancel_application\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:removeApplication(\'e_id\');\"></a></li><li><a title=\"View Details\" id=\"view_details\" class=\"ui-icon ui-icon-document g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:popupViewApplicationDetails(\'e_id\');\"></a></li></ul></div>'));

		echo $dt->constructDataTableForApplicant();				
				
	}
	
	function profile()
	{
		$this->applicant_login();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainUploadify();		
	
		Loader::appMainScript('applicant.js');
		$p = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($this->applicant_id));
		if($this->is_profile_exist) {			
			$file = HR_BASE_FOLDER . 'files/applicant/resume/'. $p->getResumeName();
			if(Tools::isFileExist($file)==1) {
				$this->var['attached_resume_exist'] = 1;
				$this->var['attached_resume'] 		= $p->getResumeName();
			}else{
				$this->var['attached_resume_exist'] = 0;
				$this->var['attached_resume'] 		= '';			
			}
		}
						
		$this->var['profile']				   = $p;
		$this->var['token'] 						= Utilities::createFormToken();
		$this->var['company_structure_id'] 	= $this->company_structure_id;
		
		$this->var['page_title']	= 'Profile';		
		
		$this->view->setTemplate('template_applicant_nosidebar.php');
		if($this->is_profile_exist) {
			$this->view->render('applicant/front/forms/profile_edit.php',$this->var);
		} else {			
			$this->var['applicant_id'] = $this->applicant_id;
			$this->view->render('applicant/front/forms/profile_add.php',$this->var);
		}		
	}
	
	function _load_photo()
	{	
		Utilities::ajaxRequest();	
		$employee_id = $_POST['employee_id'];
		$e = G_Applicant_Profile_Finder::findByApplicantLogId($employee_id);
		if($e){			
			$this->var['employee'] = $e;
			$file = HR_BASE_FOLDER . 'files/photo/' . $e->getPhoto();
			if(Tools::isFileExist($file)==1 && $e->getPhoto()!='') {
				$this->var['filemtime'] = md5($e->getPhoto()).date("His");
				$this->var['filename'] = $file;
			}else {
				$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
			}
		}else{			
			$this->var['employee'] = G_Applicant_Logs_Finder::findById($employee_id);			
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}		
		$this->view->noTemplate();
		$this->view->render('applicant/front/forms/photo/index.php',$this->var);			
	}
	
	function _upload_photo()
	{
		$prefix = 'applicant_';
		$employee_id =  $_POST['employee_id'];
		$hash = $this->applicant_id;
		$len = strlen($_FILES['fileField']['name']);
		$pos = strpos($_FILES['fileField']['name'],'.');
		$extension_name =  substr($_FILES['fileField']['name'],$pos, 5);
		$handle = new upload($_FILES['fileField']);
		//$path = $_SERVER['DOCUMENT_ROOT'] . PHOTO_FOLDER;
		$path = $_SERVER['DOCUMENT_ROOT'] . HR_BASE_FOLDER . 'files/photo/';
	
	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.$hash;
			$handle->file_overwrite 	   = true;
			$handle->image_resize         = true;
			$handle->image_x              = 300;
			$handle->image_ratio_y        = true;
			$handle->allowed = array('image/*');
	       $handle->process($path);
	       if ($handle->processed) {
	         
			  	$e = G_Applicant_Profile_Finder::findById($employee_id);
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
	
	function update_profile()
	{		
		$this->applicant_login();
		if(Utilities::isFormTokenValid($_POST['token'])) {
		
		if($this->is_profile_exist) {
			$p = G_Applicant_Profile_Finder::findByApplicantLogId(Utilities::decrypt($this->applicant_id));
		} else {
			$p = new G_Applicant_Profile();
			$p->setApplicantLogId(Utilities::decrypt($this->applicant_id));
		}
			
			if($p){
				$p->setCompanyStructureId(1);
				$p->setLastname($_POST['lastname']); 
				$p->setFirstname($_POST['firstname']);
				$p->setMiddlename($_POST['middlename']);
				$p->setExtensionName($_POST['extension_name']);
				$p->setBirthdate($_POST['birthdate']);
				$p->setGender($_POST['gender']);
				$p->setMaritalStatus($_POST['marital_status']);
				$p->setHomeTelephone($_POST['home_telephone']);
				$p->setMobile($_POST['mobile']);
				$p->setBirthPlace($_POST['birth_place']);
				$p->setAddress($_POST['address']);
				$p->setCity($_POST['city']);
				$p->setProvince($_POST['province']);
				$p->setZipCode($_POST['zip_code']);
				$p->setSssNumber($_POST['sss_number']);
				$p->setTinNumber($_POST['tin_number']);
				$p->setPhilhealthNumber($_POST['philhealth_number']);
				$p->setPagibigNumber($_POST['pagibig_number']);
				if(!empty($_POST['directory_name'])){				
					$p->setResumeName($_POST['directory_name']);
				}
				$p->setResumePath($_POST['resume_path']);
				$p->save();
												
				$json['is_saved'] 	= true;
			} else {
				$json['is_saved'] 	= false;
			}
		}
		
		$return['token']			   = Utilities::createFormToken();
		
		echo json_encode($json);	
	}
	
	function _load_cancel_application_confirmation()
	{
		if(!empty($_POST['application_id'])){			
			$a = G_Applicant_Finder::findById(Utilities::decrypt($_POST['application_id']));
			
			if($a){	
				$j = G_Job_Vacancy_Finder::findByJobIdAndIsActive($a->getJobId());
				$this->var['job_title'] = $j->getJobTitle();
				$this->view->noTemplate();
				$this->view->render('applicant/front/delete_confirmation.php',$this->var);
			}
		}
	}
	
	function delete_application()
	{
		$applicant_id = Utilities::decrypt($_POST['application_id']); 
		if(!empty($_POST['application_id'])){
				$a = G_Applicant_Finder::findById($applicant_id);									
				if($a){
					$a->cancelApplication();
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
}
?>