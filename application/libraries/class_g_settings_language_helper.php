<?php
class G_Settings_Language_Helper {
	public static function isIdExist(G_Settings_Language $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_LANGUAGE ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>