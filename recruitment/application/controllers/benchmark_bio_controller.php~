<?php
class Benchmark_Bio_Controller extends Controller
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
			$this->ahid =  Utilities::createHash($this->applicant_id);
			$this->aeid =  Utilities::encrypt($this->applicant_id);
			
			//to patch			
			$count = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
			$this->a_has_applicant_info = $count; 
		}
	}

	function getAllExam()
	{
		$exams = G_Exam_Finder::findAllExamByJobIdAndApplyToAllJobs(1);
		echo "<pre>";
		print_r($exams);	
	}	
	

	function createRandomPassword()
	{
		$password 			  = Tools::createRandomPasswordByLength(10);
		$epassword 		     = Utilities::encrypt($password);	
		echo $password . "<br />" . $epassword;
	}	
	
	function captureIPAddressAndCountry()
	{
		$my_ip = Tools::getRealIpAddr();		
		echo $my_ip;
	}
	
	function getCountryByIP()
	{
		$my_ip   = Tools::getRealIpAddr();
		$url     = "http://api.hostip.info/country.php?ip={$my_ip}&position=true";
		$data    = file_get_contents($url);
		//$curl_data = Tools::file_get_contents_curl($url);		
		echo $data;
	}
	
	function getHRDifference()
	{
		$total_hr = Tools::computeTimeDifferenceInHrs("2013-03-22 17:30:24",Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));
		echo $total_hr;
	}
	
	function index() 
	{
		echo 2;
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
					$sprint_email->applicantExaminationMessageBodyEmail($exam_details,$applicant_details);
					$sprint_email->setFrom("hr@sprinthr.com");
					$sprint_email->setTo($_SESSION['sprint_applicant']['username']);
					$sprint_email->setSubject("[SprintHR]Applicant Examination");
					$sprint_email->sendEmail();
				//			
				
				$eid = Utilities::encrypt($a_id);
				$hid = Utilities::createHash($a_id);			
				
				$json['url']					= url("applicant/application_completed?eid={$eid}&hid={$hid}");
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
	
	function test_exam_code_generator()
	{
		$counter = 1;
		for($x=1;$x<=5;$x++){
			$exam_code = substr(md5(strtotime(date("Y-m-d H:i:s")) + $counter),0,7);				
			echo "{$exam_code}<br />";
			$counter++;
		}
	}
	
	function job_vacancy_list() 
	{
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
	
	function application_completed()
	{
	
	}
	
	function examDetailsTagToJob()
	{
		$e = new G_Exam();		
		$exam_details = $e->sendExaminationToApplicant(1);
		echo "<pre>";
		print_r($exam_details);		
		foreach($exam_details as $e){
			echo $e['code'];
		}
		
		$sprint_email = new Sprint_Email();
		$sprint_email->applicantExaminationMessageBodyEmail($exam_details,$applicant_details);
	}

	function jobVacancyXMLReader()
	{	
		$jxml   = new G_Job_Vacancy();
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . 'files/xml/job_vacancy/';		
		$data   = $jxml->readActiveJobVacancyXMLFile($xmlUrl,G_Job_Vacancy::xmlFILENAME);		
		echo "<pre>";
		print_r($data);
			foreach($data as $key => $value){
				foreach($value as $keysub => $subvalue){
				echo "Job Name: {$subvalue['job_title']}<br />";
				echo "Job Description: {$subvalue[job_description]}<br />";
				echo "<a>/a>";
				}
			}	
			
	}	
	
}
?>