<?php

class Sprint_Modules {
	
	const PERMISSION_01 = "View Only";
	const PERMISSION_02 = "View and Edit";	
	const PERMISSION_03 = "Can Access";
	const PERMISSION_04 = "No Access";

	const PERMISSION_05 = "Both";
	const PERMISSION_06 = "Confidential Employees";
	const PERMISSION_07 = "Non-confidential Employees";

	const YES = "Yes";
	const NO  = "No";
	
	public function __construct() {
		
	}

	public function payrollModules() {
		$payroll_modules = array(
			'payroll' => array(
				"caption" => "Payroll",
				"url" => hr_url('payroll_register/generation'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon schedule"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
				"children" => ''					
			),
			'earnings_deductions' => array(
				"caption" => "Earnings / Deductions",
				"url" => hr_url('earnings'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon attendance"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
					'earnings' => array(
						"caption" => "Earnings",
						"url" => hr_url('earnings'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::YES,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
						"children" => ''
					),
					 'yearly_bonus' => array(
					 	"caption" => "13th Month",
					 	"url" => hr_url('earnings/yearly_bonus/'),
					 	"attributes" => array(
					 		"id" => "",
					 		"class" => ""					
					 	),
					 	"is_visible" => self::YES,
					 	"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
					 	"children" => ''
					 ),
					'deductions' => array(
						"caption" => "Deductions",
						"url" => hr_url('deductions'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::YES,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
						"children" => ''
					),
					'leave_conversion' => array(
						"caption" => "Leave Conversion",
						"url" => hr_url('earnings/converted_list'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => LEAVE_CONVERSION_MENU_ENABLED ? self::YES : self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
						"children" => ''
					),
					'loans' => array(
						"caption" => "Loans",
						"url" => hr_url('loan'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::YES,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
						"children" => ''
					)
					 ,
					 'tax_annualization' => array(
					 	"caption" => "Tax Annualization",
					 	"url" => hr_url('annualize_tax'),
					 	"attributes" => array(
					 		"id" => "",
					 		"class" => ""					
					 	),
					 	"is_visible" => self::YES,
					 	"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
					 	"children" => ''
					 )
				)	
			),
			'reports' => array(
				"caption" => "Reports",
				"url" => hr_url('payroll_reports/payroll_management#payslip'),
				"attributes" => array(
					"id" => "mnu_payroll_report",
					"class" => "menu_icon reports"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
					'payslip' => array(
						"caption" => "Payslip",
						"url" => hr_url('payroll_reports/payroll_management#payslip'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'payroll_register' => array(
						"caption" => "Payroll Register",
						"url" => hr_url('payroll_reports/payroll_management#payroll_register'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'cost_center' => array(
						"caption" => "Cost Center",
						"url" => hr_url('payroll_reports/payroll_management#cost_center'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'leave_converted' => array(
						"caption" => "Leave Converted",
						"url" => hr_url('payroll_reports/payroll_management#leave_converted'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'other_earnings' => array(
						"caption" => "Other Earnings",
						"url" => hr_url('payroll_reports/payroll_management#other_earnings'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'cash_file' => array(
						"caption" => "Cash File",
						"url" => hr_url('payroll_reports/payroll_management#cash_file'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ""
					),
					'sss' => array(
						"caption" => "SSS",
						"url" => hr_url('payroll_reports/payroll_management#sss_r1a'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'philhealth' => array(
						"caption" => "PhilHealth",
						"url" => hr_url('payroll_reports/payroll_management#philhealth'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'pagibig' => array(
						"caption" => "Pagibig",
						"url" => hr_url('payroll_reports/payroll_management#pagibig'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'tax' => array(
						"caption" => "Tax",
						"url" => hr_url('payroll_reports/payroll_management#tax'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'annual_tax' => array(
						"caption" => "Annual Tax",
						"url" => hr_url('payroll_reports/annual_tax#annual_tax'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'alphalist' => array(
						"caption" => "Alpha List",
						"url" => hr_url('payroll_reports/payroll_management#alphalist'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'bir_2316' => array(
						"caption" => "BIR 2316",
						"url" => hr_url('payroll_reports/payroll_management#bir_2316'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					),
					'yearly_bonus' => array(
						"caption" => "13th Month",
						"url" => hr_url('payroll_reports/payroll_management#yearly_bonus'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_04),
						"children" => ''
					)
				)
			)/*,
			'settings' => array(
				"caption" => "Settings",
				"url" => "",
				"attributes" => array(
					"id" => "",
					"class" => ""					
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
				"children" => ''
			)	*/
		);

		return $payroll_modules;
	}
							
	public function hrModules() {		
		$now = date('Y-m-d');
		$hr_modules = array(
			
			'employees' => array(
				"caption" => "Employees",
				"url" => hr_url('employee'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon dashboard"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(

						'employee_evaluation' => array(
							"caption" => "Employee Evaluation",
							"url" => hr_url('evaluation'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" =>array(self::PERMISSION_03, self::PERMISSION_04),
							"children" => ''
						),

						'employee_access' => array(
							"caption" => "Employee Access",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_05, self::PERMISSION_06, self::PERMISSION_07),
							"children" => ''
						),
						'employee_management' => array(
							"caption" => "Employee Management",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'personal_details' => array(
							"caption" => "Personal Details",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'contact_details' => array(
							"caption" => "Contact Details",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'emergency_contacts' => array(
							"caption" => "Emergency Contacts",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'dependents' => array(
							"caption" => "Dependents",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""				
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'bank' => array(
							"caption" => "Bank",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""				
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'employment_status' => array(
							"caption" => "Employment Status",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'compensation' => array(
							"caption" => "Compensation",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'benefits' => array(
							"caption" => "Benefits",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'contract' => array(
							"caption" => "Contract",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'contribution' => array(
							"caption" => "Contribution",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'training' => array(
							"caption" => "Training",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'memo' => array(
							"caption" => "Memo",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""				
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'requirements' => array(
							"caption" => "Requirements",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'supervisor' => array(
							"caption" => "Supervisor",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'employees_leave' => array(
							"caption" => "Leave",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'work_experience' => array(
							"caption" => "Work Experience",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'educations' => array(
							"caption" => "Education",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'skills' => array(
							"caption" => "Skills",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'language' => array(
							"caption" => "Language",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'license' => array(
							"caption" => "License",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'attachment' => array(
							"caption" => "Attachment",
							"url" => "",
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::NO,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						)
				)
			),

			'schedule' => array(
				"caption" => "Schedule",
				"url" => hr_url('schedule'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon dashboard"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
				"children" => array(

					'new_schedule' => array(
						"caption" => "New Schedule",
						"url" => hr_url("new_schedule/schedule_main?date={$now}"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::YES,
						"actions" =>array(self::PERMISSION_03, self::PERMISSION_04),
						"children" => ''
					)
				)	
			),


			'attendance' => array(
				"caption" => "Attendance",
				"url" => hr_url('attendance'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon recruitment"					
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
						'attendance_daily_time_record' => array(
							"caption" => "Daily Time Record",
							"url" => hr_url('attendance/attendance_logs'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'attendance_overtime' => array(
							"caption" => "Overtime",
							"url" => hr_url('overtime'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'attendance_leave' => array(
							"caption" => "Leave",
							"url" => hr_url('leave'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
						'official_business' => array(
							"caption" => "Official Business",
							"url" => hr_url('ob'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),

						'activity' => array(
							"caption" => "Activity",
							"url" => hr_url('activity'),	
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),

						'project_site' => array(
							"caption" => "Project Site",
							"url" => hr_url('project_site'),	
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" =>array(self::PERMISSION_03, self::PERMISSION_04),
							"children" => ''
						),

						'attendance_timesheet' => array(
							"caption" => "Timesheet",
							"url" => hr_url('attendance'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
							"children" => ''
						),
				)
			),			
			'reports' => array(
				"caption" => "Reports",
				"url" => hr_url('reports/time_management#attendance_absence_data'),
				"attributes" => array(
					"id" => "mnu_hr_report",
					"class" => "menu_icon reports"					
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
					'absences' => array(
						"caption" => "Absences",
						"url" => hr_url("reports/time_management#attendance_absence_data"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),	
					'tardiness' => array(
						"caption" => "Late",
						"url" => hr_url("reports/time_management#display_absence_quota_information"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_overtime' => array(
						"caption" => "Overtime",
						"url" => hr_url("reports/time_management#overtime"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'undertime' => array(
						"caption" => "Undertime",
						"url" => hr_url("reports/time_management#display_undertime"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'resigned_employees' => array(
						"caption" => "Resigned Employees",
						"url" => hr_url("reports/time_management#display_resigned_employees"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'terminated_employees' => array(
						"caption" => "Terminated Employees",
						"url" => hr_url("reports/time_management#display_terminated_employees"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_leave' => array(
						"caption" => "Leave",
						"url" => hr_url("reports/time_management#display_leave"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_incentive_leave' => array(
						"caption" => "Incentive Leave",
						"url" => hr_url("reports/time_management#display_incentive_leave"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_birthday' => array(
						"caption" => "Birthday",
						"url" => hr_url("reports/time_management#display_birthday"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_leave_balance' => array(
						"caption" => "Leave Balance",
						"url" => hr_url("reports/time_management#display_leave_balance"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'shift_schedule' => array(
						"caption" => "Shift Schedule",
						"url" => hr_url("reports/time_management#display_shift_schedule"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'manpower_count' => array(
						"caption" => "Manpower Count",
						"url" => hr_url("reports/time_management#display_manpower_count"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'end_of_contract' => array(
						"caption" => "End of Contract",
						"url" => hr_url("reports/time_management#display_end_of_contract"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_daily_time_record' => array(
						"caption" => "Daily Time Record",
						"url" => hr_url("reports/time_management#display_daily_time_record"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'inc_time_in_and_time_out' => array(
						"caption" => "Incomplete Time In / Out",
						"url" => hr_url("reports/time_management#display_incomplete_time_in_out"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_incorrect_shift' => array(
						"caption" => "Incorrect Shift",
						"url" => hr_url("reports/time_management#display_incorrect_shift"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_timesheet' => array(
						"caption" => "Timesheet",
						"url" => hr_url("reports/time_management#display_timesheet"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_disciplinary_action' => array(
						"caption" => "Disciplinary Action",
						"url" => hr_url("reports/time_management#display_disciplinary_action"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_final_pay' => array(
						"caption" => "Resigned Accountability Reports",
						"url" => hr_url("reports/time_management#display_final_pay"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_loans' => array(
						"caption" => "Loan",
						"url" => hr_url("reports/time_management#display_loans"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_employment_status' => array(
						"caption" => "Employment Status",
						"url" => hr_url("reports/time_management#display_employment_status"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_employee_details' => array(
						"caption" => "Employee Details",
						"url" => hr_url("reports/time_management#display_employee_details"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_ee_er_contribution' => array(
						"caption" => "EE / ER Contribution",
						"url" => hr_url("reports/time_management#display_ee_er_contribution"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_notifications' => array(
						"caption" => "Notifications",
						"url" => hr_url("notifications"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_perfect_attendance' => array(
						"caption" => "Near Perfect Attendance",
						"url" => hr_url("reports/time_management#display_perfect_attendance"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),					
					'reports_coe' => array(
						"caption" => "Certificate of Employment",
						"url" => hr_url("reports/time_management#reports_coe"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_actual_hours' => array(
						"caption" => "Actual Hours",
						"url" => hr_url("reports/time_management#reports_actual_hours"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_required_shift' => array(
						"caption" => "Required Shift",
						"url" => hr_url("reports/time_management#reports_required_shift"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_government_remittances' => array(
						"caption" => "Government Remittances",
						"url" => hr_url("reports/time_management#reports_government_remittances"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					),
					'reports_last_pay' => array(
						"caption" => "Last Pay",
						"url" => hr_url("reports/time_management#reports_last_pay"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					)/*,

					'audit_trail' => array(
						"caption" => "Audit Trail",
						"url" => hr_url("reports/time_management#audit_trail_data"),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::NO,
						"actions" => array(self::PERMISSION_01,self::PERMISSION_04),
						"children" => ''
					)*/
				)
			),
			'settings' => array(
				"caption" => "Settings",
				"url" => hr_url('settings'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon dashboard"					
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_02, self::PERMISSION_04),
				"children" => ''
			)					
		);
	
		return $hr_modules;
	}	

	public function dtrModules() {
		$dtr_modules = array(
			'dtr' => array(
				"caption" => "DTR",
				"url" => hr_url("dtr"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			)
		);		
		return $dtr_modules;
	}

	public function employeeModules() {
		$employee_modules = array(
			'employee_dashboard' => array(
				"caption" => "Dashboard",
				"url" => employee_url("dashboard"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			),'employee_overtime' => array(
				"caption" => "Overtime",
				"url" => employee_url("overtime"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			),'employee_leave' => array(
				"caption" => "Leave",
				"url" => employee_url("leave"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			),'employee_official_business' => array(
				"caption" => "Official Business",
				"url" => employee_url("ob"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			),'employee_reports' => array(
				"caption" => "Reports",
				"url" => employee_url('reports'),
				"attributes" => array(
					"id" => "",
					"class" => " "					
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
						'employee_payslip' => array(
							"caption" => "Payslip",
							"url" => employee_url('reports/payslip'),
							"attributes" => array(
								"id" => "",
								"class" => ""					
							),
							"is_visible" => self::YES,
							"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
							"children" => ''
						)
				)
			),	
			'employee_profile' => array(
				"caption" => "My Profile",
				"url" => employee_url("profile"),
				"attributes" => array(
					"id" => "",
					"class" => ""				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => ''					
			)
		);

		return $employee_modules;
	}

	public function auditTrailModules() {
		$audit_trail_modules = array(
			'general_reports' => array(
				"caption" => "General Reports",
				"url" => hr_url('time_management/audit_trail_data'),
				"attributes" => array(
					"id" => "",
					"class" => "menu_icon reports"				
				),
				"is_visible" => self::YES,
				"actions" => array(self::PERMISSION_03, self::PERMISSION_04),
				"children" => array(
					'audit_trail' => array(
						"caption" => "Audit Trail",
						"url" => hr_url('time_management/audit_trail_data'),
						"attributes" => array(
							"id" => "",
							"class" => ""					
						),
						"is_visible" => self::YES,
						"actions" => array(self::PERMISSION_01, self::PERMISSION_02, self::PERMISSION_04),
						"children" => ''
					)
				)	
			)
		);

		return $audit_trail_modules;
	}


}
?>