<?php
class G_Applicant_Attachment_Manager {
	public static function save(G_Applicant_Attachment $gcb) {
		if (G_Applicant_Attachment_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_ATTACHMENT . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_ATTACHMENT . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id 			= " . Model::safeSql($gcb->getApplicantId()) . ",
			name		 			= " . Model::safeSql($gcb->getName()) . ",
			filename				= " . Model::safeSql($gcb->getFilename()) . ",
			description             = " . Model::safeSql($gcb->getDescription()) . ",
			size			        = " . Model::safeSql($gcb->getSize()) . ",
			type			        = " . Model::safeSql($gcb->getType()) . ",
			date_attached	        = " . Model::safeSql($gcb->getDateAttached()) . ",
			added_by			    = " . Model::safeSql($gcb->getAddedBy()) . ",
			screen			        = " . Model::safeSql($gcb->getScreen()) . "
			 "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Attachment $gcb){
		if(G_Applicant_Attachment_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_ATTACHMENT ."
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
}
?>