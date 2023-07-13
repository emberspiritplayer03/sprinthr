<?php
class G_Break_Time_Schedule extends Break_Time_Schedule {
	const YES = 1;
	const NO  = 0;

	public function __construct() {
		
	}

	private function checkIfNotEmpty( $obj_variables = array() ) {
		foreach( $obj_variables as $variable ){
			if( trim($this->{$variable}) == '' ){
				return false;
			}
		}

		return true;
	}

	private function isTimeInGreaterThanTimeOut( $time_in = '', $time_out = ''){
		$is_greater_than = true;

		if( strtotime($time_in) <= $strtotime($time_out) ){
			$is_greater_than = false;
		}

		return $is_greater_than;
	}

	/*
		Usage : 
		$schedule_time_in  = "12:00:00";
		$schedule_time_out = "13:00:00";
		$break_time = new G_Break_Time_Schedule();
		$break_time->setScheduleIn($schedule_time_in);
		$break_time->setScheduleOut($schedule_time_out);
		$break_time_schedules = $break_time->getBreakTimeBySchedules(); //Returns array
	*/
					
	public function getBreakTimeBySchedules($fields = array(), $order_by = ''){
		$data = array();

		if( $this->schedule_in != '' && $this->schedule_out ){
			
			if( empty($fields) ){
				$fields = array('break_in','break_out','total_hrs_break','to_deduct');
			}

			if( trim($order_by) == '' ){
				$order_by = 'break_in ASC';
			}

			$data = G_Break_Time_Schedule_Helper::sqlAllScheduledBreakTimeByScheduleInOut($this->schedule_in, $this->schedule_out, $fields, $order_by);
		}
		
		return $data;
	}	

	/*
		Usage : 
		$schedule_time_in  = "12:00:00";
		$schedule_time_out = "13:00:00";
		$break_time = new G_Break_Time_Schedule();
		$break_time->setScheduleIn($schedule_time_in);
		$break_time->setScheduleOut($schedule_time_out);
		$total_hrs_deductible = $break_time->getTotalBreakTimeHrsDeductible(); //Returns integer
	*/

	public function getTotalBreakTimeHrsDeductible() {		
		$data   = self::getBreakTimeBySchedules();
		$total_hrs_to_deduct = 0;

		if( $data ){			
			foreach( $data as $value ){
				$to_deduct = $value['to_deduct'];
				$num_hrs   = $value['total_hrs_break'];
				if( $to_deduct == G_Break_Time_Schedule::YES ){
					$total_hrs_to_deduct += $num_hrs;
				}
			}
		}

		return $total_hrs_to_deduct;
	}

	public function checkIfWithConflictSchedule() {
		$is_with_conflict = true;

		$required_variables = array('break_in','break_out');
		if( self::checkIfNotEmpty($required_variables) ){
			if( Tools::isTime1LessThanTime2($this->break_in, $this->break_out) && Tools::isTime1LessThanTime2($this->schedule_in, $this->schedule_out) ){
				$total_conflict_schedules = G_Break_Time_Schedule_Helper::sqTotalConflictBreakSchedule($this->break_in, $this->break_out,$this->schedule_in, $this->schedule_out);

				if( $total_conflict_schedules <= 0 ){
					$is_with_conflict = false;					
				}
			}
		}

		return $is_with_conflict;
	}

	public function addBreakInSchedule() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save record';

		$required_variables = array('break_in','break_out','schedule_in','schedule_out');		
		if( self::checkIfNotEmpty($required_variables) ){			
			if( !self::checkIfWithConflictSchedule() ){				
				$this->total_hrs_break = Tools::computeHoursDifference($this->break_in, $this->break_out);
				$is_success = self::save();
				if( $is_success ){					
					$return['is_success'] = true;
					$return['message'] = "Record saved";
				}
			}else{
				$return['message'] = "Conflict break schedule.";
			}			
		}

		return $return;
	}

	public function save() {
		return G_Break_Time_Schedule_Manager::save($this);
	}
}
?>