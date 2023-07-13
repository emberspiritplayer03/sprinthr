<?php
class G_Access_Rights_Manager {
	public static function save(G_Access_Rights $e) {
		if (G_Access_Rights_Helper::isIdExist($e) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_ACCESS_RIGHTS . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_ACCESS_RIGHTS . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			user_group_id			= " . Model::safeSql($e->getUserGroupId()) .",
			policy_type				= " . Model::safeSql($e->getPolicyType()) .",
			rights					= " . Model::safeSql($e->getRights()) .",
			date_added				= " . Model::safeSql($e->getDateAdded()) ."
			"
	
			. $sql_end ."	
		
		";	
		Model::runSql($sql);
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return $e->getId();
		}		
	}
		
	public static function delete(G_Access_Rights $e){
		if(G_Access_Rights_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_ACCESS_RIGHTS ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>