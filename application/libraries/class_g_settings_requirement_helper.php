<?php
class G_Settings_Requirement_Helper {
	public static function isIdExist(G_Settings_Requirement $gsr) {	
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUIREMENTS ."
			WHERE id = ". Model::safeSql($gsr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUIREMENTS ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>