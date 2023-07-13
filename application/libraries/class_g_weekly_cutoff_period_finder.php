<?php 
		class G_Weekly_Cutoff_Period_Finder {

			public static function findByPeriod($start_date, $end_date) {
				$sql = "
					SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
					FROM g_weekly_cutoff_period
					WHERE period_start = ". Model::safeSql($start_date) ."
					AND period_end = ". Model::safeSql($end_date) ."
					ORDER BY id DESC
					LIMIT 1
				";
				return self::getRecord($sql);		
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
					$records[] = self::newObject($row);
				}
				return $records;
			}
	
			private static function newObject($row) {
				$c = new G_Weekly_Cutoff_Period;
				$c->setId($row['id']);
				$c->setYearTag($row['year_tag']);
				$c->setStartDate($row['period_start']);
				$c->setEndDate($row['period_end']);
				$c->setPayoutDate($row['payout_date']);
        $c->setCutoffNumber($row['cutoff_number']);
				$c->setSalaryCycleId($row['salary_cycle_id']);
        $c->setIsPayrollGenerated($row['is_payroll_generated']);
				$c->setIsLock($row['is_lock']);
				return $c;
			}	

	 public static function findAllByYear($year) {
			$sql = "
				SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
				FROM ". G_WEEKLY_CUTOFF_PERIOD ."
	            WHERE YEAR(period_start) = ". Model::safeSql($year) ."
	            AND year_tag = ". Model::safeSql($year) ."
				ORDER BY period_start ASC
			";

			return self::getRecords($sql);
		}
   public static function findByYearMonthAndCutoffNumber($year, $month_number, $cutoff_number) {
        $sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE MONTH(period_end) = ". Model::safeSql($month_number) ."
			AND YEAR(period_start) = ". Model::safeSql($year) ."
			AND cutoff_number = ". Model::safeSql($cutoff_number) ."
			LIMIT 1
		";

        return self::getRecord($sql);
    }

   public static function findByMonthlyCutoffsAndYear($month_number, $year) {
        $sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE MONTH(period_start) = ". Model::safeSql($month_number) ."
				AND YEAR(period_end) = ". Model::safeSql($year) ."			
				AND year_tag = ". Model::safeSql($year) ."	
			ORDER BY cutoff_number ASC
			LIMIT 5
		";

        return self::getRecords($sql);
    }

	public static function _findByMonthlyCutoffsAndYear($month_number, $year) {
        $sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE MONTH(period_start) = ". Model::safeSql($month_number) ."
				AND YEAR(period_start) = ". Model::safeSql($year) ."			
				AND year_tag = ". Model::safeSql($year) ."	
			ORDER BY cutoff_number ASC
			LIMIT 5
		";

        return self::getRecords($sql);
    }

  public static function findAllCutoffByYear($year) {
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
            WHERE YEAR(period_start) = ". Model::safeSql($year) ."
            AND year_tag = ". Model::safeSql($year) ."
			ORDER BY period_start ASC
		";

		return self::getRecords($sql);
	}
	
	public static function findById($id) {
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE id = ". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByDate($date) {
		$day = date("d",strtotime($date));
		if( $day == 31 ){
			$d = DateTime::createFromFormat('Y-m-d', $date);
			$d->modify('-1 day');
			$date = $d->format('Y-m-d');
		}		
		
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE ". Model::safeSql($date) ." >= period_start
			AND ". Model::safeSql($date) ." <= period_end
			ORDER BY id DESC
			LIMIT 1
		";
		
		return self::getRecord($sql);		
	}

	public static function findPreviousByCutoffId($cutoff_id) {
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE id > " . Model::safeSql($cutoff_id) ."
			ORDER BY id ASC
			LIMIT 1
		";		
		return self::getRecord($sql);
 }		

 public static function findNextByCutoffId($cutoff_id) {
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE id < " . Model::safeSql($cutoff_id) . "
			ORDER BY id DESC
			LIMIT 1
		";
		return self::getRecord($sql);
  }	

  	public static function findAllByMonthYear($year, $month_number) {
		 $sql = "
				SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
				FROM ". G_WEEKLY_CUTOFF_PERIOD ."
				WHERE MONTH(period_start) = ". Model::safeSql($month_number) ."
				AND YEAR(period_start) = ". Model::safeSql($year) ."
				AND year_tag = ". Model::safeSql($year) ."
				ORDER BY period_start ASC
		 	";

		 return self::getRecords($sql);
	}

	public static function findAllByMonthYearCutoffNumber($year, $month_number, $cutoff_number) {
		   $sql = "
			   	SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			   	FROM ". G_WEEKLY_CUTOFF_PERIOD ."
				WHERE MONTH(period_start) = ". Model::safeSql($month_number) ."
				AND YEAR(period_start) = ". Model::safeSql($year) ."
				AND year_tag = ". Model::safeSql($year) ."
				AND cutoff_number = ". Model::safeSql($cutoff_number) ."
			   	ORDER BY id DESC
			   	LIMIT 1
		   ";
		   return self::getRecord($sql);
	}	

	public static function findAllByPeriodEndRange($first_date, $second_date) {
	   $sql = "
			  SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			  FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			  WHERE period_end >= ". Model::safeSql($first_date) ."
			  AND period_end <= ". Model::safeSql($second_date) ."
			  ORDER BY period_start ASC
		   ";

	   return self::getRecords($sql);
  	}

  	public static function findAllIsUnLock() {
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE is_lock = ". Model::safeSql(G_Cutoff_Period::NO) ."			
			ORDER BY period_start DESC
		";
		return self::getRecords($sql);		
	}

	public static function findByYearMonthAndPeriod($year, $month, $period) {
		$sql = "
			SELECT id 
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE year_tag =" . Model::safeSql($year) . " 
			AND (DATE_FORMAT(period_start,'%b')  =" . Model::safeSql($month) . " 
				OR DATE_FORMAT(period_start,'%M')  =" . Model::safeSql($month) . " )
			AND (DATE_FORMAT(period_end,'%b')  =" . Model::safeSql($month) . " 
				OR DATE_FORMAT(period_end,'%M')  =" . Model::safeSql($month) . " )
			AND (DATE_FORMAT(payout_date,'%b')  =" . Model::safeSql($month) . "
				OR DATE_FORMAT(payout_date,'%M')  =" . Model::safeSql($month) . ")
			AND cutoff_number = " . Model::safeSql($period) . " 
		";

		return self::getRecord($sql);
	}

	public static function findByYearMonthAndPeriodEnd($year, $month, $period) {
		$sql = "
			SELECT id 
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE year_tag =" . Model::safeSql($year) . " 
			AND (DATE_FORMAT(period_end,'%b')  =" . Model::safeSql($month) . " 
				OR DATE_FORMAT(period_end,'%M')  =" . Model::safeSql($month) . " )
			AND (DATE_FORMAT(payout_date,'%b')  =" . Model::safeSql($month) . "
				OR DATE_FORMAT(payout_date,'%M')  =" . Model::safeSql($month) . ")
			AND cutoff_number = " . Model::safeSql($period) . " 
		";

		return self::getRecord($sql);
	}



	public static function findByYearMonthAndPeriodStart($year, $month, $period) {
		$sql = "
			SELECT id 
			FROM ". G_WEEKLY_CUTOFF_PERIOD ."
			WHERE year_tag =" . Model::safeSql($year) . " 
			AND (DATE_FORMAT(period_start,'%b')  =" . Model::safeSql($month) . " 
				OR DATE_FORMAT(period_start,'%M')  =" . Model::safeSql($month) . " )
			AND cutoff_number = " . Model::safeSql($period) . " 
		";

		return self::getRecord($sql);
	}





}
?>