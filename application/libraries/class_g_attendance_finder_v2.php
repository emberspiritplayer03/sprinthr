<?php
class G_Attendance_Finder_V2 {
    public static function getFields() {
        $fields = "id,employee_id,date,schedule_template_id,schedule_in,schedule_out,schedule_break_out,schedule_break_in,
		schedule_snack_out,schedule_snack_in,schedule_ot_out,schedule_ot_in,is_auto_sched,early_ot_in,early_ot_out,
		early_break_in,early_break_out,in,out,snack_in,snack_out,break_in,break_out,ot_in,ot_out,ot_break_in,ot_break_out,
		is_leave,is_leave_with_pay,is_ob,is_half_day,is_absent,is_rest_day,undertime_hours,late_hours,work_day_type,
		calendar_holiday,calendar_holiday_id,project_site_id,has_error,error_message,has_approval";

        return $fields;
    }
	public static function findByEmployeeAndDate(IEmployee $e, $date) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			AND date_attendance = ". Model::safeSql($date) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findById($id) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE id = ". Model::safeSql($id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findEmployeeLastAttendance($employee_id) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE employee_id = ". Model::safeSql($employee_id) ." 
				AND actual_date_in <> '' AND actual_date_out <> '' 
				AND actual_time_in <> '' AND actual_time_out <> ''
			ORDER BY date DESC
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByDate($date)
	{
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE
					date = ". Model::safeSql($date) ."
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByEmployee(IEmployee $e)
	{
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE
					employee_id = ". Model::safeSql($e->getId()) ."
			ORDERY BY id, date  ASC
		";
		return self::getRecords($sql);
	}
	
	public static function findByEmployeeAndPeriod(IEmployee $e, $start, $end) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			AND 
				(
					date >= ". Model::safeSql($start) ."
					AND
					date <= ". Model::safeSql($end) ."
				)
			ORDER BY date
		";

		return self::getRecords($sql);
	}


		public static function findAttendanceWithLeaveAndPaidByEmployeeAndPeriod($employee_id, $start, $end) {
		$is_leave = 1;
		$is_paid  = 1;
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
			AND is_leave = " . $is_leave . "
			and is_paid = " . $is_paid . "
			AND 
				(
					date >= ". Model::safeSql($start) ."
					AND
					date <= ". Model::safeSql($end) ."
				)
			ORDER BY date
		";

		return self::getRecords($sql);
	}	

	public static function findAllEmployeeAttendanceByStartAndEndDate($start, $end) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
			WHERE 
				(
					date >= ". Model::safeSql($start) ."
					AND
					date <= ". Model::safeSql($end) ."
				)
			ORDER BY date
		";
		
		return self::getRecords($sql);
	}
	
