<?php
class G_Philhealth_History_Finder {
    
	public static function findAll() {
		$sql = "
			SELECT *
			FROM ". G_PHILHEALTH_HISTORY ."
			ORDER BY salary_from ASC
		";
		return self::getRecords($sql);
	}

	public static function findAllByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT *
			FROM ". G_PHILHEALTH_HISTORY ."
			WHERE company_structure_id =". Model::safeSql($company_structure_id) ."
			ORDER BY salary_from DESC
		";
		return self::getRecords($sql);
	}	

	public static function findBySalary2($salary, $period_start) {

		$return['company_share']  = 100;
		$return['employee_share'] = 100;

		$sql = "
			SELECT * 
			FROM ". G_PHILHEALTH_HISTORY ." p
			WHERE p.salary_from <= " . Model::safeSql($salary) . "
				AND p.salary_to >= " . Model::safeSql($salary) . "
				AND  p.date_end >=  " . Model::safeSql($period_start) . "
			ORDER BY p.id DESC LIMIT 1		
		";

		$data = self::getRecord($sql);

		return $data;
	}

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_PHILHEALTH_HISTORY ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}


	public static function findHistory($d){

		$sql = "
			SELECT * 
			FROM " . G_PHILHEALTH_HISTORY ." 
			WHERE salary_from =". Model::safeSql($d->getSalaryFrom()) ."
			AND salary_to =". Model::safeSql($d->getSalaryTo()) ."
			AND multiplier_employee =". Model::safeSql($d->getMultiplierEmployee()) ."
			LIMIT 1
		";	

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
		$c = new G_Philhealth_History;
		$c->setId($row['id']);
		$c->setCompanyStructureId($row['company_structure_id']);
		$c->setSalaryFrom($row['salary_from']);
		$c->setSalaryTo($row['salary_to']);
		$c->setMultiplierEmployee($row['multiplier_employee']);
        $c->setMultiplierEmployer($row['multiplier_employer']);
		$c->setIsFixed($row['is_fixed']);
		$c->setDateEnd($row['date_end']);
		return $c;
	}	
}
?>