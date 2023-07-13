<?php
class G_Converted_Leave_Helper {
	public static function isIdExist(G_Convert_Leave $gl) {
		$sql = "
			SELECT COUNT(*) AS total
			FROM " . CONVERTED_LEAVES ."
			WHERE id = ". Model::safeSql($gl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	/**
	* Get all data by year
	*
	* @param year int
	* @return array
	*/
	public static function allConvertedLeavesByYear($year = 0) {
		$sql = "
			SELECT l.name AS leave_type, e.firstname, e.lastname, e.employee_code, cl.amount, cl.total_leave_converted
			FROM " . CONVERTED_LEAVES ." cl
				LEFT JOIN " . EMPLOYEE . " e ON cl.employee_id = e.id 
				LEFT JOIN " . G_LEAVE . " l ON cl.leave_id = l.id
			WHERE cl.year =" . Model::safeSql($year) . "			
			AND e.employee_status_id = 1
			ORDER BY cl.id DESC 
		";
		//echo $sql;
		$result = Model::runSql($sql,true);		
		return $result;
	}

	/**
	* Get all data by year
	* @param string year
	* @param array fields - optional
	* @return array
	*/
	public static function getDataByYear($query = array(), $add_query = ''){
		$year = $query['year'];

    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}

		if( !empty($query['employee_ids']) && isset($query['employee_ids']) ){
			$employee_ids = implode(",", $query['employee_ids']);			
			$search .= " AND e.id IN ({$employee_ids})";
		}
        
        $sql = "
            SELECT e.id as employee_pkid, e.company_structure_id, e.employee_code, e.lastname, e.firstname, e.middlename, e.hired_date, es.status AS employee_status,
            	cs.title AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				l.name AS leave_name, cl.total_leave_converted, cl.amount
			FROM ". CONVERTED_LEAVES ." cl           			
				LEFT JOIN " . EMPLOYMENT_STATUS . " es ON cl.employee_id = es.id	
                LEFT JOIN " . G_EMPLOYEE  . " e ON cl.employee_id = e.id
                LEFT JOIN " . G_LEAVE . " l ON cl.leave_id = l.id 
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON cl.employee_id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON cl.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
			WHERE 
				cl.year =" . Model::safeSql($year) . "
					AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				{$sql_add_query}				
                " . $search . "            
        ";    
		$result = Model::runSql($sql,true);		
		return $result;		
	}
}
?>