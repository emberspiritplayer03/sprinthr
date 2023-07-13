<?php
// Script Error Reporting
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class Job_Vacancy_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style_website.css');
		$this->c_date  			    = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');			
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];		
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
		Jquery::loadInlineValidation2();					
		$jxml   = new G_Job_Vacancy();
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'files/xml/job_vacancy/';
		$data   = $jxml->readActiveJobVacancyXMLFile($xmlUrl,G_Job_Vacancy::xmlFILENAME);
		if($data){				
			$this->var['data'] 		= $data;
		}		
		$this->var['ahid'] 		= $this->ahid;
		$this->var['aeid']		= $this->ehid;
		$this->var['page_title']= 'Job Vacancy List';
		$this->view->setTemplate('template_job_vacancy.php');
		$this->view->render('jobs/front/job_vacancy_list.php',$this->var);
	}
	
	function search() 
	{
		Jquery::loadInlineValidation2();			
		if($_POST['job_search_input']){
			$jxml   = new G_Job_Vacancy();
			$xmlUrl = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'files/xml/job_vacancy/';
			$data   = $jxml->readActiveJobVacancyXMLFile($xmlUrl,G_Job_Vacancy::xmlFILENAME);
			$data   = $jxml->searchXMLJob($_POST['job_search_input'],$data,"job_title");			
			$this->var['data'] 		= $data;
			$this->var['page_title']= 'Search Results';
			$this->view->setTemplate('template_job_vacancy.php');		
			$this->view->render('jobs/front/job_vacancy_list.php',$this->var);
		}else{			
			$this->var['page_title']= 'Search Results';
			$this->view->setTemplate('template_job_vacancy.php');
			$this->view->render('jobs/front/job_vacancy_list_no_records.php',$this->var);	
		}
	}
	
	function apply()
	{	
		if(!empty($_GET['jeid']) && !empty($_GET['jhid'])){
			$jeid = $_GET['jeid'];
			$jhid = $_GET['jhid'];
			if($this->applicant_id){
				$url = recruitment_url("applicant/apply?jeid=" . $jeid . "&jhid=" . $jhid);
				header( 'Location: ' . $url) ;
			}else{				

				Utilities::verifyHash(Utilities::decrypt($jeid),$jhid);
				
				Jquery::loadInlineValidation2();				
				//Jquery::loadTextBoxList();
				Jquery::loadJqueryFormSubmit();
				Jquery::loadTipsy();						
				Loader::appScript('register.js');								
				$this->var['jeid']      = $jeid;
				$this->var['token']		= Utilities::createFormToken();
				$this->var['action'] 	= url('register/save_register');
				$this->var['page_title']= 'Applicant Registration';
				$this->view->setTemplate('template_fullwidth.php');
				$this->view->render('application/forms/register_form.php',$this->var);
			}
		}elseif(!empty($_GET['eid']) && !empty($_GET['hid'])){			
			$eid = $_GET['eid'];
			$hid = $_GET['hid'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);
			$applicant_id = Utilities::decrypt($eid);			
			Loader::appScript("generic/main.js");
			Jquery::loadInlineValidation2();	
			//Jquery::loadTextBoxList();		
			Jquery::loadJqueryFormSubmit();
			Jquery::loadUploadify();	
					
			if($_GET['jeid']){				
				$with_job_application = 1;
				$jid = Utilities::decrypt($_GET['jeid']);				
				$j   = G_Job_Vacancy_Finder::findByJobIdAndIsActive($jid);
			}else{				
				$with_job_application = 0;
			}
			
			$a = G_Applicant_Logs_Finder::findById($applicant_id);			
			if($j){
				$this->var['j']      = $j;
			}
			
			$this->var['with_job_application'] = $with_job_application;
			$this->var['token']					  = Utilities::createFormToken();
			$this->var['a']					     = $a;
			$this->var['page_title']			  = 'Step 02: Applicant Details';
			$this->view->setTemplate('template_fullwidth.php');
			$this->view->render('application/forms/registration_form_applicant_details.php',$this->var);
		}
	}	
}
?>