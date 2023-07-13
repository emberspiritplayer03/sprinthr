<?php
function get_attendance_string($attendance) {
	if ($attendance->isPresent()) {
		$present = '<span class="present-font-style">Present</span>';
	}
	if (($h = $attendance->getHoliday())) {
		$holiday_name = $h->getTitle();
		$holiday = '<span class="holiday-font-style">'. $holiday_name .'</span>';
	}
	if ($attendance->isRestday()) {						
		$restday = '<span class="restday-font-style">Restday</span>';
	}
	if (($leave_id = $attendance->getLeaveId())) {
		$leave = G_Leave_Finder::findById($leave_id);
		$leave_name = '<span class="leave-font-style">'. $leave->getName() .'</span>';	
	}
	
	if (!$attendance->isPresent() && (!$attendance->getHoliday() && !$attendance->isRestday() && !$attendance->getLeaveId())) {
		$present = '<span class="absent-font-style">Absent</span>';	
	}
	return "{$present} {$holiday} {$restday} {$leave_name} {$attendance_type_name}";
}
?>