<?php
class G_Employee_Loan_Payment_Schedule_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByLoanId($id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 
			WHERE loan_id =". Model::safeSql($id) ."
			AND is_lock = 'NO'
		";
		return self::getRecords($sql);
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$gel = new G_Employee_Loan_Payment_Schedule();
		$gel->setId($row['id']);
		$gel->setEmployeeId($row['employee_id']);
		$gel->setLoanId($row['loan_id']);
		$gel->setReferenceNumber($row['reference_number']);	
		$gel->setLoanPaymentScheduledDate($row['loan_payment_scheduled_date']);	
		$gel->setAmountToPay($row['amount_to_pay']);			
		$gel->setAmountPaid($row['amount_paid']);
		$gel->setDatePaid($row['date_paid']);	
		$gel->setIsLock($row['is_lock']);
		$gel->setRemarks($row['remarks']);					
		return $gel;
	}
}
?>