<?php
class G_Employee_Training_Helper {
		
	public static function isIdExist(G_Employee_Training $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_TRAINING ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>