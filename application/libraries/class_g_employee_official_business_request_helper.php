<?php
class G_Employee_Official_Business_Request_Helper {
    /*
     * @param object $ob_request Instance of G_Employee_Official_Business_Request
     */
    public static function disapprove(G_Employee_Official_Business_Request $ob_request) {
        $is_approved = G_Employee_Official_Business_Request_Manager::disapprove($ob_request);
        $employee_id = $ob_request->getEmployeeId();

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
        	/*$request = new G_Request();
			$request->setRequestId($ob_request->getId());
			$request->setRequestType(G_Request::PREFIX_OFFICIAL_BUSSINESS);
			$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); */
			
            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $ob_request->getDateStart(), $ob_request->getDateEnd());
        }

        return $is_approved;
    }

    public static function hr_disapprove(G_Employee_Official_Business_Request $ob_request) {
        $is_approved = G_Employee_Official_Business_Request_Manager::disapprove($ob_request);
        $employee_id = $ob_request->getEmployeeId();

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
        	$request = new G_Request();
			$request->setRequestId($ob_request->getId());
			$request->setRequestType(G_Request::PREFIX_OFFICIAL_BUSSINESS);
			$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); 
			
            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $ob_request->getDateStart(), $ob_request->getDateEnd());
        }

        return $is_approved;
    }

    /*
     * @param object $ob_request Instance of G_Employee_Official_Business_Request
     */
    public static function approve(G_Employee_Official_Business_Request $ob_request) {    	
        $is_approved = G_Employee_Official_Business_Request_Manager::approve($ob_request);
        $employee_id = $ob_request->getEmployeeId();

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
        	$request = new G_Request();
			$request->setRequestId($ob_request->getId());
			$request->setRequestType(G_Request::PREFIX_OFFICIAL_BUSSINESS);
			$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 

            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $ob_request->getDateStart(), $ob_request->getDateEnd());
        }

        return $is_approved;
    }

    public static function create($employee_id, $applied_date, $start_date, $end_date, $is_approved, $comment = '', $is_whole_day, $time_start, $time_end) {
        $ob = G_Employee_Official_Business_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        if (!$ob) {
            $ob = new G_Employee_Official_Business_Request;
        }
        $ob->setEmployeeId($employee_id);
        $ob->setDateApplied($applied_date);
        $ob->setDateStart($start_date);
        $ob->setDateEnd($end_date);

        $ob->setWholeDay($is_whole_day);
        $ob->setTimeStart($time_start);
        $ob->setTimeEnd($time_end);

        $ob->setComments($comment);
        $ob->setIsApproved($is_approved);

        return $ob;
    }

    /*
    public static function addNewRequest($employee_id, $applied_date, $start_date, $end_date, $comment = '') {
        if ($end_date == '') {
            $end_date = $start_date;
        }

        $ob = G_Employee_Official_Business_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        if (!$ob) {
            //$applied_time = Tools::getGmtDate('H:i:s');
            $applied_time = date("H:i:s");
            $ob = new G_Employee_Official_Business_Request;
            $ob->setEmployeeId($employee_id);
            $ob->setDateApplied($applied_date);
            $ob->setDateStart($start_date);
            $ob->setDateEnd($end_date);
            $ob->setComments($comment);
            $ob->setIsApproved(G_Employee_Official_Business_Request::STATUS_PENDING);

            return $ob->save();
        }
    }
    */

    public static function addNewRequest($employee_id, $applied_date, $start_date, $end_date, $comment = '',$time_start, $time_end,$is_whole_day) {
        if ($end_date == '') {
            $end_date = $start_date;
        }

        if(!empty($time_start)){
        	$new_time_start = date("H:i:s", strtotime($time_start));
        }
        if(!empty($time_end)){
        	$new_time_end = date("H:i:s", strtotime($time_end));
        }

        $ob = G_Employee_Official_Business_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        if (!$ob) {
        	
           // $applied_time = Tools::getGmtDate('H:i:s');
        	$applied_time = date("H:i:s");
            $ob = new G_Employee_Official_Business_Request;
            $ob->setEmployeeId($employee_id);
            $ob->setDateApplied($applied_date);
            $ob->setDateStart($start_date);
            $ob->setDateEnd($end_date);
            //new columns
            $ob->setWholeDay($is_whole_day);
            $ob->setTimeStart($new_time_start);
            $ob->setTimeEnd($new_time_end);
            $ob->setComments($comment);
            $ob->setIsApproved(G_Employee_Official_Business_Request::STATUS_PENDING);

            return $ob->save();
        }
    }

	public static function isIdExist(G_Employee_Official_Business_Request $gobr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE id = ". Model::safeSql($gobr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlRequestDetailsById($id = 0, $fields = array()) {
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{	
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
				LEFT JOIN " . EMPLOYEE . " ON " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".employee_id = "  . EMPLOYEE . ".id 
			WHERE " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . ".id = ". Model::safeSql($id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function getAllByPeriodAndCompanyStructureId($from,$to,$company_structure_id) {
		$sql = "
			SELECT ob.id, CONCAT(e.firstname ,', ',e.lastname) as emp_name,CONCAT(jbh.name) as job_name, ob.date_start,ob.date_end,ob.comments,ob.is_approved
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." ob 
				LEFT JOIN " . EMPLOYEE . " e ON ob.employee_id = e.id
				LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id
			WHERE ob.is_archive = ". Model::safeSql(G_Employee_Official_Business_Request::NO) ."
				AND ob.company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND ob.date_start BETWEEN '" . $from . "' AND '" . $to . "' 
		";		
		return Model::runSql($sql,true);
	}
	
	public static function countTotalRecordsByIsArchive($is_archive) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE is_archive = ". Model::safeSql($is_archive) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByPeriodCompanyStructureIdPendingAndIsNotArchive($from,$to,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE is_archive = " . Model::safeSql(G_Employee_Official_Business_Request::NO) ."
				AND is_approved = " . Model::safeSql(G_Employee_Official_Business_Request::NO) . "
				AND company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND date_start BETWEEN '" . $from . "' AND '" . $to . "' 
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByPeriodCompanyStructureIdApprovedAndIsNotArchive($from,$to,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE is_archive = " . Model::safeSql(G_Employee_Official_Business_Request::NO) ."
				AND is_approved = " . Model::safeSql(G_Employee_Official_Business_Request::YES) . "
				AND company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND date_start BETWEEN '" . $from . "' AND '" . $to . "' 
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureIdAndIsApproved($company_structure_id,$is_approved) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE is_approved = ". Model::safeSql($is_approved) ."
				AND company_structure_id = " . Model::safeSql($company_structure_id) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByIsApproved($is_approved) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE is_approved = ". Model::safeSql($is_approved) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllByUnRequest() {
		$sql = "
			SELECT id,employee_id 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE id NOT IN ( SELECT request_id FROM ". REQUESTS ." WHERE request_type = 'ob') AND is_archive = 'No' AND  is_approved = 'Pending'
		";		
		return Model::runSql($sql,true);
	}
}
?>