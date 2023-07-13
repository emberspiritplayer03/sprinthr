<?php
class G_Applicant_Education_Manager {
	public static function save(G_Applicant_Education $e) {
		if (G_Applicant_Education_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_EDUCATION . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_EDUCATION . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id		= " . Model::safeSql($e->getApplicantId()) .",
			institute		   	= " . Model::safeSql($e->getInstitute()) .",
			course		   		= " . Model::safeSql($e->getCourse()) .",
			year		   		= " . Model::safeSql($e->getYear()) .",
			start_date		   	= " . Model::safeSql($e->getStartDate()) .",
			end_date		   	= " . Model::safeSql($e->getEndDate()) .",
			gpa_score		   	= " . Model::safeSql($e->getGpaScore()) .",
			attainment		   	= " . Model::safeSql($e->getAttainment()) ."
			
			"
	
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Education $e){
		if(G_Applicant_Education_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_EDUCATION ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>