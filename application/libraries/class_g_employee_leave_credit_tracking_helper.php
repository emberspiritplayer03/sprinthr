<?php
class G_Employee_Leave_Credit_Tracking_Helper {
	public static function isIdExist(G_Employee_Leave_Credit_Tracking $lt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE_LEAVE_CREDIT_TRACKING ."
			WHERE id = ". Model::safeSql($lt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>