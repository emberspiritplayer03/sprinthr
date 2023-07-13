<?php 
		class G_Monthly_Cutoff_Period_Helper {

			public static function getExpectedCutoffsByMonthAndYear($month,$year){
				$sql = "
					SELECT year_tag,period_start,period_end,cutoff_number
					FROM g_monthly_cutoff_period
					WHERE year_tag = ".$year."
					AND month(period_end) =".$month."			
				";
				$records = Model::runSql($sql, true);		
				return $records;
			}
			


			public static function isIdExist(G_Monthly_Cutoff_Period $gcp) {
				$sql = "
					SELECT COUNT(*) as total
					FROM " . G_MONTHLY_CUTOFF_PERIOD ."
					WHERE id = ". Model::safeSql($gcp->getId()) ."
				";
				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				return $row['total'];
			}



			public static function countTotalCutoffByYear( $year = 0 ) {
				$sql = "
					SELECT COUNT(*) as total
					FROM " . G_MONTHLY_CUTOFF_PERIOD ."
					WHERE year_tag = ". Model::safeSql($year) ."
				";
				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				return $row['total'];
			}




			public static function isCutoffPeriodStartAndEndExists($start = '', $end = '') {
				$is_exists = false;
				$sql = "
					SELECT COUNT(*)AS total
					FROM " . G_MONTHLY_CUTOFF_PERIOD ."
					WHERE period_start =" . Model::safeSql($start) . "
						AND period_end =" . Model::safeSql($end) . "
					ORDER BY id DESC 
					LIMIT 1
				";
				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				if( $row['total'] > 0 ){
					$is_exists = true;
				}
				return $is_exists;
			}


			public static function sqlGetCurrentCutoffPeriod($date = '') {
				$sql = "
					SELECT period_start, period_end, is_payroll_generated, is_lock
					FROM " . G_MONTHLY_CUTOFF_PERIOD ."
					WHERE period_start <= " . Model::safeSql($date) . " AND period_end >= " . Model::safeSql($date) . "
					ORDER BY id DESC 
					LIMIT 1
				";
				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				return $row;
			}


			public static function sqlCutoffPeriodByPeriodStartAndPeriodEnd($period_start = '', $period_end = '', $fields = array())
			{
				if (!empty($fields)) {
					$sql_fields = implode(",", $fields);
				} else {
					$sql_fields = " * ";
				}

				$sql = "
					SELECT {$sql_fields}
					FROM " . G_MONTHLY_CUTOFF_PERIOD . "
					WHERE period_start = " . Model::safeSql($period_start) . " AND period_end =" . Model::safeSql($period_end) . "
					ORDER BY id DESC
					LIMIT 1
				";
				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				return $row;
			}


			public static function sqlCutoffPeriodsByYearTag($year = 0, $fields = array())
				{
					if (!empty($fields)) {
						$sql_fields = implode(",", $fields);
					} else {
						$sql_fields = " * ";
					}

					$sql = "
						SELECT {$sql_fields}
						FROM " . G_MONTHLY_CUTOFF_PERIOD . "
						WHERE year_tag =" . Model::safeSql($year) . "			
						ORDER BY period_start, cutoff_number ASC			
					";

					$records = Model::runSql($sql, true);
					return $records;
				}



		public static function countTotalLockedCutoffByYear($year = 0)
			{
				$sql = "
					SELECT COUNT(*) as total
					FROM " . G_MONTHLY_CUTOFF_PERIOD . "
					WHERE year_tag = " . Model::safeSql($year) . "
					AND is_lock = " . Model::safeSql('Yes') . "
				";

				$result = Model::runSql($sql);
				$row    = Model::fetchAssoc($result);
				return $row['total'];
			}



			public static function sqlAllByPeriodStartAndPeriodEnd($period_start = array(), $period_end = array())
			{
				$start = implode(",", $period_start);
				$end   = implode(",", $period_end);
				$sql = "
					SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
					FROM " . G_MONTHLY_CUTOFF_PERIOD . "
					WHERE period_start IN(" . $start . ")
						AND period_end IN(" . $end . ")
					GROUP BY period_start
					ORDER BY id DESC
					LIMIT 2
				";

				$records = Model::runSql($sql, true);
				return $records;
			}



			public static function isPeriodLock($id)
				{
					if ($id) {
						$cp = G_Monthly_Cutoff_Period_Finder::findById(Utilities::decrypt($id));
						if ($cp) {
							if ($cp->getIsLock() == G_Monthly_Cutoff_Period::YES) {
								return G_Monthly_Cutoff_Period::YES;
							} else {
								return G_Monthly_Cutoff_Period::NO;
							}
						} else {
							return G_Monthly_Cutoff_Period::NO;
						}
					}
				}



		public static function sqlGetAllExistYearTags()
				{
					$data = array();

					$sql = "
						SELECT year_tag 
						FROM " . G_MONTHLY_CUTOFF_PERIOD . "
						WHERE year_tag NOT IN (2014,2015)
						GROUP BY year_tag
						ORDER BY year_tag DESC
					";

					$records = Model::runSql($sql, true);

					foreach ($records as $r) {
						$data[] = $r['year_tag'];
					}

					return $data;
				}

}

?>