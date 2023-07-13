<?php
class G_Applicant_Logs_Manager {
	public static function save(G_Applicant_Logs $al) {
		if (G_Applicant_Logs_Helper::isIdExist($al) > 0 ) {
			$sql_start = "UPDATE ". APPLICANT_LOGS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($al->getId());		
		}else{
			$sql_start = "INSERT INTO ". APPLICANT_LOGS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			ip 						=" . Model::safeSql($al->getIp()) . ",
			country 					=" . Model::safeSql($al->getCountry()) . ",
			firstname 				=" . Model::safeSql($al->getFirstName()) . ",
			lastname					=" . Model::safeSql($al->getLastName()) . ",
			email						=" . Model::safeSql($al->getEmail()) . ",
			password					=" . Model::safeSql($al->getPassword()) . ",
			status					=" . Model::safeSql($al->getStatus()) . ",
			date_time_created		=" . Model::safeSql($al->getDateTimeCreated()) . ",
			date_time_validated	=" . Model::safeSql($al->getDateTimeValidated()) . ",
			link						=" . Model::safeSql($al->getLink()) . ",
			is_password_change 	=" . Model::safeSql($al->getIsPasswordChange()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function save_link(G_Applicant_Logs $al){
		if(G_Applicant_Logs_Helper::isIdExist($al) > 0){
			$sql = "
				UPDATE ". APPLICANT_LOGS . "
				SET
					link =" . Model::safeSql($al->getLink()) . " 
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function activateAccount(G_Applicant_Logs $al){
		if(G_Applicant_Logs_Helper::isIdExist($al) > 0){
			$sql = "
				UPDATE ". APPLICANT_LOGS . "
				SET
					status 					=" . Model::safeSql(G_Applicant_Logs::VALIDATED) . ",
					date_time_validated 	=" . Model::safeSql(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila')) . " 
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function setAccountToExpired(G_Applicant_Logs $al){
		if(G_Applicant_Logs_Helper::isIdExist($al) > 0){
			$sql = "
				UPDATE ". APPLICANT_LOGS . "
				SET
					status 					=" . Model::safeSql(G_Applicant_Logs::EXPIRED) . " 					
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Applicant_Logs $al){
		if(G_Applicant_Logs_Helper::isIdExist($al) > 0){
			$sql = "
				DELETE FROM ". APPLICANT_LOGS ."
				WHERE id =" . Model::safeSql($al->getId());
			Model::runSql($sql);
		}	
	}
}
?>