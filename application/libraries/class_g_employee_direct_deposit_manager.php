<?php
class G_Employee_Direct_Deposit_Manager {
	public static function save(G_Employee_Direct_Deposit $e) {
		if (G_Employee_Direct_Deposit_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_DIRECT_DEPOSIT . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_DIRECT_DEPOSIT . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id				= " . Model::safeSql($e->getEmployeeId()) .",
			bank_name		   		= " . Model::safeSql($e->getBankName()) .",
			account   				= " . Model::safeSql($e->getAccount()) .",
			account_type			= " . Model::safeSql($e->getAccountType()) ." "
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Direct_Deposit $e){
		if(G_Employee_Direct_Deposit_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_DIRECT_DEPOSIT ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>