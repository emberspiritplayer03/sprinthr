<?php
class G_Philhealth_History_Helper {
	
	public static function isIdExist(G_Philhealth_History $gcp) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PHILHEALTH_HISTORY ."
			WHERE id = ". Model::safeSql($gcp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>