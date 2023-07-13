<?php
class G_Schedule_Helper {
	
	/*
		returns array from G_Schedule_Finder
	*/
	public static function getCurrentEmployeeSchedule(IEmployee $e) {
		$date = Tools::getGmtDate('Y-m-d');	
		$s = G_Schedule_Finder::findActiveByEmployee($e, $date);
		if ($s) {
			$schedule_group_id = $s->getScheduleGroupId();
			$schedules = G_Schedule_Finder::findAllByScheduleGroupId($schedule_group_id);
		} else {
			$schedules = G_Schedule_Finder::findAllDefault();
		}
		return $schedules;
	}
	
	/*
		$schedules - Returned value from G_Schedule_Finder
	*/
	public static function showSchedules($schedules) {
		$string = '<div class="styled_items_holder"><ul>';
		foreach ($schedules as $schedule) {
			$string .= '<li><div class="item-detail-styled"><i class="icon-time icon-fade vertical-middle"></i> ';
			$string .= '<strong>'. $schedule->getWorkingDays() . '</strong>';
			$string .= ' ('. Tools::timeFormat($schedule->getTimeIn()) .' - '. Tools::timeFormat($schedule->getTimeOut()).')';	
			$string .= '</div></li>';
		}
		$string .= '</div></ul>';
        		
		return $string;
	}

	public static function isGroupAlreadyAssigned(IGroup $g, G_Schedule $s) {
		define('ENTITY_GROUP', 2);
		$sql = "
			SELECT COUNT(*) as total
			FROM g_employee_group_schedule s
			WHERE s.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND s.schedule_id = ". Model::safeSql($s->getId()) ."
			AND s.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return ($row['total'] > 0) ? true : false;
	}
	
	public static function isEmployeeAlreadyAssigned(IEmployee $e, G_Schedule $s) {
		define('ENTITY_EMPLOYEE', 1);
		$sql = "
			SELECT COUNT(*) as total
			FROM g_employee_group_schedule s
			WHERE s.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND s.schedule_id = ". Model::safeSql($s->getId()) ."
			AND s.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return ($row['total'] > 0) ? true : false;
	}
	
	public static function countMembers(G_Schedule $s) {
		$sql = "
			SELECT COUNT(*) as total
			FROM g_employee_group_schedule s
			WHERE s.schedule_id = ". Model::safeSql($s->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function mergeByName(G_Schedule $schedule) {			
		$schedules = array();
		foreach ($schedule as $s) {
			$schedules[$s->getName()][] = $s;
		}
		return $schedules;
	}
	
	/*
		$schedules = G_Schedule_Finder::findAllByName($schedule_name);
		$times = G_Schedule_Helper::getTimeInAndOutByDay('mon', $schedules);
		echo $times['in'];
		echo $times['out'];
	*/
	public static function getTimeInAndOutByDay($day, $schedules) {
		foreach ($schedules as $s) {
			$working_days = $s->getWorkingDays();
			$days = explode(',', $working_days);
			if (in_array($day, $days)) {
				$return['in'] = $s->getTimeIn();
				$return['out'] = $s->getTimeOut();
				return $return;
			}			
		}
		return false;
	}
	
	public static function isScheduleEmpty() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SCHEDULE . " s			
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDataByScheduleGroupId( $schedule_group_id = 0, $fields = array() ) {
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_SCHEDULE . "
			WHERE schedule_group_id =" . Model::safeSql($schedule_group_id) . "
			ORDER BY id desc
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function getDefaultSchedule() {
		$sql = "
			SELECT gs.schedule_name, gs.grace_period, gs.working_days, gs.time_in, gs.time_out, gs.is_default 
			FROM " . G_SCHEDULE . " gs 
			WHERE gs.is_default = 1				
		";		
		$result = Model::runSql($sql);
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['schedule_name'] = $row['schedule_name'];
			$return[$counter]['working_days']  = $row['working_days'];
			$return[$counter]['grace_period']  = $row['grace_period'];
			$return[$counter]['time_in'] 	   = $row['time_in'];
			$return[$counter]['time_out']      = $row['time_out'];
			$counter++;
		}
		return $return;
	}
	
	public static function loadArrayEmployeeSchedule($eArray) {		
		if(!empty($eArray)){
			$e_array = array();
			foreach($eArray as $eid){				
				$e = G_Employee_Finder::findById($eid);				
				if($e){
					$ss = G_Employee_Group_Schedule_Helper::getAllScheduleByEmployeeGroupId($e->getId());
					if($ss){
						$e_array[$e->getId()] = $ss;
					}										
				}else{
				
				}
			}
		}else{
		
		}
		return $e_array;
	}
}
?>