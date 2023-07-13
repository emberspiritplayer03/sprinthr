<?php
class G_Break_Time_Schedule_Details_Helper {

    public static function isIdExist(G_Break_Time_Schedule_Details $gbd) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE_DETAILS ."
			WHERE id = ". Model::safeSql($gbd->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlObjDataByHeaderId( $header_id = 0 ) {
		$sql = "
			SELECT obj_type, obj_id
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . "
			WHERE header_id =" . Model::safeSql($header_id) . "
			ORDER BY id DESC
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlGetAllDataByHeaderId( $header_id = 0, $fields = array() ) {
		$sql_fields = " * ";

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . "
			WHERE header_id =" . Model::safeSql($header_id) . "
		";
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlGetAllGroupBreaktimeSchedulesByGroupIdAndScheduleInAndScheduleOut( $group_id = 0, $schedule_in, $schedule_out, $fields = array() ){
		$sql_schedule_in  = date("H:i:s",strtotime($schedule_in));
		$sql_schedule_out = date("H:i:s",strtotime($schedule_out));
		$sql = "
			SELECT DATE_FORMAT(brd.break_in,'%r')AS break_in, DATE_FORMAT(brd.break_out,'%r')AS break_out
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " brd 
			  LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " brh ON brd.header_id = brh.id
			WHERE (brd.obj_type =" . Model::safeSql(G_Break_Time_Schedule_Details::PREFIX_ALL) . " OR (brd.obj_type = " . Model::safeSql(G_Break_Time_Schedule_Details::PREFIX_DEPARTMENT) . " AND brd.obj_id = " . Model::safeSql($group_id) . ")) 
				AND ( brh.schedule_in = " . Model::safeSql($sql_schedule_in) . " AND brh.schedule_out = " . Model::safeSql($sql_schedule_out) . " )
			GROUP BY brd.break_in, brd.break_out
		";	
		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function sqlGetAllBreaktimeSchedulesByEmployeeIdAndScheduleInAndScheduleOut( $employee_id = 0, $schedule_in, $schedule_out, $fields = array() ){
		$sql_schedule_in  = date("H:i:s",strtotime($schedule_in));
		$sql_schedule_out = date("H:i:s",strtotime($schedule_out));
		$sql = "
			SELECT DATE_FORMAT(brd.break_in,'%r')AS break_in, DATE_FORMAT(brd.break_out,'%r')AS break_out, brd.to_required_logs
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " brd 
			  LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " brh ON brd.header_id = brh.id
			WHERE (brd.obj_type =" . Model::safeSql(G_Break_Time_Schedule_Details::PREFIX_ALL) . " OR (brd.obj_type = " . Model::safeSql(G_Break_Time_Schedule_Details::PREFIX_EMPLOYEE) . " AND brd.obj_id = " . Model::safeSql($employee_id) . ")) 
				AND ( brh.schedule_in = " . Model::safeSql($sql_schedule_in) . " AND brh.schedule_out = " . Model::safeSql($sql_schedule_out) . " )
			GROUP BY brd.break_in, brd.break_out
		";	
		
		$record = Model::runSql($sql,true);
		return $record;
	}

	public function sqlGetAllBreaktimeSchedulesByBreakInAndOut( $schedule_in = '', $schedule_out = '' ) {
		$sql_schedule_in  = date("H:i:s",strtotime($schedule_in));
		$sql_schedule_out = date("H:i:s",strtotime($schedule_out));
		$sql = "
			SELECT DATE_FORMAT(d.break_in,'%r')AS break_in, DATE_FORMAT(d.break_out,'%r')AS break_out
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " d
			 LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " h ON d.header_id = h.id 
			WHERE h.schedule_in = " . Model::safeSql($sql_schedule_in) . " AND h.schedule_out = " . Model::safeSql($schedule_out) . "
			GROUP BY d.break_in, d.break_out
		";

		$record = Model::runSql($sql,true);
		return $record;
	}
	
	public static function sqlObjBreakTimeByScheduleInOut( $schedule = array(), $object_type, $object_id, $day_type = array()) {
		$schedule_in  = $schedule['schedule_in'];
		$schedule_out = $schedule['schedule_out'];

		$sql_fields = "CONCAT( DATE_FORMAT(d.break_in,'%r'), ' to ', DATE_FORMAT(d.break_out,'%r') )AS `Break Time`";

		//Day Type condition
		$sql_add_query = '';
		if( !empty($day_type) ){
			foreach( $day_type as $type ){
				$a_add_query[] = "d.{$type} =" . Model::safeSql(G_Break_Time_Schedule_Details::YES);	
			}

			if( !empty($a_add_query) ){
				$sql_add_query = "AND (" . implode(" OR ", $a_add_query) . ")";
			} 
			
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " d
				LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " h
					ON d.header_id = h.id
			WHERE (d.obj_id =" . Model::safeSql($object_id) . "
				AND d.obj_type =" . Model::safeSql($object_type) . ")
				AND (h.schedule_in =" . Model::safeSql($schedule_in) . " AND h.schedule_out =" . Model::safeSql($schedule_out) . ")	
				{$sql_add_query} 
			GROUP BY d.break_in, d.break_out
			ORDER BY d.break_in
		";
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlObjDeductibleBreakTimeByScheduleInOut( $schedule = array(), $object_type, $object_id, $day_type = array()) {
		$schedule_in  = date("H:i:s", strtotime($schedule['schedule_in']));
		$schedule_out = date("H:i:s", strtotime($schedule['schedule_out']));	

		$date_in  = date("Y-m-d", strtotime($schedule_in));
		$date_out = date("Y-m-d", strtotime($schedule_out)); 

		//Day Type condition
		$sql_add_query = '';
		if( !empty($day_type) ){
			foreach( $day_type as $type ){
				$a_add_query[] = "d.{$type} =" . Model::safeSql(G_Break_Time_Schedule_Details::YES);	
			}

			if( !empty($a_add_query) ){
				$sql_add_query = "AND (" . implode(" OR ", $a_add_query) . ")";
			} 
			
		}

		$sql = "
			SELECT d.break_in, d.break_out, 
				CONCAT( DATE_FORMAT(d.break_in,'%r'), ' to ', DATE_FORMAT(d.break_out,'%r') )AS `Break Time`,
				IF( (TIME_TO_SEC(d.break_out) - TIME_TO_SEC(d.break_in)) < 0, 			 		
					TIME_TO_SEC(
						TIMEDIFF( CONCAT('2015-05-06', ' ', d.break_out), CONCAT('2015-05-05',' ',d.break_in))
					) / 3600,
			 		(
						TIME_TO_SEC(d.break_out) - TIME_TO_SEC(d.break_in)
					) / 3600
			 	) AS total_hrs_deductible
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " d
				LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " h
					ON d.header_id = h.id
			WHERE (d.obj_id =" . Model::safeSql($object_id) . "
				AND d.obj_type =" . Model::safeSql($object_type) . " AND d.to_deduct =" . Model::safeSql(G_Break_Time_Schedule_Details::YES) . ")
				AND (h.schedule_in =" . Model::safeSql($schedule_in) . " AND h.schedule_out =" . Model::safeSql($schedule_out) . ")			
				{$sql_add_query} 
			GROUP BY d.break_in, d.break_out
			ORDER BY d.id DESC
		";	

		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlObjDeductibleBreakTimeByScheduleInOutDateStart( $schedule = array(), $object_type, $object_id, $day_type = array()) {
		$schedule_in  = date("H:i:s", strtotime($schedule['schedule_in']));
		$schedule_out = date("H:i:s", strtotime($schedule['schedule_out']));	

		//Day Type condition
		$sql_add_query = '';
		if( !empty($day_type) ){
			foreach( $day_type as $type ){
				$a_add_query[] = "d.{$type} =" . Model::safeSql(G_Break_Time_Schedule_Details::YES);	
			}

			if( !empty($a_add_query) ){
				$sql_add_query = "AND (" . implode(" OR ", $a_add_query) . ")";
			} 
			
		}

		$sql = "
			SELECT d.break_in, d.break_out, d.to_deduct, d.to_required_logs,
				CONCAT( DATE_FORMAT(d.break_in,'%r'), ' to ', DATE_FORMAT(d.break_out,'%r') )AS `Break Time`,
				IF( (TIME_TO_SEC(d.break_out) - TIME_TO_SEC(d.break_in)) < 0, 			 		
					TIME_TO_SEC(
						TIMEDIFF( CONCAT('2015-05-06', ' ', d.break_out), CONCAT('2015-05-05',' ',d.break_in))
					) / 3600,
			 		(
						TIME_TO_SEC(d.break_out) - TIME_TO_SEC(d.break_in)
					) / 3600
			 	) AS total_hrs_deductible
			FROM " . BREAK_TIME_SCHEDULE_DETAILS . " d
				LEFT JOIN " . BREAK_TIME_SCHEDULE_HEADER . " h
					ON d.header_id = h.id
			WHERE (d.obj_id =" . Model::safeSql($object_id) . "
				AND d.obj_type =" . Model::safeSql($object_type) . ")
				AND (h.schedule_in =" . Model::safeSql($schedule_in) . " AND h.schedule_out =" . Model::safeSql($schedule_out) . ")			
				AND h.date_start <= " . Model::safeSql(date('Y-m-d')) . "
				{$sql_add_query} 
			GROUP BY d.break_in, d.break_out
			ORDER BY d.id DESC
		";	

		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE_DETAILS			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>