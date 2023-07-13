<?php
class G_Applicant_Training_Manager {
	public static function save(G_Applicant_Training $e) {
		if (G_Applicant_Training_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_TRAINING . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_TRAINING . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id		= " . Model::safeSql($e->getApplicantId()) .",
			from_date		   	= " . Model::safeSql($e->getFromDate()) .",
			to_date		   		= " . Model::safeSql($e->getToDate()) .",
			description			= " . Model::safeSql($e->getDescription()) .",
			provider	   		= " . Model::safeSql($e->getProvider()) .",
			location	   		= " . Model::safeSql($e->getLocation()) .",
			cost		   		= " . Model::safeSql($e->getCost()) .",
			renewal_date   		= " . Model::safeSql($e->getRenewalDate()) ."
			"
		
			. $sql_end ."	
		
		";	
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Training $e){
		if(G_Applicant_Training_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_TRAINING ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>