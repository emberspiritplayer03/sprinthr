<?php
class G_Settings_Membership_Type_Helper {
	public static function isIdExist(G_Settings_Membership_Type $gsmt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . MEMBERSHIP_TYPE ."
			WHERE id = ". Model::safeSql($gsmt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . MEMBERSHIP_TYPE ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . MEMBERSHIP_TYPE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>