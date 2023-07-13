<?php
class G_Employee_Branch_History_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_BRANCH_HISTORY ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findCurrentBranch(G_Employee $e) {
		$sql = "
			SELECT 
			*

			FROM ". G_EMPLOYEE_BRANCH_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			AND end_date=''
			LIMIT 1		
		";
		return self::getRecord($sql);	
	}
	
	///reset to active
	public static function findCurrentBranch2(G_Employee $e) {
		$sql = "
			SELECT 
			*

			FROM ". G_EMPLOYEE_BRANCH_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			ORDER BY e.id DESC
			LIMIT 1		
		";
		return self::getRecord($sql);	
	}
	

	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_BRANCH_HISTORY ." e
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
		
		$e = new G_Employee_Branch_History;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setCompanyBranchId($row['company_branch_id']);
		$e->setBranchName($row['branch_name']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);

		return $e;
	}
}
?>