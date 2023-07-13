<?php
class G_Employee_Loan_Details_Manager {
	public static function save(G_Employee_Loan_Details $geld) {
		if (G_Employee_Loan_Details_Helper::isIdExist($geld) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_LOAN_DETAILS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($geld->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LOAN_DETAILS . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($geld->getCompanyStructureId()) . ",
			employee_id  		 =" . Model::safeSql($geld->getEmployeeId()) . ",
			loan_id 	 		 =" . Model::safeSql($geld->getLoanId()) . ",			
			date_of_payment 	 =" . Model::safeSql($geld->getDateOfPayment()) . ",
			amount	 	 		 =" . Model::safeSql($geld->getAmount()) . ",
			amount_paid	 		 =" . Model::safeSql($geld->getAmountPaid()) . ",  		
			is_paid 	 		 =" . Model::safeSql($geld->getIsPaid()) . ",  		
			remarks		 		 =" . Model::safeSql($geld->getRemarks()) . ",  					
			date_created 		 =" . Model::safeSql($geld->getDateCreated()) . " "				
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function appendPayement(G_Employee_Loan_Details $geld) {
		$sql_start = "INSERT INTO ". G_EMPLOYEE_LOAN_DETAILS . " ";
		$sql_end   = " ";				
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($geld->getCompanyStructureId()) . ",
			employee_id  		 =" . Model::safeSql($geld->getEmployeeId()) . ",
			loan_id 	 		 =" . Model::safeSql($geld->getLoanId()) . ",			
			date_of_payment 	 =" . Model::safeSql($geld->getDateOfPayment()) . ",
			amount	 	 		 =" . Model::safeSql($geld->getAmount()) . ",
			amount_paid	 		 =" . Model::safeSql($geld->getAmountPaid()) . ",  		
			is_paid 	 		 =" . Model::safeSql($geld->getIsPaid()) . ",  		
			remarks		 		 =" . Model::safeSql($geld->getRemarks()) . ",  					
			date_created 		 =" . Model::safeSql($geld->getDateCreated()) . " "				
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function delete(G_Employee_Loan_Details $geld){
		if(G_Employee_Loan_Details_Helper::isIdExist($geld) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LOAN_DETAILS ."
				WHERE id =" . Model::safeSql($geld->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function deleteAllUnpaidPaymentByLoanId(G_Employee_Loan $gel){
		$sql = "
			DELETE FROM ". G_EMPLOYEE_LOAN_DETAILS ."
			WHERE loan_id =" . Model::safeSql($gel->getId()) . " AND amount_paid = 0";
			Model::runSql($sql);	
	}
	
	public static function deleteAllByLoanId(G_Employee_Loan $gel){
		$sql = "
			DELETE FROM ". G_EMPLOYEE_LOAN_DETAILS ."
			WHERE loan_id =" . Model::safeSql($gel->getId());
			Model::runSql($sql);	
	}
}
?>