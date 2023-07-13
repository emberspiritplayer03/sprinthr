<?php
class G_Employee_Supervisor_Helper {
		
	public static function isIdExist(G_Employee_Supervisor $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SUPERVISOR ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>