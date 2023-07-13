<?php
class G_Salary_Cycle_Finder {	
	public static function findDefault() {
//		$sql = "
//			SELECT sc.id as type, sc.cut_offs
//			FROM ". G_SALARY_CYCLE ." sc
//			WHERE sc.is_default = ". YES ."
//			LIMIT 1			
//		";
		$sql = "
			SELECT sc.cut_off as cut_offs, sc.payout_day
			FROM ". G_SETTINGS_PAY_PERIOD ." sc
			WHERE sc.is_default = ". YES ."
			LIMIT 1			
		";		
		
		return self::getRecord($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		if (!$row['cut_offs']) {
			return false;
		}
		return self::newObject($row);
	}
	
	private static function newObject($row) {
		//$cutoffs = (array) unserialize($row['cut_offs']);
		$cutoffs = explode(',', $row['cut_offs']);
		$days = explode(',', $row['payout_day']);
		$s = new G_Salary_Cycle($cutoffs, $row['type']);
		$s->setPayoutDays($days);
		return $s;
	}
}
?>