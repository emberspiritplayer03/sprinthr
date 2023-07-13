<?php
class G_Employee_Loan_Payment_History_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findByLoanId($loan_id) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 
			WHERE loan_id =". Model::safeSql($loan_id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
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
	

	public static function findByLoanIdAndDateScheduled($loan_id, $date_schedule) {
		$sql = "
			SELECT * 
			FROM " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE ." 
			WHERE loan_id =". Model::safeSql($loan_id) ."
			AND loan_payment_scheduled_date =". Model::safeSql($date_schedule) ."
			LIMIT 1
		";
		return self::getRecord($sql);
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
		$lh = new G_Employee_Loan_Payment_History();
		$lh->setId($row['id']);
		$lh->setEmployeeId($row['employee_id']);
		$lh->setLoanId($row['loan_id']);
		$lh->setReferenceNumber($row['reference_number']);					
		$lh->setLoanPaymentScheduledDate($row['loan_payment_scheduled_date']);					
		$lh->setAmountToPay($row['amount_to_pay']);					
		$lh->setAmountPaid($row['amount_paid']);								
		$lh->setDatePaid($row['date_paid']);									
		$lh->setRemarks($row['remarks']);		
		$lh->setIsLock($row['is_lock']);		
		return $lh;
	}
}
?>