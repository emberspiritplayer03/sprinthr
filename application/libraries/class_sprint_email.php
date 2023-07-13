<?php
/**
* Gleent Inc. Email Class
*
* This class send email using swiftmailer
*
* @version 1.0.0
* @author Gleent Inc.
* @date created March-25-2013    
*/


// Sample Usage:
/********************
	$email = new Sprint_Email();			
	$email->setFrom("hr@sprinthr.com");
	$email->setTo($_POST["email_address"]);
	$email->setSubject("[SprintHR: Register] Verify Registration");
	$email->recruitmentMessageBodyEmail($message);
	$email->recruitmentConfirmationEmail();	
*/


class Sprint_Email
{
	protected $message;
	protected $from;
	protected $to;	
	protected $subject;
	
	//object
	protected $gal;
	
	const PORT = '26';
	const SMTP = 'mail.sprinthr.com';
	const UN   = 'esender@sprinthr.com';
	const PW   = 'abc123!@#';	
	const EFROM    = "noreply@sprinthr.com";	
	const EFROM_PW = "abc123!@#";

	public function setFrom($value) {
		$this->from = $value;
	}
	
	public function setTo($value) {					
		$this->to = $value;	
	}
	
	public function setMessage($value) {
		$this->message = $value;
	}
	
	public function setSubject($value) {
		$this->subject = $value;
	}

	public function recruitmentConfirmationEmail() {
		
		if($this->checkRequiredFunction()) {

			Loader::appMainLibrary('swiftmailer/lib/swift_required');		
		
			//Create the Transport
			$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
  				->setUsername(self::UN)
  				->setPassword(self::PW)
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
			$message = Swift_Message::newInstance($this->subject)
  				->setFrom(array($this->from => $this->from))
  				->setTo(array($this->to => 'SprintHR Recruitment'))
  				->setBody($this->message,'text/html')
  			;   		
			//Send the message
			if( ENABLE_EMAIL_NOTIFICATION ){
				$result = $mailer->send($message);
			}
		
			if($result) {
				return TRUE;
				//echo 'successfully send mail';
			}else{
				return FALSE;
				//echo 'ERROR SENDING MESSAGE';	
			}  			
		}
		
	}
	
	public function debugMainEmail() {
		
		if($this->checkRequiredFunction()) {

			Loader::appLibrary('swiftmailer/lib/swift_required');	
					//Create the Transport
			$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
  				->setUsername(self::UN)
  				->setPassword(self::PW)
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
			$message = Swift_Message::newInstance($this->subject)
  				->setFrom(array($this->from => $this->from))
  				->setTo(array($this->to => 'SpirntHR Recruitment'))
  				->setBody($this->message,'text/html')
  			;  
		
			if( ENABLE_EMAIL_NOTIFICATION ){
				try  
				{  
					$result = $mailer->send($message);			
				}  
				catch (Exception $e)  
				{  
				 echo($e->getMessage().'<pre>'.$e->getTraceAsString().'</pre>');
				}    	
			}else{
				$result = false;
			}		
  			 	
  						
			//Send the message
			/*try{
				$result = $mailer->send($message);								
			}catch(Exception $e){				
				$result = Tools::send_email_default($this->from,$this->to, $this->subject, $this->message);				 
			}*/
			
			if($result) {
				return TRUE;
				//echo 'successfully send mail';
			}else{
				return FALSE;
				//echo 'ERROR SENDING MESSAGE';	
			}  			
		}
		
	}
	
	public function mainSendEmail() {		
		if($this->checkRequiredFunction()) {			

			Loader::appLibrary('swiftmailer/lib/swift_required');	
					//Create the Transport
			$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
  				->setUsername(self::UN)
  				->setPassword(self::PW)
  			;

  			if( $this->from == '' ){
  				$this->from = self::EFROM;
  			}

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
			$message = Swift_Message::newInstance($this->subject)
  				->setFrom(array($this->from => $this->from))
  				->setTo(array($this->to => $this->to))
  				->setBody($this->message,'text/html')
  			;   		
			//Send the message
			if( ENABLE_EMAIL_NOTIFICATION ){
				try{
					$result = $mailer->send($message);								
				}catch(Exception $e){				
					$result = Tools::send_email_default($this->from,$this->to, $this->subject, $this->message);				 
				}
				
			}else{
				$result = false;
			}
		
			if($result) {
				return TRUE;
				//echo 'successfully send mail';
			}else{
				return FALSE;
				//echo 'ERROR SENDING MESSAGE';	
			}  			
		}
		
	}
	
