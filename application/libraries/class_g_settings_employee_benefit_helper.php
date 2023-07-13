<?php
class G_Settings_Employee_Benefit_Helper {

    public static function isIdExist(G_Settings_Employee_Benefit $gseb) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ."
			WHERE id = ". Model::safeSql($gseb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlBenefitLastId() {
		$sql = "
			SELECT id
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS . "
			ORDER BY id DESC
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['id'];
	}

	public static function sqlIsIdExists( $id = 0 ) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ."
			WHERE id = ". Model::safeSql($id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		if( $row['total'] > 0 ){
			$return = true;
		}else{
			$return = false;
		}

		return $return;
	}
	
	public static function sqlTotalRecordsIsNotArchive() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS . "
			WHERE is_archive =" . Model::safeSql(G_Settings_Employee_Benefit::NO) . "
		";		

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlEmployeeUnregisteredBenefits($employee_id = 0) {
		$sql = "
			SELECT b.id, b.code, b.name 
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ." b 
			WHERE b.id NOT IN(
				SELECT eb.benefit_id
				FROM " . G_EMPLOYEE_BENEFITS_MAIN . " eb
				WHERE eb.employee_department_id =" . Model::safeSql($employee_id) . "
					OR eb.applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . "
			) AND b.is_archive =" . Model::safeSql(G_Settings_Employee_Benefit::NO) . "
		";						
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function sqlGetAllIsNotArchiveRecords($order_by = "", $limit = "", $fields = array()){		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ."
			WHERE is_archive =" . Model::safeSql(G_Settings_Employee_Benefit::NO) . "
			{$order_by}
			{$limit}
		";						
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function sqlGetBenefitDetailsByBenefitCode( $benefit_code = '', $fields = array() ){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS . "
			WHERE code =" . Model::safeSql($benefit_code) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

}
?>