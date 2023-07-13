<?php
class G_Daily_Time_Record_Manager {
	public static function save($dtr) {
		echo G_DAILY_TIME_RECORD;
		$sql = "
			INSERT INTO ". G_DAILY_TIME_RECORD ." (employee_code, employee_name, date_entry, time_entry)
			VALUES (
				". Model::safeSql($dtr->getEmployeeCode()) .",
				". Model::safeSql($dtr->getEmployeeName()) .",
				". Model::safeSql($dtr->getDate()) .",
				". Model::safeSql($dtr->getTime()) ."
			)
		";
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		return mysql_insert_id();
	}
}
?>