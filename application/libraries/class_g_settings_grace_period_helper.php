<?php
class G_Settings_Grace_Period_Helper {
		
	public static function isIdExist(G_Settings_Grace_Period $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_GRACE_PERIOD ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	

}
?>