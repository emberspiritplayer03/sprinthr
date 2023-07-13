<?php
class G_Job_Salary_Rate_Helper {
	public static function isIdExist(G_Job_Salary_Rate $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_SALARY_RATE ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_SALARY_RATE			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_SALARY_RATE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}


}
?>