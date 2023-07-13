<?php
class G_Philhealth_Table_Finder {
    
	public static function findAll() {
		$sql = "
			SELECT *
			FROM ". G_PHILHEALTH ."
			ORDER BY salary_from ASC
		";
		return self::getRecords($sql);
	}

	public static function findAllByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT *
			FROM ". G_PHILHEALTH ."
			WHERE company_structure_id =". Model::safeSql($company_structure_id) ."
			ORDER BY salary_from DESC
		";
		return self::getRecords($sql);
	}	

	public static function findBySalary($salary) {

		$return['company_share']  = 100;
		$return['employee_share'] = 100;

		$sql = "
			SELECT * 
			FROM ". G_PHILHEALTH ." p
			WHERE p.salary_from <= " . Model::safeSql($salary) . "
				AND p.salary_to >= " . Model::safeSql($salary) . "
			LIMIT 1		
		";

		$data = self::getRecord($sql);

		if( $data ){
			$min_basic_salary = 10000;
			if ($data->getIsFixed() == "Yes") {
				if( $salary <= $min_basic_salary ) {
					$return['employee_share'] = ($min_basic_salary * ($data->getMultiplierEmployee() / 100 )) / 2;
					$return['company_share']  = ($min_basic_salary * ($data->getMultiplierEmployee() / 100 )) / 2;
				} elseif( $salary >= $data->getSalaryFrom() && $salary <= $data->getSalaryTo() ) {
					$return['employee_share'] = ($data->getSalaryFrom() * ($data->getMultiplierEmployee() / 100 )) / 2;
					$return['company_share']  = ($data->getSalaryFrom() * ($data->getMultiplierEmployee() / 100 )) / 2;
				}
			} else { 
				$return['employee_share'] = ($salary * ($data->getMultiplierEmployee() / 100 )) / 2;
				$return['company_share']  = ($salary * ($data->getMultiplierEmployee() / 100 )) / 2;
			}

		}else{
			$return['employee_share'] = 100;
			$return['company_share']  = 100;
		}

		return $return;
	}


    //new alex- philhealth with history
	public static function findBySalary2($salary, $period_start) {

		$return['company_share']  = 100;
		$return['employee_share'] = 100;

		$sql = "
			SELECT * 
			FROM ". G_PHILHEALTH ." p
			WHERE p.salary_from <= " . Model::safeSql($salary) . "
				AND p.salary_to >= " . Model::safeSql($salary) . "
				AND p.effective_date <=  " . Model::safeSql($period_start) . "
			LIMIT 1		
		";

		$data = self::getRecord($sql);

		//check history if effective date is less than period_start
		if(!$data){
			$data = G_Philhealth_History_Finder::findBySalary2($salary, $period_start);
		}

		 //utilities::displayArray($data);exit();

		if( $data ){
			$min_basic_salary = 10000;
			if ($data->getIsFixed() == "Yes") {
				if( $salary <= $min_basic_salary ) {
					$return['employee_share'] = ($min_basic_salary * ($data->getMultiplierEmployee() / 100 )) / 2;
					$return['company_share']  = ($min_basic_salary * ($data->getMultiplierEmployee() / 100 )) / 2;
				} elseif( $salary >= $data->getSalaryFrom() && $salary <= $data->getSalaryTo() ) {
					$return['employee_share'] = ($data->getSalaryFrom() * ($data->getMultiplierEmployee() / 100 )) / 2;
					$return['company_share']  = ($data->getSalaryFrom() * ($data->getMultiplierEmployee() / 100 )) / 2;
				}
			} else { 
				$return['employee_share'] = ($salary * ($data->getMultiplierEmployee() / 100 )) / 2;
				$return['company_share']  = ($salary * ($data->getMultiplierEmployee() / 100 )) / 2;
			}

		}else{
			$return['employee_share'] = 100;
			$return['company_share']  = 100;
		}

		return $return;
	}


	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_PHILHEALTH ." 
			WHERE id =". Model::safeSql($id) ."
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
			$records[] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$c = new G_Philhealth_Table;
		$c->setId($row['id']);
		$c->setCompanyStructureId($row['company_structure_id']);
		$c->setSalaryFrom($row['salary_from']);
		$c->setSalaryTo($row['salary_to']);
		$c->setMultiplierEmployee($row['multiplier_employee']);
        $c->setMultiplierEmployer($row['multiplier_employer']);
		$c->setIsFixed($row['is_fixed']);
		$c->setEffectiveDate($row['effective_date']);
		return $c;
	}	
}
?>