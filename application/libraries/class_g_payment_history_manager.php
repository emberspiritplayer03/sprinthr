<?php
class G_Payment_History_Manager {
	public static function save(G_Payment_History $ph) {	
		if ($ph->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_PAYABLE_HISTORY;
			$sql_end   = " WHERE id = ". Model::safeSql($ph->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_PAYABLE_HISTORY;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			amount_paid        		= " . Model::safeSql($ph->getAmountPaid()) .",
			date_paid				= " . Model::safeSql($ph->getDatePaid()) ."
			". $sql_end ."		
		";		

		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			$insert_id = mysql_insert_id();
			return $insert_id;
		} else if ($action == 'update') {
			return true;
		}			
	}
}
?>