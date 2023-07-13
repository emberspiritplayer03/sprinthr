<?php
class G_Employee_Loan_Payment_History_Manager {
	public static function save(G_Employee_Loan_Payment_History $lh) {
		if (G_Employee_Loan_Payment_History_Helper::isIdExist($lh) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($lh->getId());		
		}else{
			$sql_start = "INSERT INTO ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET			
			employee_id  	     =" . Model::safeSql($lh->getEmployeeId()) . ",
			loan_id     		 =" . Model::safeSql($lh->getLoanId()) . ",					
			reference_number 	 =" . Model::safeSql($lh->getReferenceNumber()) . ",		
			loan_payment_scheduled_date =" . Model::safeSql($lh->getLoanPaymentScheduledDate()) . ",									
			amount_to_pay 	 	 =" . Model::safeSql($lh->getAmountToPay()) . ",									
			amount_paid 	 	 =" . Model::safeSql($lh->getAmountPaid()) . ",									
			date_paid 	 	 	 =" . Model::safeSql($lh->getDatePaid()) . ",									
			remarks 	 	 	 =" . Model::safeSql($lh->getRemarks()) . ",									
			is_lock 		 	 =" . Model::safeSql($lh->getIsLock()) . " "				
			. $sql_end ."	
		
		";					
		Model::runSql($sql);
		return mysql_insert_id();
	}

	public static function update(G_Employee_Loan_Payment_History $lh){
		$total_records_updated = 0;
		if(G_Employee_Loan_Payment_History_Helper::isIdExist($lh) > 0){
			$sql = "
				UPDATE ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " 
				SET			
					employee_id  	     =" . Model::safeSql($lh->getEmployeeId()) . ",
					loan_id     		 =" . Model::safeSql($lh->getLoanId()) . ",					
					reference_number 	 =" . Model::safeSql($lh->getReferenceNumber()) . ",		
					loan_payment_scheduled_date =" . Model::safeSql($lh->getLoanPaymentScheduledDate()) . ",									
					amount_to_pay 	 	 =" . Model::safeSql($lh->getAmountToPay()) . ",									
					amount_paid 	 	 =" . Model::safeSql($lh->getAmountPaid()) . ",									
					date_paid 	 	 	 =" . Model::safeSql($lh->getDatePaid()) . ",									
					remarks 	 	 	 =" . Model::safeSql($lh->getRemarks()) . ",									
					is_lock 		 	 =" . Model::safeSql($lh->getIsLock()) . "
				WHERE id =" . Model::safeSql($lh->getId());									
			Model::runSql($sql);	
			$total_records_updated = mysql_affected_rows();			
		}	
		return $total_records_updated;
	}
		
	public static function delete(G_Employee_Loan_Payment_History $lh){
		$total_deleted = 0;
		if(G_Employee_Loan_Payment_History_Helper::isIdExist($lh) > 0){
			$sql = "
				DELETE FROM ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				WHERE id =" . Model::safeSql($lh->getId());
			Model::runSql($sql);
			$total_deleted = mysql_affected_rows();		
		}	
		return $total_deleted;
	}
}
?>