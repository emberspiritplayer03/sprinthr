<?php
define('REST_DAY_PER_WEEK', 4);
define('GRACE_PERIOD', 10); //MINUTES
define('MOD_PAYROLL', true);
define('MOD_CLERK', false);
define('MOD_EMPLOYEE', true);
define('MOD_HR', true);
define('MOD_AUDIT_TRAIL', true);
define('MAX_JOB_APPLICATION',10);

define('DEFAULT_EMAIL_RECIPIENT',"sales@gleent.com");

define('EVALUATION_VERSION', false);
define('TRIAL_PERIOD', false);
define('APPLICANT_EVENT_SEND_EMAIL',false);
define('APPLICANT_EXAMINATION_SEND_EMAIL',false);

define('INTERVAL_SYNC_ATTENDANCE',120000);
define('ENABLE_EMAIL_NOTIFICATION', true);
define('EMAIL_HDR_FTR', '');

$GLOBALS['module_package']['hr'] = array(
	'recruitment'			=> true, #ok
	'job_vacancy'			=> true, #ok
	'application_portal'	=> true, #ok
	'applicant'				=> true, #ok
	'examination'			=> true, #ok
	'employee'				=> true, #ok
	'employee_201'			=> true, #ok
	'user_account'			=> true, #ok
	'performance'			=> true, #ok
	'schedule'				=> true, #ok
	'holiday'				=> true,  #ok
	'main'				=> true  #ok
);


$GLOBALS['module_package']['attendance'] = array(
	'dtr'						 => true, #ok
	'employee_basic_info' => true, #ok
	'request'				 => true, #ok
	'leave_request'		 => true, #ok
	'ob_request'			 => true, #ok
	'undertime_request'	 => true, #ok
	'ot_request'			 => true, #ok
	'payroll'				 => true  #ok
);

$email_hdr_ftr['header'] = "SprintHR";	
$email_hdr_ftr['footer'] = 'SprintHR';

	define(EMAIL_HDR_FTR,serialize($email_hdr_ftr));
?>
