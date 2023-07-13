<?php
class G_Employee_Leave_Request_Helper {
    public static function addNewRequest($employee_id, $leave_id, $applied_date, $start_date, $end_date, $comment = '', $is_half_day1 = '', $is_half_day2 = '', $is_paid = '') {
        $lr = G_Employee_Leave_Request_Finder::findByEmployeeIdAndStartDateAndEndDate($employee_id, $start_date, $end_date);
        if (!$lr) {
            $applied_time = Tools::getGmtDate('H:i:s');
            $leave = new G_Employee_Leave_Request;
            $leave->setEmployeeId($employee_id);
            $leave->setLeaveId($leave_id);
            $leave->setDateApplied($applied_date);
            $leave->setTimeApplied($applied_time);
            $leave->setDateStart($start_date);
            $leave->setDateEnd($end_date);
            $leave->setLeaveComments($comment);
            $leave->setIsApproved(G_Employee_Leave_Request::PENDING);
            $leave->setApplyHalfDayDateStart($is_half_day1);
            $leave->setApplyHalfDayDateEnd($is_half_day2);

            // TODO PUT IN HELPER FUNCTION
            $year = Tools::getGmtDate('Y', strtotime($start_date));
            $la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($employee_id, $leave_id, $year);
            $leave->setIsPaid(G_Employee_Leave_Request::IS_PAID_NO);
            if ($la) {
                $available_days = $la->getNoOfDaysAvailable();
                if ($available_days > 0) {
                    //$leave->setIsPaid(G_Employee_Leave_Request::IS_PAID_YES);
                }
            }

            if ($is_paid == G_Employee_Leave_Request::IS_PAID_YES) {
                $leave->setIsPaid(G_Employee_Leave_Request::IS_PAID_YES);
            }
            return $leave->save();
        } else {
            return false;
        }
    }

    /*
     * @param object $lr Instance of G_Employee_Leave_Request
     * @return float Number of days
     */
    public static function countLeaveDays($lr) {
        $start_date = $lr->getDateStart();
        $end_date = $lr->getDateEnd();
        $d = new DateTime($start_date);
        $d2 = new DateTime($end_date);
        $x = $d->diff($d2);
        $days = $x->days + 1;

        $is_half_day1 = $lr->getApplyHalfDayDateStart();
        $is_half_day2 = $lr->getApplyHalfDayDateEnd();

        $minus_days = 0;
        if ($is_half_day1 == G_Employee_Leave_Request::YES) {
            $minus_days = 0.5;
        }
        if ($is_half_day2 == G_Employee_Leave_Request::YES) {
            $minus_days += 0.5;
        }

        return $days - $minus_days;
    }
		
