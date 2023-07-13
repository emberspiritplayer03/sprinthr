<?php
class G_Applicant_Profile_Finder {

	public static function findById($id) {
		$sql = "
			SELECT 
			*
			FROM " . APPLICANT_PROFILE ." 
			WHERE id=". Model::safeSql($id)." 
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByApplicantLogId($applicant_log_id) {
		$sql = "
			SELECT 
			*
			FROM " . APPLICANT_PROFILE ." 
			WHERE applicant_log_id =". Model::safeSql($applicant_log_id)." 
			LIMIT 1
		";
		//echo $sql;
		return self::getRecord($sql);
	}
	
	public static function findByEmailAddress($email) {
		$sql = "
			SELECT 
			*
			FROM " . APPLICANT_PROFILE ."
			WHERE email_address=". Model::safeSql($email)." 
		";
echo $sql;
		return self::getRecords($sql);		
	}
		
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . APPLICANT_PROFILE ." 			
			
		";
		return self::getRecord($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
			
		}
		return $records;
	}
	
	private static function newObject($row) {
		$gcb = new G_Applicant_Profile();
		$gcb->setId($row['id']);
		$gcb->setCompanyStructureId($row['company_structure_id']);
		$gcb->setApplicantLogId($row['applicant_log_id']);
		$gcb->setLastname($row['lastname']);
		$gcb->setFirstname($row['firstname']);
		$gcb->setMiddlename($row['middlename']);
		$gcb->setExtensionName($row['extension_name']);
		$gcb->setBirthdate($row['birthdate']);
		$gcb->setGender($row['gender']);
		$gcb->setMaritalStatus($row['marital_status']);
		$gcb->setHomeTelephone($row['home_telephone']);
		$gcb->setMobile($row['mobile']);
		$gcb->setBirthPlace($row['birth_place']);
		$gcb->setAddress($row['address']);
		$gcb->setCity($row['city']);
		$gcb->setProvince($row['province']);
		$gcb->setZipCode($row['zip_code']);
		$gcb->setSssNumber($row['sss_number']);
		$gcb->setTinNumber($row['tin_number']);
		$gcb->setPhilhealthNumber($row['philhealth_number']);
		$gcb->setPagibigNumber($row['pagibig_number']);
		$gcb->setResumeName($row['resume_name']);
		$gcb->setResumePath($row['resume_path']);
		$gcb->setPhoto($row['photo']);
		
		return $gcb;
	}
}
?>