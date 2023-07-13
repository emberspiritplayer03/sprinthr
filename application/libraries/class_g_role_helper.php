<?php
class G_Role_Helper {

    public static function isIdExist(G_Role $gr) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLES ."
			WHERE id = ". Model::safeSql($gr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLES			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsIdExists($id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLES ."
			WHERE id = ". Model::safeSql($id) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlTotalRecordsIsNotArchive() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLES . "
			WHERE is_archive =" . Model::safeSql(G_Role::NO) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlGetAllIsNotArchiveRecords($order_by = "", $limit = "", $fields = array(), $user_role){		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		//newly added for filtering the display of role name on not super admin accouont
		if($user_role == 'sprint_super_admin'){
			$sql = "
				SELECT {$sql_fields}
				FROM " . ROLES ."
				WHERE is_archive =" . Model::safeSql(G_Role::NO) . "
				{$order_by}
				{$limit}
			";	
		}
		else{
			$sql = "
				SELECT {$sql_fields}
				FROM " . ROLES ."
				WHERE is_archive =" . Model::safeSql(G_Role::NO) . "
				AND name != 'Super Admin'
				{$order_by}
				{$limit}
			";		
		}

				
		$record = Model::runSql($sql,true);
		return $record;
	}
}
?>