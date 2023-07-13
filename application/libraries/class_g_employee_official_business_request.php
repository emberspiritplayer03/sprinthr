<?php
class G_Employee_Official_Business_Request extends Employee_Official_Business_Request {
	
	public $is_approved;
	public $created_by;
	public $is_whole_day;
	public $time_start;
	public $time_end;
	public $is_archive = self::NO;

	const STATUS_PENDING     = 'Pending';
	const STATUS_APPROVED    = 'Approved';
	const STATUS_DISAPPROVED = 'Disapproved';
	
	public function __construct() {
		
	}
	
	public function setIsApproved($value) {
		$this->is_approved = $value;
	}
	
	public function getIsApproved() {
		return $this->is_approved;
	}

    public function isApproved() {
        if ($this->is_approved == self::STATUS_APPROVED) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setCreatedBy($value) {
		$this->created_by = $value;
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}

		//new columns for ob time based
	public function setWholeDay($value) {
		$this->is_whole_day = $value;
	}
	
	public function getWholeDay() {
		return $this->is_whole_day;
	}

	public function setTimeStart($value) {
		$this->time_start = $value;
	}
	
	public function getTimeStart() {
		return $this->time_start;
	}

	public function setTimeEnd($value) {
		$this->time_end = $value;
	}
	
	public function getTimeEnd() {
		return $this->time_end;
	}

	//end new columns

	
	public function save() {		
		return G_Employee_Official_Business_Request_Manager::save($this);
	}
	
	public function approve() {		
		return G_Employee_Official_Business_Request_Helper::approve($this);
	}

	public function hr_disapprove() {		
		return G_Employee_Official_Business_Request_Helper::hr_disapprove($this);
	}
	
	public function disapprove() {		
		return G_Employee_Official_Business_Request_Helper::disapprove($this);
	}
	
	public function archive() {		
		return G_Employee_Official_Business_Request_Manager::archive($this);
	}
	
	public function restore_archived() {		
		return G_Employee_Official_Business_Request_Manager::restore_archived($this);
	}
	
	public function delete() {
		return G_Employee_Official_Business_Request_Manager::delete($this);
	}
}
?>