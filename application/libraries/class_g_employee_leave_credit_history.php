<?php
class G_Employee_Leave_Credit_History extends Employee_Leave_Credit_History {

	public function __construct() {
		
	}

	/**
	 * Add leave credit to history
	 * 
	 * @return array return
	*/
	public function addToHistory() {
		$return = array('is_success' => false, 'message' => 'Cannot save record');

		if( $this->employee_id > 0 && $this->leave_id > 0 ){
			$is_leave_id_exists = G_Leave_Helper::isLeaveIdExists($this->leave_id);
			if( $is_leave_id_exists ){				
				$this->date_added = date("Y-m-d H:i:s");				
				$id = $this->save();				
				if( $id > 0 ){
					$return = array('is_success' => true, 'message' => 'Leave was successfully added to history');
				}
			}
		}

		return $return;
	}
	
	public function save() {		
		return G_Employee_Leave_Credit_History_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Leave_Credit_History_Manager::delete($this);
	}
}
?>