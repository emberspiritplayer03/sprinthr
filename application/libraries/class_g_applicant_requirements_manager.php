<?php
class G_Applicant_Requirements_Manager {
	public static function save(G_Applicant_Requirements $gcb) {
		if (G_Applicant_Requirements_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_REQUIREMENTS. " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_REQUIREMENTS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id 			= " . Model::safeSql($gcb->getApplicantId()) . ",
			requirements			= " . Model::safeSql($gcb->getRequirements()) . ",
			date_updated			= " . Model::safeSql($gcb->getDateUpdated()) . ",
			is_complete				= " . Model::safeSql($gcb->getIsComplete()) . "
			 "
			. $sql_end ."	
		
		";
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Requirements $gcb){
		if(G_Applicant_Requirements_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_REQUIREMENTS ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>