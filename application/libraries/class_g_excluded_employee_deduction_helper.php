<?php
class G_Excluded_Employee_Deduction_Helper {

    public static function isIdExist(G_Excluded_Employee_Deduction $o) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION ."
			WHERE id = ". Model::safeSql($o->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function findByPayrollPeriodId($payroll_period_id) {
		$sql = "
			SELECT *
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION ."
			WHERE payroll_period_id = ".Model::safeSql($payroll_period_id)."
			"		
		;
		$result = Model::runSql($sql,true);
		return $result;
	}
	
}
?>