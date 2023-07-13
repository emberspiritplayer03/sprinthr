<?php
class G_Job_Application_Event_Helper {
		
	public static function isIdExist(G_Job_Application_Event $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_APPLICATION_EVENT ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countInterview($applicant_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_APPLICATION_EVENT ."
			WHERE applicant_id = ". Model::safeSql($applicant_id) ." 
			AND application_status_id=".INTERVIEW."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function loadDefaultApplicationEventHistory($aid) {
		$e = new G_Job_Application_Event;
		$e->setCompanyStructureId($_SESSION['sprint_hr']['company_structure_id']);
		$e->setApplicantid($aid);
		$e->setDateTimeCreated(date("Y-m-d h:i:s"));
		$e->setCreatedBy(0);
		$e->setHiringManagerId('');
		$e->setDateTimeEvent(date("Y-m-d"));
		$e->setEventType(APPLICATION_SUBMITTED);
		$e->setApplicationStatusId(APPLICATION_SUBMITTED);
		$e->setRemarks('Application Submitted');
		//$e->setNotes($_POST['notes']);
		$e->save();
		
	
	}
	
	//G_Job_Applicant_Event_Helper::displayOptions($applicant_status_id);
	public static function displayOptions($applicant_id, $application_status_id,$hash) {

		if(APPLICATION_SUBMITTED==$application_status_id) {	
			$link ='<ul>';
			
			$url_set_interview = url("recruitment/profile?rid=".$applicant_id.'&hash='.$hash.'&status='.$application_status_id."#interview");
			$url_offer_job     = url("recruitment/profile?rid=".$applicant_id.'&hash='.$hash.'&status='.$application_status_id."#offer_job");
			$url_reject_fail   = url("recruitment/profile?rid=".$applicant_id.'&hash='.$hash.'&status='.$application_status_id."#rejected");
			$url_hire		    = url("recruitment/profile?rid=".$applicant_id.'&hash='.$hash.'&status='.$application_status_id."#hired");
			$url_take_exam     = url("recruitment/examination?add=show&rid=".$applicant_id.'&hash='.$hash);
			
			$link .= '<li><a href="' . $url_set_interview . '">Set Interview</a></li>';
			$link .= '<li><a href="' . $url_offer_job . '">Offer a Job</a></li>';
			$link .= '<li><a href="' . $url_reject_fail . '">Rejected / Failed Applicant</a></li>';
			$link .= '<li><a href="' . $url_hire . '">Hire Applicant</a></li>';
			$link .= '<li><a href="' . $url_take_exam . '">Take Exam</a></li>';
		
			$link .= '</td></tr></table>';
			$return .= '<div>'.$link.'</div>';
		}
		
		if(INTERVIEW==$application_status_id) {
			$link ='<ul>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#interview">Set Another Interview</a></li>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#offer_job">Offer a Job</a></li>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#rejected">Rejected / Failed Applicant</a></li>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#hired">Hire Applicant</a></li>';
			$link .= '<li><a href="examination?add=show&rid='.$applicant_id.'&hash='.$hash.'">Take Exam</a></li>';
			$link .= '</ul>';
			$return .= '<div>'.$link.'</div>';
		}
		
		if(JOB_OFFERED==$application_status_id) {
			$link ='<ul>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#declined_offer">Decline Offer</a></li>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#rejected">Rejected / Failed Applicant</a></li>';
			$link .= '<li><a href="profile?rid='.$applicant_id.'&hash='.$hash.'&status='.$application_status_id.'#hired">Hire Applicant</a></li>';
			$link .= '</ul>';
			$return .= '<div>'.$link.'</div>';
		}
		
		if(OFFER_DECLINED==$application_status_id) {
			$link = '<a>Offer Declined</a>';
			$return .= '<center>'.$link.'</center>';
		}
		
		if(REJECTED==$application_status_id) {
			$link = '<a>Rejected</a>';
			$return .= '<center>'.$link.'</center>';
		}
		
		if(HIRED==$application_status_id) {
			$link = '<div class="info hired">Hired</div>';
			$return .= '<div>'.$link.'</div>';
		}
		
		return $return;
	}

}
?>