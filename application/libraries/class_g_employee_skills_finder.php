<?php
class G_Employee_Skills_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_SKILLS ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_SKILLS ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
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
		
		$e = new G_Employee_Skills;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setSkill($row['skill']);
		$e->setYearsExperience($row['years_experience']);
		$e->setComments($row['comments']);
		
		return $e;
	}
}
?>