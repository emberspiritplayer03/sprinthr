<?php
class G_Employee_Loan_Manager {
	public static function save(G_Employee_Loan $gel) {
		if (G_Employee_Loan_Helper::isIdExist($gel) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LOAN . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gel->getId());		
			$action    = "update";
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LOAN . " ";
			$sql_end  = "";		
			$action   = "insert";
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gel->getCompanyStructureId()) . ",
			employee_id  		 =" . Model::safeSql($gel->getEmployeeId()) . ",
			loan_type_id 	 	 =" . Model::safeSql($gel->getLoanTypeId()) . ",			
			employee_name 	 	 =" . Model::safeSql($gel->getEmployeeName()) . ",
			loan_title	 	 	 =" . Model::safeSql($gel->getLoanTitle()) . ",
			interest_rate 	  	 =" . Model::safeSql($gel->getInterestRate()) . ",
			loan_amount	 		 =" . Model::safeSql($gel->getLoanAmount()) . ",  	
			amount_paid	 		 =" . Model::safeSql($gel->getAmountPaid()) . ",  		
			months_to_pay		 =" . Model::safeSql($gel->getMonthsToPay()) . ",  					
			deduction_type	  	 =" . Model::safeSql($gel->getDeductionType()) . ", 			
			start_date	  		 =" . Model::safeSql($gel->getStartDate()) . ",  
			end_date	  		 =" . Model::safeSql($gel->getEndDate()) . ",  
			total_amount_to_pay	 =" . Model::safeSql($gel->getTotalAmountToPay()) . ",  
			deduction_per_period =" . Model::safeSql($gel->getDeductionPerPeriod()) . ",  
			status	  		 	 =" . Model::safeSql($gel->getStatus()) . ",  
			is_lock	  		 	 =" . Model::safeSql($gel->getIsLock()) . ",  
			is_archive	  		 =" . Model::safeSql($gel->getIsArchive()) . ",  
			date_created	  	 =" . Model::safeSql($gel->getDateCreated()) . " "				
			. $sql_end ."	
		
		";						
		Model::runSql($sql);
		if( $action == 'update' ){
			$id = $gel->getId();
		}else{
			$id = mysql_insert_id();		
		}
		
		return $id;
	}

	public static function updateAmountPaid($id, $new_amount_paid){
		if(G_Employee_Loan_Helper::sqlIsIdExist($id) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_LOAN ."
				SET amount_paid =" . Model::safeSql($new_amount_paid) . "
				WHERE id =" . Model::safeSql($id);
			
			Model::runSql($sql);
			return true;
		}else{
			return false;
		}	
	}

	public static function updateAllFinishedLoans(){
		$total_records_updated = 0;
		$sql = "
			UPDATE ". G_EMPLOYEE_LOAN ."
			SET status =" . Model::safeSql(G_Employee_Loan::DONE) . "
			WHERE amount_paid >= total_amount_to_pay
		";
		
		Model::runSql($sql);
		$total_records_updated = mysql_affected_rows();	
		return $total_records_updated;
			
	}

	public static function updateLoanStatus($id, $status){
		if(G_Employee_Loan_Helper::sqlIsIdExist($id) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_LOAN ."
				SET status =" . Model::safeSql($status) . "
				WHERE id =" . Model::safeSql($id);
			Model::runSql($sql);
			return true;
		}else{
			return false;
		}	
	}
		
	public static function delete(G_Employee_Loan $gel){
		if(G_Employee_Loan_Helper::isIdExist($gel) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LOAN ."
				WHERE id =" . Model::safeSql($gel->getId());
			Model::runSql($sql);
		}	
	}
}
?>