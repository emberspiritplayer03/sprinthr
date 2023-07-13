<?php
class G_User_Group_Helper {
		
	public static function isIdExist(G_User_Group $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_USER_GROUP ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findGroupName($q) {
		$sql = "
			SELECT
				ug.id,
				ug.group_name,
				ug.group_name as name,
				ug.description
			FROM " . G_USER_GROUP ." ug
			WHERE 
			group_name LIKE '%$q%' OR
			description LIKE '%$q%'
		";
		$record = Model::runSql($sql,true);
		return $record;
	}
}
?>