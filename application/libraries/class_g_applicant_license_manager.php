<?php
class G_Applicant_License_Manager {
	public static function save(G_Applicant_License $e) {
		if (G_Applicant_License_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_LICENSE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_LICENSE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id		= " . Model::safeSql($e->getApplicantId()) .",
			license_type	   	= " . Model::safeSql($e->getLicenseType()) .",
			license_number	   	= " . Model::safeSql($e->getLicenseNumber()) .",
			issued_date		   	= " . Model::safeSql($e->getIssuedDate()) .",
			expiry_date		   	= " . Model::safeSql($e->getExpiryDate()) ."
			"
		
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_License $e){
		if(G_Applicant_License_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_LICENSE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>