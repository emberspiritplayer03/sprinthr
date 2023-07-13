<?php
class Attendance {
	
	protected $id;
	protected $date;
	protected $is_paid = false;
	protected $is_present = false;
	protected $is_restday = false;
	protected $is_suspended = false;
	protected $is_holiday = false;
    protected $is_ob = false;
	protected $holiday_type;
	protected $is_leave = false;
	protected $leave_id;
	
	protected $holiday;
	protected $timesheet;

	protected $project_site_id;
		
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setAsPaid() {
		$this->is_paid = true;	
	}
	
	public function setAsNotPaid() {
		$this->is_paid = false;	
	}	
	
	public function isPaid() {
		return $this->is_paid;	
	}
	
	public function setAsPresent() {
		$this->is_present = true;	
	}
	
	public function setAsAbsent() {
		$this->is_present = false;	
	}	
	
	public function isPresent() {
		return $this->is_present;	
	}
	
	public function setAsRestday() {
		$this->is_restday = true;	
	}
	
	public function setAsNotRestday() {
		$this->is_restday = false;	
	}	
	
	public function isRestday() {
		return $this->is_restday;	
	}
	
	public function setAsHoliday() {
		$this->is_holiday = true;	
	}
	
	public function setAsNotHoliday() {
		$this->is_holiday = false;	
	}

    public function setAsOfficialBusiness() {
        $this->is_ob = true;
    }

    public function setAsNotOfficialBusiness() {
        $this->is_ob = false;
    }

    public function isOfficialBusiness() {
        return $this->is_ob;
    }
	
	public function isHoliday() {
		return $this->is_holiday;	
	}
	
	public function setAsLeave() {
		$this->is_leave = true;	
	}
	
	public function setAsNotLeave() {
		$this->is_leave = false;	
	}	
	
	public function isLeave() {
		return $this->is_leave;	
	}	
	
	public function setHolidayType($value) {
		$this->holiday_type = $value;	
	}
	
	public function getHolidayType() {
		return $this->holiday_type;	
	}	
	
	public function setLeaveId($leave_id) {
		$this->leave_id = $leave_id;
	}
	
	public function getLeaveId() {
		return $this->leave_id;	
	}
	
	public function setAsSuspended() {
		$this->is_suspended = true;
	}
	
	public function setAsNotSuspended() {
		$this->is_suspended = false;
	}	
	
	public function isSuspended() {
		return $this->is_suspended;	
	}
	
	public function setHoliday(G_Holiday $h) {
		$this->holiday = $h;
	}
	
	public function getHoliday() {
		return $this->holiday;	
	}
	
	public function setTimesheet($value) {
		$this->timesheet = $value;	
	}
	
	public function getTimesheet() {
		return $this->timesheet;	
	}

	public function setProjectSiteId($value){
		$this->project_site_id = $value;
	}

	public function getProjectSiteId(){
		return $this->project_site_id;
	}
	
}
?>