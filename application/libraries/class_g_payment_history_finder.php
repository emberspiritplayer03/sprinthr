<?php
/*
	$e = Employee_Factory::get(28);
	$pf = G_Payment_History_Finder::findByEmployeeAndPaymentNameAndDatePaid($e, 'uniform', '2012-05-25');
	$pf->setAmountPaid(150);
	$pf->save();
*/
class G_Payment_History_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT ph.id, ph.amount_paid, ph.date_paid
			FROM ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
			WHERE ph.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	/*
		$pf = G_Payment_History_Finder::findByEmployeeAndPaymentNameAndDatePaid($e, 'uniform', '2012-06-10');
	*/
	public static function findByEmployeeAndPaymentNameAndDatePaid(IEmployee $e, $payment_name, $date_paid) {
		$sql = "
			SELECT ph.id, ph.amount_paid, ph.date_paid
			FROM ". G_EMPLOYEE_PAYABLE ." p, ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
			WHERE p.id = ph.employee_payable_id
			AND ph.employee_id = ". Model::safeSql($e->getId()) ."
			AND ph.date_paid = ". Model::safeSql($date_paid) ."
			AND p.balance_name = ". Model::safeSql($payment_name) ."
			AND p.employee_id = ph.employee_id
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
		$pb = new G_Payment_History;
		$pb->setId($row['id']);
		$pb->setAmountPaid($row['amount_paid']);
		$pb->setDatePaid($row['date_paid']);
		return $pb;
	}
}
?>