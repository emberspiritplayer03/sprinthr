<?php
class G_Settings_Employee_Status_Helper {
	
	public static function isIdExist(G_Settings_Employee_Status $gses) {	
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ."
			WHERE id = ". Model::safeSql($gses->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_EMPLOYEE_STATUS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>