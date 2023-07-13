<?php
class G_Applicant_Attachment_Helper {
	public static function isIdExist(G_Applicant_Attachment $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_ATTACHMENT ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	
	public static function findByApplicantId($applicant_id,$order_by,$limit) {
		$sql = "
			*
			FROM ". G_APPLICANT_ATTACHMENT ."
			WHERE a.applicant_id=".$applicant_id."
			
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}

}
?>