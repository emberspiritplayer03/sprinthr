<?php
class G_Employee_Overtime_Rate_Helper {
	public static function isIdExist(G_Employee_Overtime_Rate $or) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_OVERTIME_RATES ."
			WHERE id = ". Model::safeSql($or->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function searchValidEmployees($search = '') {
		$sql = "
			SELECT e.id, CONCAT(e.lastname, ' ', e.firstname) as employee_name
			FROM " . EMPLOYEE . " e 
			WHERE e.id NOT IN( SELECT eor.employee_id FROM " . G_EMPLOYEE_OVERTIME_RATES . " eor) 
			AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . ")
			AND (e.firstname LIKE '%{$search}%' OR e.lastname LIKE '%{$search}%') 
		";		
		
		$records = Model::runSql($sql,true);		
		return $records;
	}

	public static function getAllData($fields = array()) {
		$sql_fields = ' * ';

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_OVERTIME_RATES . "
		";		
		
		$records = Model::runSql($sql,true);		
		return $records;
	}
}
?>