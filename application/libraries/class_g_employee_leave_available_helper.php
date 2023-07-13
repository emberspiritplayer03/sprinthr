<?php
class G_Employee_Leave_Available_Helper {
	public static function isIdExist(G_Employee_Leave_Available $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsEmployeeLeaveTypeExists($employee_id = 0, $leave_id = 0, $covered_year = 0 ) {
		$is_with_leave_type = false;
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ."
			WHERE employee_id =". Model::safeSql($employee_id) ."
				AND leave_id =" . Model::safeSql($leave_id) . "
				AND covered_year =" . Model::safeSql($covered_year) . "
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		if( $row['total'] > 0 ){
			$is_with_leave_type = true;
		}
		return $is_with_leave_type;
	}

	public static function sqlIsEmployeeLeaveTypeExist($employee_id = 0, $leave_id = 0, $covered_year = 0 ) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ."
			WHERE employee_id =". Model::safeSql($employee_id) ."
				AND leave_id =" . Model::safeSql($leave_id) . "
				AND covered_year =" . Model::safeSql($covered_year) . "
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

    public static function getAvailableLeaveCredit($e, $leave, $year) {
        if ($year == '') {
            $year = Tools::getGmtDate('Y');
        }
        $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
        if (!$la) {
            return 0;
        }
        return $la->getNoOfDaysAvailable();
    }
	
	public static function subtractLeaveAvailable(G_Employee_Leave_Available $la) {
		if($la) {
			$available_leave = $la->getNoOfDaysAvailable();
			$available_leave--;
			$la->getNoOfDaysAvailable($available_leave);
		}
	}

	public static function sqlEmployeeLeaveTypeAvailable($employee_id = 0){
		$sql = "
			SELECT l.id, l.name
			FROM " . G_LEAVE . " l 				
			WHERE l.id NOT IN(
				SELECT DISTINCT(la.leave_id)
				FROM " . G_EMPLOYEE_LEAVE_AVAILABLE . " la 
				WHERE la.employee_id =" . Model::safeSql($employee_id) . "
			) AND l.gl_is_archive = 'No'

		";		
		
		$result = Model::runSql($sql,true);

		return $result;	
	}

	public static function sqlEmployeeAvailableLeaveCreditByEmployeeIdAndLeaveId($employee_id = 0, $leave_id = 0){
		$sql = "
			SELECT SUM(no_of_days_available)AS available_credits
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND leave_id =" . Model::safeSql($leave_id) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['available_credits'];
	}

	public static function sqlGetEmployeeNonZeroAvailableCreditByLeaveIdAndEmployeeId($leave_id = 0, $employee_id = 0 ){
		$sql = "
			SELECT id, COALESCE(no_of_days_available,0)AS no_of_days_available
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND leave_id =" . Model::safeSql($leave_id) . "
				AND no_of_days_available > 0
			ORDER BY no_of_days_available DESC
			LIMIT 1
		";			
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetEmployeeLeaveCreditsByEmployeeIdAndLeaveId($leave_id = 0, $employee_id = 0 ){
		$sql = "
			SELECT id, no_of_days_available
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND leave_id =" . Model::safeSql($leave_id) . "				
			ORDER BY no_of_days_available ASC
			LIMIT 1
		";			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function getEmployeeLeaveAvailable(G_Employee $e) {
		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted,gla.no_of_days_available,
				   gl.id,gl.name 
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.employee_id = ". Model::safeSql($e->getId()) ."
		";		
		$result = Model::runSql($sql);
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = $row;
		}	
		return $records;		
	}


	public static function getEmployeeLeaveAvailableForIncrease() {
		$time = strtotime("-1 year", time());
  		$for_increase_year = date("Y", $time);		
		$sql = "
			SELECT id, employee_id, leave_id, no_of_days_alloted, no_of_days_available, no_of_days_used, covered_year
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ."
			WHERE covered_year <=  ". $for_increase_year . "
		";
		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function getAllUnusedLeaveCreditLastYear() {
		$year = date("Y") - 1;		
		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted, gla.no_of_days_available,
				   gla.id, gl.name, gl.is_paid
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.covered_year = ".Model::safeSql($year)."
		";
		
		$result = Model::runSql($sql,true);

		return $result;		
	}

	public static function getAllUnusedLeaveCreditByYear($year) {		
		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted, gla.no_of_days_available,
				   gla.id, gl.name, gl.is_paid
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.covered_year = ".Model::safeSql($year)."
		";		
		$result = Model::runSql($sql,true);

		return $result;		
	}

	public static function getAllUnusedLeaveCreditLessThanYear($year) {		
		$sql = "
			SELECT gla.employee_id, gla.leave_id, gla.no_of_days_alloted, gla.no_of_days_available,
				   gla.id, gl.name, gl.is_paid
			FROM " . G_EMPLOYEE_LEAVE_AVAILABLE ." gla 
			LEFT JOIN " . G_LEAVE . " gl 
				ON gla.leave_id = gl.id 
			WHERE gla.covered_year <= ".Model::safeSql($year)."
		";
		
		$result = Model::runSql($sql,true);

		return $result;		
	}

}
?>