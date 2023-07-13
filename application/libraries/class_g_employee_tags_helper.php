<?php
class G_Employee_Tags_Helper {
	public static function isIdExist(G_Employee_Tags $get) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_TAGS ."
			WHERE id = ". Model::safeSql($get->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByEmployeeId(G_Employee $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_TAGS ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getEmployeeTags($employee_id) {
		$sql = "
			SELECT *
			FROM " . G_EMPLOYEE_TAGS ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
		";		
		$rows = Model::runSql($sql,false);		
		return $rows;
	}	
}
?>