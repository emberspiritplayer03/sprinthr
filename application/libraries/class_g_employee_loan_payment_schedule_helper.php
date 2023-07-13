<?php
class G_Employee_Loan_Payment_Schedule_Helper {
	public static function isIdExist(G_Employee_Loan_Payment_Schedule $gel) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			WHERE id = ". Model::safeSql($gel->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlGetDataByLoanId($loan_id = 0, $fields = array(), $cutoff_period = null) {
		
		if($cutoff_period) {			
			$cutoff_period_exp = explode("/", $cutoff_period);

	        $sql_from = date("Y-m-d",strtotime($cutoff_period_exp[0]));
	        $sql_to   = date("Y-m-d",strtotime($cutoff_period_exp[1]));						
		}

		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		if($cutoff_period) {
			$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				WHERE loan_payment_scheduled_date BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
				AND loan_id =" . Model::safeSql($loan_id) . "
			";	
		} else {
			$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				WHERE loan_id =" . Model::safeSql($loan_id) . "
			";
		}
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetDataByLoanId_depre($loan_id = 0, $fields = array()) {
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
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetDataByLoanIds_depre($loan_ids = array(), $fields = array()) {
		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		if( !empty($loan_ids) ){
			$s_loan_ids    = implode(",", $loan_ids);
			$sql_condition = "WHERE loan_id IN({$s_loan_ids})";
		}else{
			$sql_condition = " ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
			{$sql_condition}
			ORDER BY employee_id, loan_id, loan_payment_scheduled_date ASC
		";
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlGetDataByLoanIds($loan_ids = array(), $fields = array(), $cutoff_period = null) {

		if($cutoff_period) {			
			$cutoff_period_exp = explode("/", $cutoff_period);

	        $sql_from = date("Y-m-d",strtotime($cutoff_period_exp[0]));
	        $sql_to   = date("Y-m-d",strtotime($cutoff_period_exp[1]));						
		}

		if(!empty($fields)){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = " * ";
		}

		if( !empty($loan_ids) ){
			$s_loan_ids    = implode(",", $loan_ids);
			$sql_condition = "WHERE loan_id IN({$s_loan_ids})";
		}else{
			$sql_condition = " ";
		}

		if($cutoff_period) {
			$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				{$sql_condition} 
				AND loan_payment_scheduled_date BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
				ORDER BY employee_id, loan_id, loan_payment_scheduled_date ASC
			";	
		} else {
			$sql = "
				SELECT {$sql_fields}
				FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				{$sql_condition}
				ORDER BY employee_id, loan_id, loan_payment_scheduled_date ASC
			";			
		}
		
		$records = Model::runSql($sql,true);
		return $records;
	}


	public static function sqlEmployeeTotalAmountPaidByLoanTitleAndUptoDate($employee_id = 0, $loan_title = '', $to_date = '') {

		$sql = "
			SELECT SUM(lp.amount_paid) AS total_amount_paid
			FROM " . G_EMPLOYEE_LOAN ." l
				LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " lp ON l.id = lp.loan_id 
			WHERE l.employee_id =" . Model::safeSql($employee_id) . "
				AND l.loan_title =" . Model::safeSql($loan_title) . "
				AND (l.status =" . Model::safeSql(G_Employee_Loan::PENDING) . " OR l.status = " . Model::safeSql(G_Employee_Loan::IN_PROGRESS) . ")
				AND l.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
			AND lp.date_paid < " . Model::safeSql($to_date) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		$total_paid = 0;
		if( !empty($row) ){
			$total_paid = $row['total_amount_paid'];
		}
		return $total_paid;
	}

	public static function sqlGetAllLoansPaymentScheduleDataIsNotPaidAndWithBalanceByEmployeeIdAndDateRange($employee_id = 0, $date_from = '', $date_to = '', $fields = array()) {
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
				AND (ls.amount_paid = 0 OR ls.amount_paid <> ls.amount_to_pay)
				AND ls.loan_payment_schedule <=" . Model::safeSql($date_to) . "
		";
		
		$records = Model::runSql($sql,true);
		return $records;
	}
}
?>