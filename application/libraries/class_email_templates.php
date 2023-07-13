<?php
class Email_Templates {

	
	public static function leaveForApproval($emails,$employee,$leave) {
		//Emails   = 'array("bryan.bio@gleent.com" => "Bryan Bio", "test@test.com" => "Test Email")
		//Employee = array('name'=>'Bryan Bio','department'=>'Developer','position'=>'Software Engineer')
		//Leave  = array('date_applied'=>'10/02/2012','leave_type'=>'Sick Leave','date_to'=>'10/03/2012','reasons'=>'Not Feeling Well')
		$to       = $emails;
		$subject  = '[SprintHR]Leave Request';			
		$msg .= '<img alt="Company Logo" src="#" /><br><br>';
			$msg .= '<p>Hi,</p>';
			$msg .= 'The following employee has submitted a request. Below are the details :<br><br>';
			$msg .= '<hr><br>';
			$msg .= '<table>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Employee Name';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['name'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Department';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['department'];
					$msg .= '</td>';
				$msg .= '</tr>';				
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Position';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['position'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date Applied';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $leave['date_applied'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Leave Type';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $leave['type'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date From';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $leave['date_from'];
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date To';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $leave['date_to'];
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Reason';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $leave['reason'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= '';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .='';
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$url_approve = url();
					$msg .= '<td width="35%" colspan="2">';
					$msg .= 'Click <a href="$url_approve">here</a> to Approve';
					$msg .= '</td>';
					
					$url_disapprove = url();
					$msg .= '<td width="45%"> : ';
					$msg .='Click <a href="$url_disapprove">here</a> to Disapprove';
					$msg .= '</td>';
				$msg .= '</tr>';							
			$msg .= '</table>';	
			
			$msg .= '<br><hr><br>';
			
			$msg .= '<small><b><i>Note : This is an autosender. Do not reply.</i></b></small><br><br>';
			
			$email     = $emails;		
			$message11   = Swift_Message::newInstance();					
			$transport11 = Swift_SmtpTransport::newInstance('mail.krikel.com', 26);
			$transport11->setUsername("sender_email@krikel.com");
			$transport11->setpassword("abc123!");	
			
			$mailer11 = Swift_Mailer::newInstance($transport11);			
			$message11->setSubject($subject);			
			$message11->setFrom(array('noreply@krikel.com' => 'SprintHR'));	
					
			foreach($email as $key=>$value) :
				//if (preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$value)){
					$message11->setTo(array($value => $key));								
					$message11->setBody($msg , 'text/html');
					$numsent += $mailer11->send($message11);
				//}
			endforeach;	
			
		return $numsent;
	}
	
	public static function overtimeForApproval($emails,$employee,$ot) {
		//Emails   = 'array("bryan.bio@gleent.com" => "Bryan Bio", "test@test.com" => "Test Email")
		//Employee = Array('name'=>'Bryan Bio','department'=>'Developer','position'=>'Software Engineer')
		//OT  = Array('date_applied'=>'10/02/2012','date_of_ot'=>'10/05/03','time_from'=>'06:00 PM','time_to'=>'07:00 PM')
		
		$to       = $emails;
		$subject  = '[SprintHR]Overtime Request';			
		$msg .= '<img alt="Company Logo" src="#" /><br><br>';
			$msg .= '<p>Hi,</p>';
			$msg .= 'The following employee has submitted a request. Below are the details :<br><br>';
			$msg .= '<hr><br>';
			$msg .= '<table>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Employee Name';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['name'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Department';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['department'];
					$msg .= '</td>';
				$msg .= '</tr>';				
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Position';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['position'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date Applied';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['date_applied'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date of Overtime';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['date_of_ot'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Time From';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['time_from'];
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Time To';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['time_to'];
					$msg .= '</td>';
				$msg .= '</tr>';					
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= '';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .='';
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$url_approve = url();
					$msg .= '<td width="35%" colspan="2">';
					$msg .= 'Click <a href="$url_approve">here</a> to Approve';
					$msg .= '</td>';
					
					$url_disapprove = url();
					$msg .= '<td width="45%"> : ';
					$msg .='Click <a href="$url_disapprove">here</a> to Disapprove';
					$msg .= '</td>';
				$msg .= '</tr>';							
			$msg .= '</table>';	
			
			$msg .= '<br><hr><br>';
			
			$msg .= '<small><b><i>Note : This is an autosender. Do not reply.</i></b></small><br><br>';
			
			$email     = $emails;		
			$message11   = Swift_Message::newInstance();					
			$transport11 = Swift_SmtpTransport::newInstance('mail.krikel.com', 26);
			$transport11->setUsername("sender_email@krikel.com");
			$transport11->setpassword("abc123!");	
			
			$mailer11 = Swift_Mailer::newInstance($transport11);			
			$message11->setSubject($subject);			
			$message11->setFrom(array('noreply@krikel.com' => 'SprintHR'));	
					
			foreach($email as $key=>$value) :
				//if (preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$value)){
					$message11->setTo(array($value => $key));								
					$message11->setBody($msg , 'text/html');
					$numsent += $mailer11->send($message11);
				//}
			endforeach;	
			
		return $numsent;
	}
	
	public static function bufferOvertimeApproval($approvers, $employee,$ot) {
		//Emails   = 'array("bryan.bio@gleent.com" => "Bryan Bio", "test@test.com" => "Test Email")
		//Employee = Array('name'=>'Bryan Bio','department'=>'Developer','position'=>'Software Engineer')
		//OT  = Array('date_applied'=>'10/02/2012','date_of_ot'=>'10/05/03','time_from'=>'06:00 PM','time_to'=>'07:00 PM')
					
		$msg .= '<img alt="Company Logo" src="#" /><br><br>';
			$msg .= '<p>Hi,</p>';
			$msg .= 'The following employee has submitted a request. Below are the details :<br><br>';
			$msg .= '<hr><br>';
			$msg .= '<table>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Employee Name';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['name'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Department';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['department'];
					$msg .= '</td>';
				$msg .= '</tr>';				
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Position';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $employee['position'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date Applied';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['date_applied'];
					$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Date of Overtime';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['date_of_ot'];
					$msg .= '</td>';
				$msg .= '</tr>';		
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Time From';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['time_from'];
					$msg .= '</td>';
				$msg .= '</tr>';	
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Time To';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['time_to'];
					$msg .= '</td>';
				$msg .= '</tr>';					
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= '';
					$msg .= '</td>';
				$msg .= '</tr>';
				
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= 'Reason';
					$msg .= '</td>';
					$msg .= '<td width="45%"> : ';
					$msg .= $ot['reason'];
					$msg .= '</td>';
				$msg .= '</tr>';					
				$msg .= '<tr>';
					$msg .= '<td width="35%">';
					$msg .= '';
					$msg .= '</td>';
				$msg .= '</tr>';	
				
				$msg .= '<tr>';
					$url_approve = $ot['url_approve'];
					$msg .= '<td width="35%" colspan="2">';
					$msg .= 'Click <a href="'.$url_approve.'">here</a> to Approve';
					$msg .= '</td>';
					
					$url_disapprove = $ot['url_disapprove'];
					$msg .= '<td width="45%"> : ';
					$msg .='Click <a href="'.$url_disapprove.'">here</a> to Disapprove';
					$msg .= '</td>';
				$msg .= '</tr>';							
			$msg .= '</table>';	
			
			$msg .= '<br><hr><br>';
			
			$msg .= '<small><b><i>Note : This is an autosender. Do not reply.</i></b></small><br><br>';	
			
			$subject  = '[SprintHr] Overtime Request (FOR APPROVAL)';
			$array_sent_from = array('noreply@krikel.com','noreply');
			$sent_from 		 = serialize($array_sent_from);
			$email_address 	 = $approvers['email'];  // approvers email address
			
			$eb = new G_Email_Buffer;
			$eb->setFrom($sent_from);
			$eb->setEmailAddress($email_address);
			$eb->setName();
			$eb->setSubject($subject);
			$eb->setMessage($msg);
			$eb->setIsSent(G_Email_Buffer::NO);
			$eb->setIsArchive(G_Email_Buffer::NO);
			$eb->setErrorMessage((empty($email_address) ? 'No Email Address' : '' ));
			$eb->setDateAdded($ot['date_applied']);
			$eb->save();
			
			sleep(1);
			
			$parts=parse_url(url('email/send_email_buffer'));
			$fp = fsockopen($parts['host'], 
			isset($parts['port'])?$parts['port']:80,$errno, $errstr, 30);
			
			if (!$fp) {}
			else {
			  $out = "POST ".$parts['path']." HTTP/1.1\r\n";
			  $out.= "Host: ".$parts['host']."\r\n";
			  $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			  $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
			  $out.= "Connection: Close\r\n\r\n";
			  if (isset($parts['query'])) $out.= $parts['query'];				
			  fwrite($fp, $out);
			  fclose($fp);
			}
	}
	
	/*
		Usage : 
		$era = G_Employee_Request_Approver_Finder::findById(1);
		Email_Templates::sendApproverRequestNotification($era);	
	*/
	public static function sendApproverRequestNotification($era,$request_type = "") {
		if($era) {
			// get employee name, position, department of requestor
			$requestor = self::getRequestorDetails($era); 
			
			// get the contact details of the approvers. e.g Email Address
			$approvers  = G_Employee_Finder::findById($era->getPositionEmployeeId());
			if($request_type == Settings_Request::LEAVE) {
				
			} else if($request_type == Settings_Request::RESTDAY) {
				
			} else if($request_type == Settings_Request::CHANGED_SCHEDULE) {
				
			} else if($request_type == Settings_Request::UNDERTIME) {
				
			} else if($request_type == Settings_Request::MAKE_UP) {
				
			} else if($request_type == Settings_Request::OB) {
				
			} else if($request_type == Settings_Request::AC) {
				
			} else if($request_type == Settings_Request::GENERIC) {
				
			} else {
				self::constructOvertimeEmailNotification($era,$requestor,$approvers);
			}
		}
	}
	
	public static function sendApproverByPositionRequestNotification($era,$request_type = "") {
		if($era) {
			$requestor 	= self::getRequestorDetails($era);
			$position	= G_Employee_Job_History_Finder::findAllEmployeeByCurrentJob($era->getPositionEmployeeId());
			
			foreach($position as $p):
				$approvers  = G_Employee_Finder::findById($p->getEmployeeId());
				if($request_type == Settings_Request::LEAVE) {
				
				} else if($request_type == Settings_Request::RESTDAY) {
					
				} else if($request_type == Settings_Request::CHANGED_SCHEDULE) {
					
				} else if($request_type == Settings_Request::UNDERTIME) {
					
				} else if($request_type == Settings_Request::MAKE_UP) {
					
				} else if($request_type == Settings_Request::OB) {
					
				} else if($request_type == Settings_Request::AC) {
				
				} else if($request_type == Settings_Request::GENERIC) {
					
				} else {
					self::constructOvertimeEmailNotification($era,$requestor,$approvers);
				}
				
			endforeach;
		}
	}
	
	public static function getRequestorDetails($era) {
		if($era) {
			if($era->getRequestType() == Settings_Request::OT) {
				$r = G_Employee_Overtime_Request_Finder::findById($era->getRequestTypeId());
			}
			
			if($r) {
				$employee	= G_Employee_Finder::findById($r->getEmployeeId()); //requestor
				$department	= G_Employee_Subdivision_History_Finder::findEmployeeCurrentDepartment($r->getEmployeeId());
				$position	= G_Employee_Job_History_Finder::findCurrentJob($employee);
				
				$requestor['name'] 		= $employee->getFullName();
				$requestor['department']= $department->getName();
				$requestor['position']	= $position->getName();
			}
		}
		
		return $requestor;
	}
	
	public static function constructOvertimeEmailNotification($era,$requestor,$approver) {
		$eor 		= G_Employee_Overtime_Request_Finder::findById($era->getRequestTypeId());
		$contact	= G_Employee_Contact_Details_Finder::findByEmployeeId($era->getPositionEmployeeId());
		
		date_default_timezone_set('Asia/Manila');
		
		$ot['date_applied']	= date('Y-m-d g:i:s a',time());
		$ot['date_of_ot']	= $eor->getDateStart();
		$ot['time_from']	= Tools::convert24To12Hour($eor->getTimeIn());
		$ot['time_to']		= Tools::convert24To12Hour($eor->getTimeOut());
		$ot['reason']		= $eor->getOvertimeComments();
		
		$id 	= Utilities::encrypt($era->getId());
		$hash 	= Utilities::createHash($era->getId());	
		
		$ot['url_approve']		= url('request/_load_update_fa_overtime_request?id='.$id.'&hash='.$hash.'&status='.G_Employee_Overtime_Request::APPROVED);
		$ot['url_disapprove']	= url('request/_load_update_fa_overtime_request?id='.$id.'&hash='.$hash.'&status='.G_Employee_Overtime_Request::DISAPPROVED);
		
		$employee['name']		= $requestor['name'];
		$employee['department']	= $requestor['department'];
		$employee['position']	= $requestor['position'];
		
		$appr['email']	= (!empty($contact) ? $contact->getWorkEmail() : 'sprinthrapprover3@gmail.com');

		self::bufferOvertimeApproval($appr,$employee,$ot);
	}
	
	
}
?>