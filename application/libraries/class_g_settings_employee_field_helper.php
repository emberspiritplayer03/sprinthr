<?php
class G_Settings_Employee_Field_Helper {
		
	public static function isIdExist(G_Settings_Employee_Field $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_EMPLOYEE_FIELD ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	

}
?>