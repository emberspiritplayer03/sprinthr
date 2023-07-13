<?php
class G_Job_Application_Event_Manager {
	public static function save(G_Job_Application_Event $e) {
		if (G_Job_Application_Event_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_JOB_APPLICATION_EVENT . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_JOB_APPLICATION_EVENT . "";
			$sql_end   = " ";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			applicant_id	   		= " . Model::safeSql($e->getApplicantId()) .",
			date_time_created  		= " . Model::safeSql($e->getDateTimeCreated()) .",
			created_by				= " . Model::safeSql($e->getCreatedBy()) .", 
			hiring_manager_id		= " . Model::safeSql($e->getHiringManagerId()) .",
			date_time_event			= " . Model::safeSql($e->getDateTimeEvent()) .",
			event_type				= " . Model::safeSql($e->getEventType()) .",
			application_status_id	= " . Model::safeSql($e->getApplicationStatusId()) .",
			notes					= " . Model::safeSql($e->getNotes()) .",
			remarks					= " . Model::safeSql($e->getRemarks()) ."
			"
			. $sql_end ."	
		";	
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Job_Application_Event $e){
		if(G_Job_Application_Event_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_JOB_APPLICATION_EVENT ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>