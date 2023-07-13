<?php
class G_Company_Branch_Helper {
	public static function convertToArray($branches) {
		$array_branches = array();
		foreach ($branches as $b) {
			$array_branches[$b->getId()] = $b->getName();
		}
		ksort($array_branches);
		return $array_branches;
	}
	
	public static function isIdExist(G_Company_Branch $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_BRANCH ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_BRANCH ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalBranches() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_BRANCH ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isEmployeeBranchHistoryExist(G_Employee $e,G_Company_Branch $b, $start_date, $end_date) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BRANCH_HISTORY ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ." 
			AND company_branch_id = ".Model::safeSql($b->getId())."  AND start_date = ". Model::safeSql($start_date) ." AND end_date = ".Model::safeSql($end_date)."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>