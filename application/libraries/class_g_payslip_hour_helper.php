<?php
class G_Payslip_Hour_Helper {
	public static function getAllHoursByPeriodGroupByEmployee($start_date, $end_date) {
		$employee_hours = array();
		$employees = G_Employee_Finder::findAllActiveByDate($start_date);
		foreach ($employees as $e) {
			$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
			if ($a) {
				$h = G_Payslip_Hour_Finder::findByAttendanceFinder($a);		
				if ($h) {
					$employee_hours[$e->getId()] = $h;	
				}
			}
		}
		if (count($employee_hours) > 0) {
			return $employee_hours;
		} else {
			return false;	
		}
	}
	
	/*
		$employees - value from G_Employee_Finder::findAll(), findAllActiveDate()
	*/
	public static function getAllHoursByEmployeesAndPeriod($employees, $start_date, $end_date) {
		$employee_hours = array();
		foreach ($employees as $e) {
			$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
			if ($a) {
				$h = G_Payslip_Hour_Finder::findByAttendanceFinder($a);		
				if ($h) {
					$employee_hours[$e->getId()] = $h;	
				}
			}
		}
		if (count($employee_hours) > 0) {
			return $employee_hours;
		} else {
			return false;	
		}
	}
}
?>