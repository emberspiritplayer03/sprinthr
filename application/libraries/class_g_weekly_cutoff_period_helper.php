<?php
class G_Weekly_Cutoff_Period_Helper
{

     //added function for yearly bonus weekly
    public static function isPeriodLockByDate($start_date, $end_date)
    {
        $cp = G_Weekly_Cutoff_Period_Finder::findByPeriod($start_date, $end_date);
        if ($cp) {
            if ($cp->getIsLock() == G_Weekly_Cutoff_Period::YES) {
                return G_Weekly_Cutoff_Period::YES;
            } else {
                return G_Weekly_Cutoff_Period::NO;
            }
        } else {
            return G_Weekly_Cutoff_Period::NO;
        }
    }


    public static function getExpectedCutoffsByMonthAndYear($month, $year)
    {
        $sql = "
					SELECT year_tag,period_start,period_end,cutoff_number
					FROM g_weekly_cutoff_period
					WHERE year_tag = " . $year . "
					AND month(period_start) =" . $month . "			
				";
        $records = Model::runSql($sql, true);
        return $records;
    }

    public static function countTotalCutoffByYear($year = 0)
    {
        $sql = "
					SELECT COUNT(*) as total
					FROM g_weekly_cutoff_period
					WHERE year_tag = " . Model::safeSql($year) . "
				";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public function generateWeeklyByGroup($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false)
    {
        $temp = array();
        foreach ($arr as $key => $value) {
            $groupValue = $value[$group];
            if (!$preserveGroupKey) {
                unset($arr[$key][$group]);
            }
            if (!array_key_exists($groupValue, $temp)) {
                $temp[$groupValue] = array();
            }

            if (!$preserveSubArrays) {
                $data = count($arr[$key]) == 1 ? array_pop($arr[$key]) : $arr[$key];
            } else {
                $data = $arr[$key];
            }
            $temp[$groupValue][] = $data;
        }
        return $temp;
    }

    public static function getWeeklyCutoffPeriod()
    {
        $location       = G_Settings_Pay_Period_Finder::findAll($order_by, $limit);
        foreach ($location as $value) {
            if (strtolower($value->pay_period_name) == "weekly") {
                $cut_off = $value->cut_off;
            }
        }

        return $cut_off;
    }

    public static function countTotalLockedCutoffByYear($year = 0)
    {
        $sql = "
					SELECT COUNT(*) as total
					FROM " . G_WEEKLY_CUTOFF_PERIOD . "
					WHERE year_tag = " . Model::safeSql($year) . "
					AND is_lock = " . Model::safeSql('Yes') . "
				";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }


    public static function generateWeeklyCutoffPeriods($created, $given_year, $start_day, $count_locked_cutoff)
    {

        $wc = new G_Weekly_Cutoff_Period_Helper();
        $weekly = $wc->getWeeklyCutoffPeriod();

        $for_start = strtotime($start_day, $given_year);
        $for_end = strtotime('+1 year', $given_year);
        $year = date('Y', $given_year);


        $array_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $year_now = date('Y');
        $array_seperate = array();
        $array_cutoffs = array();
        $data = array();
        $count = 1;
        for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
            $get_year = date('Y',  $i);


            foreach ($array_months as  $value) {
                $cutoff_number = 1;

                if ($get_year == $year_now) {

                    if ($value == date('F', $i)) {

                        $data = [
                            'month' => $value,
                            'date_start' => date('Y-m-d', $i),
                            'date_end' =>  date('Y-m-d', strtotime('+6 days', $i))
                        ];



                        if ($count > $count_locked_cutoff) {
                            array_push($array_cutoffs, $data);
                        }

                        $count++;
                    }
                }
            }
        }


        $weekly_by_group = G_Weekly_Cutoff_Period_Helper::generateWeeklyByGroup($array_cutoffs, "month");


        $insert_values = array();
        foreach ($weekly_by_group as $key => $value_by_group) {
            foreach ($array_months as $value_months) {
                if ($key == $value_months) {
                    foreach ($value_by_group as $by_group_key => $value) {
                        $cutoff = $by_group_key + 1;
                        $values = "('" . Model::safeSql($year) . "'," . Model::safeSql($value['date_start']) . "," . Model::safeSql($value['date_end']) . "," . Model::safeSql($value['date_end']) . "," . $cutoff . "," . G_Salary_Cycle::TYPE_SEMI_MONTHLY . ",'" . G_Cutoff_Period::NO . "','" . G_Cutoff_Period::NO . "')";
                        "<br>";

                        array_push($insert_values, $values);
                    }
                }
            }
        }


        $insert_sql_queries = implode(",", $insert_values);

        $return = G_Weekly_Cutoff_Period_Manager::bulkInsertWeeklyCutoff($insert_sql_queries);
        if ($return) {
            $return['is_success'] = true;
            $return['message']    = "Cutoff periods was successfully created";
            // $return['cutoffs']    = $cutoffs;
        } else {
            $return['is_success'] = false;
            $return['message']    = "Cutoff periods update failed";
            // $return['cutoffs']    = $cutoffs;
        }

        return $return;
    }

    public static function isIdExist(G_Weekly_Cutoff_Period $gcp)
    {
        $sql = "
                SELECT COUNT(*) as total
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
                WHERE id = " . Model::safeSql($gcp->getId()) . "
            ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function isPeriodLock($id)
    {
        if ($id) {
            $cp = G_Weekly_Cutoff_Period_Finder::findById(Utilities::decrypt($id));
            if ($cp) {
                if ($cp->getIsLock() == G_Weekly_Cutoff_Period::YES) {
                    return G_Weekly_Cutoff_Period::YES;
                } else {
                    return G_Weekly_Cutoff_Period::NO;
                }
            } else {
                return G_Weekly_Cutoff_Period::NO;
            }
        }
    }

    public static function sqlGetAllExistYearTags()
    {
        $data = array();

        $sql = "
                SELECT year_tag 
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
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


    public static function sqlGetCurrentCutoffPeriod($date = '')
    {
        $sql = "
			SELECT period_start, period_end, is_payroll_generated, is_lock
			FROM " . G_WEEKLY_CUTOFF_PERIOD . "
			WHERE period_start <= " . Model::safeSql($date) . " AND period_end >= " . Model::safeSql($date) . "
			ORDER BY id DESC 
			LIMIT 1
		";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
    }

    public static function sqlCutOffDataById($id = 0)
    {
        $sql = "
                SELECT *
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
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
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
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
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
                WHERE period_end = " . Model::safeSql($sql_query) . "
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
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
                WHERE period_start = " . Model::safeSql($period_start) . " AND period_end =" . Model::safeSql($period_end) . "
                ORDER BY id DESC
                LIMIT 1
            ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
    }

    public static function sqlAllByPeriodStartAndPeriodEnd($period_start = array(), $period_end = array())
    {
        $start = implode(",", $period_start);
        $end   = implode(",", $period_end);
        $sql = "
                SELECT id, year_tag, period_start, period_end, cutoff_number, payout_date, is_lock, is_payroll_generated, salary_cycle_id
                FROM " . G_WEEKLY_CUTOFF_PERIOD . "
                WHERE period_start IN(" . $start . ")
                    AND period_end IN(" . $end . ")
                GROUP BY period_start
                ORDER BY id DESC
                LIMIT 2
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
            FROM " . G_WEEKLY_CUTOFF_PERIOD . "
            WHERE year_tag =" . Model::safeSql($year) . "           
            ORDER BY period_start, cutoff_number ASC            
        ";
        // echo $sql;

        $records = Model::runSql($sql, true);
        return $records;
    }

    public static function sqlCutoffPeriodsByYearTagNotLock($year = 0, $fields = array())
    {
        if (!empty($fields)) {
            $sql_fields = implode(",", $fields);
        } else {
            $sql_fields = " * ";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM " . G_WEEKLY_CUTOFF_PERIOD . "
            WHERE year_tag =" . Model::safeSql($year) . " 
            AND is_lock = 'No'          
            ORDER BY period_start, cutoff_number ASC            
        ";
        // echo $sql;

        $records = Model::runSql($sql, true);
        return $records;
    }
}
