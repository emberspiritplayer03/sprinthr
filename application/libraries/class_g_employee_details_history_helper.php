<?php
class G_Employee_Details_History_Helper {
	public static function isIdExist(G_Employee_Details_History $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DETAILS_HISTORY ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>