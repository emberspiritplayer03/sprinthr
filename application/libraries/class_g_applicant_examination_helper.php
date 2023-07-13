<?php
class G_Applicant_Examination_Helper {
	public static function isIdExist(G_Applicant_Examination $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_EXAMINATION ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isApplicantIdExist(G_Applicant_Examination $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_EXAMINATION ."
			WHERE applicant_id = ". Model::safeSql($gcb->getApplicantId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function findByCompanyStructureId($company_structure_id,$order_by,$limit,$date)
	{
		$sql = "
			SELECT
			e.id,
			e.company_structure_id,
			e.applicant_id,
			e.exam_id,
			e.title,
			e.description,
			e.exam_code,
			e.passing_percentage,
			DATE_FORMAT(e.schedule_date, '%W %D %M %Y') as schedule_date,
			e.status,
			e.result,
			e.questions,
			CONCAT(p.lastname,', ', p.firstname) as scheduled_by,
			CONCAT(a.lastname,', ', a.firstname) as applicant_name
			FROM ". G_APPLICANT_EXAMINATION ." e INNER JOIN ".APPLICANT." a
			ON e.applicant_id=a.id
			LEFT JOIN ".EMPLOYEE." p ON p.id=e.scheduled_by
			WHERE e.company_structure_id=".$company_structure_id."
		
			".$date."
			".$order_by."
			".$limit."
		";
		
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_EXAMINATION ."
			WHERE id = ". Model::safeSql($company_structure_id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByApplicantId($applicant_id,$order_by,$limit,$date) {
		$sql = "
			SELECT
			e.id,
			e.company_structure_id,
			e.applicant_id,
			e.exam_id,
			e.title,
			e.description,
			e.exam_code,
			e.passing_percentage,
			DATE_FORMAT(e.schedule_date, '%W %D %M %Y') as schedule_date,
			e.status,
			e.result,
			e.questions,
			CONCAT(p.lastname,', ', p.firstname) as scheduled_by,
			CONCAT(a.lastname,', ', a.firstname) as applicant_name
			FROM ". G_APPLICANT_EXAMINATION ." e, ".APPLICANT." a, ".EMPLOYEE." p
			WHERE e.company_structure_id=".$company_structure_id." AND e.applicant_id=a.id AND p.id=e.scheduled_by
		
			".$date."
			".$order_by."
			".$limit."
		";

		$result = Model::runSql($sql,true);

		return $result;
	}

}
?>