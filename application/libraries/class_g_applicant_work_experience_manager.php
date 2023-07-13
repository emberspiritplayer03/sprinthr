<?php
class G_Applicant_Work_Experience_Manager {
	public static function save(G_Applicant_Work_Experience $e) {
		if (G_Applicant_Work_Experience_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_APPLICANT_WORK_EXPERIENCE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_APPLICANT_WORK_EXPERIENCE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			applicant_id		= " . Model::safeSql($e->getApplicantId()) .",
			company		   		= " . Model::safeSql($e->getCompany()) .",
			address 	  		= " . Model::safeSql($e->getAddress()) .",
			job_title			= " . Model::safeSql($e->getJobTitle()) .",
			from_date			= " . Model::safeSql($e->getFromDate()) .",
			to_date				= " . Model::safeSql($e->getToDate()) .",
			comment				= " . Model::safeSql($e->getComment()) ." 
			 "
			. $sql_end ."
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Applicant_Work_Experience $e){
		print_r($e);
		if(G_Applicant_Work_Experience_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_APPLICANT_WORK_EXPERIENCE ."
				WHERE id =" . Model::safeSql($e->getId());
				
			echo $sql;
			Model::runSql($sql);
		}
	
	}
}
?>