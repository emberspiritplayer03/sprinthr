<?php
$GLOBALS['lang'] = array (
	'app_short_name'                     => 'Apps',
	'app_full_name'                      => 'Apps'
);

$GLOBALS['lang_general'] = array(
	'loading_message'					=> 'Loading...'		
);

$GLOBALS['lang_module_names'] = array (
	MODULE_USERS                        => 'User Administration'		
);

$footer_year = date('Y');
$GLOBALS['lang_general'] = array(
	'title'					=> 'Gleent Human Resources',
	'copyright_statement'	=> '&copy; ' . $footer_year . ' Gleent Incorporated. All Rights Reserved.',
	'footer_title'			=> 'Krikel Framework Version 3'
);


$GLOBALS['hr']['marital_status'] = array(
	SINGLE		=> 'Single',
	MARRIED 	=> 'Married',
	SEPARATED	=> 'Separated',
	WIDOWED		=> 'Widowed',
	HF		    => 'Head of the family with dependent'
);


//default applicant status
$GLOBALS['hr']['application_status'] = array(
	APPLICATION_SUBMITTED 	=> 	'Application Submitted',
	INTERVIEW				=>	'Interview',
	JOB_OFFERED				=>	'Job Offered',
	OFFER_DECLINED			=>	'Offer Declined',
	REJECTED				=>	'Rejected',
	HIRED					=>	'Applicant Passed'
	
);


//default applicant event
$GLOBALS['hr']['application_event'] = array(
	INTERVIEW_EVENT 		=> 	'Interview',
	OFFER_JOB_EVENT			=>	'Offer a Job',
	DECLINED_OFFER_EVENT	=>	'Decline Offer',
	REJECTED_EVENT			=>	'Reject',
	HIRED_EVENT				=>	'Hired'
);


//default employee status
$GLOBALS['hr']['employee_status'] = array(
	PROBATIONARY 		=> 	'Probationary',
	REGULAR				=>	'Regular / Full Time',
	PART_TIME			=>	'Part Time',
	AWOL				=>	'AWOL',
	RESIGNED			=>	'Resigned',
	TERMINATED			=>	'Terminated',
	SUSPENDED			=>	'Suspended'
	
);

$GLOBALS['language'] = array(
	'ENGLISH'	=> 'English',
	'Tagalog'	=> 'Tagalog'
);


$GLOBALS['hr']['requirements'] = array(
	'Required 2x2 Picture'	=> '',
	'Medical'				=> '',
	'SSS'					=> '',
	'Tin'					=> ''
);

$GLOBALS['hr']['performance_rate'] = array(
	RATE_1				=> 'Does not Meet Minimum Standards',
	RATE_2				=> 'Needs Improvement',
	RATE_3				=> 'Meets Expectations',
	RATE_4				=> 'Exceeds Expectation',
	RATE_5				=> 'Outstanding'
);

// DEFAULT USER ACCESS RIGHTS

$GLOBALS['sprint_hr']['module_access'] = array(
	HR => HAS_ACCESS,
	EMPLOYEE_MODULE => HAS_ACCESS,
	PAYROLL => HAS_ACCESS,
);

//NO_ACCESS

$GLOBALS['sprint_hr']['sub_module_access'][HR] = array(
	DASHBOARD => array(
			'main_settings' => CUSTOM,
			'general_information' => CAN_MANAGE,
			'recruitment' => CAN_MANAGE,
			'employee' => CAN_MANAGE
		),
	RECRUITMENT => array(
			'main_settings' => CUSTOM,
			'recruitment' => CAN_MANAGE,
			'job_vacancy' => CAN_MANAGE,
			'candidate' => CAN_MANAGE,
			'examination' => CAN_MANAGE
		),
	EMPLOYEE_MODULE => array(
			'main_settings' => CUSTOM,
			'employee_management' => CAN_MANAGE,
			'account_management' => CAN_MANAGE,
			'deduction_management' => CAN_MANAGE,
			'schedule' => CAN_MANAGE,
			'leave' => CAN_MANAGE,
			'overtime' => CAN_MANAGE,
			'attendance' => CAN_MANAGE,
			'performance' => CAN_MANAGE,
		),
);


/*$GLOBALS['module_package']['hr'] = array(
	'recruitment'			=> true, #ok
	'job_vacancy'			=> true, #ok
	'application_portal'	=> true,  
	'applicant'				=> true, #ok
	'examination'			=> true, #ok
	'employee'				=> true, #ok
	'employee_201'			=> true,
	'user_account'			=> true, #ok
	'performance'			=> true, #ok
	'schedule'				=> true, #ok
	'holiday'				=> true  #ok
);

$GLOBALS['module_package']['attendance'] = array(
	'dtr'					=> true, #ok
	'employee_basic_info'	=> true,
	'request'				=> true, #ok
	'leave_request'			=> true, #ok
	'ob_request'			=> true, #ok
	'undertime_request'		=> true, #ok
	'ot_request'			=> true  #ok
);*/



?>