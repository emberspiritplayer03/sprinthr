<?php
class G_User_Group_Manager {
	public static function save(G_User_Group $e) {
		if (G_User_Group_Helper::isIdExist($e) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_USER_GROUP . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_USER_GROUP . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($e->getCompanyStructureId()) .",
			group_name				= " . Model::safeSql($e->getGroupName()) .",
			description				= " . Model::safeSql($e->getDescription()) ."
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
		
	public static function delete(G_User_Group $e){
		if(G_User_Group_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_USER_GROUP ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>