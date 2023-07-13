<?php
class G_Payment_Helper {
	public static function isPaymentHistoryExist(IEmployee $e, $payment_history, $payment_id) {
		$sql = "
			SELECT COUNT(*) AS total FROM ". G_EMPLOYEE_PAYABLE_HISTORY ." 
			WHERE 
			employee_payable_id = ". Model::safeSql($payment_id) ."
			AND employee_id = ". Model::safeSql($e->getId()) ."
			AND amount_paid = ". Model::safeSql($payment_history->getAmountPaid()) ."
			AND date_paid = ". Model::safeSql($payment_history->getDatePaid()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		if ($row['total'] > 0) {
			return true;	
		} else {
			return false;	
		}
	}
}
?>