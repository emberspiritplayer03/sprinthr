<?php
class G_Employee_Annualize_Tax_Helper {

    public static function isIdExist(G_Employee_Annualize_Tax $at) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ANNUALIZE_TAX ."
			WHERE id = ". Model::safeSql($at->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . ANNUALIZE_TAX			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getAllDataByYear($year = 0, $fields = array()){  
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}		
			FROM tmp_employee_payslip				
			WHERE year =" . $year . "
			ORDER BY employee_id ASC
		";				
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function getAllAnnualizedTaxByYear($year = 0, $fields = array()){  
		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}		
			FROM " . ANNUALIZE_TAX . "				
			WHERE year =" . $year . "
			ORDER BY employee_id ASC
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function getAnnualizedTaxByEmployeeIdAndCutoffPeriod( $employee_id = 0, $cutoff_period = array(), $fields = array() ) {

		$sql_fields = " * ";
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . ANNUALIZE_TAX . "
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND cutoff_start_date =" . Model::safeSql($cutoff_period['from']) . "
				AND cutoff_end_date =" . Model::safeSql($cutoff_period['to']) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}
}
?>