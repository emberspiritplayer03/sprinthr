<?php
class Inquiry_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();			
		Loader::appScript('yshout/yshout.js');			
		Loader::appLibrary('class_loader');	
		Loader::appLibrary('ckeditor/ckeditor');		
		Loader::appLibrary('ckeditor/ckfinder/ckfinder');					
		$this->default_method = 'send';		
	}
	
	function send()
	{
		sleep(1);
		if(!empty($_POST['g_token'])){
				if(Utilities::isFormTokenValid($_POST['g_token'])) {
					$arSprint = $_POST['sprnt'];
					
					$msg .= "<img alt=\"SprintHR\" src=\"http://www.sprinthr.com/beta/themes/default/themes-images/logo.png\" /><br><br>";
					$msg .= "The following user has submitted an inquiry:<br><br>";
					$msg .= "<hr><br>";
					$msg .= "<table>";
					foreach($arSprint as $key => $value){
						$msg .= "<tr>";
							$msg .= "<td width='35%'>";
							$msg .= $key;
							$msg .= "</td>";
							$msg .= "<td width='45%'> : ";
							$msg .= $value;
							$msg .= "</td>";
						$msg .= "</tr>";
					}
							
					$msg .= "</table>";		
					$msg .= "<br><hr><br>";
					
					$subject   = '[SprintHR Recruitment]Inquiry';					
					$numsent   = 1;
					Tools::send_email_default("noreply@sprinthr.com", DEFAULT_EMAIL_RECIPIENT, $subject, $msg);
					
					if($numsent > 0){
						$json['is_success'] = 1;	
						$json['message']    = "<div class=\"alert alert-info\"><strong>Your inquiry was successfully sent.</strong>We will contact you as soon as possible. Thank you</div>";
					}else{
						$json['is_success'] = 0;
						$json['message']    = "<div class=\"alert alert-error\">Cannot send mail.</div>";			
					}
					
				
			}else{
				$json['is_success'] = 0;
				$json['message']    = "<div class=\"alert alert-error\">Invalid form <strong>token</strong>. Kindly refresh the page.</div>";		
			}
			echo json_encode($json);
		}
	}
}
?>