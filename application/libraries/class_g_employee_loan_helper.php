<?php
class G_Employee_Loan_Helper {
	public static function isIdExist(G_Employee_Loan $gel) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_LOAN ."
			WHERE id = ". Model::safeSql($gel->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsIdExist($id = 0) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_LOAN ."
			WHERE id = ". Model::safeSql($id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlLoanDetailsById($id = 0, $fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_LOAN ." l 
			WHERE l.id =" . Model::safeSql($id) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlLoanDataById($employee_loan_id = 0, $fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_LOAN ."
			WHERE employee_loan_id =" . Model::safeSql($employee_loan_id) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlEmployeeLoanBalanceByLoanTitle($employee_id = 0, $loan_title = '') {

		$sql = "
			SELECT SUM(lp.amount_to_pay)AS total_amount_to_pay, SUM(lp.amount_paid) AS total_amount_paid
			FROM " . G_EMPLOYEE_LOAN ." l
				LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " lp ON l.id = lp.loan_id 
			WHERE l.employee_id =" . Model::safeSql($employee_id) . "
				AND l.loan_title =" . Model::safeSql($loan_title) . "				
			AND l.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		$total_balance = 0;
		if( !empty($row) ){
			$total_balance = $row['total_amount_to_pay'] - $row['total_amount_paid'];
		}
		return $total_balance;
	}

	public static function sqlEmployeeLoanTotalAmountToPayByLoanTitle($employee_id = 0, $loan_title = '') {

		$sql = "
			SELECT SUM(lp.amount_to_pay)AS total_amount_to_pay
			FROM " . G_EMPLOYEE_LOAN ." l
				LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " lp ON l.id = lp.loan_id 
			WHERE l.employee_id =" . Model::safeSql($employee_id) . "
				AND l.loan_title =" . Model::safeSql($loan_title) . "
				AND (l.status =" . Model::safeSql(G_Employee_Loan::PENDING) . " OR l.status = " . Model::safeSql(G_Employee_Loan::IN_PROGRESS) . ")
				AND l.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		$total_amount_to_pay = 0;
		if( !empty($row) ){
			$total_amount_to_pay = $row['total_amount_to_pay'];
		}
		return $total_amount_to_pay;
	}


	public static function sqlEmployeeLoanBalanceByLoanTitleAndUptoDate($employee_id = 0, $loan_title = '', $to_date = '') {

		$sql = "
			SELECT SUM(lp.amount_to_pay)AS total_amount_to_pay, SUM(lp.amount_paid) AS total_amount_paid
			FROM " . G_EMPLOYEE_LOAN ." l
				LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " lp ON l.id = lp.loan_id 
			WHERE l.employee_id =" . Model::safeSql($employee_id) . "
				AND l.loan_title =" . Model::safeSql($loan_title) . "				
			AND l.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		$total_balance = 0;
		if( !empty($row) ){
			$total_balance = $row['total_amount_to_pay'] - $row['total_amount_paid'];
		}
		return $total_balance;
	}

	public static function sqlGetUserLoanBalance($employee_id = 0) {

		$sql = "
			SELECT SUM(loan_amount - amount_paid)AS balance
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['balance'];
	}

	public static function getLoanData($query, $add_query = '')	{

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
		
		if($query['loan_type'] != '' && $query['loan_type'] != 'all'){
			$search .= " AND l.loan_type_id =" . Model::safeSql($query['loan_type']);			
		}
	    
	    if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND l.status =" . Model::safeSql($query['status']);			
		}

	    if($query['deduction_type'] != '' && $query['deduction_type'] != 'all'){
			$search .= " AND l.deduction_type =" . Model::safeSql($query['deduction_type']);			
		}	

		if($query['position_type'] != '' && $query['position_type'] != 'all'){
			$search .= " AND gejh.job_id =" . Model::safeSql($query['position_type']);
		}	

		if($query['startdate'] != '' && $query['enddate'] != '') {
			$search .= " AND l.start_date >=" . Model::safeSql($query['startdate']);
			$search .= " AND l.start_date <=" . Model::safeSql($query['enddate']);
		}					

	    if($query['position_type'] != '' && $query['position_type'] != 'all'){

		   	$sql = "
		    	SELECT l.id, gejh.name AS emp_position, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, l.status, l.start_date, l.months_to_pay
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " gejh ON gejh.employee_id = e.id
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . "
					" . $search . " 
					{$sql_add_query}	
					ORDER BY employee_name, lt.loan_type
		    ";	    	

	    } else {

		   	$sql = "
		    	SELECT l.id, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, l.status, l.start_date, l.months_to_pay
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . "
					" . $search . " 
					{$sql_add_query} 
					ORDER BY employee_name, lt.loan_type
					
		    ";

	    }	    
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function getLoanLedgerData($query, $add_query = '')	{

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
		
		if($query['loan_type'] != '' && $query['loan_type'] != 'all'){
			$search .= " AND l.loan_type_id =" . Model::safeSql($query['loan_type']);			
		}

	    if($query['deduction_type'] != '' && $query['deduction_type'] != 'all'){
			$search .= " AND l.deduction_type =" . Model::safeSql($query['deduction_type']);			
		}	

		if($query['position_type'] != '' && $query['position_type'] != 'all'){
			$search .= " AND gejh.job_id =" . Model::safeSql($query['position_type']);
		}				

	    if($query['position_type'] != '' && $query['position_type'] != 'all'){

		   	$sql = "
		    	SELECT l.id, gejh.name AS emp_position, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name, e.sss_number, e.pagibig_number, 
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, l.status, l.start_date, l.months_to_pay
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " gejh ON gejh.employee_id = e.id
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . " 
					
					" . $search . " 
					{$sql_add_query}	
					ORDER BY employee_name, lt.loan_type, l.start_date
		    ";	    	

	    } else {

		   	$sql = "
		    	SELECT l.id, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name, e.sss_number, e.pagibig_number, 
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, l.status, l.start_date, l.months_to_pay
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . "      
					
					" . $search . " 
					{$sql_add_query} 
					ORDER BY employee_name, lt.loan_type, l.start_date
					
		    ";

	    }	  

		$result = Model::runSql($sql,true);		
		return $result;
	}	

	public static function getLoanDataSemiMonthLoanRegister($query, $add_query = '') {

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
		
		if($query['loan_type'] != '' && $query['loan_type'] != 'all'){
			$search .= " AND l.loan_type_id =" . Model::safeSql($query['loan_type']);			
		}
	    
	    if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND l.status =" . Model::safeSql($query['status']);			
		}

	    if($query['deduction_type'] != '' && $query['deduction_type'] != 'all'){
			$search .= " AND l.deduction_type =" . Model::safeSql($query['deduction_type']);			
		}	

		if($query['position_type'] != '' && $query['position_type'] != 'all'){
			$search .= " AND gejh.job_id =" . Model::safeSql($query['position_type']);
		}	

		/*if($query['cutoff01_startdate'] != '' && $query['cutoff02_enddate'] != '') {
			$search .= " AND l.start_date >=" . Model::safeSql($query['cutoff01_startdate']);
			$search .= " AND l.start_date <=" . Model::safeSql($query['cutoff02_enddate']);
		}*/	

		if($query['cutoff01_startdate'] != '' && $query['cutoff02_enddate'] != '') {
			$search .= " AND elps.loan_payment_scheduled_date >=" . Model::safeSql($query['cutoff01_startdate']);
			$search .= " AND elps.loan_payment_scheduled_date <=" . Model::safeSql($query['cutoff02_enddate']);
		}				

	    if($query['position_type'] != '' && $query['position_type'] != 'all'){

		   	$sql = "
		    	SELECT l.id, gejh.name AS emp_position, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
		    		elps.loan_payment_scheduled_date AS payment_date,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, 
					(SELECT es.status FROM `g_settings_employment_status` es WHERE es.id = e.employment_status_id ORDER BY es.id DESC LIMIT 1 )AS employment_status, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, 
						COALESCE(`ejh`.`name`,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
	                ))AS `position_name`, 
		                COALESCE(`esh`.`name`,(
	                    SELECT name FROM `g_employee_subdivision_history`
	                    WHERE employee_id = e.id 
	                        AND end_date <> ''
	                    ORDER BY end_date DESC
	                    LIMIT 1
	                ))AS `department_name`,
					l.status, l.start_date, l.months_to_pay, elps.amount_paid AS monthly_amount_paid 
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " elps ON elps.loan_id = l.id 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " gejh ON gejh.employee_id = e.id
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
					LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''      
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . "
					AND elps.is_lock = 'Yes' 
					" . $search . " 
					{$sql_add_query}	
					ORDER BY employee_name, lt.loan_type
		    ";	    

	    } else {

		   	$sql = "
		    	SELECT l.id, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
		    		elps.loan_payment_scheduled_date AS payment_date,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
					(SELECT es.status FROM `g_settings_employment_status` es WHERE es.id = e.employment_status_id ORDER BY es.id DESC LIMIT 1 )AS employment_status, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, 
						COALESCE(`ejh`.`name`,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
	                ))AS `position_name`, 
		                COALESCE(`esh`.`name`,(
	                    SELECT name FROM `g_employee_subdivision_history`
	                    WHERE employee_id = e.id 
	                        AND end_date <> ''
	                    ORDER BY end_date DESC
	                    LIMIT 1
	                ))AS `department_name`,
					l.status, l.start_date, l.months_to_pay, elps.amount_paid AS monthly_amount_paid 
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " elps ON elps.loan_id = l.id 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
					LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''      
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . " 
					AND elps.is_lock = 'Yes' 
					" . $search . " 
					{$sql_add_query} 
					ORDER BY employee_name, lt.loan_type	
		    ";

	    }	  
	    
		$result = Model::runSql($sql,true);		
		return $result;		
	}

	public static function getLoanDataMonthlyLoanRegister($query, $add_query = '') {

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
		
		if($query['loan_type'] != '' && $query['loan_type'] != 'all'){
			$search .= " AND l.loan_type_id =" . Model::safeSql($query['loan_type']);			
		}
	    
	    if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND l.status =" . Model::safeSql($query['status']);			
		}

	    if($query['deduction_type'] != '' && $query['deduction_type'] != 'all'){
			$search .= " AND l.deduction_type =" . Model::safeSql($query['deduction_type']);			
		}	

		if($query['position_type'] != '' && $query['position_type'] != 'all'){
			$search .= " AND gejh.job_id =" . Model::safeSql($query['position_type']);
		}	

		/*if($query['cutoff01_startdate'] != '' && $query['cutoff02_enddate'] != '') {
			$search .= " AND l.start_date >=" . Model::safeSql($query['cutoff01_startdate']);
			$search .= " AND l.start_date <=" . Model::safeSql($query['cutoff02_enddate']);
		}*/			

		if($query['cutoff01_startdate'] != '' && $query['cutoff02_enddate'] != '') {
			$search .= " AND elps.loan_payment_scheduled_date >=" . Model::safeSql($query['cutoff01_startdate']);
			$search .= " AND elps.loan_payment_scheduled_date <=" . Model::safeSql($query['cutoff02_enddate']);
		}			

	    if($query['position_type'] != '' && $query['position_type'] != 'all'){

		   	$sql = "
		    	SELECT l.id, gejh.name AS emp_position, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
		    		elps.loan_payment_scheduled_date AS payment_date,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
					(SELECT es.status FROM `g_settings_employment_status` es WHERE es.id = e.employment_status_id ORDER BY es.id DESC LIMIT 1 )AS employment_status, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, 
					COALESCE(`ejh`.`name`,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
	                ))AS `position_name`, 
		                COALESCE(`esh`.`name`,(
	                    SELECT name FROM `g_employee_subdivision_history`
	                    WHERE employee_id = e.id 
	                        AND end_date <> ''
	                    ORDER BY end_date DESC
	                    LIMIT 1
	                ))AS `department_name`,
					l.status, l.start_date, l.months_to_pay, elps.amount_paid AS monthly_amount_paid 
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " elps ON elps.loan_id = l.id 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " gejh ON gejh.employee_id = e.id
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
					LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''      
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . " 
					AND elps.is_lock = 'Yes' 
					" . $search . " 
					{$sql_add_query}	
					ORDER BY employee_name, lt.loan_type
		    ";	    

	    } else {

		   	$sql = "
		    	SELECT l.id, e.employee_code, CONCAT(e.lastname, ', ', e.middlename, ' ', e.firstname)AS employee_name,
		    		elps.loan_payment_scheduled_date AS payment_date,
					lt.loan_type, CONCAT(l.interest_rate, '%')AS interest_rate, FORMAT(COALESCE(l.loan_amount,0),2)AS loan_amount, FORMAT(COALESCE(l.total_amount_to_pay,0),2)AS total_amount_to_pay, FORMAT(COALESCE(l.amount_paid,0),2)AS amount_paid, 
					l.deduction_type, FORMAT(COALESCE(l.deduction_per_period,0),2)AS total_deduction_per_period, 
					(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
					(SELECT es.status FROM `g_settings_employment_status` es WHERE es.id = e.employment_status_id ORDER BY es.id DESC LIMIT 1 )AS employment_status, 
					FORMAT(
						COALESCE(
							l.total_amount_to_pay - l.amount_paid
						,0)
					,2)AS balance, 
					COALESCE(`ejh`.`name`,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
	                ))AS `position_name`, 
		                COALESCE(`esh`.`name`,(
	                    SELECT name FROM `g_employee_subdivision_history`
	                    WHERE employee_id = e.id 
	                        AND end_date <> ''
	                    ORDER BY end_date DESC
	                    LIMIT 1
	                ))AS `department_name`,
					l.status, l.start_date, l.months_to_pay, elps.amount_paid AS monthly_amount_paid 
				FROM ". G_EMPLOYEE_LOAN ." l 
					LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " elps ON elps.loan_id = l.id 
					LEFT JOIN " . G_EMPLOYEE . " e ON e.id = l.employee_id 
					LEFT JOIN " . G_LOAN_TYPE . " lt ON l.loan_type_id = lt.id 
					LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON  `ejh`.`employee_id` = `e`.`id` AND `ejh`.`end_date` = '' 
					LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh  ON e.id = esh.employee_id AND esh.end_date = ''      
				WHERE l.is_archive = " . Model::safeSql(G_Employee_Loan::NO) . " 
					AND elps.is_lock = 'Yes' 
					" . $search . " 
					{$sql_add_query} 
					ORDER BY employee_name, lt.loan_type	
		    ";

	    }
	   
		$result = Model::runSql($sql,true);		
		return $result;		
	}
}
?>