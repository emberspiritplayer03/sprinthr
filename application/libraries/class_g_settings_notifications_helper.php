<?php
class G_Settings_Notifications_Helper {
	public static function isIdExist(G_Settings_Notifications $at) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_NOTIFICATIONS ."
			WHERE id = ". Model::safeSql($at->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_NOTIFICATIONS ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
			
	
}
?>