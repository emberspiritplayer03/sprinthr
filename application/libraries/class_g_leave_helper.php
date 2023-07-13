<?php
class G_Leave_Helper {

    public static function getActionLinks($leave) {
        if ($leave->getIsDefault() == G_Leave::IS_DEFAULT_NO) {
            $string = '<div class="i_container"><ul class="dt_icons">';
            $string .= '<li><a onclick="javascript:editLeaveType(\''. Utilities::encrypt($leave->getId()) .'\');" href="javascript:void(0);" class="ui-icon ui-icon-pencil g_icon" id="edit" original-title="Edit"></a></li>';
            $string .= '<li><a onclick="javascript:archiveLeaveType(\''. Utilities::encrypt($leave->getId()) .'\')" href="javascript:void(0);" class="ui-icon ui-icon-trash g_icon" id="delete" original-title="Send to Archive"></a></li>';
            $string .= '</ul></div>';
            return $string;
        } else {
            $string = '<div class="i_container"><ul class="dt_icons">';
            $string .= '<li><a onclick="javascript:editLeaveType(\''. Utilities::encrypt($leave->getId()) .'\');" href="javascript:void(0);" class="ui-icon ui-icon-pencil g_icon" id="edit" original-title="Edit"></a></li>';
            $string .= '</ul></div>';
            return $string;
        }
    }
    /**
     * Adds default leave credits to employee
     *
     * @param object $e Instance of G_Employee
     * @param int $year Year when this new credit get affected
     * @return bool
     */
    public static function addDefaultLeaveCreditsToEmployee($e, $year) {
        $leaves = G_Leave_Finder::findWithCredits();

        foreach ($leaves as $leave) {
            $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
            if (!$l) {
                $l = new G_Employee_Leave_Available;
                $l->setEmployeeId($e->getId());
                $l->setLeaveId($leave->getId());
                $l->setNoOfDaysAlloted($leave->getDefaultCredit());
                $l->setNoOfDaysAvailable($leave->getDefaultCredit());
                $l->setCoveredYear($year);
                $l->save();
            }
        }
    }

    /**
     * Adds leave credit or days
     *
     * Wrapper:
     * $e = G_Employee_Finder::findById(3519);
     * $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
     * $e->addLeaveCredit($l, 2);
     *
     * @param object $e Instance of G_Employee
     * @param object $leave Instance of G_Leave
     * @param float $number_of_days Number of days to add in leave credit
     * @param int $year Year to add
     * @return boolean Gets 'true' if no error found
     */
    public static function addLeaveCreditsToEmployee($e, $leave, $number_of_days, $year) {
        if ($year == '') {
            $year = Tools::getGmtDate('Y');
        }
        $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
        if (!$l) {
            $l = new G_Employee_Leave_Available;
        }
        $l->setEmployeeId($e->getId());
        $l->setLeaveId($leave->getId());
        $alloted = $l->getNoOfDaysAlloted();
        $l->setNoOfDaysAlloted($alloted + $number_of_days);
        $available = $l->getNoOfDaysAvailable();
        $l->setNoOfDaysAvailable($available + $number_of_days);
        $l->setCoveredYear($year);

        return $l->save();
    }

    /**
     * Adds leave credit or days to a group. All employees under this group will be added leave credits
     *
     * Wrapper:
     * $g = G_Group_Finder::findByName('Logistics');
     * $l = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
     * $g->addLeaveCredit($l, 2);
     *
     * @param object $g Instance of G_Group
     * @param object $leave Instance of G_Leave
     * @param float $number_of_days Number of days to add in leave credit
     * @param int $year Year to add
     * @return boolean Gets 'true' if no error found
     */
    public static function addLeaveCreditsToGroup($g, $leave, $number_of_days, $year) {
        if ($year == '') {
            $year = Tools::getGmtDate('Y');
        }
        $employees = $g->getEmployees();
        foreach ($employees as $e) {
            $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
            if (!$l) {
                $l = new G_Employee_Leave_Available;
            }
            $l->setEmployeeId($e->getId());
            $l->setLeaveId($leave->getId());
            $alloted = $l->getNoOfDaysAlloted();
            $l->setNoOfDaysAlloted($alloted + $number_of_days);
            $available = $l->getNoOfDaysAvailable();
            $l->setNoOfDaysAvailable($available + $number_of_days);
            $l->setCoveredYear($year);
            $leaves[] = $l;
        }
        return G_Employee_Leave_Available_Manager::saveMultiple($leaves);
    }

    /**
     * Adds leave credit or days to employees.
     *
     * Usage:
     * $leave = G_Leave_Finder::findByName(G_Leave::NAME_VACATION);
     * G_Leave_Helper::addLeaveCreditsToEmployees($es, $leave, $number_of_days, $year)
     *
     * @param array $employees Array instance of G_Employee
     * @param object $leave Instance of G_Leave
     * @param float $number_of_days Number of days to add in leave credit
     * @param int $year Year to add
     * @return boolean Gets 'true' if no error found
     */
    public static function addLeaveCreditsToEmployees($employees, $leave, $number_of_days, $year) {
        if ($year == '') {
            $year = Tools::getGmtDate('Y');
        }
        foreach ($employees as $e) {
            $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
            if (!$l) {
                $l = new G_Employee_Leave_Available;
            }
            $l->setEmployeeId($e->getId());
            $l->setLeaveId($leave->getId());
            $alloted = $l->getNoOfDaysAlloted();
            $l->setNoOfDaysAlloted($alloted + $number_of_days);
            $available = $l->getNoOfDaysAvailable();
            $l->setNoOfDaysAvailable($available + $number_of_days);
            $l->setCoveredYear($year);
            $leaves[] = $l;
        }
        return G_Employee_Leave_Available_Manager::saveMultiple($leaves);
    }
		
	public static function isIdExist(G_Leave $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LEAVE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

    public static function isLeaveIdExists( $id = 0 ) {
        $sql = "
            SELECT COUNT(*) as total
            FROM " . G_LEAVE ."
            WHERE id = ". Model::safeSql($id) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        if( !empty($row) && $row['total'] > 0 ){
            return true;
        }else{
            return false;
        }        
    }

    public static function isNameExist($name) {
        $sql = "
            SELECT COUNT(*) as total
            FROM " . G_LEAVE ."
            WHERE name = ". Model::safeSql($name) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }    
	
	public static function countTotalRecordsIsNotArchive() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LEAVE ."
			WHERE gl_is_archive = ". Model::safeSql(G_Leave::NO) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsIsArchive() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_LEAVE ."
			WHERE gl_is_archive = ". Model::safeSql(G_Leave::YES) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public function checkIsPaid($leave_request) {
		$la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($leave_request->getEmployeeId(),$leave_request->getLeaveId());
		if($la) {
			$from 	= $leave_request->getDateStart();
			$to		= $leave_request->getDateEnd();
			$diff	= Tools::getDayDifference($from,$to);
			if(($la->getNoOfDaysAvailable() - $diff) >= 0){
				$leave_request->setIsPaid($_POST['is_paid']);
				$available_leave = $la->getNoOfDaysAvailable();
				$available_leave-=$diff;
				$la->getNoOfDaysAvailable($available_leave);
			}else{
				$leave_request->setIsPaid(G_Employee_Leave_Request::NO);
			}	
		} else {
			$leave_request->setIsPaid(G_Employee_Leave_Request::NO);
		}
		$leave_request->save();
	}

}
?>