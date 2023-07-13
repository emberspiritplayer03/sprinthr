<?php
class G_Attendance_Rate_Finder {
	
	public static function findDefault() {
		// $sql = "
		// 	SELECT id, nightshift_rate, regular_overtime, nightshift_overtime, restday, restday_overtime, holiday_special, holiday_special_overtime, holiday_legal, holiday_legal_overtime, holiday_special_restday, holiday_special_restday_overtime, holiday_legal_restday, holiday_legal_restday_overtime
		// 	FROM g_attendance_rate  a
		// 	LIMIT 1
		// ";
			$sql = "
			SELECT *
			FROM g_attendance_rate  a
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$x = new G_Attendance_Rate;
		$x->setNightShiftDiff($row['nightshift_rate']);
		$x->setRegularOvertime($row['regular_overtime']);
		$x->setNightShiftOvertime($row['nightshift_overtime']);
			//$x->setHolidaySpecialNightShiftOvertime($row['holiday_special_night_shift_overtime']);
		$x->setRestDay($row['restday']);
		$x->setRestDayOvertime($row['restday_overtime']);
		$x->setHolidaySpecial($row['holiday_special']);
		$x->setHolidaySpecialOvertime($row['holiday_special_overtime']);
		$x->setHolidayLegal($row['holiday_legal']);
		$x->setHolidayLegalOvertime($row['holiday_legal_overtime']);
		$x->setHolidaySpecialRestday($row['holiday_special_restday']);
		$x->setHolidaySpecialRestdayOvertime($row['holiday_special_restday_overtime']);
		$x->setHolidayLegalRestday($row['holiday_legal_restday']);
		$x->setHolidayLegalRestdayOvertime($row['holiday_legal_restday_overtime']);
		return $x;
	}
}
?>