<?php
class G_Pagibig_Finder {
	public static function findBySalary($salary) {
		$row['id'] = 1;

		if ($salary < 1500) {
			$employee = $salary * 0.01;
			$company = $salary * 0.02;
		} else if ($salary >= 1500 && $salary <= 4999) {
			$employee = $salary * 0.02;
			$company = $salary * 0.02;
		} else if ($salary >= 5000) {
			$employee = 100;
			$company = 100;
		}
		$row['company_share'] = $company;
		$row['employee_share'] = $employee;

		return self::newObject($row);
	}
	
	public static function findByEmployee(IEmployee $e) {
		$sql = "
			SELECT id, pagibig_ee as employee_share, pagibig_er as company_share
			FROM g_employee_contribution
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
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
	
	private static function newObject($row) {
		$s = new G_Pagibig;
		$s->setId($row['id']);
		$s->setCompanyShare($row['company_share']);
		$s->setEmployeeShare($row['employee_share']);
		return $s;
	}
}
?>