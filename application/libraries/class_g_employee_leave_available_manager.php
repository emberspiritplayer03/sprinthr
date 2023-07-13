<?php
class G_Employee_Leave_Available_Manager {
    /*
     * @param array $leaves Array instance of G_Employee_Leave_Available
     */
    public static function saveMultiple($leaves) {
        $has_record = false;
        foreach ($leaves as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getEmployeeId()) .",
                ". Model::safeSql($o->getLeaveId()) .",
                ". Model::safeSql($o->getNoOfDaysAlloted()) .",
                ". Model::safeSql($o->getNoOfDaysAvailable()) .",
                ". Model::safeSql($o->getNoOfDaysUsed()) .",
                ". Model::safeSql($o->getCoveredYear()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_LEAVE_AVAILABLE ." (id, employee_id, leave_id, no_of_days_alloted, no_of_days_available, no_of_days_used, covered_year)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    employee_id = VALUES(employee_id),
                    leave_id = VALUES(leave_id),
                    no_of_days_alloted = VALUES(no_of_days_alloted),
                    no_of_days_available = VALUES(no_of_days_available),
                    no_of_days_used = VALUES(no_of_days_used),
                    covered_year = VALUES(covered_year)
            ";

            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            $insert_id = Sql::getInsertId();
            if ($insert_id == 0) {
                return true;
            } else {
                return $insert_id;
            }
        }
    }

    public static function deductLeaveCredits($id = 0, $num_to_deduct = 0){
        $sql = "
            UPDATE " .G_EMPLOYEE_LEAVE_AVAILABLE . "
            SET no_of_days_available = no_of_days_available - " . Model::safeSql($num_to_deduct) . "
            WHERE id =" . Model::safeSql($id) . "
        ";        
        Model::runSql($sql);
        return true;
    }

    public static function resetLeaveCreditsByLeaveId($leave_id = 0, $credits = 0){
        $sql = "
            UPDATE " .G_EMPLOYEE_LEAVE_AVAILABLE . "
            SET no_of_days_available =" . Model::safeSql($credits) . ",
                no_of_day_alloted =" . Model::safeSql($credits) . ",
                no_of_days_used =" . Model::safeSql($credits) . "
            WHERE leave_id =" . Model::safeSql($leave_id) . "
        ";        
        Model::runSql($sql);
        return true;
    }

    public static function updateEmployeeLeaveCredits(G_Employee_Leave_Available $l){
        $sql = "
            UPDATE " .G_EMPLOYEE_LEAVE_AVAILABLE . "
            SET no_of_days_alloted = no_of_days_alloted + " . Model::safeSql($l->getNoOfDaysAlloted()) . ",
                no_of_days_available = no_of_days_available + " . Model::safeSql($l->getNoOfDaysAvailable()) . "                
            WHERE employee_id =" . Model::safeSql($l->getEmployeeId()) . "
                AND leave_id =" . Model::safeSql($l->getLeaveId()) . "
                AND covered_year =" . Model::safeSql($l->getCoveredYear()) . "
        ";                
        Model::runSql($sql);
        return true;
    }

    public static function addLeaveCredits($id = 0, $num_to_add = 0){
        $sql = "
            UPDATE " .G_EMPLOYEE_LEAVE_AVAILABLE . "
            SET no_of_days_available = no_of_days_available + " . Model::safeSql($num_to_add) . "
            WHERE id =" . Model::safeSql($id) . "
        ";      
                 
        Model::runSql($sql);
        return true;
    }

	public static function save(G_Employee_Leave_Available $e) {
		if ($e->getId() > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LEAVE_AVAILABLE . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LEAVE_AVAILABLE . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			 = " . Model::safeSql($e->getEmployeeId()) .",
			leave_id		   	 = " . Model::safeSql($e->getLeaveId()) .",
			no_of_days_alloted	 = " . Model::safeSql($e->getNoOfDaysAlloted()) .",
			no_of_days_available = " . Model::safeSql($e->getNoOfDaysAvailable()) .",
			no_of_days_used      = " . Model::safeSql($e->getNoOfDaysUsed()) .",
            covered_year         = " . Model::safeSql($e->getCoveredYear()) ."
			"
	
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Leave_Available $e){
		if(G_Employee_Leave_Available_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LEAVE_AVAILABLE ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

    public static function deleteAllLeaveCredit(){
         $sql = "
            DELETE FROM ". G_EMPLOYEE_LEAVE_AVAILABLE . "
        ";             
        Model::runSql($sql);
    }

    /*
     * @param object $la Instance of G_Employee_Leave_Available
     * @return boolean If successfully subtracted
     */
    public static function subtractLeaveCredit($la, $number_of_days) {
        $alloted = $la->getNoOfDaysAlloted();
        //$available = $la->getNoOfDaysAvailable();
        $days_used = $la->getNoOfDaysUsed();

        $new_days_used = $days_used + $number_of_days;
        $new_available = $alloted - $new_days_used;

        $la->setNoOfDaysAvailable($new_available);
        $la->setNoOfDaysUsed($new_days_used);
        $la->save();
    }

    /*
     * @param object $la Instance of G_Employee_Leave_Available
     * @return boolean If successfully added
     */
    public static function addLeaveCredit($la, $number_of_days) {
        $alloted = $la->getNoOfDaysAlloted();
        //$available = $la->getNoOfDaysAvailable();
        $days_used = $la->getNoOfDaysUsed();

        $new_days_used = $days_used - $number_of_days;
        $new_available = $alloted + $new_days_used;

        $la->setNoOfDaysAvailable($new_available);
        $la->setNoOfDaysUsed($new_days_used);
        $la->save();
    }
	
	public static function minusAvailableLeave(G_Employee_Leave_Available $available, $number_of_days) {
		if ($available) {
			$alloted = $available->getNoOfDaysAlloted()+$number_of_days;
			$balance = $available->getNoOfDaysAvailable();
			
			$diff = $balance - $number_of_days;				
			$remaining = ($diff > 0) ? $diff : 0 ;
			
			if ($diff < 0) {
				$alloted = $available->getNoOfDaysAlloted()+$balance;
			}
			$available->setNoOfDaysAlloted($alloted);	
			$available->setNoOfDaysAvailable($remaining);
			$available->save();
		}
	}
}
?>