	public static function findByEmployeeAndPeriodFilterByTerminatedDate(IEmployee $e, $start, $end) {
		if($e->getTerminatedDate() <= $end){
			$sql = "
				SELECT ". self::getFields() ."
				FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
				WHERE employee_id = ". Model::safeSql($e->getId()) ."
				AND 
					(
						date >= ". Model::safeSql($start) ."
						AND
						date <= ". Model::safeSql($e->getTerminatedDate()) ."
					)
				ORDER BY date
			";
			return self::getRecords($sql);		
		}
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
		$a = new G_Attendance;
		$a->setId($row['id']);
        $a->setEmployeeId($row['employee_id']);
		$a->setDate($row['date']);
		if ($row['is_paid']) { $a->setAsPaid(); }
		if ($row['is_present']) { $a->setAsPresent(); }
		if ($row['is_restday']) { $a->setAsRestday(); }
		if ($row['is_holiday']) { $a->setAsHoliday(); $a->setHolidayType($row['holiday_type']); }
        if ($row['is_ob']) { $a->setAsOfficialBusiness(); }
		if ($row['is_leave']) { $a->setAsLeave(); }
		if ($row['leave_id']) { $a->setLeaveId($row['leave_id']); }
		if ($row['is_suspended']) { $a->setAsSuspended(); }
		
		$h = G_Holiday_Finder::findById($row['holiday_id']);
		if ($h) { $a->setHoliday($h); }
		
		$t = new G_Timesheet;
		$t->setScheduledTimeIn($row['scheduled_time_in']);
		$t->setScheduledTimeOut($row['scheduled_time_out']);
        $t->setScheduledDateIn($row['scheduled_date_in']);
        $t->setScheduledDateOut($row['scheduled_date_out']);
        $t->setTotalDeductibleBreaktimeHours($row['total_breaktime_deductible_hours']);

		$t->setTimeIn($row['actual_time_in']);
		$t->setTimeOut($row['actual_time_out']);
		$t->setDateIn($row['actual_date_in']);
		$t->setDateOut($row['actual_date_out']);

		
		//new ob with timebase
		$t->setOBIn($row['ob_in']);
		$t->setOBOut($row['ob_out']);
		$t->setOBTotalHrs($row['ob_total_hrs']);


		$t->setOverTimeIn($row['overtime_time_in']);
		$t->setOverTimeOut($row['overtime_time_out']);
        $t->setOvertimeDateIn($row['overtime_date_in']);
        $t->setOvertimeDateOut($row['overtime_date_out']);

		$t->setEarlyOverTimeIn($row['early_overtime_in']);
		$t->setEarlyOverTimeOut($row['early_overtime_out']);

		$t->setTotalHoursWorked($row['total_hours_worked']);
        $t->setTotalScheduleHours($row['total_schedule_hours']);
        $t->setTotalOvertimeHours($row['total_overtime_hours']);

		$t->setNightShiftHours($row['night_shift_hours']);
		$t->setLateHours($row['late_hours']);
		$t->setUndertimeHours((float) $row['undertime_hours']);

        // Start - Deprecated
		$t->setNightShiftHoursSpecial($row['night_shift_hours_special']);
		$t->setNightShiftHoursLegal($row['night_shift_hours_legal']);
		$t->setOvertimeHours($row['overtime_hours']);
        $t->setOvertimeExcessHours($row['overtime_excess_hours']);
        $t->setOvertimeNightShiftHours($row['night_shift_overtime_hours']);
        $t->setOvertimeNightShiftExcessHours($row['night_shift_overtime_excess_hours']);
		$t->setNightShiftOvertimeHours($row['night_shift_overtime_hours']); // Deprecated
		$t->setNightShiftOvertimeExcessHours($row['night_shift_overtime_excess_hours']); // Deprecated
        $t->setHolidayHoursSpecial($row['holiday_hours_special']);
        $t->setHolidayHoursLegal($row['holiday_hours_legal']);
        // End - Deprecated

        $t->setRegularOvertimeHours($row['regular_overtime_hours']);
        $t->setRegularOvertimeExcessHours($row['regular_overtime_excess_hours']);
        $t->setRegularOvertimeNightShiftHours($row['regular_overtime_nightshift_hours']);
        $t->setRegularOvertimeNightShiftExcessHours($row['regular_overtime_nightshift_excess_hours']);

		$t->setRestDayOvertimeHours($row['restday_overtime_hours']);
		$t->setRestDayOvertimeExcessHours($row['restday_overtime_excess_hours']);
		$t->setRestDayOvertimeNightShiftHours($row['restday_overtime_nightshift_hours']);
		$t->setRestDayOvertimeNightShiftExcessHours($row['restday_overtime_nightshift_excess_hours']);

        $t->setRestDayLegalOvertimeHours($row['restday_legal_overtime_hours']);
        $t->setRestDayLegalOvertimeExcessHours($row['restday_legal_overtime_excess_hours']);
        $t->setRestDayLegalOvertimeNightShiftHours($row['restday_legal_overtime_ns_hours']);
        $t->setRestDayLegalOvertimeNightShiftExcessHours($row['restday_legal_overtime_ns_excess_hours']);

        $t->setRestDaySpecialOvertimeHours($row['restday_special_overtime_hours']);
        $t->setRestDaySpecialOvertimeExcessHours($row['restday_special_overtime_excess_hours']);
        $t->setRestDaySpecialOvertimeNightShiftHours($row['restday_special_overtime_ns_hours']);
        $t->setRestDaySpecialOvertimeNightShiftExcessHours($row['restday_special_overtime_ns_excess_hours']);

        $t->setLegalOvertimeHours($row['legal_overtime_hours']);
        $t->setLegalOvertimeExcessHours($row['legal_overtime_excess_hours']);
        $t->setLegalOvertimeNightShiftHours($row['legal_overtime_ns_hours']);
        $t->setLegalOvertimeNightShiftExcessHours($row['legal_overtime_ns_excess_hours']);

        $t->setSpecialOvertimeHours($row['special_overtime_hours']);
        $t->setSpecialOvertimeExcessHours($row['special_overtime_excess_hours']);
        $t->setSpecialOvertimeNightShiftHours($row['special_overtime_ns_hours']);
        $t->setSpecialOvertimeNightShiftExcessHours($row['special_overtime_ns_excess_hours']);
		
		$a->setTimesheet($t);	
		return $a;
	}
}
?>
