<?php
class G_Schedule_Settings_Helper {

        public static function isIdExist(G_Schedule_Settings $gra) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . V2_SCHEDULE_SETTINGS ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>