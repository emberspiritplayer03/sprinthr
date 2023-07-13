<?php
class G_Settings_Weekly_Deduction_Breakdown_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_WEEKLY_DEDUCTION_BREAKDOWN ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByName($name) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_WEEKLY_DEDUCTION_BREAKDOWN ." e
			WHERE e.name = ". Model::safeSql($name) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByIdAndIsActive($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_WEEKLY_DEDUCTION_BREAKDOWN ." e
			WHERE e.id = ". Model::safeSql($id) ." AND e.is_active =" . Model::safeSql(G_Settings_Weekly_Deduction_Breakdown::YES) . "	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_WEEKLY_DEDUCTION_BREAKDOWN ."
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
		$e = new G_Settings_Weekly_Deduction_Breakdown;
		$e->setId($row['id']);
		$e->setName($row['name']);
		$e->setBreakdown($row['breakdown']);
		$e->setIsActive($row['is_active']);
		$e->setIsTaxable($row['is_taxable']);
		$e->setSalaryCredit($row['salary_credit']);
		return $e;
	}
}
?>