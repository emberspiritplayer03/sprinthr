<?php
class G_Employee_Fixed_Contribution_Helper {

    public static function isIdExist(G_Employee_Fixed_Contribution $gefc) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_FIXED_CONTRI ."
			WHERE id = ". Model::safeSql($gefc->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_FIXED_CONTRI			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getEmployeeFixedContributions( $employee_id = null ) {
		$sql = "
			SELECT *
			FROM " . G_FIXED_CONTRI . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
		";
		$rows = Model::runSql($sql,true);	
		return $rows;
	}

	public static function getEmployeeContributionsByType($employee_id = null, $type = '') {
		$sql = "
			SELECT *
			FROM " . G_FIXED_CONTRI ."
			WHERE employee_id = ". Model::safeSql($employee_id) ."
				AND type =" . Model::safeSql($type) . "
			ORDER BY type ASC
		";
		
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}
}
?>