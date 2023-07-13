<?php
class G_Email_Buffer_Manager {
	public static function save(G_Email_Buffer $pt) {
		if (G_Email_Buffer_Helper::isIdExist($pt) > 0 ) {

			$sql_start = "UPDATE " . EMAIL_BUFFER;
			$sql_end = " WHERE id = ". Model::safeSql($pt->getId());

		}else{

			$sql_start = "INSERT INTO " . EMAIL_BUFFER;
			$sql_end = "";		
		}
		
		$sql = $sql_start ."

			SET
			sent_from		= ". Model::safeSql($pt->getFrom())	.",
			email_address	= ". Model::safeSql($pt->getEmailAddress())	.",
			sent_name		= ". Model::safeSql($pt->getName())	.",
			subject			= ". Model::safeSql($pt->getSubject())	.",
			message			= ". Model::safeSql($pt->getMessage())	.",
			attachment		= ". Model::safeSql($pt->getAttachment()).",
			is_sent			= ". Model::safeSql($pt->getIsSent())	.",
			is_archive		= ". Model::safeSql($pt->getIsArchive())	.",
			error_message	= ". Model::safeSql($pt->getErrorMessage())	.",
			date_added 		= ". Model::safeSql($pt->getDateAdded())	.""
			. $sql_end ."			
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}		

	public static function delete(G_Email_Buffer $pt){

		if(G_Email_Buffer_Helper::isIdExist($pt) > 0){
			$sql = "
				DELETE FROM " . EMAIL_BUFFER . " 
				WHERE id =" . Model::safeSql($pt->getId());

			Model::runSql($sql);
		}
	}
	
}

?>