<?php
class G_Leave_Finder {
    public static function findVacation() {
        return self::findByType(G_Leave::TYPE_VACATION);
    }

    public static function findByType($leave_type) {
        $sql = "
			SELECT
				e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e
			WHERE e.type = ". Model::safeSql($leave_type) ."
			LIMIT 1
		";
        return self::getRecord($sql);
    }

	public static function findWithCredits() {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e
			WHERE e.default_credit > 0
		";
		return self::getRecords($sql);
	}

	public static function findApprovedByEmployeeAndDate(IEmployee $e, $date) {
		$sql = "
			SELECT l.id, l.company_structure_id, l.name, l.is_paid
			FROM ". G_LEAVE ." l, ". G_EMPLOYEE_LEAVE_REQUEST ." lr
			WHERE l.id = lr.leave_id
			AND lr.employee_id = ". Model::safeSql($e->getId()) ."
			AND lr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::APPROVED) ."
			AND (
					". Model::safeSql($date) ." >= lr.date_start
					AND
					". Model::safeSql($date) ." <= lr.date_end
				)
		";
		return self::getRecord($sql);
	}
	
	public static function findById($id) {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByName($name) {
		$sql = "
			SELECT
				e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e
			WHERE e.name = ". Model::safeSql($name) ."
			AND e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
			LIMIT 1
		";				
		return self::getRecord($sql);
	}
	
	public static function findAllIsNotArchive() {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e 
			WHERE e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
		";
		return self::getRecords($sql);
	}

	public static function findAllGeneralAndIncentiveIsNotArchive() {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e 
			WHERE e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
			AND (name LIKE '%General%' OR name LIKE '%Incentive%')
		";
		return self::getRecords($sql);
	}	
	
	public static function findAllIsArchive() {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e 
			WHERE e.gl_is_archive =" . Model::safeSql(G_Leave::YES) . "
		";
		return self::getRecords($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e WHERE e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
		";
		return self::getRecords($sql);
	}	
	
	public static function findByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT 
				e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e
			WHERE e.company_structure_id = ". Model::safeSql($gcs->id) ."	
			AND e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
		";

		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureIdIsNotArchive($company_id) {
		$sql = "
			SELECT e.id, e.company_structure_id, e.name, e.type, e.default_credit, e.is_paid, e.gl_is_archive, e.is_default
			FROM ". G_LEAVE ." e 
			WHERE e.company_structure_id =" . Model::safeSql($company_id) . "
			AND e.gl_is_archive =" . Model::safeSql(G_Leave::NO) . "
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
		
		$e = new G_Leave;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setName($row['name']);
        $e->setType($row['type']);
		$e->setDefaultCredit($row['default_credit']);
		$e->setIsPaid($row['is_paid']);
		$e->setIsArchive($row['gl_is_archive']);
        $e->setIsDefault($row['is_default']);
		return $e;
	}
}
?>