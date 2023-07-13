<?php
class G_Settings_Memo_Helper {
	public static function isIdExist(G_Settings_Memo $sm) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_MEMO ."
			WHERE id = ". Model::safeSql($sm->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>