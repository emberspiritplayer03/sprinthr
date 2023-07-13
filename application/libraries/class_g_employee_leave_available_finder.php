<?php
class G_Employee_Leave_Available_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByEmployeeId($employee_id, $year) {

		$sqlold = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
		";

		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted,gla.no_of_days_available,
				   gl.id,gl.name 
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.employee_id = ". Model::safeSql($employee_id) ."
			AND gl.gl_is_archive = 'No'
		";			
		
		return self::getRecords($sql);


	}

	public static function findByEmployeeIdNew($employee_id, $year) {

		$sqlold = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
		";

		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted,gla.no_of_days_available,
				   gla.id,gl.name 
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.employee_id = ". Model::safeSql($employee_id) ."
			AND gl.gl_is_archive = 'No'
			AND gla.covered_year = ". Model::safeSql($year) ."
			GROUP BY gl.id
		";		
		
		return self::getRecords($sql);


	}

	public static function findByEmployeeIdYear($employee_id, $year) {

		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted,gla.no_of_days_available,
				   gla.id, gl.name as leave_name
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.employee_id = ". Model::safeSql($employee_id) ."
			AND gl.gl_is_archive = 'No'
			GROUP BY gl.id
		";			
		
		$result = Model::runSql($sql,true);
		return $result;


	}	

    public static function findByEmployeeIdAndYear($employee_id, $year) {
		$sql = "
			SELECT
				*
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
            AND e.covered_year = ". Model::safeSql($year) ."
		";

		return self::getRecords($sql);
	}

    public static function findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year) {
        $sql = "
			SELECT *
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
            AND e.covered_year = ". Model::safeSql($year) ."
            AND e.leave_id = ". Model::safeSql($leave_id) ."
            LIMIT 1
		";
		
        return self::getRecord($sql);
    }
	
	public static function findByEmployeeIdLeaveId($employee_id,$leave_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ." AND e.leave_id=".Model::safeSql($leave_id)."
	
		";
		
		return self::getRecord($sql);
	}

	public static function findByEmployeeIdLeaveIdAndYear($employee_id,$leave_id, $year) {
		if(empty($year)) {
			$year = date("Y");
		} 
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ." AND e.leave_id=".Model::safeSql($leave_id)."
			AND e.covered_year=".Model::safeSql($year)."
	
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
		
		$e = new G_Employee_Leave_Available;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setLeaveId($row['leave_id']);
		$e->setNoOfDaysAlloted($row['no_of_days_alloted']);
		$e->setNoOfDaysAvailable($row['no_of_days_available']);
        $e->setNoOfDaysUsed($row['no_of_days_used']);
        $e->setCoveredYear($row['covered_year']);

		return $e;
	}
}
?>