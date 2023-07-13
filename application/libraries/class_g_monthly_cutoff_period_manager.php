<?php
class G_Monthly_Cutoff_Period_Manager {
	public static function savePeriod($current_year,$start, $end, $type, $payout_date, $cutoff_number) {
		$sql = "
			SELECT COUNT(*) as total
			FROM ". G_MONTHLY_CUTOFF_PERIOD ."
			WHERE period_start = ". Model::safeSql($start) ."
			AND period_end = ". Model::safeSql($end) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		if ($row['total'] == 0) {
			$sql = "
				INSERT INTO ". G_MONTHLY_CUTOFF_PERIOD ." (year_tag,period_start,period_end, salary_cycle_id, payout_date, cutoff_number)
				VALUES (
					". Model::safeSql($current_year) .",
					". Model::safeSql($start) .",
					". Model::safeSql($end) .",
					". Model::safeSql($type) .",
					". Model::safeSql($payout_date) .",
					". Model::safeSql($cutoff_number) ."
				)
			";			
			Model::runSql($sql);
		}
	}
	
	public static function save(G_MonthlyCutoff_Period $gcp) {
		if (G_Monthly_Cutoff_Period_Helper::isIdExist($gcp) > 0 ) {
			$action    = "update";
			$sql_start = "UPDATE ". G_MONTHLY_CUTOFF_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
		}else{
			$action    = "insert";
			$sql_start = "INSERT INTO ". G_MONTHLY_CUTOFF_PERIOD . " ";
			$sql_end   = " ";		
		}
		
		$sql = $sql_start ."
			SET
			year_tag	     = " . Model::safeSql($gcp->getYearTag()) .",
			period_start	 = " . Model::safeSql($gcp->getStartDate()) .",
			period_end		 = " . Model::safeSql($gcp->getEndDate()) .",
			payout_date		 = " . Model::safeSql($gcp->getPayoutDate()) .",			
			salary_cycle_id	 = " . Model::safeSql($gcp->getSalaryCycleId()) .",
			cutoff_number	 = " . Model::safeSql($gcp->getCutoffNumber()) .",
			is_lock			 = " . Model::safeSql($gcp->getIsLock()) .",
			is_payroll_generated = " . Model::safeSql($gcp->getIsPayrollGenerated()) ."
			"	
			. $sql_end ."	
		";	
	
		Model::runSql($sql);
		
		if($action == "update") {
			return true;
		} else {
			return mysql_insert_id();	
		}
		
	}
	
	public static function lockPayrollPeriod(G_Cutoff_Period $gcpMonthly) {
		
		if (G_Monthly_Cutoff_Period_Helper::isIdExist($gcpMonthly) > 0 ) {
			$sql_start = "UPDATE ". G_MONTHLY_CUTOFF_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcpMonthly->getId());		
			
				$sql = $sql_start ."
				SET								
				is_lock =" . Model::safeSql(G_Monthly_Cutoff_Period::YES) . " "				
				. $sql_end ."
				";		
			Model::runSql($sql);
		}				
	}
	
	public static function lockAllPayrollPeriodBySelectedYear($selected_year) {
		$sql_start = "UPDATE ". G_MONTHLY_CUTOFF_PERIOD . " ";
		$sql_end   = "WHERE year_tag = ". Model::safeSql($selected_year);		
		
			$sql = $sql_start ."
			SET								
			is_lock =" . Model::safeSql(G_Monthly_Cutoff_Period::YES) . " "				
			. $sql_end ."
			";		
						
		Model::runSql($sql);				
	}
	
	public static function deleteAllByYear($year){
		$sql = "
				DELETE FROM ". G_MONTHLY_CUTOFF_PERIOD ."
				WHERE year_tag =" . Model::safeSql($year);				
		Model::runSql($sql);
	}	

	public static function deleteAllCutOffPeriods($year){
		$sql = "
				DELETE FROM ". G_MONTHLY_CUTOFF_PERIOD ."
		";		
		Model::runSql($sql);
	}	
	
	public static function unLockPayrollPeriod(G_Monthly_Cutoff_Period $gcp) {
		if (G_Monthly_Cutoff_Period_Helper::isIdExist($gcp) > 0 ) {
			$sql_start = "UPDATE ". G_MONTHLY_CUTOFF_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
			
				$sql = $sql_start ."
				SET								
				is_lock =" . Model::safeSql(G_Monthly_Cutoff_Period::NO) . " "				
				. $sql_end ."
				";		
							
			Model::runSql($sql);
		}				
	}	
}
?>