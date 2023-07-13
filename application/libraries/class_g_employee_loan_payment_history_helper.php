<?php
class G_Employee_Loan_Payment_History_Helper {
	public static function isIdExist(G_Employee_Loan_Payment_History $gelph) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE id = ". Model::safeSql($gelph->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsWithPaymentByEmployeeIdAndDatePattern($employee_id, $date_pattern = '') {		
		$sql = "
			SELECT COALESCE(COUNT(id),0) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE remarks LIKE '%{$date_pattern}%'
				AND employee_id =" . Model::safeSql($employee_id) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlSumAmountPaidByLoanId($loan_id) {		
		$sql = "
			SELECT COALESCE(SUM(amount_paid),0) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE loan_id =" . Model::safeSql($loan_id) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlSumAmountToPayByLoanId($loan_id) {		
		$sql = "
			SELECT COALESCE(SUM(amount_to_pay),0) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE loan_id =" . Model::safeSql($loan_id) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlLoanPaymentHistoryDetailsByEmployeeLoanId($employee_loan_id = 0, $fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."			
			WHERE employee_id =" . Model::safeSql($employee_loan_id) . "
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlEmployeeScheduledUnpaidLoans($employee_id = 0, $date_from = '', $date_to = '', $fields = array()) {

		$date_from = date('Y-m-d',strtotime($date_from));
		$date_to   = date('Y-m-d',strtotime($date_to));

		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." ls 
				LEFT JOIN " . G_EMPLOYEE_LOAN . " l ON ls.loan_id = l.id 			
			WHERE ls.employee_id =" . Model::safeSql($employee_id) . "
			 AND ls.is_lock =" . Model::safeSql(G_Employee_Loan::NO) . "
			 AND ls.loan_payment_scheduled_date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			 AND l.is_archive = 'No'
			 AND l.status != ". Model::safeSql(G_Employee_Loan::STOP) . " AND l.status != " . Model::safeSql( G_Employee_Loan::DONE ) . "
			ORDER BY ls.id ASC 
		";

		$result = Model::runSql($sql,true);		

		if(empty($result)){
			$sql = "
				SELECT ls.id,l.loan_title,ls.amount_paid AS balance
				FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." ls 
					LEFT JOIN " . G_EMPLOYEE_LOAN . " l ON ls.loan_id = l.id 			
				WHERE ls.employee_id =" . Model::safeSql($employee_id) . "
				 AND ls.is_lock =" . Model::safeSql(G_Employee_Loan::YES) . "
				 AND ls.loan_payment_scheduled_date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				 AND ls.amount_paid =" . Model::safeSql(0) . "
				 AND l.is_archive = 'No'
				 AND l.status != ". Model::safeSql(G_Employee_Loan::STOP) . "	 
				ORDER BY ls.id DESC 
			";
			//AND l.status != ". Model::safeSql(G_Employee_Loan::STOP) . " AND l.status != " . Model::safeSql( G_Employee_Loan::DONE ) . "

			$result = Model::runSql($sql,true);	
		}

		return $result;	
	}



   //alex unlock test
	public static function sqlEmployeeScheduledUnpaidLoans2($date_from = '', $date_to = '' , $fields = array()) {

		$date_from = date('Y-m-d',strtotime($date_from));
		$date_to   = date('Y-m-d',strtotime($date_to));

		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." ls 
				LEFT JOIN " . G_EMPLOYEE_LOAN . " l ON ls.loan_id = l.id 			
			WHERE ls.is_lock =" . Model::safeSql(G_Employee_Loan::YES) . "
			 AND ls.loan_payment_scheduled_date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			 AND l.is_archive = 'No'
			 AND l.status != ". Model::safeSql(G_Employee_Loan::STOP) . " 
			 AND ( l.status = " . Model::safeSql( G_Employee_Loan::DONE ) . " OR l.status = " . Model::safeSql( G_Employee_Loan::PENDING ) . ")
			ORDER BY ls.id ASC 
		";

		$result = Model::runSql($sql,true);		
		return $result;	
	}



	public static function sqlLoanPaymentHistoryDetailsByLoanId($loan_id = 0, $fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."			
			WHERE loan_id =" . Model::safeSql($loan_id) . "
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlCountEntriesByEmployeeLoanId($employee_loan_id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE employee_loan_id = ". Model::safeSql($employee_loan_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>