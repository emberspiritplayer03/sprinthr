<?php
class G_Payment_Manager {	
	public static function saveToEmployee(IEmployee $e, G_Payment $p) {	
		if ($p->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_PAYABLE;
			$sql_end   = " WHERE id = ". Model::safeSql($p->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_PAYABLE;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			employee_id        		= " . Model::safeSql($e->getId()) .",
			balance_name			= " . Model::safeSql($p->getName()) .",
			total_amount		 	= " . Model::safeSql($p->getTotalAmount()) .",
			date_started			= " . Model::safeSql($p->getDateStarted()) ."
			". $sql_end ."		
		";		

		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			$insert_id = mysql_insert_id();
			self::savePaymentHistory($e, $p, $insert_id);
			return $insert_id;
		} else if ($action == 'update') {
			self::savePaymentHistory($e, $p, $p->getId());
			return true;
		}			
	}
	
	private static function savePaymentHistory(IEmployee $e, G_Payment $p, $payment_id) {
		$payments = $p->getPaymentHistories();
		foreach ($payments as $payment) {
			if (!G_Payment_Helper::isPaymentHistoryExist($e, $payment, $payment_id)) {
				$values[] = "(". Model::safeSql($payment_id) .",". Model::safeSql($e->getId()) .",". Model::safeSql($payment->getAmountPaid()) .",". Model::safeSql($payment->getDatePaid()) .")";	
			}				
		}							
		$value = implode(',', $values);
		$sql = "
			INSERT INTO ". G_EMPLOYEE_PAYABLE_HISTORY ." (employee_payable_id, employee_id, amount_paid, date_paid)
			VALUES ". $value ."
		";
		Model::runSql($sql);		
	}
	
/*	public static function assignToEmployee(IEmployee $e, G_Schedule $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO g_employee_group_schedule (employee_group_id, schedule_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($e->getId()) .",
				". Model::safeSql($s->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_EMPLOYEE) ."				
			)
		";
		Model::runSql($sql);
		return mysql_insert_id();
	}
	
	public static function assignToGroup(IGroup $g, G_Schedule $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO g_employee_group_schedule (employee_group_id, schedule_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($g->getId()) .",
				". Model::safeSql($s->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_GROUP) ."				
			)
		";
		Model::runSql($sql);
		return mysql_insert_id();
	}	
	
	public static function removeEmployee(IEmployee $e, G_Schedule $s) {
		$sql = "
			DELETE FROM g_employee_group_schedule
			WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function removeGroup(IGroup $g, G_Schedule $s) {
		$sql = "
			DELETE FROM g_employee_group_schedule
			WHERE employee_group_id = ". Model::safeSql($g->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function deleteSchedule(G_Schedule $s) {
		$sql = "
			DELETE FROM g_schedule
			WHERE id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}*/
}
?>