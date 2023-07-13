<?php
class G_Schedule_Template extends Schedule_Template {
	protected $public_id;
	protected $is_default = false;
	
	const IS_DEFAULT = 1;
	
	public function __construct() {
		
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
		return G_Schedule_Template_Manager::save($this);	
	}

	public function saveStaggered() {
		return G_Schedule_Template_Manager::saveStaggered($this);	
	}

	public function saveCompress() {
		return G_Schedule_Template_Manager::saveCompress($this);	
	}

	public function saveShiftAm() {
		return G_Schedule_Template_Manager::saveShiftAm($this);	
	}

	public function saveShiftPm() {
		return G_Schedule_Template_Manager::saveShiftPm($this);	
	}

	public function saveFlexible() {
		return G_Schedule_Template_Manager::saveFlexible($this);	
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
		return G_Schedule_Template_Manager::saveToScheduleGroup($schedule_group, $this);
	}

	/*
	* 	Assign schedule to employee
	*
		Usage:
		$e = G_Employee_Finder::findById(2);
		$sc = G_Schedule_Finder::findById(61);
		$sc->assignToEmployee($e, '2011-10-25', '2011-10-30');
	*/		
	public function assignToEmployee(IEmployee $e, $employe_already_assigned, $date) {
		return G_Schedule_Template_Manager::assignToEmployee($e, $this, $employe_already_assigned, $date);
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
		return G_Schedule_Template_Manager::assignToGroup($g, $this, $start_date, $end_date);
	}

    public function assignToEmployeeWithGroup(IGroup $g, $start_date, $end_date) {
		return G_Schedule_Template_Manager::assignToEmployeeWithGroup($g, $this, $start_date, $end_date);
	}
	
	public function removeEmployee(IEmployee $e) {
		return G_Schedule_Template_Manager::removeEmployee($e, $this);
	}
	
	public function removeGroup(IGroup $g) {
		return G_Schedule_Template_Manager::removeGroup($g, $this);	
	}
	
	public function deleteSchedule() {
		return G_Schedule_Template_Manager::deleteSchedule($this);	
	}
	
	public function setDefaultSchedule() {
		return G_Schedule_Template_Manager::setDefaultSchedule($this);	
	}
	
	public function countMembers() {
		return G_Schedule_Template_Helper::countMembers($this);
	}
	
	public function isDefault() {
		return ($this->is_default) ? true : false;
	}
	
	public function setAsDefault() {
		$this->is_default = true;
	}
	
	public function loadArrayEmployeeSchedule($eArray) {
		return G_Schedule_Template_Helper::loadArrayEmployeeSchedule($eArray);	
	}
}
?>