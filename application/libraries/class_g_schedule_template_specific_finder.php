<?php
class G_Schedule_Template_Specific_Finder {

	public static function findAllByEmployee(IEmployee $e) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($e->getId()) ."
			ORDER BY s.date_start DESC
		";
		return self::getRecords($sql);
	}

    public static function findAllByEmployeeAndMonthAndYear($e, $month, $year) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($e->getId()) ."
			AND MONTH(s.date_start) = ". Model::safeSql($month) ."
			AND YEAR(s.date_start) = ". Model::safeSql($year) ."
			ORDER BY s.date_start DESC
		";		
		return self::getRecords($sql);
    }
	
	public static function findAllByEmployeeAndPeriod(IEmployee $e, $from, $to) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($e->getId()) ."
			AND s.date_start >= ". Model::safeSql($from) ."
			AND s.date_start <= ". Model::safeSql($to) ."
			ORDER BY s.date_start DESC
		";
		return self::getRecords($sql);
	}
	
	public static function findById($id) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.id = ". Model::safeSql($id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeAndDate(IEmployee $e, $date) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($e->getId()) ."
			AND ((". Model::safeSql($date) ." >= s.date_start AND (s.date_end = '0000-00-00' OR s.date_end = '')) OR (". Model::safeSql($date) ." >= s.date_start AND ". Model::safeSql($date) ." <= s.date_end))	
			AND s.date_start = 
				(
					SELECT s2.date_start 
					FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s2
					WHERE s2.date_start <= ". Model::safeSql($date) ."
					AND s2.employee_id = ". Model::safeSql($e->getId()) ."
					ORDER BY s2.date_start DESC
					LIMIT 1
				)
			ORDER BY s.id DESC
			LIMIT 1	
		";	
				return self::getRecord($sql);
	}

	public static function findEmployeeScheduleByEmployeeIdAndDate($id = '', $date = '') {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($id) ."
			AND ((". Model::safeSql($date) ." >= s.date_start AND (s.date_end = '0000-00-00' OR s.date_end = '')) OR (". Model::safeSql($date) ." >= s.date_start AND ". Model::safeSql($date) ." <= s.date_end))	
			AND s.date_start = 
				(
					SELECT s2.date_start 
					FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s2
					WHERE s2.date_start <= ". Model::safeSql($date) ."
					AND s2.employee_id = ". Model::safeSql($id) ."
					ORDER BY s2.date_start DESC
					LIMIT 1
				)
			ORDER BY s.id DESC
			LIMIT 1	
		";	
		return self::getRecord($sql);
	}

	public static function findEmployeeScheduleByEmployeeIdAndDateRange($id = '', $date_from = '', $date_to = '') {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($id) ."
			AND (
					(". Model::safeSql($date_from) ." >= s.date_start AND (s.date_end = '0000-00-00' OR s.date_end = '')) OR (". Model::safeSql($date_from) ." >= s.date_start AND ". Model::safeSql($date_to) ." <= s.date_end)
				)	
			AND s.date_start = 
				(
					SELECT s2.date_start 
					FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s2
					WHERE s2.date_start <= ". Model::safeSql($date) ."
					AND s2.employee_id = ". Model::safeSql($id) ."
					ORDER BY s2.date_start DESC
					LIMIT 1
				)
			ORDER BY s.id DESC
			LIMIT 1	
		";	

		return self::getRecord($sql);
	}

	public static function findChangeEmployeeScheduleByEmployeeIdAndDateRange($id = '', $date_from = '', $date_to = '') {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.employee_id = ". Model::safeSql($id) ."
			AND (
					(". Model::safeSql($date_from) ." >= s.date_start AND (s.date_end = '0000-00-00' OR s.date_end = '')) OR (". Model::safeSql($date_from) ." >= s.date_start AND ". Model::safeSql($date_to) ." <= s.date_end)
				)	
			ORDER BY s.id DESC
			LIMIT 1	
		";	

		return self::getRecord($sql);
	}	
	
	public static function findByEmployeeAndStartAndEndDate(IEmployee $e, $date_start, $date_end) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.date_start = ". Model::safeSql($date_start) ."
			AND s.date_end = ". Model::safeSql($date_end) ."
			AND s.employee_id = ". Model::safeSql($e->getId()) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeIdAndStartAndEndDate($employee_id, $date_start, $date_end) {
		$sql = "
			SELECT s.id, s.employee_id, s.date_start, s.date_end, s.time_in, s.time_out
			FROM ". G_EMPLOYEE_STAGGERED_SCHEDULE ." s
			WHERE s.date_start = ". Model::safeSql($date_start) ."
			AND s.date_end = ". Model::safeSql($date_end) ."
			AND s.employee_id = ". Model::safeSql($employee_id) ."
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
		$s = new G_Schedule_Specific;
		$s->setId($row['id']);
		$s->setDateStart($row['date_start']);
		$s->setDateEnd($row['date_end']);
		$s->setTimeIn($row['time_in']);
		$s->setTimeOut($row['time_out']);
		$s->setEmployeeId($row['employee_id']);
		return $s;
	}
}
?>