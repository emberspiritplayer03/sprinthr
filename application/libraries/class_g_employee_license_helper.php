<?php
class G_Employee_License_Helper {
		
	public static function isIdExist(G_Employee_License $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LICENSE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>