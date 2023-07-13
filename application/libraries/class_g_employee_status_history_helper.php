<?php
class G_Employee_Status_History_Helper {
	public static function isIdExist(G_Employee_Status_History $gel) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_STATUS_HISTORY ."
			WHERE id = ". Model::safeSql($gel->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function findIfactiveEmployeeInBetweenDates($employee_id, $start_date, $end_date) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(1) ." 
			AND employee_id =". Model::safeSql($employee_id) ."
			AND start_date BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . "
			AND start_date != ''
			AND end_date = ''
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

	public static function findInactiveEmployeeByIdAndInBetweenDates($e, $start_date, $end_date) {
		$sql = "
			SELECT COUNT(*) as total 
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(5) ." 
			AND employee_id = ". Model::safeSql($e->getId()) ." 
			AND " . Model::safeSql($start_date) . " >= start_date
			AND " . Model::safeSql($end_date) . " <= end_date
			AND end_date != ''
			AND start_date != ''
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

		public static function findInactiveEmployeesByIdAndInBetweenDates($id, $start_date, $end_date) {
		$sql = "
			SELECT *
			FROM " . G_EMPLOYEE_STATUS_HISTORY ." 
			WHERE employee_status_id =". Model::safeSql(5) ." 
			AND employee_id = ". Model::safeSql($id) ." 
			AND " . Model::safeSql($start_date) . " >= start_date
			AND " . Model::safeSql($end_date) . " <= end_date
			AND end_date != ''
			AND start_date != ''

		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}


}
?>