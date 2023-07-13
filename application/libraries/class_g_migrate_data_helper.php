<?php
class G_Migrate_Data_Helper {
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ANNUALIZE_TAX			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllData(){    	
		$sql = "
			SELECT *			
			FROM tmp_employee_payslip				
			ORDER BY employee_id ASC
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function getAllDataByYear($year){    	
		$sql = "
			SELECT *			
			FROM tmp_employee_payslip			
			WHERE year =" . Model::safeSql($year) . "	
			ORDER BY employee_id ASC
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}
}
?>