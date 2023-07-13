<?php
class G_Settings_Application_Status_Helper {
	public static function isIdExist(G_Settings_Application_Status $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_APPLICATION_STATUS ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_APPLICATION_STATUS			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>