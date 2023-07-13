<?php
class G_Settings_Policy_Helper {
	public static function isIdExist(G_Settings_Policy $sp) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_POLICY ."
			WHERE id = ". Model::safeSql($sp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>