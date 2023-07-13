<?php

	class G_Weekly_Cutoff_Period_Manager {
		
		public static function bulkInsertWeeklyCutoff($values){
        	$sql = "INSERT INTO g_weekly_cutoff_period (year_tag,period_start,period_end,payout_date,cutoff_number,salary_cycle_id,is_lock,is_payroll_generated) 
            VALUES ".$values."
        	";
        
        	Model::runSql($sql);
        	return mysql_insert_id();    
		}

		public static function deleteAllByYear($year){
		$sql = "
				DELETE FROM ". G_WEEKLY_CUTOFF_PERIOD ."
				WHERE year_tag =" . Model::safeSql($year);				
		Model::runSql($sql);
	}	
	public static function deleteAllByYearAndLock($year){
		$sql = "
				DELETE FROM ". G_WEEKLY_CUTOFF_PERIOD ."
				WHERE year_tag =" . Model::safeSql($year) ." AND 
				is_lock = 'No'
				";
				
		Model::runSql($sql);
	}

	public static function lockPayrollPeriod(G_Weekly_Cutoff_Period $gcp_weekly) {
	
		if (G_Weekly_Cutoff_Period_Helper::isIdExist($gcp_weekly) > 0 ) {
			$sql_start = "UPDATE g_weekly_cutoff_period ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp_weekly->getId());		
			
				$sql = $sql_start ."
				SET								
				is_lock =" . Model::safeSql(G_Weekly_Cutoff_Period::YES) . " "				
				. $sql_end ."
				";		
						
			Model::runSql($sql);
		}				
	}

	public static function unLockPayrollPeriod(G_Weekly_Cutoff_Period $gcp) {
		if (G_Weekly_Cutoff_Period_Helper::isIdExist($gcp) > 0 ) {
			$sql_start = "UPDATE g_weekly_cutoff_period ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
			
				$sql = $sql_start ."
				SET								
				is_lock =" . Model::safeSql(G_Weekly_Cutoff_Period::NO) . " "				
				. $sql_end ."
				";		
					
			Model::runSql($sql);
		}				
	}	
	
	public static function save(G_Weekly_Cutoff_Period $gcp) {
		if (G_Weekly_Cutoff_Period_Helper::isIdExist($gcp) > 0 ) {
			$action    = "update";
			$sql_start = "UPDATE ". G_WEEKLY_CUTOFF_PERIOD . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
		}else{
			$action    = "insert";
			$sql_start = "INSERT INTO ". G_WEEKLY_CUTOFF_PERIOD . " ";
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



	}

?>