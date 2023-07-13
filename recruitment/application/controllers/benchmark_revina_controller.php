<?php
class Benchmark_Revina_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appUtilities();
		//Loader::appScript('settings.js');
		//Loader::appScript('settings_base.js');
		//Loader::appScript('startup.js');
		Loader::appStyle('style.css');
		//Loader::appScript('jquerytimepicker/jquery.timepicker.min.js');
		//Loader::appStyle('jquerytimepicker/jquery.timepicker.css');
		$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		//$this->var['settings'] = 'current';
		//$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}

	function index()
	{	
		$c_structure = G_Company_Structure_Finder::findById(6);
		echo '<pre>';
		print_r($c_structure);	
	}
	
	function add_policy()
	{
		$t = new G_Settings_Policy();
		$t->setPolicy("File OT when later cccc");
		$t->setDescription("Settings for filing of OT when late cccc ccc");
		$t->setIsActive("Yes");
		$t->save();			
	}
	
	function update_policy()
	{
		$u = G_Settings_Policy_Finder::findById(1);
		$u->setPolicy("test edit");
		$u->setDescription("Test Edit");
		$u->setIsActive("No");
		$u->save();			
	}
	
	function find_policy()
	{
		$p = G_Settings_Policy_Finder::findById(1);
		
		echo '<pre>';
		print_r($p);
		echo '</pre>';
	}
	
	function delete_policy()
	{
		$p = G_Settings_Policy_Finder::findById(1);
		$p->delete();
	}
	
	function view_ot_policy()
	{
		// for tdd
		$policy = new G_Settings_Policy();
		$policy->OvertimePolicyWhenLate();
		
		if($policy->OvertimePolicyWhenLate() == G_Settings_Policy::IS_ACTIVATED){
			echo 'This is activated';			
		}else{
			echo 'Not Activated';	
		}
	}
	
	function add_memo_template()
	{
		$post 						= array();
		$post['title'] 			= 'Revina Template';
		$post['content'] 			= 'This is the content';
		$post['created_by'] 		= 'b1';
		$post['date_created'] 	= $this->c_date;
		
		if(!empty($post)) {
			
		$sm = new G_Settings_Memo();
		$sm->setTitle($post['title']);
		$sm->setContent($post['content']);
		$sm->setCreatedBy($post['created_by']);
		$sm->setDateCreated($post['date_created']);			
		
		if($sm->save()) {
			echo 'save';			
		}
		
		} else {
			echo 'not save';		
		}	
	}
	
	function update_memo_template()
	{
		$id = 1;		
		$post 						= array();
		$post['title'] 			= 'Revina Template update';
		$post['content'] 			= 'This is the content update';
		$post['created_by'] 		= 'b1 gar';
		$post['date_created'] 	= $this->c_date;
		
		if(!empty($post)) {
			
		$sm = G_Settings_Memo_Finder::findById($id);
		$sm->setTitle($post['title']);
		$sm->setContent($post['content']);
		$sm->setCreatedBy($post['created_by']);
		$sm->setDateCreated($post['date_created']);			
		
		$sm->save();
		echo 'update successful';
		
		}
	}
	
	function delete_memo_template()
	{
		$id = 1;
		if($id) {
			$sm = G_Settings_Memo_Finder::findById($id);
			$sm->delete();
			echo 'successfully deleted';
		}else{
			echo 'error deleting records';	
		}
	}
	
	function add_update_applicant_logs()
	{
		//$app_id 								= 1;
		$post 								= array();
		$post['ip'] 						= '1.80.81.801 xxx';
		$post['country'] 					= 'Philippines xxx';
		$post['firstname'] 				= 'Bryann xxx';
		$post['lastname'] 				= 'Revina xxx';
		$post['email'] 					= 'bryann.revina@gleent.comxx';
		$post['password'] 				= '0dfddfsf210ffddffd68dsf98dfrefdkjdsf xxx';
		$post['status'] 					= 'Single and Very Available xx';
		$post['date_time_created'] 	= $this->c_date;
		$post['date_time_validated'] 	= $this->c_date;
		$post['link'] 						= 'http://sprinthr/recruitment/index.phpxxx';
		
		if(!empty($post)) {
		
		if(!empty($app_id)) {
			$al =  G_Applicant_Logs_Finder::findById(1);	
		} else {
			$al = new G_Applicant_Logs();	
		}
		$al->setIp($post['ip']);
		$al->setCountry($post['country']);
		$al->setFirstName($post['firstname']);
		$al->setLastName($post['lastname']);
		$al->setEmail($post['email']);
		$al->setPassword($post['password']);
		$al->setStatus($post['status']);
		$al->setDateTimeCreated($post['date_time_created']);
		$al->setDateTimeValidated($post['date_time_validated']);
		$al->setLink($post['link']);												
		
		$al->save();
		echo 'saved';
		
		} else {
			echo 'not save';		
		}			
	}
	
	function delete_applicant_logs()
	{
		$al =  G_Applicant_Logs_Finder::findById(1);	
		$al->delete();
		echo 'successfuly deleted';
	}
	
	function test_swiftmail()
	{
		//test function required by swiftmailer
		echo function_exists('proc_open') ? "Yep, that will work" : "Sorry, that won't work";
				
		Loader::appLibrary('swiftmailer/lib/swift_required');
					
		//Create the Transport
		$transport = Swift_SmtpTransport::newInstance('mail.slickpoint.com', 26)
  			->setUsername('sender_email@slickpoint.com')
  			->setPassword('abc123!')
  		;
 
		/*
		You could alternatively use a different transport such as Sendmail or Mail:
 
		//Sendmail
		$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
 
		//Mail
		$transport = Swift_MailTransport::newInstance();
		*/
 
		//Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
 
		//Create a message
		$message = Swift_Message::newInstance('Wonderful Subject')
  			->setFrom(array('john@doe.com' => 'John Doe'))
  			->setTo(array('bryann.revina@gmail.com', 'bryann.revina@gmail.com' => 'A name'))
  			->setBody('Here is the message itself')
  		;
   
		//Send the message
		$result = $mailer->send($message);
		
		if($result) {
			echo 'successfully send mail';
		}
 
		/*
		You can alternatively use batchSend() to send the message
 
		$result = $mailer->batchSend($message);
		*/
	}
	
	function send_sprint_email() {
		
		$email = new Sprint_Email();
		
		$from 		= 'hr@sprinthr.com';
		$to   		= 'bryann.revina@gmail.com';
		$subject 	= 'Thanks for Registering';
		$message    = '<h3>THIS IS A SAMPLE MESSAGE</h3>';
		
		$email->recruitmentConfirmationEmail($from,$to,$subject,$message);
	}
	
	function testDecryptDefaultPw() {
		$default_password = Utilities::decrypt('qfEubqdhhEv');
		echo $default_password;
	}
	
	function application_details() {

		$this->view->setTemplate('template_registration.php');
		$this->view->render('applicant/front/application_details.php',$this->var);		
	} 
	
	function add_update_applicant_profile() {
		 //$app_id 							= 1;
		 $post 								= array();		
		 $post['lastname']				= 'Revina';
		 $post['firstname']				= 'Bryann';
		 $post['middlename']				= 'Dimabayao';
		 $post['extension_name']		= 'I';
		 $post['birthdate']				= $this->c_date;
		 $post['gender']					= 'Male';
		 $post['marital_status']		= 'Single';	
		 $post['home_telephone']		= '5454';
		 $post['mobile']					= '4545';
		 $post['birth_place']			= 'Iriga City';	
		 $post['address']					= 'San Vicente, Binan, Laguna';
		 $post['city']						= 'Bina City';
		 $post['province']				= 'Laguna';
		 $post['zip_code']				= '1544';
		 $post['sss_number']				= '343 3434 34';
		 $post['tin_number']				= '3434 3434 43';
		 $post['philhealth_number']	= '4545 454';
		 $post['pagibig_number']		= '34343';
		 $post['resume_name']			= 'dsfds';
		 $post['resume_path']			= 'dfdsf';
		
		
		if(!empty($post)) {
		
			if(!empty($app_id)) {
				$gcb =  G_Applicant_Profile_Finder::findById($app_id);	
			} else {
				$gcb = new G_Applicant_Profile();	
			}
			
			$gcb->setId($post['id']);
			$gcb->setLastname($post['lastname']);
			$gcb->setFirstname($post['firstname']);
			$gcb->setMiddlename($post['middlename']);
			$gcb->setExtensionName($post['extension_name']);
			$gcb->setBirthdate($post['birthdate']);
			$gcb->setGender($post['gender']);
			$gcb->setMaritalStatus($post['marital_status']);
			$gcb->setHomeTelephone($post['home_telephone']);
			$gcb->setMobile($post['mobile']);
			$gcb->setBirthPlace($post['birth_place']);
			$gcb->setAddress($post['address']);
			$gcb->setCity($post['city']);
			$gcb->setProvince($post['province']);
			$gcb->setZipCode($post['zip_code']);
			$gcb->setSssNumber($post['sss_number']);
			$gcb->setTinNumber($post['tin_number']);
			$gcb->setPhilhealthNumber($post['philhealth_number']);
			$gcb->setPagibigNumber($post['pagibig_number']);
			$gcb->setResumeName($post['resume_name']);
			$gcb->setResumePath($post['resume_path']);
						
			$gcb->save();
			echo 'saved';
		
		} else {
			echo 'not save';		
		}			
		
	}
	
	
	
}
?>