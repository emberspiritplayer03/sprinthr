<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Applicant_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style_website.css');
		
		$this->c_date  			    = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');			
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];
		
		$this->var['profile_photo'] = 'test only';
		
		if($this->applicant_id){
			$count = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
						
			$this->a_has_applicant_info = $count;
			$this->is_profile_exist 	 = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id)); 
			$this->ahid 				    =  Utilities::createHash($this->applicant_id);
			$this->aeid 				    =  Utilities::encrypt($this->applicant_id);
		}

	}

	function _save_applicant_info(){
		if(Utilities::isFormTokenValid($_POST['token'])) {
			$hash = Utilities::createHash($a_id);
			if($this->applicant_id){				
				$gcb = new G_Applicant_Profile();
				$gcb->setCompanyStructureId(1);
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
				$a_id = $gcb->save();
				
				//With job application details
				if($_POST['job_id']){
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
						$sprint_email->applicantExaminationMessageBodyEmail($exam_details,$applicant_details);
						$sprint_email->setFrom("hr@sprinthr.com");
						$sprint_email->setTo($_SESSION['sprint_applicant']['username']);						
						$sprint_email->setSubject("[SprintHR]Applicant Examination");
						$sprint_email->mainSendEmail();
					//
				}
				
				$eid = Utilities::encrypt($a_id);
				$hid = Utilities::createHash($a_id);
				$jeid= $_POST['job_id'];
				
				if($jeid == ""){
					$json['with_job_id'] = 0;
				}else{
					$json['with_job_id']	= 1;
					$json['jeid']			= $jeid;
				}
				
				$json['url']					= url("applicant/application_completed");
				$json['token']   			   = Utilities::createFormToken();
				$json['h_application_id'] 	= $eid;
				$json['ehash']				   = $hid;
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
		$jeid= $_GET['jeid'];
		
		Utilities::verifyHash(Utilities::decrypt($eid),$hid);
		
		if($jeid){
			$this->var['with_job_application'] = 1;
			$this->var['page_title']= 'Job Application Completed';
		}else{
			$this->var['with_job_application'] = 0;
			$this->var['page_title']= 'Registration Completed';
		}		
		$this->view->setTemplate('template_fullwidth.php');
		$this->view->render('application/application_completed.php',$this->var);	
	}	
}
?>