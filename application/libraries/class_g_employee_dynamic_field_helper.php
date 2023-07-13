<?php
class G_Employee_Dynamic_Field_Helper {
		
	public static function isIdExist(G_Employee_Dynamic_Field $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DYNAMIC_FIELD ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsLabelAndValueExistsByEmployeeId($employee_id = 0, $label = '', $value = '') {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DYNAMIC_FIELD ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
				AND title =" . Model::safeSql($label) . "
				AND value =" . Model::safeSql($value) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		$total  = $row['total'];

		if( $total > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public static function sqlDynamicFieldsByEmployeeId( $employee_id = 0, $fields = array() ) {
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_DYNAMIC_FIELD ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
		";
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllDataByTitle( $title = 0, $fields = array() ) {
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_DYNAMIC_FIELD ."
			WHERE title = ". Model::safeSql($title) ."
		";
		
		$records = Model::runSql($sql,true);
		return $records;
	}

}
?>