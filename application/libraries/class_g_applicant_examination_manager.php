<?php
class G_Applicant_Examination_Manager {
	public static function save(G_Applicant_Examination $gcb) {
		if (G_Applicant_Examination_Helper::isIdExist($gcb) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_EXAMINATION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcb->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_EXAMINATION . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($gcb->getCompanyStructureId()) . ",
			applicant_id 			= " . Model::safeSql($gcb->getApplicantId()) . ",
			exam_id		 			= " . Model::safeSql($gcb->getExamId()) . ",
			title					   = " . Model::safeSql($gcb->getTitle()) . ",
			description				= " . Model::safeSql($gcb->getDescription()) . ",
			exam_code				= " . Model::safeSql($gcb->getExamCode()) . ",
			passing_percentage	= " . Model::safeSql($gcb->getPassingPercentage()) . ",
			schedule_date			= " . Model::safeSql($gcb->getScheduleDate()) . ",
			date_taken				= " . Model::safeSql($gcb->getDateTaken()) . ",
			status					= " . Model::safeSql($gcb->getStatus()) . ",
			result					= " . Model::safeSql($gcb->getResult()) . ",
			questions				= " . Model::safeSql($gcb->getQuestions()) . ",
			time_duration			= " . Model::safeSql($gcb->getTimeDuration()) . ",
			scheduled_by			= " . Model::safeSql($gcb->getScheduledBy()) . "
			
			 "
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function cancel(G_Applicant_Examination $gcb){
		if(G_Applicant_Examination_Helper::isIdExist($gcb) > 0){
			$sql = "
				UPDATE ". G_APPLICANT_EXAMINATION ."
				SET status = " . Model::safeSql(G_Applicant_Examination::CANCELLED) . "
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Applicant_Examination $gcb){
		if(G_Applicant_Examination_Helper::isIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_EXAMINATION ."				
				WHERE id =" . Model::safeSql($gcb->getId());
			Model::runSql($sql);
		}
	}
	
	public static function deleteAllByApplicantId(G_Applicant_Examination $gcb){
		if(G_Applicant_Examination_Helper::isApplicantIdExist($gcb) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_EXAMINATION ."				
				WHERE applicant_id =" . Model::safeSql($gcb->getApplicantId());				
			Model::runSql($sql);
		}	
	}	
}
?>