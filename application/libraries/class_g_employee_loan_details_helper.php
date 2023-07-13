<?php
class G_Employee_Loan_Details_Helper {
	public static function isIdExist(G_Employee_Loan_Details $geld) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LOAN_DETAILS ."
			WHERE id = ". Model::safeSql($geld->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByLoanId($loan_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LOAN_DETAILS ."
			WHERE loan_id = ". Model::safeSql($loan_id) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByIsPaid($is_paid) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_LOAN_DETAILS ."
			WHERE is_paid = ". Model::safeSql($is_paid) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function sumTotalLoanPaymentsByLoanId(G_Employee_Loan $gel) {
		$sql = "
			SELECT SUM(amount_paid) as total
			FROM " . G_EMPLOYEE_LOAN_PAYMENT_BREAKDOWN ."
			WHERE  	loan_id = ". Model::safeSql($gel->getId()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>