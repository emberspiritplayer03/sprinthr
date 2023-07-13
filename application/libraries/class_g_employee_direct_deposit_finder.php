<?php
class G_Employee_Direct_Deposit_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.bank_name,
				e.account, 
				e.account_type

			FROM ". G_EMPLOYEE_DIRECT_DEPOSIT ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.bank_name,
				e.account, 
				e.account_type
				
			FROM ". G_EMPLOYEE_DIRECT_DEPOSIT ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
		";
		return self::getRecords($sql);
	}
	
	public static function findSingleEmployeeBankRecordByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.bank_name,
				e.account, 
				e.account_type
				
			FROM ". G_EMPLOYEE_DIRECT_DEPOSIT ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findRecordByEmployeeIdBankName($employee_id, $bank_name) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.bank_name,
				e.account, 
				e.account_type
				
			FROM ". G_EMPLOYEE_DIRECT_DEPOSIT ." e
			WHERE 
				e.employee_id 	= ". Model::safeSql($employee_id) ." AND
				e.bank_name		= ". Model::safeSql($bank_name) ."
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
		
		$e = new G_Employee_Direct_Deposit;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setBankName($row['bank_name']);
		$e->setAccount($row['account']);
		$e->setAccountType($row['account_type']);

		return $e;
	}
}
?>