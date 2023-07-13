<?php
class G_Incentive_Leave_History extends Incentive_Leave_History {

	public $employees_incentive_leave = array();

	public function __construct() {
		
	}

	/**
	* Process incentive leave
	*	
	* @return instance of object
	*/
	public function process() {
		if( $this->month_number > 0 && $this->year > 0 ){
			$year  = $this->year;
			$month = $this->month_number;

			$from = "{$year}-{$month}-01";
			$to   = date("Y-m-t",strtotime($from));

			$att   = G_Attendance_Helper::perfectAttendanceDataByDateRange($from, $to);		

			$id = G_Incentive_Leave_History_Helper::isMonthNumberAndYearExists($this->month_number, $this->year);
			if( $id > 0 ){
				$this->id = $id;
			}

			$total_incentive_leave = count($att);
			$this->total_given     = $total_incentive_leave;
			$this->date_process    = date("Y-m-d H:i:s");			
			self::save();

			$this->employees_incentive_leave = $att;
		}

		return $this;
	}

	/**
	 * Add to employee leave credits
	 *
	 * @param array leave_data
	 * @return array
	*/
	public function addToCredits( $leave_data = array() ) {
		$return = array(
			'is_success' => false,
			'message' => 'No data processed'
		);

		if( !empty($leave_data) ){
			$this->employees_incentive_leave = $leave_data;
		}

		if( !empty($this->employees_incentive_leave) ){
			$count = 0;			
			foreach( $this->employees_incentive_leave as $el ){
				$credit = new G_Employee_Leave_Available();
				$credit->setEmployeeId($el['id']);
				$credit->setLeaveId(11);
				$r = $credit->addLeaveCredits(1);
				$count++;

				if($r['is_success']) {
					//add also on employee leave credit history
					$h = new G_Employee_Leave_Credit_History();
					$h->setEmployeeId(Utilities::decrypt($el['id']));
					$h->setLeaveId(11);
					$h->setCreditsAdded(1);
					$h->addToHistory();	
				}			
			} 

			$return = array(
				'is_success' => true,
				'message' => "Total incentive leave processed {$count}"
			);
		}

		return $return;
	}
	
	public function save() {		
		return G_Incentive_Leave_History_Manager::save($this);
	}
	
	public function delete() {
		return G_Incentive_Leave_History_Manager::delete($this);
	}
}
?>