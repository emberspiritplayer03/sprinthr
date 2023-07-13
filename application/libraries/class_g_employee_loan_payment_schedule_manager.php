<?php
class G_Employee_Loan_Payment_Schedule_Manager {
	public static function save(G_Employee_Loan_Payment_Schedule $gel) {
		if (G_Employee_Loan_Payment_Schedule_Helper::isIdExist($gel) > 0 ) {
			$sql_start = "UPDATE ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gel->getId());		
		}else{
			$sql_start = "INSERT INTO ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id           =" . Model::safeSql($gel->getEmployeeId()) . ",
			loan_id  		 	  =" . Model::safeSql($gel->getLoanId()) . ",
			reference_number 	  =" . Model::safeSql($gel->getReferenceNumber()) . ",			
			loan_payment_scheduled_date =" . Model::safeSql($gel->getLoanPaymentScheduledDate()) . ",
			amount_to_pay		  =" . Model::safeSql($gel->getAmountToPay()) . ",
			amount_paid	 		  =" . Model::safeSql($gel->getAmountPaid()) . ",  	
			date_paid	 		  =" . Model::safeSql($gel->getDatePaid()) . ",  	
			is_lock 	 		  =" . Model::safeSql($gel->getIsLock()) . ",  				
			remarks	  	 		  =" . Model::safeSql($gel->getRemarks()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $return = false;
        if( !empty( $a_bulk_insert ) ){

        	$default_fields = "loan_id,employee_id,reference_number,loan_payment_scheduled_date,amount_to_pay,amount_paid,date_paid,remarks";
        	if( !empty($fields) ){
        		$default_fields = implode(",", $fields);
        	}

            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . "({$default_fields})
                VALUES{$sql_values}
            ";
            
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }
		
	public static function delete(G_Employee_Loan_Payment_Schedule $gel){
		if(G_Employee_Loan_Payment_Schedule_Helper::isIdExist($gel) > 0){
			$sql = "
				DELETE FROM ". EMPLOYEE_LOAN_PAYMENT_SCHEDULE ."
				WHERE id =" . Model::safeSql($gel->getId());
			Model::runSql($sql);
		}	
	}
}
?>