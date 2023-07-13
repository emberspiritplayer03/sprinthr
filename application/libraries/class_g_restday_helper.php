<?php
class G_Restday_Helper {

    public static function addRestDay($e, $date) {
        $o = G_Restday_Finder::findByEmployeeAndDate($e, $date);
        if (!$o) {
            $o = new G_Restday;
        }
        $o->setDate($date);
        $o->setEmployeeId($e->getId());
        $is_saved = $o->save();

        G_Attendance_Helper::updateAttendance($e, $date);

        return $is_saved;
    }

    public static function countRestdayByEmployeeAndDates($e, $date_start, $date_end) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_RESTDAY ."
			WHERE date
            BETWEEN ". Model::safeSql($date_start) ." AND ". Model::safeSql($date_end) ."
            AND employee_id = " . Model::safeSql($e->getId()) ."
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

    public static function countRestDayByEmployeeIdAndDate($employee_id = 0, $date = '') {
    	$b_return = false;

		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_RESTDAY ."
			WHERE date =" . Model::safeSql($date) . "
            AND employee_id = " . Model::safeSql($employee_id) ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		if( $row['total'] > 0 ){
			$b_return = true;
		}

		return $b_return;
    }

	public static function getEmployeeLastMonthUntilNowSchedules($e) {
		$now = Tools::getGmtDate('Y-m-d');
		$last_month = date('Y-m-d', strtotime($now .' - 1 month'));
		$schedules = G_Restday_Finder::findAllByEmployeeAndPeriod($e, $last_month, $now);		
		return $schedules;
	}
	
	public static function getEmployeeLastMonthUntilNextMonthSchedules($e) {
		$now = Tools::getGmtDate('Y-m-d');
		$next_month = date('Y-m-d', strtotime($now .' + 1 month'));
		$last_month = date('Y-m-d', strtotime($now .' - 2 month'));
		$schedules = G_Restday_Finder::findAllByEmployeeAndPeriod($e, $last_month, $next_month);		
		return $schedules;	
	}
	
	public static function countRestDayByEmployeeAndWeekNumber(G_Employee $e,$week_number) {
		$week_number = $week_number;
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_ATTENDANCE ." a
			WHERE WEEK(a.date_attendance,1) = ". Model::safeSql($week_number) ."
				AND a.employee_id = " . Model::safeSql($e->getId()) . " AND a.is_restday = 1
		";
		//echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	/*
		$rds - Returned value from G_Restday_Finder
	*/
	public static function showSchedules($restdays) {
		$string = '<div class="styled_items_holder"><ul>';
		foreach ($restdays as $restday) {
			$date = $restday->getDate();
			$date_string = date('M j, Y', strtotime($date));	

			$time_in = $restday->getTimeIn();
			$time_out = $restday->getTimeOut();
			$time_string = Tools::timeFormat($time_in) .' - '. Tools::timeFormat($time_out);
			$string .= '<li><div class="item-detail-styled">';
			$string .= '<span><a class="ui-icon ui-icon-close tooltip" href="javascript:void(0)" onclick="javascript:deleteRestday(\''. $restday->getId() .'\')" style="float:right" title="Remove"></a></span>';
			$string .= '<span class="date_string">'. $date_string .' </span>';
			$string .= ' <span class="time_string">('. $time_string .')</span>';			
			$string .= '</div></li>';
		}
		$string .= '</div>';
        		
		return $string;
	}
}
?>