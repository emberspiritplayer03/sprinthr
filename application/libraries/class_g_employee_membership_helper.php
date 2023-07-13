<?php
class G_Employee_Membership_Helper {
		
	public static function isIdExist(G_Employee_Membership $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_MEMBERSHIP ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>