<?php
class G_Break_Time_Schedule_Helper {

    public static function isIdExist(G_Break_Time_Schedule $gbts) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE ."
			WHERE id = ". Model::safeSql($gbts->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsWithConflictTimeBreakInAndBreakOutByScheduleInAndOut( $break_in = '', $break_out = '' ) {
		$sql = "
			SELECT COUNT(id) as total_conflict
			FROM " . BREAK_TIME_SCHEDULE ."
			WHERE " . Model::safeSql($break_in) . " BETWEEN break_in AND break_out OR 
				" . Model::safeSql($break_out) . " BETWEEN break_in AND break_out
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total_conflict'];
	}

	public static function sqlAllScheduledBreakTimeByScheduleInOut( $schedule_in = '', $schedule_out = '', $fields = array(), $order_by = '') {		
		$sql_fields   = (!empty($fields)) ? implode(",", $fields) : ' * ';
		if( $order_by != '' ){
			$sql_order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE ." 
			WHERE schedule_in =" . Model::safeSql($schedule_in) . " 
				AND schedule_out =" . Model::safeSql($schedule_out) . "		
			{$sql_order_by}
		";	
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqTotalConflictBreakSchedule( $break_in = '', $break_out = '', $schedule_in = '', $schedule_out = '') {		
		
		$sql = "
			SELECT COUNT(id)AS total_conflict_schedules
			FROM " . BREAK_TIME_SCHEDULE ." 
			WHERE (schedule_in =" . Model::safeSql($schedule_in) . " 
				AND schedule_out =" . Model::safeSql($schedule_out) . ")
				AND (" . Model::safeSql($break_in) . " BETWEEN break_in AND break_out OR " . Model::safeSql($break_out) . " BETWEEN break_in AND break_out)			
		";			
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total_conflict_schedules'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>