<?php
class G_Employee_Language_Helper {
		
	public static function isIdExist(G_Employee_Language $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LANGUAGE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>