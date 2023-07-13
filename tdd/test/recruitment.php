<?php
class TestRecruitment extends UnitTestCase {
	

	function testAddNewRegistration()
	{
		print_r($GLOBALS['sprint_hr']);
		$_POST['company_structure_id'] = 1;
		$_POST['job_id'] = 3;
		$_POST['date_applied'] = '2012-10-09';
		$_POST['email_address'] = 'marvin.dungog@gmail.com';
		$_POST['lastname'] = 'dungog';
		$_POST['firstname'] = 'marvin';
		$_POST['middlename'] = 'bantigue';
		$_POST['extension_name'] = '2';
		$_POST['birthdate'] = '1982-03-05';
		$_POST['gender'] = 'male';
		$_POST['marital_status'] = 'single';
		$_POST['home_telephone'] = '9847-64-56';
		$_POST['mobile'] = '132-456-54';
		$_POST['birth_place'] = 'manila';
		$_POST['address'] = '123';
		$_POST['city'] = 'cabuyao';
		$_POST['province'] = 'laguna';
		$_POST['zip_code'] = '2045';
		$_POST['sss_number'] = '23424-23424';
		$_POST['tin_number'] = '23424-23423';
		$_POST['philhealth_number'] = '12321';
		
		$applicant = new G_Applicant;
		$applicant->setLastname($_POST['lastname']);
		$applicant->setFirstname($_POST['firstname']);
		$applicant->setMiddlename($_POST['middlename']);
		$a_id = $applicant->save();
		
		$hash = Utilities::createHash($a_id);
		$gss = new G_Applicant;
		$gss->setId($a_id);
		$gss->setHash($hash);
		//$gss->setEmployeeId('');
		$gss->setCompanyStructureId($_POST['company_structure_id']);
		$gss->setJobVacancyId('');
		$gss->setApplicationStatusId(APPLICATION_SUBMITTED);

		$gss->setJobId($_POST['job_id']);
		$gss->setAppliedDateTime($_POST['date_applied']);
		$gss->setLastname($_POST['lastname']);
		$gss->setFirstname($_POST['firstname']);
		$gss->setMiddlename($_POST['middlename']);
		$gss->setGender($_POST['gender']);
		$gss->setMaritalStatus($_POST['marital_status']);
		$gss->setBirthdate($_POST['birthdate']);

		$gss->setAddress($_POST['address']);
		$gss->setCity($_POST['city']);
		$gss->setProvince($_POST['province']);
		$gss->setHomeTelephone($_POST['home_telephone']);
		$gss->setMobile($_POST['mobile']);
		$gss->setEmailAddress($_POST['email_address']);
		$gss->setZipCode($_POST['zip_code']);
		$gss->setSssNumber($_POST['sss_number']);
		$gss->setTinNumber($_POST['tin_number']);
		$gss->setPhilhealthNumber($_POST['philhealth_number']);
		$gss->save();
		
		// add requirements
		$req = G_Applicant_Requirements_Finder::findByApplicantId($a_id);
		
		//requirements from file
		$file = BASE_FOLDER. 'files/xml/requirements.xml';
		
		if(Tools::isFileExist($file)==true) {
			$requirements = Requirements::getDefaultRequirements();	
		}else {
			$GLOBALS['hr']['requirements'] = array(
				'Required 2x2 Picture'	=> '',
				'Medical'				=> '',
				'SSS'					=> '',
				'Tin'					=> ''
			);
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
			$r->save();	
		
		
		//end of requirements
		
		//Create an Application Event History
		$e = new G_Job_Application_Event;

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
?>