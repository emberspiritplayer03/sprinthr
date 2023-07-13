<?php
class G_Settings_Default_Leave_Helper {
		
	public static function isIdExist(G_Settings_Default_Leave $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_DEFAULT_LEAVE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
}
?>