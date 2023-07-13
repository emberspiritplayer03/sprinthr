<?php
class G_Schedule_Specific_Helper {
	public static function getEmployeeLastMonthUntilNowSchedules($e) {
		$now = Tools::getGmtDate('Y-m-d');
		$last_month = date('Y-m-d', strtotime($now .' - 1 month'));
		$schedules = G_Schedule_Specific_Finder::findAllByEmployeeAndPeriod($e, $last_month, $now);		
		return $schedules;
	}
	
	public static function getEmployeeLastMonthUntilNextMonthSchedules($e) {
		$now = Tools::getGmtDate('Y-m-d');
		$next_month = date('Y-m-d', strtotime($now .' + 1 month'));
		$last_month = date('Y-m-d', strtotime($now .' - 2 month'));
		$schedules = G_Schedule_Specific_Finder::findAllByEmployeeAndPeriod($e, $last_month, $next_month);		
		return $schedules;	
	}

    public static function showDateString($date_start, $date_end) {
        if ($date_start == $date_end) {
            return Tools::convertDateFormat($date_start);
        } else {
            return Tools::convertDateFormat($date_start) .' - '. Tools::convertDateFormat($date_end);
        }
    }
	
	/*
		$schedules - Returned value from G_Schedule_Specific_Finder
	*/
	public static function showSchedules($schedules) {
		$string = '<div class="styled_items_holder"><ul>';
		foreach ($schedules as $schedule) {
			$date_start = $schedule->getDateStart();
			$date_end = $schedule->getDateEnd();
			if ($date_start == $date_end || $date_end == '') {
				$date_string = date('M j, Y', strtotime($date_start));	
			} else {
				$date_string = date('M j', strtotime($date_start)) .' - '. date('M j, Y', strtotime($date_end));	
			}
			$time_in = $schedule->getTimeIn();
			$time_out = $schedule->getTimeOut();
			$time_string = Tools::timeFormat($time_in) .' - '. Tools::timeFormat($time_out);
			$string .= '<li><div class="item-detail-styled">';
			$string .= '<span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:deleteSpecificSchedule(\''. $schedule->getId() .'\')" style="float:right" title="Remove"></a></span>';
			$string .= '<span class="date_string">'. $date_string .' </span>';
			$string .= ' <span class="time_string">('. $time_string .')</span>';			
			$string .= '</div></li>';
		}
		$string .= '</div>';
        		
		return $string;
	}
}
?>