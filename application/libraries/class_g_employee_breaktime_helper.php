<?php
class G_Employee_Breaktime_Helper {
	public static function isIdExist(G_Employee_Breaktime $glt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BREAKTIME ."
			WHERE id = ". Model::safeSql($glt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getLateHoursByEmployeeIdPeriod($employee_id, $date_from, $date_to) {
		$sql = "
			SELECT SUM(late_hours) as total
			FROM " . G_EMPLOYEE_BREAKTIME ."
			WHERE date >= ". Model::safeSql($date_from) ." 
				AND date <= ". Model::safeSql($date_to) ." 
				AND time_in <> '' AND time_out <> ''
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>