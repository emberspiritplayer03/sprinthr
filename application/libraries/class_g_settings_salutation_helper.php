<?php
class G_Settings_Salutation_Helper {
	public static function isIdExist(G_Settings_Salutation $gss) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_SALUTATION ."
			WHERE id = ". Model::safeSql($gss->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>