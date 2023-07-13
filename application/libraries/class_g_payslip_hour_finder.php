<?php
class G_Payslip_Hour_Finder {
	/*
		$af = array produced by G_Attendance_Finder
	*/
	public static function findByAttendanceFinder($af) {
		if (!is_array($af) && $af) {
			$a[] = $af;
		} else if (is_array($af)) {
			$a = $af;	
		}
		
		$row['present_days'] = G_Attendance_Helper::countPresentDays($a);
		$row['present_days_with_pay'] = G_Attendance_Helper::countPresentDaysWithPay($a);
		$row['absent_days'] = G_Attendance_Helper::countAbsentDays($a);
		$row['absent_days_with_pay'] = G_Attendance_Helper::countAbsentDaysWithPay($a);
		$row['absent_days_without_pay'] = G_Attendance_Helper::countAbsentDaysWithoutPay($a);
		
		$row['late_hours'] = G_Attendance_Helper::getTotalLateHours($a);//
		$row['undertime_hours'] = G_Attendance_Helper::getTotalUndertimeHours($a);//
		
		$row['regular_hours'] = G_Attendance_Helper::getTotalRegularHours($a);//	
		$row['overtime_hours'] = G_Attendance_Helper::getTotalOvertimeHours($a);//	
		$row['overtime_excess_hours'] = G_Attendance_Helper::getTotalOvertimeExcessHours($a);//		
		$row['night_shift_hours'] = G_Attendance_Helper::getTotalRegularNightShiftHours($a);//
		$row['night_shift_overtime_hours'] = G_Attendance_Helper::getTotalNightShiftOvertimeHours($a);//
		$row['night_shift_overtime_excess_hours'] = G_Attendance_Helper::getTotalNightShiftOvertimeExcessHours($a);//
					
		$row['restday_hours'] = G_Attendance_Helper::getTotalRestDayHours($a);//
		$row['restday_overtime_hours'] = G_Attendance_Helper::getTotalRestDayOvertimeHours($a);//
		$row['restday_overtime_excess_hours'] = G_Attendance_Helper::getTotalRestDayOvertimeExcessHours($a);//
		$row['restday_nightshift_hours'] = G_Attendance_Helper::getTotalRestDayNightShiftHours($a);//
		$row['restday_nightshift_overtime_hours'] = G_Attendance_Helper::getTotalRestDayNightShiftOvertimeHours($a);
		$row['restday_nightshift_overtime_excess_hours'] = G_Attendance_Helper::getTotalRestDayNightShiftOvertimeExcessHours($a);
		
		$row['holiday_special_hours'] = G_Attendance_Helper::getTotalHolidaySpecialHours($a);//
		$row['holiday_special_overtime_hours'] = G_Attendance_Helper::getTotalHolidaySpecialOvertimeHours($a);//
		$row['holiday_special_overtime_excess_hours'] = G_Attendance_Helper::getTotalHolidaySpecialOvertimeExcessHours($a);//
		$row['holiday_special_nightshift_hours'] = G_Attendance_Helper::getTotalHolidaySpecialNightShiftHours($a);//
		$row['holiday_special_nightshift_overtime_hours'] = G_Attendance_Helper::getTotalHolidaySpecialNightShiftOvertimeHours($a);//
		$row['holiday_special_nightshift_overtime_excess_hours'] = G_Attendance_Helper::getTotalHolidaySpecialNightShiftOvertimeExcessHours($a);//
		
		$row['holiday_special_restday_hours'] = G_Attendance_Helper::getTotalHolidaySpecialRestdayHours($a);//
		$row['holiday_special_restday_overtime_hours'] = G_Attendance_Helper::getTotalHolidaySpecialRestdayOvertimeHours($a);//
		$row['holiday_special_restday_nightshift_hours'] = G_Attendance_Helper::getTotalHolidaySpecialRestdayNightShiftHours($a);//
		
		$row['holiday_legal_hours'] = G_Attendance_Helper::getTotalHolidayLegalHours($a);//
		$row['holiday_legal_overtime_hours'] = G_Attendance_Helper::getTotalHolidayLegalOvertimeHours($a);//
		$row['holiday_legal_overtime_excess_hours'] = G_Attendance_Helper::getTotalHolidayLegalOvertimeExcessHours($a);//
		$row['holiday_legal_nightshift_hours'] = G_Attendance_Helper::getTotalHolidayLegalNightShiftHours($a);//
		$row['holiday_legal_nightshift_overtime_hours'] = G_Attendance_Helper::getTotalHolidayLegalNightShiftOvertimeHours($a);//
		$row['holiday_legal_nightshift_overtime_excess_hours'] = G_Attendance_Helper::getTotalHolidayLegalNightShiftOvertimeExcessHours($a);//
		
		$row['holiday_legal_restday_hours'] = G_Attendance_Helper::getTotalHolidayLegalRestdayHours($a);//
		$row['holiday_legal_restday_overtime_hours'] = G_Attendance_Helper::getTotalHolidayLegalRestdayOvertimeHours($a);//
		$row['holiday_legal_restday_nightshift_hours'] = G_Attendance_Helper::getTotalHolidayLegalRestdayNightShiftHours($a);		
		return self::newObject($row);
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
		$p = new G_Payslip_Hour;
				
		$p->setPresentDays($row['present_days']);
		$p->setPresentDaysWithPay($row['present_days_with_pay']);
		$p->setAbsentDays($row['absent_days']);
		$p->setAbsentDaysWithPay($row['absent_days_with_pay']);
		$p->setAbsentDaysWithoutPay($row['absent_days_without_pay']);		
		$p->setRegularLate($row['late_hours']);
		$p->setRegularUndertime($row['undertime_hours']);	
		
		$p->setRegular($row['regular_hours']);
		$p->setRegularOvertime($row['overtime_hours']);
		$p->setRegularOvertimeExcess($row['overtime_excess_hours']);	
		$p->setRegularNightShift($row['night_shift_hours']);
		$p->setNightShiftOvertime($row['night_shift_overtime_hours']);
		$p->setNightShiftOvertimeExcess($row['night_shift_overtime_excess_hours']);
		
		$p->setRestDay($row['restday_hours']);
		$p->setRestDayOvertime($row['restday_overtime_hours']);
		$p->setRestDayOvertimeExcess($row['restday_overtime_excess_hours']);		
		$p->setRestdayNightShift($row['restday_nightshift_hours']);
		$p->setRestDayNightShiftOvertime($row['restday_nightshift_overtime_hours']);
		$p->setRestDayNightShiftOvertimeExcess($row['restday_nightshift_overtime_excess_hours']);			
		
		$p->setHolidaySpecial($row['holiday_special_hours']);
		$p->setHolidaySpecialOvertime($row['holiday_special_overtime_hours']);
		$p->setHolidaySpecialOvertimeExcess($row['holiday_special_overtime_excess_hours']);
		$p->setHolidaySpecialNightShift($row['holiday_special_nightshift_hours']);
		$p->setHolidaySpecialNightShiftOvertime($row['holiday_special_nightshift_overtime_hours']);
		$p->setHolidaySpecialNightShiftOvertimeExcess($row['holiday_special_nightshift_overtime_excess_hours']);		
		$p->setHolidaySpecialRestDay($row['holiday_special_restday_hours']);
		$p->setHolidaySpecialRestDayOvertime($row['holiday_special_restday_overtime_hours']);		
		$p->setHolidaySpecialRestdayNightShift($row['holiday_special_restday_nightshift_hours']);		
		
		$p->setHolidayLegal($row['holiday_legal_hours']);
		$p->setHolidayLegalOvertime($row['holiday_legal_overtime_hours']);
		$p->setHolidayLegalOvertimeExcess($row['holiday_legal_overtime_excess_hours']);
		$p->setHolidayLegalNightShift($row['holiday_legal_nightshift_hours']);
		$p->setHolidayLegalNightShiftOvertime($row['holiday_legal_nightshift_overtime_hours']);
		$p->setHolidayLegalNightShiftOvertimeExcess($row['holiday_legal_nightshift_overtime_excess_hours']);		
		$p->setHolidayLegalRestDay($row['holiday_legal_restday_hours']);
		$p->setHolidayLegalRestDayOvertime($row['holiday_legal_restday_overtime_hours']);
		$p->setHolidayLegalRestdayNightShift($row['holiday_legal_restday_nightshift_hours']);
		
		return $p;
	}
}
?>