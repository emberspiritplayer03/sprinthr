<?php
class G_Cutoff_Period_Helper
{

	public static function showPeriodNavigation($current_cutoff_id, $location)
	{
		$cutoff_id = $current_cutoff_id; //Utilities::decrypt($_GET['hpid']);
		//$previous_cutoff = G_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
		//$next_cutoff = G_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);

		$from_date = $_GET['from'];
		$to_date   = $_GET['to'];

		/*$c  = new G_Cutoff_Period();
		$c->setId($cutoff_id);
		$next_cutoff_data = $c->getNextCutOff();
		$previous_cutoff_data = $c->getPreviousCutOff();*/

		$c = new G_Cutoff_Period();
		$next_cutoff_data     = $c->getNextCutOffByDate($to_date);
		$previous_cutoff_data = $c->getPreviousCutOffByDate($from_date);

		/*$next_from = $next_cutoff_data['period_start'];
		$next_to   = $next_cutoff_data['period_end'];
		$next_id   = Utilities::encrypt($next_cutoff_data['id']);	*/

		$next_from = $next_cutoff_data['start_date'];
		$next_to   = $next_cutoff_data['end_date'];
		$next_id   = $next_cutoff_data['eid'];

		/*$previous_from = $previous_cutoff_data['period_start'];
		$previous_to   = $previous_cutoff_data['period_end'];
		$previous_id   = Utilities::encrypt($previous_cutoff_data['id']);		*/

		$previous_from = $previous_cutoff_data['start_date'];
		$previous_to   = $previous_cutoff_data['end_date'];
		$previous_id   = $previous_cutoff_data['eid'];

		if ($previous_cutoff_data) {
			//$previous_from = $previous_cutoff->getStartDate();
			//$previous_to = $previous_cutoff->getEndDate();
			//$previous_id = Utilities::encrypt($previous_cutoff->getId());
			$previous_cutoff_link = url("{$location}?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
		}

		if ($next_cutoff_data) {
			//$next_from = $next_cutoff->getStartDate();
			//$next_to = $next_cutoff->getEndDate();
			//$next_id = Utilities::encrypt($next_cutoff->getId());
			$next_cutoff_link = url("{$location}?from={$next_from}&to={$next_to}&hpid={$next_id}");
		}

		$str = '[ Go to: ';
		$str .= '';
		if ($previous_cutoff_link != '') :
			$str .= '<a href="' . $previous_cutoff_link . '">Previous Cutoff</a>';
		else :
			$str .= 'Previous Cutoff';
		endif;
		$str .= ' | ';
		if ($next_cutoff_link != '') :
			$str .= '<a href="' . $next_cutoff_link . '">Next Cutoff</a>';
		else :
			$str .= 'Next Cutoff';
		endif;
		$str .= ' ]';

		return $str;
	}

	public static function getMonth(G_Cutoff_Period $period)
	{
		$date = $period->getStartDate();
		return date('n', strtotime($date));
	}

	public static function isIdExist(G_Cutoff_Period $gcp)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE id = " . Model::safeSql($gcp->getId()) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countTotalCutoffByYear($year = 0)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE year_tag = " . Model::safeSql($year) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countTotalLockedCutoffByYear($year = 0)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE year_tag = " . Model::safeSql($year) . "
			AND is_lock = " . Model::safeSql('Yes') . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCutOffDataById($id = 0)
	{
		$sql = "
			SELECT *
			FROM " . G_CUTOFF_PERIOD . "
			WHERE id = " . Model::safeSql($id) . "
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlCutOffDataByPeriodStart($period_start)
	{
		$sql_query = date("Y-m-d", strtotime($period_start));

		$sql = "
			SELECT *
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start = " . Model::safeSql($sql_query) . "
			ORDER BY id DESC
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlCutOffDataByPeriodEnd($period_end)
	{
		$sql_query = date("Y-m-d", strtotime($period_end));

		$sql = "
			SELECT *
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_end = " . Model::safeSql($sql_query) . "
			ORDER BY id DESC
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetCurrentCutoffPeriod($date = '')
	{
		$sql = "
			SELECT period_start, period_end, is_payroll_generated, is_lock
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start <= " . Model::safeSql($date) . " AND period_end >= " . Model::safeSql($date) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetCutoffPeriodByStartEndDate($from = '', $to = '')
	{
		$sql = "
			SELECT period_start, period_end, is_payroll_generated, is_lock
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start = " . Model::safeSql($from) . " AND period_end = " . Model::safeSql($to) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetNextCutoffPeriodByCurrentId($id = '', $date = '', $year = '')
	{
		$sql = "
			SELECT id, period_start, period_end, is_payroll_generated, is_lock
			FROM " . G_CUTOFF_PERIOD . "
			WHERE id < " . $id . " 
			AND year_tag = " . Model::safeSql($year) . "
			ORDER BY id DESC
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row_result    = Model::fetchAssoc($result);

		$row = array();
		$row['start_date'] = $row_result['period_start'];
		$row['end_date']   = $row_result['period_end'];
		$row['date']       = $date;
		$row['eid']        = Utilities::encrypt($row_result['id']);

		return $row;
	}

	public static function sqlGetPreviousCutoffPeriodByCurrentId($id = '', $start_date = '', $year = '')
	{
		$sql = "
			SELECT id, period_start, period_end, is_payroll_generated, is_lock
			FROM " . G_CUTOFF_PERIOD . "
			WHERE id > " . $id . " 
			ORDER BY id DESC
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row_result    = Model::fetchAssoc($result);

		$row = array();
		$row['start_date'] = $row_result['period_start'];
		$row['end_date']   = $row_result['period_end'];
		$row['date']       = $start_date;
		$row['eid']        = Utilities::encrypt($row_result['id']);

		return $row;
	}

	public static function isCutoffPeriodStartAndEndExists($start = '', $end = '')
	{
		$is_exists = false;
		$sql = "
			SELECT COUNT(*)AS total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start =" . Model::safeSql($start) . "
				AND period_end =" . Model::safeSql($end) . "
			ORDER BY id DESC 
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		if ($row['total'] > 0) {
			$is_exists = true;
		}
		return $is_exists;
	}

	public static function sqlGetAllCutOffPeriods()
	{
		$sql = "
			SELECT period_start, period_end, is_lock
			FROM " . G_CUTOFF_PERIOD . "			
			ORDER BY id ASC			
		";

		$records = Model::runSql($sql, true);
		return $records;
	}

	public static function sqlGetAllUniqueYearTags()
	{
		$data = array();

		$sql = "
			SELECT DISTINCT(year_tag)AS year_tag
			FROM " . G_CUTOFF_PERIOD . "
			ORDER BY year_tag DESC
		";
		$records = Model::runSql($sql, true);

		foreach ($records as $r) {
			$data[] = $r['year_tag'];
		}

		return $data;
	}

	public static function sqlGetAllExistYearTags()
	{
		$data = array();

		$sql = "
			SELECT year_tag 
			FROM " . G_CUTOFF_PERIOD . "
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

	public static function sqlGetCutoffPeriodsByMonthAndYear($month = 0, $year)
	{
		$sql = "
			SELECT * 
			FROM " . G_CUTOFF_PERIOD . " 
			WHERE DATE_FORMAT(period_start,'%m') =" . Model::safeSql($month) . "
				AND year_tag =" . Model::safeSql($year) . "			
			ORDER BY id ASC
			LIMIT 2
		";

		$records = Model::runSql($sql, true);
		return $records;
	}

	public static function sqlAllByPeriodStartAndPeriodEnd($period_start = array(), $period_end = array())
	{
		$start = implode(",", $period_start);
		$end   = implode(",", $period_end);
		$sql = "
			SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start IN(" . $start . ")
				AND period_end IN(" . $end . ")
			GROUP BY period_start
			ORDER BY id DESC
			LIMIT 2
		";

		$records = Model::runSql($sql, true);
		return $records;
	}

	public static function sqlGetCutoffPeriodsByYearMonthAndPeriod($year, $month, $period)
	{
		$sql = "
			SELECT id 
			FROM " . G_CUTOFF_PERIOD . "
			WHERE year_tag =" . Model::safeSql($year) . " 
			AND DATE_FORMAT(period_start,'%b')  =" . Model::safeSql($month) . " 
			AND DATE_FORMAT(period_end,'%b')  =" . Model::safeSql($month) . " 
			AND DATE_FORMAT(payout_date,'%b')  =" . Model::safeSql($month) . " 
			AND cutoff_number = " . Model::safeSql($period) . " 
		";

		$records = Model::runSql($sql, true);
		return $records;
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
			FROM " . G_CUTOFF_PERIOD . "
			WHERE year_tag =" . Model::safeSql($year) . "			
			ORDER BY period_start, cutoff_number ASC			
		";

		$records = Model::runSql($sql, true);
		return $records;
	}

	public static function sqlCutoffPeriodsByYearTagAndNotLock($year = 0, $fields = array())
	{
		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_CUTOFF_PERIOD . "
			WHERE year_tag =" . Model::safeSql($year) . "	
			AND is_lock = " . Model::safeSql('No') . "			
			ORDER BY period_start, cutoff_number ASC			
		";

		$records = Model::runSql($sql, true);
		return $records;
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
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start = " . Model::safeSql($period_start) . " AND period_end =" . Model::safeSql($period_end) . "
			ORDER BY id DESC
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlIsCutoffPeriodExists($period_start = '', $period_end = '')
	{
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start = " . Model::safeSql($period_start) . " AND period_end =" . Model::safeSql($period_end) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isPeriodStartExist($period_start)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_start = " . Model::safeSql($period_start) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isPeriodEndExist($period_end)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_CUTOFF_PERIOD . "
			WHERE period_end = " . Model::safeSql($period_end) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isPeriodLock($id)
	{
		if ($id) {
			$cp = G_Cutoff_Period_Finder::findById(Utilities::decrypt($id));
			if ($cp) {
				if ($cp->getIsLock() == G_Cutoff_Period::YES) {
					return G_Cutoff_Period::YES;
				} else {
					return G_Cutoff_Period::NO;
				}
			} else {
				return G_Cutoff_Period::NO;
			}
		}
	}

	public static function generatePayrollPeriodByYear($year)
	{
		$cArray = array();
		for ($x = 1; $x <= 12; $x++) {
			$cutoff_periods = array();
			//Fist Cutoff
			$date    = mktime(0, 0, 0, $x, 1,  $year);
			$date    = date('Y-m-d', $date);
			$cycle   = G_Salary_Cycle_Finder::findDefault();
			$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$cutoff_periods[] = $current;

			//Second Cutoff
			$date    = mktime(0, 0, 0, $x, 16,  $year);
			$date    = date('Y-m-d', $date);
			$cycle   = G_Salary_Cycle_Finder::findDefault();
			$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$cutoff_periods[] = $current;

			//Merge
			$cArray[] = $cutoff_periods;
		}

		return $cArray;
	}

	public static function savePayrollPeriodByYear($year)
	{
		$cycle	 = G_Salary_Cycle_Finder::findDefault();
		$cArray = array();
		if ($cycle) {
			for ($x = 1; $x <= 12; $x++) {
				$cutoff_periods = array();
				//Fist Cutoff
				$cutoff_number = 1;
				$date          = mktime(0, 0, 0, $x, 1,  $year);
				$date          = date('Y-m-d', $date);
				$cycle         = G_Salary_Cycle_Finder::findDefault();
				$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
				$payout_date   = Tools::getPayoutDate($current['start'], $cycle->getCutOffs(), $cycle->getPayoutDays());

				$gcp = new G_Cutoff_Period();
				$gcp->setYearTag($year);
				$gcp->setStartDate($current['start']);
				$gcp->setEndDate($current['end']);
				$gcp->setPayoutDate($payout_date);
				$gcp->setCutoffNumber($cutoff_number);
				$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
				$gcp->setIsLock(G_Cutoff_Period::NO);
				$gcp->save();

				//Second Cutoff
				$cutoff_number = 2;
				$date    	   = mktime(0, 0, 0, $x, 16,  $year);
				$date    	   = date('Y-m-d', $date);
				$cycle   	   = G_Salary_Cycle_Finder::findDefault();
				$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
				$payout_date   = Tools::getPayoutDate($current['start'], $cycle->getCutOffs(), $cycle->getPayoutDays());

				$gcp = new G_Cutoff_Period();
				$gcp->setYearTag($year);
				$gcp->setStartDate($current['start']);
				$gcp->setEndDate($current['end']);
				$gcp->setCutoffNumber($cutoff_number);
				$gcp->setPayoutDate($payout_date);
				$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
				$gcp->setIsLock(G_Cutoff_Period::NO);
				$gcp->save();
			}

			return true;
		} else {
			return false;
		}
	}

	public static function generateCutOffPeriodsByDate($date)
	{
		$cycle	 = G_Salary_Cycle_Finder::findDefault();

		if ($cycle) {
			$month = date("m", strtotime($date));
			$year  = date("Y", strtotime($date));

			//Second Cutoff			
			$date    	   = mktime(0, 0, 0, $month, 16,  $year);
			$date    	   = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			//Fist Cutoff			
			$date          = mktime(0, 0, 0, $month, 1,  $year);
			$date          = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			return true;
		} else {
			return false;
		}
	}

	public static function generateCutOffPeriodsByDateA($date)
	{
		$cycle	 = G_Salary_Cycle_Finder::findDefault();

		if ($cycle) {
			$month = date("m", strtotime($date));
			$year  = date("Y", strtotime($date));

			//Second Cutoff			
			$date    	   = mktime(0, 0, 0, $month, 16,  $year);
			$date    	   = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			//Fist Cutoff			
			$date          = mktime(0, 0, 0, $month, 1,  $year);
			$date          = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			return true;
		} else {
			return false;
		}
	}

	public static function generateCutOffPeriodsByDateAndPattern($date, $pattern)
	{
		$cycle	 = G_Salary_Cycle_Finder::findDefault();

		if ($cycle) {
			$month = date("m", strtotime($date));
			$year  = date("Y", strtotime($date));

			//Second Cutoff			
			$date    	   = mktime(0, 0, 0, $month, 16,  $year);
			$date    	   = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			//Fist Cutoff			
			$date          = mktime(0, 0, 0, $month, 1,  $year);
			$date          = date('Y-m-d', $date);
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());

			$gcp = new G_Cutoff_Period();
			$gcp->setYearTag($year);
			$gcp->setStartDate($current['start']);
			$gcp->setEndDate($current['end']);
			$gcp->setCutoffNumber($current['cutoff_number']);
			$gcp->setPayoutDate($payout_date);
			$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
			$gcp->setIsLock(G_Cutoff_Period::NO);
			$gcp->save();

			return true;
		} else {
			return false;
		}
	}

	public static function isPeriodLockByDate($start_date, $end_date)
	{
		$cp = G_Cutoff_Period_Finder::findByPeriod($start_date, $end_date);
		if ($cp) {
			if ($cp->getIsLock() == G_Cutoff_Period::YES) {
				return G_Cutoff_Period::YES;
			} else {
				return G_Cutoff_Period::NO;
			}
		} else {
			return G_Cutoff_Period::NO;
		}
	}

	public static function addNewPeriod()
	{
		$date = Tools::getGmtDate('Y-m-d');
		$cycle = G_Salary_Cycle_Finder::findDefault();
		$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		$payout_date = Tools::getPayoutDate($date, $cycle->getCutOffs(), $cycle->getPayoutDays());
		G_Cutoff_Period_Manager::savePeriod(date('Y', strtotime($current['start'])), $current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY, $payout_date, $current['cutoff_number']);
	}

	public static function addPeriodByDate($date)
	{
		$cycle   = G_Salary_Cycle_Finder::findDefault();
		$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		$payout_date = Tools::getPayoutDate($date, $cycle->getCutOffs(), $cycle->getPayoutDays());
		return G_Cutoff_Period_Manager::savePeriod(date('Y', strtotime($current['start'])), $current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY, $payout_date, $current['cutoff_number']);
	}

	/*
		GET CUTOFF PERIOD WHICH EACH $dates IS WITHIN CUTOFF PERIOD
		
		$dates['2012-09-01'] = '2012-09-01';
		$dates['2012-10-15'] = '2012-10-15';
		$value = G_Cutoff_Period_Helper::getAllByDates($dates);
		
		OUTPUT:
			$value - array of G_Cutoff_Period class
	*/
	public static function getAllByDates($dates)
	{
		$cutoffs = array();
		foreach ($dates as $date) {
			$c = G_Cutoff_Period_Finder::findByDate($date);
			if ($c) {
				$cutoffs[$c->getId()] = $c;
			}
		}
		return $cutoffs;
	}

	/*
		$cutoff_periods = G_Cutoff_Period_Finder::findAll();
		$array = G_Cutoff_Period_Helper::convertToArray($cutoff_periods);
		
		$array value:
		Array
		(
			[0] => Array
				(
					[start] => 2012-01-16
					[end] => 2012-01-31
				)
		
			[1] => Array
				(
					[start] => 2012-01-01
					[end] => 2012-01-15
				)
		
			[2] => Array
				(
					[start] => 2011-12-01
					[end] => 2011-12-15
				)
		)
	*/
	public static function convertToArray($cutoff_periods)
	{
		foreach ($cutoff_periods as $key => $cutoff) {
			$return[$key]['start']  = $cutoff->getStartDate();
			$return[$key]['end']    = $cutoff->getEndDate();
		}
		return $return;
	}
}
