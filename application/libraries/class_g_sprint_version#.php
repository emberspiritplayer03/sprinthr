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
			'1.3.0000' => array(
				'release_date' => '2014-12-01',
				'release_info' => array(
					'new_modules' => array(
						"HR : Settings" => "Tax table are now viewable in HR : Settings | Can define employees who are tax exempted",						
						"HR : Timesheet" => "Timesheet are now more detailed - can now see OThrs, NDhrs, etc.",
						"Security" => "Local server app can now be accessed outside LAN via IP"
						),
					'fixes_bugs' => array(
						"HR : Timesheet" => "Day type in attendance are now displayed",
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