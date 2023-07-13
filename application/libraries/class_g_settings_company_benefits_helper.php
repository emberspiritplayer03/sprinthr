<?php
class G_Settings_Company_Benefits_Helper {

        public static function isIdExist(G_Settings_Company_Benefits $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_COMPANY_BENEFITS ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_COMPANY_BENEFITS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>