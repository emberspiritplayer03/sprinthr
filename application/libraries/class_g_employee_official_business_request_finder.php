<?php
class G_Employee_Official_Business_Request_Finder {
    public static function findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date) {
        $sql = "
			SELECT *
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
            AND e.date_start = ". Model::safeSql($start_date) ."
            AND e.date_end = ". Model::safeSql($end_date) ."
			LIMIT 1
		";
        return self::getRecord($sql);
    }

    public static function findByEmployeeIdDate($employee_id, $date) {
        $sql = "
			SELECT *
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE employee_id =". Model::safeSql($employee_id) ."
			AND is_archive =". Model::safeSql(G_Employee::NO) ."
			AND is_approved =". Model::safeSql("Approved") ."
			AND ". Model::safeSql($date) ."
			BETWEEN date_start AND date_end
			LIMIT 1
		";		

        return self::getRecord($sql);
    }

    //for new ob request
     public static function findByEmployeeIdDate2($employee_id, $date) {
        $sql = "
			SELECT *
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
			WHERE employee_id =". Model::safeSql($employee_id) ."
			AND is_archive =". Model::safeSql(G_Employee::NO) ."
			AND is_approved =". Model::safeSql("Pending") ."
			AND ". Model::safeSql($date) ."
			BETWEEN date_start AND date_end
			LIMIT 1
		";		

        return self::getRecord($sql);
    }


	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByEmployeeId($employee_id, $order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllApproved($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Official_Business_Request_Finder::APPROVED) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPendingsByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Official_Business_Request_Finder::PENDING) . "
				AND company_structure_id =" . Model::safeSql($company_structure_id) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPendings($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Official_Business_Request_Finder::PENDING) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllDisApproved($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 
			WHERE is_approved =" . Model::safeSql(G_Employee_Official_Business_Request_Finder::DISAPPROVED) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findTopRecentRequestByEmployeeId($employee_id,$sort="",$limit="") {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST." e
			WHERE 
			e.employee_id = ". Model::safeSql($employee_id) ."
			$sort
			$limit
		";
		return self::getRecords($sql);
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
	
	public static function findAllByPeriodNoErrors($start_date, $end_date, $employee_ids = array(), $order_by = '', $limit = '', $filter = '', $log_ids = array(), $machine_no = "") {
		$implode_employee_ids = '';
		$implode_main_log_ids = '';
		$implode_break_log_ids = '';

		if (count($employee_ids) > 0) {
			$implode_employee_ids = ' AND e.id IN (' . implode(',', $employee_ids) . ')';
		}

		if ($filter > 0) {
			$implode_filter_department = ' AND e.department_company_structure_id IN (' . $filter . ')';
		}

		if (count($log_ids) > 0) {
			if (count($log_ids['main']) > 0) {
				$implode_main_log_ids = ' AND ob.id IN (' . implode(',', $log_ids['main']) . ')';
			}
			else {
				$implode_main_log_ids = ' AND ob.id IN (0)';
			}

			if (count($log_ids['break']) > 0) {
				$implode_break_log_ids = ' AND abl.id IN (' . implode(',', $log_ids['break']) . ')';
			}
			else {
				$implode_break_log_ids = ' AND abl.id IN (0)';
			}
		}

		if($machine_no){
			$device_no_filter = "AND SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql($machine_no) . " ";
			if($machine_no == "--no_device--"){
				$device_no_filter = "AND (SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("--no device--") . " 
				OR SUBSTRING_INDEX(`remarks`, ':', -1 ) = ".Model::safeSql("") . ") ";
			}
		}

		$sql = "
			SELECT 
				ob.id, ob.employee_id as employee_id, CONCAT(e.lastname, ' ' , e.firstname, ' ', e.middlename) as employee_name, ob.date_applied, ob.date_start, ob.date_end, ob.is_approved, ob.id
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." ob LEFT JOIN " . EMPLOYEE . " e
				ON ob.employee_id = e.id  
			WHERE 
				ob.date_start <= ". Model::safeSql($start_date) ." AND
				ob.date_end >= ". Model::safeSql($start_date) ."
				". $implode_filter_department ."	
				". $implode_employee_ids ."	
				". $implode_main_log_ids ."	
				". $device_no_filter ."	
			" . $order_by . " 
			" . $limit . "		
		";
		return self::getRecords($sql);
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
		$gobr = new G_Employee_Official_Business_Request();
		$gobr->setId($row['id']);
		$gobr->setCompanyStructureId($row['company_structure_id']);
		$gobr->setEmployeeId($row['employee_id']);
		$gobr->setDateApplied($row['date_applied']);
		$gobr->setDateStart($row['date_start']);	
		$gobr->setDateEnd($row['date_end']);	


		//new columns
		$gobr->setWholeDay($row['is_whole_day']);
		$gobr->setTimeStart($row['time_start']);
		$gobr->setTimeEnd($row['time_end']);

		$gobr->setComments($row['comments']);				
		$gobr->setIsApproved($row['is_approved']);				
		$gobr->setCreatedBy($row['created_by']);				
		$gobr->setIsArchive($row['is_archive']);				
		return $gobr;
	}
}
?>