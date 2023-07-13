<?php
class G_Role_Actions_Helper {

        public static function isIdExist(G_Role_Actions $gra) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLE_ACTIONS ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ROLE_ACTIONS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "						
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function getAllHRRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "		
				AND parent_module =" . Model::safeSql(G_Sprint_Modules::HR) . "	
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function getAllPayrollRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "		
				AND parent_module =" . Model::safeSql(G_Sprint_Modules::PAYROLL) . "	
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function getAllDTRRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "		
				AND parent_module =" . Model::safeSql(G_Sprint_Modules::DTR) . "	
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function getAllEmployeeRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "		
				AND parent_module =" . Model::safeSql(G_Sprint_Modules::EMPLOYEE) . "	
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function getAllAuditTrailRoleActionsByRoleId($role_id = 0, $fields = array()){
		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ROLE_ACTIONS ."
			WHERE role_id =" . Model::safeSql($role_id) . "		
				AND parent_module =" . Model::safeSql(G_Sprint_Modules::AUDIT_TRAIL) . "	
		";		
		$record = Model::runSql($sql,true);
		return $record;
	}

}
?>