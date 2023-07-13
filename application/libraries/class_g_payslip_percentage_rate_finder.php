<?php
class G_Payslip_Percentage_Rate_Finder {
	
	public static function findDefault() {
		// $sql = "
		// 	SELECT id, regular_overtime_nightshift_differential, nightshift_rate, regular_overtime, nightshift_overtime, restday, restday_overtime, holiday_special, holiday_special_overtime, holiday_legal, holiday_legal_overtime, holiday_special_restday, holiday_special_restday_overtime, holiday_legal_restday, holiday_legal_restday_overtime, holiday_special_restday_night_shift_overtime
		// 	FROM g_attendance_rate  a
		// 	LIMIT 1
		// ";
		$sql = "
			SELECT *
			FROM g_attendance_rate  a
			WHERE is_default = 1
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findRateBySalaryType($salary_type)
	{
		$salary_type = strtoupper($salary_type);

		$sql = "
		SELECT *
		FROM g_attendance_rate  a
		WHERE salary_type = ". Model::safeSql($salary_type) ."	
		LIMIT 1 ";

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
		$x = new G_Payslip_Percentage_Rate;
		// $x->setNightShiftDiff($row['nightshift_rate']); // deleted
		// $x->setNightShiftOvertime($row['nightshift_overtime']); // deleted
		// $x->setHolidayLegalRestDayNightShiftOvertime($row['holiday_legal_restday_night_shift_overtime']); // deleted
		// $x->setRegularOvertimeNightShiftDifferential($row['regular_overtime_nightshift_differential']); //deleted
		// $x->setHolidayLegalNightShift($row['holiday_legal_nightshift']);
		// $x->setHolidaySpecialNightShiftOvertime($row['holiday_special_night_shift_overtime']);
		// $x->setHolidaySpecialRestDayNightShiftOvertime($row['holiday_special_restday_night_shift_overtime']); 
		// $x->setHolidayLegalNightShiftOvertime($row['holiday_legal_nightshift_overtime']);

		// Regular
		$x->setRegular($row['regular']);
		$x->setRestDay($row['restday']); //ok
		$x->setHolidaySpecial($row['holiday_special']); //ok
		$x->setHolidaySpecialRestday($row['holiday_special_restday']); //ok
		$x->setHolidayLegal($row['holiday_legal']); //ok
		$x->setHolidayLegalRestday($row['holiday_legal_restday']);  //ok
		$x->setHolidayDouble($row['holiday_double']); //ok
		$x->setHolidayDoubleRestday($row['holiday_double_restday']); //ok

		// Overtime
		$x->setRegularOvertime($row['regular_overtime']); //ok
		$x->setRestDayOvertime($row['restday_overtime']); //ok
		$x->setHolidaySpecialOvertime($row['holiday_special_overtime']); //ok
		$x->setHolidaySpecialRestdayOvertime($row['holiday_special_restday_overtime']); //ok
		$x->setHolidayLegalOvertime($row['holiday_legal_overtime']); //ok
		$x->setHolidayLegalRestdayOvertime($row['holiday_legal_restday_overtime']); //ok
		$x->setHolidayDoubleOvertime($row['holiday_double_overtime']); 
		$x->setHolidayDoubleRestdayOvertime($row['holiday_double_restday_overtime']);

		// Night Differentials
		$x->setRegularNightDifferential($row['nightshift_rate']); 
		$x->setRestDayNightDifferential($row['restday_night_differential']);
		$x->setHolidaySpecialNightDifferential($row['holiday_special_night_differential']);
		$x->setHolidaySpecialRestdayNightDifferential($row['holiday_special_restday_night_differential']);
		$x->setHolidayLegalNightDifferential($row['holiday_legal_night_differential']);
		$x->setHolidayLegalRestdayNightDifferential($row['holiday_legal_restday_night_differential']);
		$x->setHolidayDoubleNightDifferential($row['holiday_double_night_differential']);
		$x->setHolidayDoubleRestdayNightDifferential($row['holiday_double_restday_night_differential']);

		// Night Differentials Overtime
		$x->setRegularNightDifferentialOvertime($row['regular_night_differential_overtime']); 
		$x->setRestDayNightDifferentialOvertime($row['restday_night_differential_overtime']);
		$x->setHolidaySpecialNightDifferentialOvertime($row['holiday_special_night_differential_overtime']);
		$x->setHolidaySpecialRestdayNightDifferentialOvertime($row['holiday_special_restday_night_differential_overtime']);
		$x->setHolidayLegalNightDifferentialOvertime($row['holiday_legal_night_differential_overtime']);
		$x->setHolidayLegalRestdayNightDifferentialOvertime($row['holiday_legal_restday_night_differential_overtime']);
		$x->setHolidayDoubleNightDifferentialOvertime($row['holiday_double_night_differential_overtime']);
		$x->setHolidayDoubleRestdayNightDifferentialOvertime($row['holiday_double_restday_night_differential_overtime']);

		return $x;
	}
}
?>