	public function applicantExaminationEmail() {
		if(APPLICANT_EXAMINATION_SEND_EMAIL == true) {	
			if($this->checkRequiredFunction()) {
	
				Loader::appMainLibrary('swiftmailer/lib/swift_required');		
			
				//Create the Transport
				$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
	  				->setUsername(self::UN)
	  				->setPassword(self::PW)
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
				$message = Swift_Message::newInstance($this->subject)
	  				->setFrom(array($this->from => $this->from))
	  				->setTo(array($this->to => 'SprintHR Recruitment'))
	  				->setBody($this->message,'text/html')
	  			;
	   
				//Send the message
				if( ENABLE_EMAIL_NOTIFICATION ){
					$result = $mailer->send($message);
				}else{
					$result = false;
				}
			
				if($result) {
					return TRUE;
				}else{
					return FALSE;	
				}  			
			}
		}			
	}
	
	public function applicationEventEmail() {
		if(APPLICANT_EVENT_SEND_EMAIL == true) {	
			if($this->checkRequiredFunction()) {
	
				Loader::appMainLibrary('swiftmailer/lib/swift_required');		
			
				//Create the Transport
				$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
	  				->setUsername(self::UN)
	  				->setPassword(self::PW)
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
				$message = Swift_Message::newInstance($this->subject)
	  				->setFrom(array($this->from => $this->from))
	  				->setTo(array($this->to => 'SprintHR Recruitment'))
	  				->setBody($this->message,'text/html')
	  			;
	   
				//Send the message
				if( ENABLE_EMAIL_NOTIFICATION ){
					$result = $mailer->send($message);
				}else{
					$result = false;
				}
			
				if($result) {
					return TRUE;
					//echo 'successfully send mail';
				}else{
					return FALSE;
					//echo 'ERROR SENDING MESSAGE';	
				}  			
			}
		}	
	}
	
	public function sendEmail() {
		
		if($this->checkRequiredFunction()) {

			Loader::appMainLibrary('swiftmailer/lib/swift_required');		
		
			//Create the Transport			
			$transport = Swift_SmtpTransport::newInstance(self::SMTP, self::PORT)
  				->setUsername(self::UN)
  				->setPassword(self::PW)
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
			$message = Swift_Message::newInstance($this->subject)
  				->setFrom(array($this->from => $this->from))
  				->setTo(array($this->to => $this->to))
  				->setBody($this->message,'text/html')
  			;
			//Send the message
			if( ENABLE_EMAIL_NOTIFICATION ){
				try{
					$result = $mailer->send($message);								
				}catch(Exception $e){				
					$result = Tools::send_email_default($this->from,$this->to, $this->subject, $this->message);				 
				}
			}else{
				$result = false;
			}			
									
			if($result) {
				return TRUE;
				//echo 'successfully send mail';
			}else{
				return FALSE;
				//echo 'ERROR SENDING MESSAGE';	
			}  			
		}
		
	} 
	
	public function applicantChangePassword(G_Applicant_Logs $gal,$new_password) {
		$this->message	    = "<p>Good day <b>" . $gal->getLastName() . ", " . $gal->getFirstName() . "</b>,</p>";
		$this->message    .= '<p>Below is your new password :</p>';		
		$this->message	   .= "New password : <b>" . $new_password . "</b><br /><br />";			
		$this->message	   .= "<p>You may change your password anytime by clicking the below link.</p>";

		$url = url("applicant/change_password");		
		$this->message	   .= "<a href=" . $url . ">Change Password</a>";
		$this->message	   .= "<br /><br />Thank you.";
	}
	
	public function recruitmentMessageBodyEmail(G_Applicant_Logs $gal) {
		$this->message	    = "<p>Good day <b>" . $gal->getLastName() . ", " . $gal->getFirstName() . "</b>,</p>";
		$this->message    .= '<p>Thank you for registering.</p>';
		$this->message	   .= "Here's your default password: ";
		$this->message	   .= "<b>" . $gal->getDefaultPassword() . "</b><br /><br />";			
		$this->message	   .= "To complete the registration process, kindly click the below url to activate your account.<br /><br />";
		$this->message	   .= "<a href=" . $gal->getLink() . ">" . $gal->getLink() . "</a>";
		$this->message	   .= "<br /><br />Thank you.";
	}
	
