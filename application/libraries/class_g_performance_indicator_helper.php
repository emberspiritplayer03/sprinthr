<?php
class G_Performance_Indicator_Helper {
	public static function isIdExist(G_Performance_Indicator $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE_INDICATOR ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE_INDICATOR			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByPerformanceId($performance_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE_INDICATOR."
			WHERE performance_id=".Model::safeSql($performance_id)."
			"			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>