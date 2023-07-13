<?php
class G_Applicant_Profile_Manager {
	public static function save(G_Applicant_Profile $gcb) {
		if (G_Applicant_Profile_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". APPLICANT_PROFILE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". APPLICANT_PROFILE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	   = " . Model::safeSql($gcb->getCompanyStructureId()) . ",
			applicant_log_id			= " . Model::safeSql($gcb->getApplicantLogId()) . ",
			lastname              	= " . Model::safeSql($gcb->getLastname()) . ",
			firstname             	= " . Model::safeSql($gcb->getFirstname()) . ",
			middlename              = " . Model::safeSql($gcb->getMiddlename()) . ",
			extension_name          = " . Model::safeSql($gcb->getExtensionName()) . ",
			birthdate  	            = " . Model::safeSql($gcb->getBirthdate()) . ",
			gender      	         = " . Model::safeSql($gcb->getGender()) . ",
			marital_status          = " . Model::safeSql($gcb->getMaritalStatus()) . ",
			home_telephone          = " . Model::safeSql($gcb->getHomeTelephone()) . ",
			mobile                  = " . Model::safeSql($gcb->getMobile()) . ",
			birth_place             = " . Model::safeSql($gcb->getMiddlename()) . ",
			address                 = " . Model::safeSql($gcb->getAddress()) . ",
			city                  	= " . Model::safeSql($gcb->getCity()) . ",
			province                = " . Model::safeSql($gcb->getProvince()) . ",
			zip_code                = " . Model::safeSql($gcb->getZipCode()) . ",
			sss_number           	= " . Model::safeSql($gcb->getSssNumber()) . ",
			tin_number		         = " . Model::safeSql($gcb->getTinNumber()) . ",
			philhealth_number       = " . Model::safeSql($gcb->getPhilhealthNumber()) . ",
			pagibig_number          = " . Model::safeSql($gcb->getPagibigNumber()) . ",
			resume_name             = " . Model::safeSql($gcb->getResumeName()) . ",
			resume_path             = " . Model::safeSql($gcb->getResumePath()) . ",
			photo				         = " . Model::safeSql($gcb->getPhoto()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Profile $gcb){
		if(G_Applicant_Profile_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". APPLICANT_PROFILE ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>