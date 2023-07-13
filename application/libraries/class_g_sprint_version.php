<?php
class G_Sprint_Version {	
	const STARTING_VERSION = '1.0.0000';
	public function __construct() {
		
	}
	
	public function getVersionInfoList() {
		$sprint_versions = array(
			'1.1.0033' => array(
				'release_date' => '2014-10-22',
				'release_info' => array(
					'new_modules' => array(
						"HR : Settings" => "Roles Management",
						"HR : Schedule" => "Can add start and end date in schedule",
						"HR : Notifications" => "Can detect employees with floating schedule",
						"DTR" => "Employee can manual add in and out"
						),
					'fixes_bugs' => array(
						"HR : Settings" => "Fixed permission errors - not showing button in some modules",
						"DTR" => "Fixed link in DTR button",
						"HR : Notification" => "Fixed wrong notification title"
						)
				)
			),
			'1.2.0017' => array(
				'release_date' => '2014-11-03',
				'release_info' => array(
					'new_modules' => array(
						"Settings : Load default values" => "Added benefits default | Added payroll variables default",
						"Settings : Payroll Default Variables" => "Can change payroll number of days variable | Adjusted payroll / payslip generator",
						"HR : Employee Benefits Management" => "HR can add benefits and enroll employees to it | Auto add in payroll earnings",
						"Attendance : Biometrics auto sync" => "Auto sync fp logs to employee attendance",
						"HR : Settings Contribution" => " Updated payroll contribution processing. Can deactivate selected employee contribution"
						),
					'fixes_bugs' => array(
						"HR : Settings" => "Fixed grace period not working - not reflecting to employee attendance. |  error on edit function on settings/company structure /department.
ex. click edit equipment then cancel the dialog box after that click edit to other department",
						"HR : Employee Details" => "Fixed cannot add memo | Fixed search employee by birthdate",
						"HR : Schedule" => "Fixed datatabled not reloading after editing",
						"HR : Overtime" => "Fixed datatable not reloading after adding request",
						"Payroll : Generation" => "Fixed wrong gross pay output | Fixed grosspay not equal with payslip | Fixed grosspay, excel file, not equal with payslip webview"
						)
				)
			),
			'1.3.0006' => array(
				'release_date' => '2014-12-01',
				'release_info' => array(
					'new_modules' => array(
						"HR : Settings" => "Tax table are now viewable in HR : Settings | Can define employees who are tax exempted",						
						"HR : Timesheet" => "Timesheet are now more detailed - can now see OThrs, NDhrs, etc.",
						"Help" => "SprintHR manual is now available"
						),
					'fixes_bugs' => array(
						"HR : Timesheet" => "Day type in attendance are now displayed",
					)
				)
			),
			'1.3.0011' => array(
				'release_date' => '2014-12-26',
				'release_info' => array(
					'new_modules' => array(
						"Initial Setup : Pay Period" => "After truncating, admin user will be prompt to enter correct pay period and will generate, upon saving, 3 previous month cutoff periods"
						),
					'fixes_bugs' => array(
						"HR : Employee" => "Removed compensation fields | Removed compensation box",
						"Payroll : Payslip" => "Will not show 0 value of government deductions in webview payslip and excel - SSS, Pagibig and Philhealth"
					)
				)
			),
			'1.4.0009' => array(
				'release_date' => '2015-01-05',
				'release_info' => array(
					'new_modules' => array(
						"Settings : User / Roles" => "Added employee modules in user roles",						
						"Employee : Portal" => "Employee can login to their own user portal where they can file requests and view their profile",					
						"Attendance" => "Can synchronize attendance data from standalone application - dtr / biometrics",												
						),
					'fixes_bugs' => array(
						"Attendance : FP Logs" => "Added field sync",
						"HR : Official Business Request" => "Increased is_approved field max characters",
						"HR : Overtime Request" => "Added date_created field",
						"HR : Requests" => "Added request approvers",
						"HR : Leave" => "Fixed not deducting leave credit when request is approved"						
					)
				)
			),
			'1.5.0006' => array(
				'release_date' => '2015-02-06',
				'release_info' => array(
					'new_modules' => array(
						"Settings : IP Management" => "Can assign IP address to employees who are allowed to access the online employee portal",														
						"Payroll Report : Alpha List" => "Can generate alpha list report",	
						"Settings : SSS" => "Can import SSS excel table",
						"Database" => "Added Addons feature - Can activate / deactivate addons"												
						),
					'fixes_bugs' => array(
						"HR : Notifications" => "Fixed slow updating of notifications on huge data",
						"HR : Attendance Sync" => "Updated sync function for faster data synchronization",
						"Employee : Requests" => "Corrected label arrangement in leave request | Fixed approve / disapprove function link sent to email",						
						"DTR" => "Will now refresh upon entering employee id via keyboard"
					)
				)
			),
			'1.6.0014' => array(
				'release_date' => '2015-03-04',
				'release_info' => array(
					'new_modules' => array(
						"Settings : OT Allowance" => "Can set earnings amount to employees who worked for certain number of approved overtime hours",														
						"Settings : Breaktime Management" => "Can add breaktime to assinged schedules",
						"Settings : Holiday" => "Holiday schedules are now in calendar view",		
						"HR : Restday" => "Now in calendar view - whole year",	
						"Report : Manpowercount" => "Added new selection / filtering",
						"HR : Employee confidential and non-confidential" => "Employee are now classified as confidential and non-confidential",														
						"Settings : Roles" => "Can now set user to view only confidential, non-confidential or both"												
						),
					'fixes_bugs' => array(
						"Report : EE/ER Contribution" => "Added Contribution Type with checkbox of SSS, Pagibig, Philhealth selections",							
						"HR : Attendance" => "Can now see other details like OT hrs, breaktime scheduels, etc, in employee attendance | Can search by department and section",	
						"HR : Timesheet" => "Now deducting breaktime hrs from breaktime management | Can search by department and section",								
						"Employee / HR : OT" => "Can now edit request | Can now approve disapprove by batch",
						"Contributions" => "Fixed roundoff issue",
						"Timesheet / Attendance / Requests" => "Fixed previous and next cutoff period buttons",
						"Settings : Holiday" => "Fixed js problem in adding and editing holiday"
					)
				)
			),
			'1.7.0014' => array(
				'release_date' => '2015-03-25',
				'release_info' => array(
					'new_modules' => array(
						"Attendance : Rotational Restday" => "Can set rotational rest day by Department",														
						"Payroll : Filtering" => "Can remove resigned, and terminated employee, and process payroll for selected employees",
						"Contribution : Additional Settings" => "Can set contribution as either taxable or non taxable | Can set deduction basis to basic pay or gross pay",								
						"Import Template : Employee" => "Added section and number of working days",						
						"Sprint Variables" => "Added default variables, total working days"																
						),
					'fixes_bugs' => array(
						"Pay Period" => "Fixed error in pay period missing data upon changing / updating pay period in settings",							
						"Pagibig Table" => "Updated Pagibig table ceiling amount",	
						"Overtime" => "Can now file Night Differential OT - for Night Shift",								
						"Payroll : Confidential / Non Confidential" => "Fixed error not processing confidential and non confidential employees | Fixed error not showing button for confidential and non confidential employees",
						"Import OT" => "Fixed errors in approvers when importing OT",
						"Ini Pay Period" => "Fixed errors not generating default payroll period upon resetting",
						"Timesheet : Details" => "Can now see other overtime hrs via more details",	
						"Request Approver" => "Data will now be locked for editing upon approval of final approver"						
					)
				)
			),
			'1.8.0006' => array(
				'release_date' => '2015-04-15',
				'release_info' => array(
					'new_modules' => array(
						"Payroll : CETA / SEA" => "Auto compute in payslip CETA / SEA",														
						"Payroll : Hold / Move Employee Deductions" => "Can hold or move to different cutoff employee deductions",
						"Overtime : Auto filing request" => "Will auto file overtime request if condition is met",								
						"Employee : Dynamic Field" => "Can add multiple field for employee"
						),
					'fixes_bugs' => array(
						"Employee Management" => "Fixed error in quick add of department in adding employee",							
						"Settings" => "Corrected caption in adding employment status",	
						"Reports : Timesheet" => "Fixed timesheet report not showing advance inputs search",								
						"Reports : Manpower Count" => "Updated sql will not show employees who are active - removed resigned, terminated and endo employees",
						"Payroll Processing" => "Fixed auto redirect after processing, will not redirect after process",
						"Payroll Register" => "Added loan deducted amount column"
					)
				)
			),
			'1.8.0009' => array(
				'release_date' => '2015-04-29',
				'release_info' => array(
					'new_modules' => array(),
					'fixes_bugs' => array(
						"Auto Overtime" => "Pending and disapproved overtime not updating timesheet",							
						"Payslip" => "Auto overtime not reflecting in paylsip",													
						"Employee" => "Employee employment status id not saving when importing, updating and saving"
					)
				)
			),
			'1.9.0000' => array(
				'release_date' => '2015-04-29',
				'release_info' => array(
					'new_modules' => array(
						"Schedule" => "Calendar Scheduling per year applicable to all employee",														
						"Earnings / Deductions" => "Adding of Earnings and Deduction by department, section, employment status and employees"						
						),
					'fixes_bugs' => array()
				)
			),
			'1.9.0007' => array(
				'release_date' => '2015-04-29',
				'release_info' => array(
					'new_modules' => array(
						"Reports" => "Added grand total in cash file report / Enhanced payroll register report. Group by department with subtotal and grand total / Added query builder in payroll register report",		
						"Schedule" => "Schedule can be assigned to all employees without typing one by one",
						"Earnings / Deductions" => "Adding of Earnings and Deduction by department, section, employment status and employees",
						"Benefits" => "Adding of Benefits by employment status, department, sections and employee / Can add criteria to benefits set to employee",
						"Anviz Biometrics" => "Can read data from Anviz Biometrics",
						"Leave" => "Employee's leave credit can be automatically added every year and can be converted into cash or added to following year"
						),
					'fixes_bugs' => array(
						"Auto Overtime" => "Fixed error in restday auto overtime - invalid total hours",							
						"Payslip" => "Fixed error not displaying rest day overtime total hours",													
						"Payroll Register" => "Fixed report not showing total other earnings and other deductions"
					)
				)
			),
			'1.9.2' => array(
				'release_date' => '2015-05-19',
				'release_info' => array(
					'new_modules' => array(
						"Overtime" => "Added datatable search",		
						"Employee" => "Added in employee import template - nationality, tags, emergency contact and education",	
						"OT Allowance" => "Added new criteria and apply to. Can apply to by department and employment status",
						"Earnings" => "Can now apply by department and employment status / Can set earning by percentage or amount",
						"Attendance" => "No late, undertime on restday and holiday",
						"Timesheet" => "Restday data are now set to 0 if employee has no in and out",
						"Settings" => "Added Night Shift hours start and end time in payroll settings",
						"Payslip" => "Can now select template to load in payslip. Available 3 templates",
						"Contribution" => "Can set base salary credit to monthly pay"
						
						),
					'fixes_bugs' => array(
						"Cutoff Generator" => "Fixed wrong cutoff arrangement and reference. Upon resetting or changing cutoff, system will delete previous cutoff, tag to current year, and will generate new cutoff periods (whole year cutoff periods)",							
						"Attendance" => "Fixed error in displaying total required working hours in timesheet - returning invalid date / Fixed error in returning duplicate employee entries",		
						"Benefits" => "Fixed error in showing employee benefits / Fixed pagination in benefits list in hr settings",
						"Employee" => "Fixed error in updating employee dependents - always returning 0 after updating / Fixed error in duplicate entries in job histroy",
						"Deductions" => "Fixed error in pagibig amount computation"
					)
				)
			),
			'1.9.3' => array(
				'release_date' => '2015-05-29',
				'release_info' => array(
					'new_modules' => array(
						"Attendance" => "Will not save default schedule in and out",		
						"Reports" => "Improved DTR summarized report | Improved timesheet summarized report",							
						"Timesheet" => "Added download timesheet - summarized or detailed",
						"Settings" => "Added Night Shift hours start and end time in payroll settings",
						"Payslip" => "Can now select template to load in payslip. Available 3 templates",						
						"Benefits" => "Added Custom Criteria - Absences and Leave (Merged)",
						"Leave" => "Displayed remarks field in viewing employees leave | Added condition when filing leave (before attendance)",
						"Notification" => "Added incorrect shifts",
						"Payslip" => "Updated payslip template"
						),
					'fixes_bugs' => array(						
						"Earnings" => "Fixed earnings wrong computation",		
						"Loan" => "Corrected add new loan caption",
						"Label Formatting" => "Corrected pay period label formatting - leave, ob, overtime, payslip",
						"Contribution" => "Fixed error in tax computation",
						"Timesheet" => "Fixed error in wrong computation for actual hrs worked"
					)
				)
			),
			'1.9.4' => array(

				'release_date' => '2015-06-04',
				'release_info' => array(
					'new_modules' => array(
						"Payslip" => "Updated layout",		
						"Benefits" => "Import employee benefits",							
						"Earnings" => "Import earnings",
						"Breaktime Management" => "Added day type - breaktime can now be set in which day type, holiday, regular day or rest day, will it effect"						
						),
					'fixes_bugs' => array(						
						"Leave" => "Fixed error in disapproved leave - not removing leave request upon disapproved",		
						"CETA / SEA" => "Removed holiday and restday in computing valid days",
						"Benefits" => "Removed holiday and restday in computing present days",
						"Payslip" => "Fixed error not displaying holiday in payslip"					
					)
				)
			)
		);

		return $sprint_versions;
	}

	public function getVersionList() {
		$data     = $this->getVersionInfoList();
		$versions = array();
		foreach( $data as $key => $value ){
			$versions[] = $key;
		}

		return $versions;
	}

	public function getVersionInfo($version = ''){
		$data         = $this->getVersionInfoList();
		$version_info = array();
		
		if( array_key_exists($version, $data) ){			
			$version_info = $data[$version];
		}

		return $version_info;
	}

	public function updateVersionTextFile($version = '', $date_updated = ''){
		$return = false;
		$version_filename = 'version';
		$file   =  $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'files/' . $version_filename;
		
		if( $version != '' ){			
			$content = "{$version} / {$date_updated}";
			$io = new IO_Reader();
			$io->setFileName($file);
			$io->setContent($content);
			$io->writeToTextFile(); //Create version file info
			$return = true;
		}

		return $return;
	}

	public function getAppVersion(){
		$data = array();
		$version_filename = 'version';
		$file =  $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . 'files/' . $version_filename;		

		$io   = new IO_Reader();
		$io->setFileName($file);
		$data = $io->readTextFile();			
		
		return $data[0][0];
	}
}
?>