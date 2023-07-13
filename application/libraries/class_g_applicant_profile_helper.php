<?php
class G_Applicant_Profile_Helper {
	public static function isIdExist(G_Applicant_Profile $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT_PROFILE ."
			WHERE id = ". Model::safeSql($gcb->id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isApplicantLogIdExist($applicant_log_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT_PROFILE ."
			WHERE applicant_log_id = ". Model::safeSql($applicant_log_id) ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function copyApplicantProfile(G_Applicant_Profile $gcb,$other_details) {
		$applicant = new G_Applicant;
		$applicant->setLastname(ucfirst($gcb->getLastname()));
		$applicant->setFirstname(ucfirst($gcb->getFirstname()));
		$applicant->setMiddlename(ucfirst($gcb->getMiddlename()));
		$a_id = $applicant->save();
		
		$hash = Utilities::createHash($a_id);
		$gss = G_Applicant_Finder::findById($a_id);
		
		$gss->setHash($hash);
		$gss->setPhoto($gcb->getPhoto());
		$gss->setCompanyStructureId($gcb->getCompanyStructureId());
		$gss->setJobVacancyId($other_details['vacancy_id']);
		$gss->setApplicationStatusId(APPLICATION_SUBMITTED);
		$gss->setJobId($other_details['job_id']);		
		$gss->setExtensionName($gcb->getExtensionName());
		$gss->setGender($gcb->getGender());
		$gss->setMaritalStatus($gcb->getMaritalStatus());
		$gss->setBirthdate($gcb->getBirthdate());
		$gss->setBirthPlace($gcb->getBirthPlace());
		$gss->setAddress($gcb->getAddress());
		$gss->setCity($gcb->getCity());
		$gss->setProvince($gcb->getProvince());
		$gss->setZipCode($gcb->getZipCode());
		//$gss->setCountry($gcb->getCountry());
		$gss->setHomeTelephone($gcb->getHomeTelephone());
		$gss->setMobile($gcb->getMobile());
		$gss->setEmailAddress($_SESSION['sprint_applicant']['username']);
		//$gss->setQualification($_POST['qualification']);
		$gss->setAppliedDateTime($other_details['date_applied']);
		$gss->setSssNumber($gcb->getSssNumber());
		$gss->setTinNumber($gcb->getTinNumber());
		$gss->setPhilhealthNumber($gcb->getPhilhealthNumber());
		//.$gss->setResumeName('test');
		$gss->setResumePath('test');
		$gss->save();
		
		return $a_id;
	}
			
	public static function getNextId($applicant_id)
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id>".Model::safeSql($applicant_id)."
			ORDER BY a.id ASC		
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['id'];
	}
	
	public static function getPreviousId($applicant_id)
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id<".Model::safeSql($applicant_id)."
			ORDER BY a.id DESC		
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['id'];
	}
	
}
?>