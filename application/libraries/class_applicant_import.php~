<?php
/*
	This is used for importing timesheet.

	Usage:
		$file = $_FILES['employee']['tmp_name'];
		//$file = BASE_PATH . 'files/files/employee.xls';
		
		$e = new Employee_Import($file);
		$return = $e->import();
*/
class Applicant_Import {
	protected $file_to_import;
	
	public function __construct($file) {
		$this->file_to_import = $file;	
	}
	
	public function import() {
		$file = $this->file_to_import;
		$data = new Excel_Reader($file);
		$total_row = $data->countRow();
	
		$error_count = 0;
		$imported_count = 0;

		for ($i = 1; $i <= $total_row; $i++) {
				$excel_applied_position = (string) trim($data->getValue($i, 'A'));
				$date_applied = (string) trim($data->getValue($i, 'B'));
				$excel_date_applied = date('Y-m-d', strtotime($date_applied));
				$excel_lastname = (string) trim($data->getValue($i, 'C'));
				$excel_firstname = (string) trim($data->getValue($i, 'D'));
				$excel_middlename = (string) trim($data->getValue($i, 'E'));
				$excel_extension_name = (string) trim(utf8_encode($data->getValue($i, 'F')));
				$birthdate = (string) trim(utf8_encode($data->getValue($i, 'G')));
				$excel_birthdate = date('Y-m-d',  strtotime($birthdate));
				$excel_gender = (string) trim(utf8_encode($data->getValue($i, 'H')));
				$excel_marital_status = (string) trim($data->getValue($i, 'I'));
				$excel_address = (string) trim($data->getValue($i, 'J'));
				$excel_city = (string) trim($data->getValue($i, 'K'));
				$excel_province = (string) trim($data->getValue($i, 'L'));
				$excel_email_address = (string) trim($data->getValue($i, 'M'));
				$excel_home_telephone = (string) trim($data->getValue($i, 'N'));
				$excel_mobile_number = (string) trim($data->getValue($i, 'O'));
				
				$company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
				if($i==1) {

					if($excel_applied_position!='Applied Position' || $excel_lastname!='Lastname' || $excel_firstname!='Firstname' ) {						
						$is_format_valid=0;
						$i=$total_row;
					}else {
						$is_format_valid=1;
					}
				}elseif($i>1) {
					if($excel_lastname != '' && $excel_firstname != '' && $excel_middlename != ''){
						$imported_count++;
						$applicant = new G_Applicant;
						$applicant->setLastname($excel_lastname);
						$applicant->setFirstname($excel_firstname);
						$applicant->setMiddlename($excel_middlename);
						$a_id = $applicant->save();
						
						$hash = Utilities::createHash($a_id);
						$gss = new G_Applicant;
						$gss->setId($a_id);
						$gss->setHash($hash);
						//$gss->setEmployeeId('');
						$gss->setCompanyStructureId($company_structure_id);
						$gss->setJobVacancyId('');
						$gss->setApplicationStatusId(APPLICATION_SUBMITTED);
						
						//FIND JOB
						$job = G_Job_Finder::findByTitle($excel_applied_position);
						if(!$job) {
							if($excel_applied_position) {
								$j = new G_Job;
								$j->setCompanyStructureId($company_structure_id);
								$j->setTitle($excel_applied_position);
								$j->setIsActive(1);
								$position_id = $j->save();	
							}
						}else {
							$position_id = $job->getId();
						//	echo "Position Id " . $position_id;
						}
						$gss->setJobId($position_id);
						$gss->setAppliedDateTime($excel_date_applied);
						$gss->setLastname($excel_lastname);
						$gss->setFirstname($excel_firstname);
						$gss->setMiddlename($excel_middlename);
						$gss->setGender($excel_gender);
						$gss->setMaritalStatus($excel_marital_status);
						$gss->setBirthdate($excel_birthdate);
		
						$gss->setAddress($excel_address);
						$gss->setCity($excel_city);
						$gss->setProvince($excel_province);
						$gss->setHomeTelephone($excel_home_telephone);
						$gss->setMobile($excel_mobile_number);
						$gss->setEmailAddress($excel_email_address);
						$gss->save();
						
						// add requirements
							//New
							$gar = new G_Applicant_Requirements();
							$gar->setApplicantId($a_id);
							$gar->loadDefaultRequirements();
							//
							
							//Deprecated 05072013
							/*$req = G_Applicant_Requirements_Finder::findByApplicantId($a_id);
							
							//requirements from file
							$file = BASE_FOLDER. 'files/xml/requirements.xml';
							
							if(Tools::isFileExist($file)==true) {
								$requirements = Requirements::getDefaultRequirements();	
							}else {
								foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
									$requirements[Tools::friendlyFormName($key)] = '';
								}	
							}
						
							$r = new G_Applicant_Requirements;
							$r->setId($req->id);
							$r->setApplicantId($a_id);
							$r->setRequirements(serialize($requirements));
							$r->setIsComplete(0);
							$r->setDateUpdated(date("Y-m-d"));
							$r->save();*/
						//end of requirements
						
						//Create an Application Event History
						$e = new G_Job_Application_Event;
						//$e->setId($row['id']);
						$e->setCompanyStructureId($_SESSION['sprint_hr']['company_structure_id']);
						$e->setApplicantid($a_id);
						$e->setDateTimeCreated(date("Y-m-d h:i:s"));
						$e->setCreatedBy($_SESSION['hr']['user_id']);
						$e->setHiringManagerId('');
						$e->setDateTimeEvent($excel_date_applied);
						$e->setEventType(APPLICATION_SUBMITTED);
						$e->setApplicationStatusId(APPLICATION_SUBMITTED);
						$e->setRemarks('Application Submitted');
						//$e->setNotes($_POST['notes']);
						$e->save();
					}
				}	
					
				
			}
				
		if ($imported_count > 0) {
			$return['is_imported'] = true;
			if ($error_count > 0) {
				$total_row = $total_row - 1; // minus the excel title header
				$msg =  $imported_count. ' of '.$total_row .' records has been successfully imported.';
				if($error_branch_name>0) {
					$msg .= '<br> Fix '. $error_branch_name.' error(s) found in Branch Column.';	
				}
				
				if($error_department>0) {
					$msg .= '<br> Fix '. $error_department .' error(s) found in Department Column.';	
				}
				
				if($error_complete_name>0) {
					$msg .= '<br> Fix '. $error_complete_name .' error(s) found in Employee Name Column.<br><br>';	
				}
	
				$return['message']= $msg;
			} else {
				$_SESSION['hr']['applicant_imported'] = $imported_count;
				$return['message'] = $imported_count . ' Record(s) has been successfully imported.';
			}
		}elseif($is_format_valid==0){
			$return['message'] = 'Invalid Excel Format.';	
		} else {
			$return['message'] = 'There was a problem importing the timesheet. Please contact the administrator.';
		}
		return $return['message'];
	}
	
	private function update($i) {
			
	}
	
	private function save($i) {
		
			
	}
}
?>