<?php
class G_Philhealth_Helper {
	public static function isIdExist(G_Philhealth $gp) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . PHILHEALTH ."
			WHERE id = ". Model::safeSql($gp->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . PHILHEALTH		
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
}
?>