	public static function isIdExist(G_Employee_Leave_Request $e) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_LEAVE_REQUEST ."
			WHERE id = ". Model::safeSql($e->getId()) ."
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
			FROM " . G_EMPLOYEE_LEAVE_REQUEST ." 
				LEFT JOIN " . EMPLOYEE . " ON " . G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = "  . EMPLOYEE . ".id 
				LEFT JOIN " . G_LEAVE . " ON " . G_LEAVE . ".id = " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id
			WHERE " . G_EMPLOYEE_LEAVE_REQUEST . ".id = ". Model::safeSql($id) ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlEmployeeLeaveRequestByDate($employee_id = 0, $date = '', $fields = array()) {
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{	
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_LEAVE_REQUEST ."  l				
			WHERE l.employee_id =" . Model::safeSql($employee_id) . "
				AND l.date_start <=" . Model::safeSql($date) . " AND l.date_end >=" . Model::safeSql($date) . "
				AND l.is_archive = 'No' 
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function findByDate($date,$order_by,$limit)
	{
		$sql = "
			SELECT a.*
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." a
			WHERE
				a.date_start >= ". Model::safeSql($date) ."
				AND a.is_approved=1
				
			".$order_by."
			".$limit."
		";
		return Model::runSql($sql,true);
	}
	
	public static function findAllByPeriodAndDepartmentId($from,$to,$department_id,$order_by,$limit) {
		$query = ($department_id != '')? 'AND d.company_structure_id='. $department_id : '' ; 
		$sql   = "
				SELECT 
				d.name as department,
				e.employee_code,
				CONCAT(e.lastname,', ',e.firstname,' ',e.middlename,' ', e.extension_name) AS `employee_name`,
				e.hired_date,
				j.name as position,
				r.date_applied,
				r.date_start,
				r.date_end,
				r.leave_comments,
				r.is_approved,
				r.is_paid,
				l.name as type,
				r.is_paid
				
				FROM " . G_EMPLOYEE_LEAVE_REQUEST . " r
				LEFT JOIN " . EMPLOYEE . " e ON e.id=r.employee_id
				LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " j ON j.employee_id=e.id
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " d ON d.employee_id=e.id
				LEFT JOIN " . G_LEAVE . " l ON l.id=r.leave_id	
				WHERE r.date_start BETWEEN ".Model::safeSql($from)." AND ".Model::safeSql($to)."

		" . $query . "
		" . $order_by . "
		" . $limit . "
		";		
		return Model::runSql($sql,true);
	}
	
	public static function findBySearch($csid,$search,$order_by,$limit) {
		$sql = "
			SELECT 
			a.id,
			a.company_structure_id,
			a.employee_id,
			a.date_applied, 
			a.date_start,
			a.date_end,
			a.leave_comments,
			a.is_paid,
			a.is_approved,
			CONCAT(e.lastname, ' ', e.firstname) AS employee_name,e.hash, l.name as leave_type
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." a
			LEFT JOIN g_employee e ON e.id=a.employee_id 
			LEFT JOIN g_leave as l ON l.id=a.leave_id
			WHERE
				a.company_structure_id = ". Model::safeSql($csid) ."
			".$search."
			".$order_by."
			".$limit."
		";

		return Model::runSql($sql,true);
	}
	
	public static function findByCompanyStructureId($csid,$order_by,$limit)
	{
		$sql = "
			SELECT 
			a.id,
			a.company_structure_id,
			a.employee_id,
			a.date_applied, 
			a.date_start,
			a.date_end,
			a.leave_comments,
			a.is_paid,
			a.is_approved,
			CONCAT(e.lastname, ' ', e.firstname) AS employee_name,e.hash, l.name as leave_type
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." a, g_employee e, g_leave as l
			WHERE
				a.company_structure_id = ". Model::safeSql($csid) ."
				AND e.id=a.employee_id AND a.leave_id=l.id
			".$order_by."
			".$limit."
		";
		//echo $sql;
		return Model::runSql($sql,true);
	}
	
	public static function getDynamicQueries($search) {
		$result = explode(':',$search);
			
				$ctr=0;
				$query='';
				foreach($result as $key=>$value) {
					if(substr_count($value,',')==1) { //with comma
						$r = explode(',',$value);
						foreach($r as $key=>$vl){
							if($ctr==0) {/* add category */
								$ctr=1;
								$str = ($vl=='') ? "" : $vl ;	
								
								$field = Tools::searchInArray($field_list,strtolower($vl));
								$category = strtolower($field[0]);	
								
								$category = strtolower($str);
							}else { /* add value*/
								$ctr=0;$str = ($vl=='') ? "" : $vl ;
								$query[$category].= strtolower($str);
							}	
						}
					}else { // no comma

						if($ctr==0) {/* add category*/
							$ctr=1;
							$field = Tools::searchInArray($field_list,strtolower($value));
							$y=0;
							foreach($field as $key=>$f) {
								if($y==0) {
									$field = $f;	
								}
								$y++;	
							}
							$category = strtolower($f);		
						}else { /* add value*/
							$ctr=0;	
							$query[$category].= strtolower($value);
						}
					}
				}
		
			$field_list = array('leave type'=>'l.name',
							'employee_code'=>'e.employee_code',
							'lastname'=>'e.lastname',
							'firstname'=>'e.firstname',
							'date filed'=>'a.date_applied',
							'status'=>'a.is_approved',
							'date started'=>'a.date_start');
				$x=0;
				foreach($query as $key=>$value) {
					//echo $value . " " . $field_list[$key];
					if($value!='') {
						if($field_list[$key]!="") {
							$q[$field_list[$key]].=$value;
							
							$where = ($x==0) ? 'AND ( ' : 'AND ' ;
							if($field_list[$key]=='e.employee_code') {
								$search.= $where. " $field_list[$key]='". $value ."' ";
							}else {
								$search.= $where. " $field_list[$key] LIKE '%". $value ."%' ";
							}
						}	
					}
					$x++;
				}
				$search.=")";
				
		return $search;
	}
	
	
	public static function countTotalRecords($csid)
	{
		$sql = "
			SELECT count(*) as total_employee
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." a, g_employee e
			WHERE
				a.company_structure_id >= ". Model::safeSql($csid) ."
				AND e.id=a.employee_id
			".$order_by."
			".$limit."
		";
		return Model::runSql($sql,true);
	}

	public static function sqlAcquiredLeaveOnCutOff($employee_id,$from, $to)
	{
		$sql = "
			SELECT  SUM(DATEDIFF(a.date_end , a.date_start)) AS leave_days_acquired
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." a
			WHERE a.employee_id = ". Model::safeSql($employee_id) ."
			AND a.date_start BETWEEN ".Model::safeSql($from)." AND ".Model::safeSql($to)."
			AND a.date_end BETWEEN ".Model::safeSql($from)." AND ".Model::safeSql($to)."
			AND a.is_approved = '". G_Employee_Leave_Request::APPROVED ."'
		
		";
		return Model::runSql($sql,true);
	}
}
?>