<?php
class G_SSS_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . SSS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findBySalary($salary) {
		$sql = "
			SELECT id, company_share, employee_share, company_ec, provident_ee, provident_er
			FROM " . SSS . "
			WHERE ". $salary ."
			BETWEEN from_salary
			AND to_salary
			LIMIT 1
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByEmployee(IEmployee $e) {
		$sql = "
			SELECT id, sss_ee as employee_share, sss_er as company_share
			FROM g_employee_contribution
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . SSS ." WHERE is_active = 'Yes' 			
			".$order_by."
			".$limit."		
	  ";		
		return self::getRecords($sql);
	}
	
	public static function getEC($basic_salary){
	$sql =	"select company_ec from `p_sss` where ".$basic_salary." between `from_salary` and `to_salary`";


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
		$s = new G_SSS;
		$s->setId($row['id']);
		$s->setCompanyShare($row['company_share']);
		$s->setEmployeeShare($row['employee_share']);
		$s->setCompanyEc($row['company_ec']);
		$s->setProvidentEr($row['provident_er']);
		$s->setProvidentEe($row['provident_ee']);
		
		$s->setMonthlySalaryCredit($row['monthly_salary_credit']);
		$s->setFromSalary($row['from_salary']);
		$s->setToSalary($row['to_salary']);
		
		return $s;
	}
}
?>