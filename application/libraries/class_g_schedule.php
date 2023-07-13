<?php
class G_Schedule extends Schedule {
	
	protected $public_id;
	protected $is_default = false;
	protected $grace_period;
	protected $schedule_group_id;
	protected $date_start;
	protected $date_end;
	
	const IS_DEFAULT = 1;
	
	public function __construct() {
		
	}
	
	public function setPublicId($value) {
		$this->public_id = $value;
	}

	public function getPublicId() {
		return $this->public_id;
	}
	
	public function setGracePeriod($value) {
		$this->grace_period = $value;	
	}
	
	public function getGracePeriod() {
		return $this->grace_period;	
	}
	
	public function setScheduleGroupId($value) {
		$this->schedule_group_id = $value;	
	}
	
	public function getScheduleGroupId() {
		return $this->schedule_group_id;	
	}	

	public function setDateStart($value) {
		$this->date_start = $value;
	}

	public function getDateStart() {
		return $this->date_start;
	}

	public function setDateEnd($value) {
		$this->date_end = $value;
	}

	public function getDateEnd() {
		return $this->date_end;
	}		
	
	/*
	* 	Save schedule to database
	*
		Usage:
		$s = new G_Schedule;
		$s->setName('My Schedule');
		$s->setWorkingDays('mon,wed,fri');
		$s->setTimeIn('09:00:00');
		$s->setTimeOut('18:00:00');
		$id = $s->save();
		$s = G_Schedule_Finder::findById($id);	
	*/	
	public function save() {
		return G_Schedule_Manager::save($this);	
	}
	
	/*
		This is used to save a schedule to Schedule Group
	
		Usage:
		$group = G_Schedule_Group_Finder::findById(1);
		$sched = G_Schedule_Finder::findById(1);
		$sched->saveToScheduleGroup($group);
		
		Variables		
		$schedule_group - Instance of G_Schedule_Group class
	*/
	public function saveToScheduleGroup($schedule_group) {
		return G_Schedule_Manager::saveToScheduleGroup($schedule_group, $this);
	}

	/*
	* 	Assign schedule to employee
	*
		Usage:
		$e = G_Employee_Finder::findById(2);
		$sc = G_Schedule_Finder::findById(61);
		$sc->assignToEmployee($e, '2011-10-25', '2011-10-30');
	*/		
	public function assignToEmployee(IEmployee $e, $start_date, $end_date) {
		return G_Schedule_Manager::assignToEmployee($e, $this, $start_date, $end_date);
	}
	
	/*
	* 	Assign schedule to group
	*
		Usage:
		$g = G_Group_Finder::findById(6);
		$sc = G_Schedule_Finder::findById(61);
		$sc->assignToGroup($g, '2011-10-25', '2011-10-30');
	*/
	public function assignToGroup(IGroup $g, $start_date, $end_date) {
		return G_Schedule_Manager::assignToGroup($g, $this, $start_date, $end_date);
	}

    public function assignToEmployeeWithGroup(IGroup $g, $start_date, $end_date) {
		return G_Schedule_Manager::assignToEmployeeWithGroup($g, $this, $start_date, $end_date);
	}
	
	public function removeEmployee(IEmployee $e) {
		return G_Schedule_Manager::removeEmployee($e, $this);
	}
	
	public function removeGroup(IGroup $g) {
		return G_Schedule_Manager::removeGroup($g, $this);	
	}
	
	public function deleteSchedule() {
		return G_Schedule_Manager::deleteSchedule($this);	
	}
	
	public function setDefaultSchedule() {
		return G_Schedule_Manager::setDefaultSchedule($this);	
	}
	
	public function countMembers() {
		return G_Schedule_Helper::countMembers($this);
	}
	
	public function isDefault() {
		return ($this->is_default) ? true : false;
	}
	
	public function setAsDefault() {
		$this->is_default = true;
	}
	
	public function loadArrayEmployeeSchedule($eArray) {
		return G_Schedule_Helper::loadArrayEmployeeSchedule($eArray);	
	}
}
?>