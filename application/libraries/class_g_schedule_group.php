<?php
/*
	This is used to group schedules
	
	Usage:
	$g = new G_Schedule_Group;
	$g->setName('New Schedule');
	$group_id = $g->save();
	$group = G_Schedule_Group_Finder::findById($group_id);
*/
class G_Schedule_Group extends Schedule_Group {
	protected $is_default = false;
	protected $public_id;
	protected $grace_period;
	protected $effectivity_date;
	protected $end_date;

    protected $schedules;
	
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
	
	public function setEffectivityDate($value) {
		$date_formatted = date("Y-m-d",strtotime($value));
		$this->effectivity_date = $date_formatted;	
	}
	
	public function getEffectivityDate() {
		return $this->effectivity_date;
	}

	public function setEndDate($value) {
		$date_formatted = date("Y-m-d",strtotime($value));
		$this->end_date = $date_formatted;	
	}
	
	public function getEndDate() {
		return $this->end_date;
	}
	
    /*
    *   Sets the schedules under this schedule group
    *
    *   $s = array of G_Schedule class
    */
    public function setSchedules($s) {
        $this->schedules = $s;
    }

    /*
    *   Gets the schedules under this schedule group
    *
    *   Returns the array of G_Schedule class
    */
    public function getSchedules() {
        return $this->schedules;
    }
	
	/*
	* 	Assign schedule group to employee
	*
		Usage:
		$e = Employee_Factory::get(1808);
		$group = G_Schedule_Group_Finder::findById(1);
		$group->assignToEmployee($e, '2012-08-16', '2012-08-20');
	*/		
	public function assignToEmployee(IEmployee $e, $start_date, $end_date) {
		return G_Schedule_Group_Manager::assignToEmployee($e, $this, $start_date, $end_date);
	}

    /*
    *   Assigns this schedule group to multiple employees
    *
    *   $es = array of G_Employee
    */
    public function assignToMultipleEmployees($es) {
        return G_Schedule_Group_Manager::assignToMultipleEmployees($es, $this);
    }
	
	/*
	* 	Assign schedule group to group
	*
		Usage:
		$g = G_Group_Finder::findById(6);
		$group = G_Schedule_Group_Finder::findById(1);
		$group->assignToGroup($g, '2011-10-25', '2011-10-30');
	*/
	public function assignToGroup(IGroup $g, $start_date, $end_date) {
		return G_Schedule_Group_Manager::assignToGroup($g, $this, $start_date, $end_date);
	}	
	
	public function save() {
		return G_Schedule_Group_Manager::save($this);	
	}
	
	public function removeEmployee(IEmployee $e) {
		return G_Schedule_Group_Manager::removeEmployee($e, $this);
	}
	
	public function removeGroup(IGroup $g) {
		return G_Schedule_Group_Manager::removeGroup($g, $this);	
	}
	
	public function removeEmployees() {
		$employees = G_Employee_Finder::findByScheduleGroup($this);
		foreach ($employees as $e) {
			if ($e) {
				$is_removed = $this->removeEmployee($e);
			}
		}
        return $is_removed;
	}
	
	public function removeGroups() {
		$groups = G_Group_Finder::findByScheduleGroup($this);
		foreach ($groups as $g) {
			if ($g) {
				$this->removeGroup($g);	
			}
		}
	}	
	
	public function countMembers() {
		return G_Schedule_Group_Helper::countMembers($this);
	}
	
	public function delete() {
		return G_Schedule_Group_Manager::delete($this);	
	}
	
	public function setDefaultGroup() {
		return G_Schedule_Group_Manager::setDefaultGroup($this);	
	}
	
	public function deleteSchedule() {
		$group = G_Schedule_Finder::findAllByScheduleGroup($this);
		foreach ($group as $schedule) {
			$schedule->deleteSchedule();	
		}
	}

	public function getAttachedBreaktimeSchedules(){
		$data = array();

		if( $this->id > 0 ){
			$fields   = array("time_in","time_out");
			$schedule = G_Schedule_Helper::sqlDataByScheduleGroupId($this->id, $fields);			
			if( !empty($schedule) ){
				$schedule_in  = $schedule['time_in'];
				$schedule_out = $schedule['time_out'];

				$breaktime_schedules = G_Break_Time_Schedule_Details_Helper::sqlGetAllBreaktimeSchedulesByBreakInAndOut($schedule_in, $schedule_out);
				foreach($breaktime_schedules as $schedule){
					$data[] = $schedule['break_in'] . " to " . $schedule['break_out'];
				}
			}
		}

		return $data;
	}


    /*
    *   Will delete all including members, groups, schedules, and itself
    */
    public function deleteAll() {
        $this->removeEmployees();
        $this->removeGroups();
        $this->deleteSchedule();
        $this->delete();
    }

	public function isDefault() {
		return ($this->is_default) ? true : false;
	}
	
	public function setAsDefault() {
		$this->is_default = true;
	}
}
?>