<?php
class G_Applicant_Manager {
	public static function save(G_Applicant $gcb) {
		if (G_Applicant_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". APPLICANT . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". APPLICANT . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id 			= " . Model::safeSql($gcb->getEmployeeId()) . ",
			hash					= " . Model::safeSql($gcb->getHash()) . ",
			photo					= " . Model::safeSql($gcb->getPhoto()) . ",
			company_structure_id    = " . Model::safeSql($gcb->getCompanyStructureId()) . ",
			job_vacancy_id          = " . Model::safeSql($gcb->getJobVacancyId()) . ",
			job_id			        = " . Model::safeSql($gcb->getJobId()) . ",
			application_status_id   = " . Model::safeSql($gcb->getApplicationStatusId()) . ",
			lastname              	= " . Model::safeSql($gcb->getLastname()) . ",
			firstname             	= " . Model::safeSql($gcb->getFirstname()) . ",
			middlename              = " . Model::safeSql($gcb->getMiddlename()) . ",
			extension_name          = " . Model::safeSql($gcb->getExtensionName()) . ",
			
			gender      	        = " . Model::safeSql($gcb->getGender()) . ",
			marital_status          = " . Model::safeSql($gcb->getMaritalStatus()) . ",
			birthdate  	            = " . Model::safeSql($gcb->getBirthdate()) . ",
			birth_place             = " . Model::safeSql($gcb->getBirthPlace()) . ",
			
			address                 = " . Model::safeSql($gcb->getAddress()) . ",
			city                  	= " . Model::safeSql($gcb->getCity()) . ",
			province                = " . Model::safeSql($gcb->getProvince()) . ",
			zip_code                = " . Model::safeSql($gcb->getZipCode()) . ",
			country                 = " . Model::safeSql($gcb->getCountry()) . ",
			home_telephone          = " . Model::safeSql($gcb->getHomeTelephone()) . ",
			mobile                  = " . Model::safeSql($gcb->getMobile()) . ",
			email_address           = " . Model::safeSql($gcb->getEmailAddress()) . ",
			qualification           = " . Model::safeSql($gcb->getQualification()) . ",
			tin_number		        = " . Model::safeSql($gcb->getTinNumber()) . ",
			sss_number           	= " . Model::safeSql($gcb->getSssNumber()) . ",
			pagibig_number          = " . Model::safeSql($gcb->getPagibigNumber()) . ",
			philhealth_number       = " . Model::safeSql($gcb->getPhilhealthNumber()) . ",
			applied_date_time       = " . Model::safeSql($gcb->getAppliedDateTime()) . ",
			hired_date			    = " . Model::safeSql($gcb->getHiredDate()) . ",
			resume_name             = " . Model::safeSql($gcb->getResumeName()) . ",
			resume_path		        = " . Model::safeSql($gcb->getResumePath()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant $gcb){
		if(G_Applicant_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". APPLICANT ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>