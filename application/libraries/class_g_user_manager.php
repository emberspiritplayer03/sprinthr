<?php
class G_User_Manager {
	public static function save(G_User $e) {
		if (G_User_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_USER . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_USER . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			user_group_id			= " . Model::safeSql($e->getUserGroupId()) .",
			employee_id				= " . Model::safeSql($e->getEmployeeId()) .",
			employment_status		= " . Model::safeSql($e->getEmploymentStatus()) .",
			username				= " . Model::safeSql($e->getUsername()) .",
			hash					= " . Model::safeSql($e->getHash()) .",
			password				= " . Model::safeSql($e->getPassword()) .",
			module					= " . Model::safeSql($e->getModule()) .",
			receive_notification	= " . Model::safeSql($e->getReceiveNotification()) .",
			date_entered			= " . Model::safeSql($e->getDateEntered()) .",
			date_modified			= " . Model::safeSql($e->getDateModified()) .",
			modified_user_id		= " . Model::safeSql($e->getModifiedUserId()) .",
			created_by				= " . Model::safeSql($e->getCreatedBy()) ."
			 "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function saveAsAdmin(G_User $e) {
		if (G_User_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_USER . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_USER . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			user_group_id			= " . Model::safeSql($e->getUserGroupId()) .",
			employee_id				= " . Model::safeSql($e->getEmployeeId()) .",
			employment_status		= " . Model::safeSql($e->getEmploymentStatus()) .",
			username				= " . Model::safeSql($e->getUsername()) .",
			hash					= " . Model::safeSql($e->getHash()) .",
			password				= " . Model::safeSql($e->getPassword()) .",
			module					= " . Model::safeSql($e->getModule()) .",
			receive_notification	= " . Model::safeSql($e->getReceiveNotification()) .",
			date_entered			= " . Model::safeSql($e->getDateEntered()) .",
			date_modified			= " . Model::safeSql($e->getDateModified()) .",
			modified_user_id		= " . Model::safeSql($e->getModifiedUserId()) .",
			is_admin				= 1,
			created_by				= " . Model::safeSql($e->getCreatedBy()) ."
			 "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function updatePassword(G_User $e){
		if(G_User_Helper::isIdExist($e) > 0){
			$sql = "
				UPDATE ". G_USER ."
					SET password =" . Model::safeSql($e->getPassword()) . "
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
		
	public static function delete(G_User $e){
		if(G_User_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_USER ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>