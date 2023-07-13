<?php
class G_Custom_Overtime extends Custom_Overtime {
	
	const DAY_TYPE_HOLIDAY = 'Holiday';
	const DAY_TYPE_RESTDAY = 'Restday';
	const APPROVED = 'Approved';
	const PENDING  = 'Pending';
	const DISAPPROVED = 'Disapproved';	
	
	public function __construct() {
		
	}

	public function setAsApproved() {
		$this->status = self::APPROVED;
	}

	public function disapprove() {
		$this->status = self::DISAPPROVED;
		$this->save();
	}

	public function approve() {
		$this->status = self::APPROVED;
		$this->save();
	}
		
	public function save() {		
		return G_Custom_Overtime_Manager::save($this);
	}
	
	public function delete() {
		return G_Custom_Overtime_Manager::delete($this);
	}
	
}
?>