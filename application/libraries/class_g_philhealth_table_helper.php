<?php
class G_Philhealth_Table_Helper {
	
	public static function isIdExist(G_Philhealth_Table $gcp) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PHILHEALTH ."
			WHERE id = ". Model::safeSql($gcp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>