<?php
class G_Employee_Education_Helper {
		
	public static function isIdExist(G_Employee_Education $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EDUCATION ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>