<?php
class G_Payment_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p
			WHERE p.id = ". Model::safeSql($id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p
		";
		return self::getRecords($sql);
	}
	
	public static function findAllUntilDate($date) {
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p, ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
			WHERE p.id = ph.employee_payable_id
			AND ph.date_paid <= ". Model::safeSql($date) ."
		";
		return self::getRecordsByDate($sql, $date);
	}
	
	public static function findByEmployeeAndPaymentName(IEmployee $e, $name) { // ex: Uniform
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p
			WHERE p.balance_name = ". Model::safeSql($name) ."	
			AND p.employee_id = ". Model::safeSql($e->getId()) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployee(IEmployee $e) {
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p
			WHERE p.employee_id = ". Model::safeSql($e->getId()) ."	
		";
		return self::getRecords($sql);
	}	
	
	public static function findAllByPaymentName($name) {
		$sql = "
			SELECT p.id, p.employee_id, p.balance_name, p.total_amount, p.date_started
			FROM ". G_EMPLOYEE_PAYABLE ." p
			WHERE p.balance_name = ". Model::safeSql($name) ."
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
		$sql_history = "
			SELECT ph.id, ph.amount_paid, ph.date_paid
			FROM ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
			WHERE ph.employee_payable_id = ". Model::safeSql($row['id']) ."
			ORDER BY ph.date_paid
		";		
		$records = self::newObject($row, $sql_history);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$sql_history = "
				SELECT ph.id, ph.amount_paid, ph.date_paid
				FROM ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
				WHERE ph.employee_payable_id = ". Model::safeSql($row['id']) ."
				ORDER BY ph.date_paid
			";			
			$records[$row['id']] = self::newObject($row, $sql_history);
		}
		return $records;
	}
	
	private static function getRecordsByDate($sql, $date) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$sql_history = "
				SELECT ph.id, ph.amount_paid, ph.date_paid
				FROM ". G_EMPLOYEE_PAYABLE_HISTORY ." ph
				WHERE ph.employee_payable_id = ". Model::safeSql($row['id']) ."
				AND ph.date_paid <= ". Model::safeSql($date) ."
				ORDER BY ph.date_paid
			";			
			$records[$row['id']] = self::newObject($row, $sql_history);
		}
		return $records;
	}	
	
	private static function newObject($row, $sql_history) {
		$p = new G_Payment;
		$p->setId($row['id']);
		$p->setName($row['balance_name']);		
		$p->setTotalAmount($row['total_amount']);
		$p->setEmployeeId($row['employee_id']);
		$p->setDateStarted($row['date_started']);
		
		$result = Model::runSql($sql_history);
		while ($row2 = Model::fetchAssoc($result)) {
			$pb = new G_Payment_History;
			$pb->setId($row2['id']);
			$pb->setAmountPaid($row2['amount_paid']);
			$pb->setDatePaid($row2['date_paid']);
			$p->addPaymentHistory($pb);
		}
		return $p;
	}
}
?>