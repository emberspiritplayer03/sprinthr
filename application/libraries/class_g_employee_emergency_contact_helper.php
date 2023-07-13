<?php
class G_Employee_Emergency_Contact_Helper {
		
	public static function isIdExist(G_Employee_Emergency_Contact $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_EMERGENCY_CONTACT ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>