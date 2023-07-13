<?php
class G_Pagibig_Table_Finder {

	public static function findBySalaryDepre($salary) {
		$ptf = self::findAll();
		if($ptf) {
			foreach($ptf as $data) {
				if ($salary >= $data->getSalaryFrom() && $salary <= $data->getSalaryTo()) {
					$return['employee_share'] = $salary * $data->getMultiplierEmployee();
					//$return['company_share'] = $salary * $data->getMultiplierEmployer();
					$return['company_share'] = 100;
					return $return;
				} else if ($salary >= $data->getSalaryFrom() && $data->getSalaryTo() == 0) {
					$return['employee_share'] = $salary * $data->getMultiplierEmployee();
					//$return['company_share'] = $salary * $data->getMultiplierEmployer();	
					$return['company_share'] = 100;
					return $return;			
				}
			}
		}

		// IF IT FAILS ALL CONDITION
		$return['company_share'] = 100;
		$return['employee_share'] = 100;
		return $return;
	}

	public static function findBySalary($salary) {
		$return['company_share']  = 100;
		$return['employee_share'] = 100;

		$sql = "
			SELECT * 
			FROM ". G_PAGIBIG ." p
			WHERE p.salary_from <= " . Model::safeSql($salary) . "
				AND p.salary_to >= " . Model::safeSql($salary) . "
			LIMIT 1		
		";

		$data = self::getRecord($sql);
		if( $data ){
			$return['employee_share'] = $salary * $data->getMultiplierEmployee();
			//$return['company_share']  = $salary * $data->getMultiplierEmployer();	
			$return['company_share'] = 100;
		}else{
			$return['employee_share'] = 0;
			//$return['company_share']  = 0;	
			$return['company_share'] = 100;
		}

		return $return;
	}

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_PAGIBIG ." 
			WHERE id =". Model::safeSql($id) ."
			ORDER BY id ASC
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_PAGIBIG ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_PAGIBIG ." 			
			".$order_by."
			".$limit."		
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
		$gpt = new G_Pagibig_Table();
		$gpt->setId($row['id']);
		$gpt->setCompanyStructureId($row['company_structure_id']);
		$gpt->setSalaryFrom($row['salary_from']);
		$gpt->setSalaryTo($row['salary_to']);				
		$gpt->setMultiplierEmployee($row['multiplier_employee']);				
		$gpt->setMultiplierEmployer($row['multiplier_employer']);									
		return $gpt;
	}
}
?>