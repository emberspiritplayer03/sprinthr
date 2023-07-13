<?php
class G_Employee_Dependent_Helper {
		
	public static function isIdExist(G_Employee_Dependent $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DEPENDENT ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCountTotalDependentsByEmployeeId($employee_id = 0) {
		$sql = "
			SELECT COALESCE(COUNT(id),0) as total
			FROM " . G_EMPLOYEE_DEPENDENT ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>