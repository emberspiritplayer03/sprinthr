<?php
class G_Applicant_Skills_Manager {
	public static function save(G_Applicant_Skills $e) {
		if (G_Applicant_Skills_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_SKILLS . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_SKILLS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id		= " . Model::safeSql($e->getApplicantId()) .",
			skill			   	= " . Model::safeSql($e->getSkill()) .",
			years_experience   	= " . Model::safeSql($e->getYearsExperience()) .",
			comments		   	= " . Model::safeSql($e->getComments()) ."
			"
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Skills $e){
		if(G_Applicant_Skills_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_SKILLS ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>