	public function recruitmentSubMessageBodyEmail(G_Applicant_Logs $gal) {		
		$this->message	    = "<p>Good day <b>" . $gal->getLastName() . ", " . $gal->getFirstName() . "</b>,</p>";
		$this->message    .= '<p>Thank you for registering.</p>';
		$this->message	   .= "Here are your login details:<br /><br />";
		$this->message	   .= "Username : <b>" . $gal->getEmail() . "</b><br />";
		$this->message	   .= "Default password : <b>" . $gal->getDefaultPassword() . "</b><br /><br />";			
		$this->message	   .= "To complete the registration process, kindly click the below url to proceed with the next step.<br /><br />";
		$this->message	   .= "<a href=" . $gal->getLink() . ">" . $gal->getLink() . "</a>";
		$this->message	   .= "<p>Note: <b>Activation link will expire after 24hrs.</b></p>";
		$this->message	   .= "<br /><br />Thank you.";		
	}
	
	public function applicantExaminationMessageBodyEmail($exam_details,$applicant_details) {		
		if($exam_details){
			$this->message	   .= "<p>Good day <b>" . $applicant_details['name'] . "</b>,</p>";
			$this->message	   .= "<p>Kindly click the exam title below to start the corresponding examination.</p>";	
			$this->message	   .= "<p><b>Examination List</b></p>";			
			$this->message    .= "<ol>";
			foreach($exam_details as $e){
				$this->message    .= "<li>";
					$url = url_to_recruitment("examination?code=".$e['code']);										
					$this->message    .= "Exam Title : <a href=\"" . $url . "\"><b>" . $e['title'] . "</b></a><br />";
					$this->message    .= "Passing Percentage: <b>" . $e['passing_percentage'] . "%</b><br />";
					$this->message		.= "<br />";
				$this->message    .= "</li>";
			}
			$this->message    .= "</ol>";		
			$this->message    .= "<br />";
			$this->message    .= "<p>Good luck!</p>";
		}		
	}
	
	public function applicantExaminationMessageBodyEmailForApplicantMember($exam_details,$applicant_details) {		
		if($exam_details){
			$this->message	   .= "<p>Good day <b>" . $applicant_details['name'] . "</b>,</p>";
			$this->message	   .= "<p>Kindly click the exam title below to start the corresponding examination.</p>";	
			$this->message	   .= "<p><b>Examination List</b></p>";			
			$this->message    .= "<ol>";
			foreach($exam_details as $e){
				$this->message    .= "<li>";
					$url = url("examination?code=".$e['code']);										
					$this->message    .= "Exam Title : <a href=\"" . $url . "\"><b>" . $e['title'] . "</b></a><br />";
					$this->message    .= "Passing Percentage: <b>" . $e['passing_percentage'] . "%</b><br />";
					$this->message		.= "<br />";
				$this->message    .= "</li>";
			}
			$this->message    .= "</ol>";		
			$this->message    .= "<br />";
			$this->message    .= "<p>Good luck!</p>";
		}		
	}
	
	public function applicantExaminationMessageBodyEmailtoRecruitment($exam_details,$applicant_details) {		
		if($exam_details){
			$this->message	   .= "<p>Good day <b>" . $applicant_details['name'] . "</b>,</p>";
			$this->message	   .= "<p>Kindly click the link below and enter the corresponding examination code to the take exam.</p>";
			$this->message    .= "URL : <a href=" . url_to_recruitment("examination") . "><b>" . url_to_recruitment("examination") . "</b></a>";
			$this->message    .= "<ul>";
			foreach($exam_details as $e){
				$this->message    .= "<li>";
					$this->message    .= "Code : " . $e['code'] . "<br />";
					$this->message    .= "Exam Title : " . $e['title'] . "<br />";
					$this->message    .= "Passing Percentage: " . $e['passing_percentage'];
				$this->message    .= "</li>";
			}
			$this->message    .= "</ul>";		
			$this->message    .= "<br />";
			$this->message    .= "<p>Good luck!</p>";
		}		
	}
	
