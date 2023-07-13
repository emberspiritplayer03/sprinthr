<?php
class G_Yearly_Bonus_Release_Date_Helper {
	public static function isIdExist(G_Yearly_Bonus $gybrd) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . YEARLY_BONUS_RELEASE_DATES ."
			WHERE id = ". Model::safeSql($gybrd->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	/**
	* Check if cutoffstart and enddate exist
	*
	* @param object instance of G_Yearly_Bonus
	* @return mixed - int if exist / false if not
	*/
	public static function isCutOffStartAndEndDateExist(G_Yearly_Bonus $gybrd) {
		$sql = "
			SELECT id
			FROM " . YEARLY_BONUS_RELEASE_DATES ."
			WHERE cutoff_start_date =" . Model::safeSql($gybrd->getCutoffStartDate()) . "
				AND cutoff_end_date =" . Model::safeSql($gybrd->getCutoffEndDate()) . "
			ORDER BY id DESC 
			LIMIT 1

		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		if( !empty( $row ) ){
			return $row['id'];
		}else{
			return false;
		}		
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

                COALESCE(bsh.basic_salary,(
	                SELECT basic_salary FROM `g_employee_basic_salary_history`
	                WHERE employee_id = e.id 
	                    AND end_date = ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS salary_rate, 
                e.cost_center,e.hired_date,e.frequency_id,
				yb.cutoff_start_date, yb.cutoff_end_date, yb.amount AS yearly_bonus, yb.total_bonus_amount AS total_bonus_amount, yb.tax AS TAX,
				yb.deducted_amount, yb.percentage, yb.id as yb_id, yb.total_basic_pay,yb.payroll_start_date,yb.deduction_month_start,yb.deduction_month_end
			FROM ". G_EMPLOYEE ." e          			
				INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id	
                INNER JOIN " . YEARLY_BONUS_RELEASE_DATES . " yb ON e.id = yb.employee_id   
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id 
                LEFT JOIN " . G_EMPLOYEE_BASIC_SALARY_HISTORY . " bsh ON esh.employee_id = bsh.employee_id 
                LEFT JOIN " . COMPANY_STRUCTURE . " cs ON e.section_id = cs.id              
			WHERE 
				yb.year_released =" . Model::safeSql($year) . "
					AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
				{$sql_add_query}				
                " . $search . "
                       
        ";    
        
		$result = Model::runSql($sql,true);		

		$data = array();
		foreach( $result as $r ){
			$a_index = date("F d, Y",strtotime($r['cutoff_start_date'])) . ' to ' . date("F d, Y",strtotime($r['cutoff_end_date']));		
        	$data[$a_index][$r['yb_id']] = $r; 
        }

		return $data;		
	}

	public static function getDataByYearAndEmployeeId( $employee_id = '', $year = '' ){        
        $sql = "
            SELECT *
			FROM ". YEARLY_BONUS_RELEASE_DATES ." yb          			
			WHERE 
				yb.year_released =" . Model::safeSql($year) . "
				AND yb.employee_id =" . $employee_id . "
        ";    

		$result = Model::runSql($sql,true);		
		return $result;		
	}	

	/**
	* Get yearly bonus release date data by year
	*
	* @param string year
	* @param array fields	
	*/
	public static function sqlAllYearlyBonusReleaseDateByYear( $year = '', $fields = array() ) {
		
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE year_released =" . Model::safeSql($year) . "
			ORDER BY id DESC 
			LIMIT 1
        ";
		$result = Model::runSql($sql,true);		
		return $result;
	}

	/**
	* Get yearly bonus release date data by year
	*
	* @param int employee_id
	* @param array range
	* @param array fields	
	* @return array 
	*/
	public static function getEmployeeDataByStartAndEndCutoff( $employee_id = 0, $range = array(), $fields = array() ) {
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE cutoff_start_date =" . Model::safeSql($range['from']) . "
				AND cutoff_end_date =" . Model::safeSql($range['to']) . "
				AND employee_id =" . Model::safeSql($employee_id) . "
			ORDER BY id DESC 			
        ";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function getPreviousEmployeeData( $employee_id = 0, $range = array(), $fields = array() ) {
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE cutoff_start_date !=" . Model::safeSql($range['from']) . "
				AND cutoff_end_date !=" . Model::safeSql($range['to']) . "
				AND employee_id =" . Model::safeSql($employee_id) . "
				AND YEAR(cutoff_start_date) = " . Model::safeSql(date("Y")) . "
				AND YEAR(cutoff_end_date) = " . Model::safeSql(date("Y")) . "
			ORDER BY id DESC 			
        ";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	/**
	* Get distinct yearly bonus release date data by year
	*
	* @param string year
	* @param array fields	
	*/
	public static function sqlGetDistinctDataByYear( $year = '', $fields = array() ) {
		
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE year_released =" . Model::safeSql($year) . "
			GROUP BY month_end, month_start
        ";
		$result = Model::runSql($sql,true);		
		return $result;
	}

	/**
	* Get employees yearly bonus data by year
	*
	* @param string year
	* @param array fields	
	*/

	public static function sqlGetEmployeesYearlyBonusByYearAndDateRange( $year = '', $employee_ids = array(), $range = array(), $fields = array() ) {
		
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql_add_query = "";
		if( !empty($employee_ids) ){
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND employee_id IN({$string_employee_ids})";
		}

		if( !empty($range) ){			
			$start_month = date("n",strtotime($range['from']));
			$end_month   = date("n",strtotime($range['to']));
			$sql_add_query .= " AND (month_start >= " . Model::safeSql($start_month) . " AND month_end <= " . Model::safeSql($end_month) . ")";
		}

        $sql = "
        	SELECT {$sql_fields}
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE year_released =" . Model::safeSql($year) . "
			{$sql_add_query}
			GROUP BY employee_id
        ";

		$result = Model::runSql($sql,true);		
		return $result;
	}

	/**
	* Get employee total yearly bonus by year
	*
	* @param int employee_id
	* @param int year	
	* @return array 
	*/
	public static function getEmployeeTotalBonusByYear( $employee_id = 0, $year = 0 ) {

        $sql = "
        	SELECT SUM(total_bonus_amount)AS total_bonus
			FROM " . YEARLY_BONUS_RELEASE_DATES . "
			WHERE year_released =" . Model::safeSql($year) . "				
				AND employee_id =" . Model::safeSql($employee_id) . "					
        ";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
}
?>