	public function examinationMessageBodyEmailtoApplicant($exam_details,$applicant_details) {		
		if($exam_details){
			$this->message	   .= "<p>Good day <b>" . $applicant_details['name'] . "</b>,</p>";
			$this->message	   .= "<p>Below are the list of your examination. Kindly click the examination title below to proceed with your examination.</p>";			
			
			$this->message    .= "<ol>";
			foreach($exam_details as $e){
				$url = url_to_recruitment("examination?code=" . $e['code']);				
				$this->message    .= "<li>";
					$this->message    .= "URL : <a href=" . $url . "><b>" . $e['title'] . "</b></a>";
				$this->message    .= "</li>";
			}
			
			$this->message    .= "</ol>";		
			$this->message    .= "<br />";
			$this->message    .= "<p>Good luck!</p>";
		}		
	}
		
	public function checkRequiredFunction() {
		//check required php function to send email
		//function_exists('proc_open') ? "Yep, that will work" : "Sorry, that won't work";
		if(function_exists('proc_open')) {
			return TRUE;
		}else{
			exit("function 'proc_open' must be enabled..");
		}
		 	
	}
	
	public function eventInterviewBodyMessage($details) {		
		$interviewer = G_Employee_Finder::findById($details['interviewer_id']);
		if($interviewer){
			$add_msg = "Interviewer : <b>" . $interviewer->getLastname() . ", " . $interviewer->getFirstname() . "</b>,<br />";
		}else{
			$add_msg = "";
		}
		
		$add_msg .= "Date / Time of interview : <b>" . $details['date_of_interview'] . " " . $details['time'] . "</b>";
		$this->message    = "<p>Good day <b>" . $details['name'] . "</b></p>";
		$this->message	   .= "<p>" . $details['content'] . "</p>";
		$this->message		.= $add_msg;
		$this->message	   .= "<p>Thank you.</p>";		
	}
	
	public function eventJobOfferedBodyMessage($details) {
		$this->message    = "<p>Good day <b>" . $details['name'] . "</b>,</p>";
		$this->message	   .= "<p>" . $details['content'] . "</p>";
		$this->message	   .= "<p>Thank you.</p>";	
	}
	
	public function eventOfferDeclinedBodyMessage($details) {
		$this->message    = "<p>Good day <b>" . $details['name'] . "</b>,</p>";
		$this->message	   .= "<p>" . $details['content'] . "</p>";
		$this->message	   .= "<p>Thank you.</p>";					
	}
	
	public function eventRejectedBodyMessage($details) {
		$this->message    = "<p>Good day <b>" . $details['name'] . "</b>,</p>";
		$this->message	   .= "<p>" . $details['content'] . "</p>";
		$this->message	   .= "<p>Thank you.</p>";					
	}
	
	public function eventHiredBodyMessage($details) {
		$this->message    = "<p>Good day <b>" . $details['name'] . "</b>,</p>";
		$this->message	   .= "<p>" . $details['content'] . "</p>";
		$this->message	   .= "<p>Thank you.</p>";					
	}

	private function addHeaderFooter() {
		$header_footer = unserialize(EMAIL_HDR_FTR);		
		$this->message = $header_footer['header'] . $this->message . $header_footer['footer'];		
	}

	public function eEmployeeRequestNotification($details = array()){		
		if( !empty($details) && !empty($this->to) ){
			$request_eid  = $details['request_eid'];
			$employee_eid = $details['employee_eid'];
			$type = $details['request_type'];

			$url = employee_url("requests/view?request_eid={$request_eid}&employee_eid={$employee_eid}&type={$type}");
			$this->subject 	= "[SprintHR] Employee Request Approval";			
			$this->from 	= self::EFROM;		
			$this->message  = "<p>Hi <b>" . $details['employee_name'] . "!<b/></p>
							   <p>Someone made a request that needs your approval.</p>
							   <p>Kindly click the below url to see the request details.</p>
							   <p>Request details : <a href=\"{$url}\">{$url}</a></p>							   
							   <Br />
							   <p>Thank you and have a great day!</p>
			";	
			$this->addHeaderFooter();
	        $this->sendEmail();	
		}
	}
				
}

?>