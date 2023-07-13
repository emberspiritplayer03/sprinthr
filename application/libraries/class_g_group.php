<?php
class G_Group extends Group implements IGroup {
    const TYPE_DEPARTMENT = 'Department';
    const TYPE_GROUP = 'Group';
    const GROUP_PARENT = 0;

    protected $parent_id;

	protected $description;
	
	public function __construct() {

	}

    public function setParentId($value) {
        $this->parent_id = $value;
    }

    public function getParentId() {
        return $this->parent_id;
    }

    public function isParent() {
        if ($this->parent_id == self::GROUP_PARENT) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setDescription($value) {
		$this->description = $value;	
	}
	
	public function getDescription() {
		return $this->description;	
	}

    public function setType($value) {
        $this->type = $value;
    }

    public function getType() {
        return $this->type;
    }
	
	/*
	* Save group to database
	*
	* Usage:
		$g = new Group('Gleent');
		$id = $g->save();	
	*/
	public function save() {
		//return G_Group_Manager::save($this);
	}
	
	/*
	* Add members to this group
	*
	* Usage:
		$g = Group_Factory::get(1);		
		$e = Employee_Factory::get(3);
		$e2 = Employee_Factory::get(4);
		$e3 = Employee_Factory::get(7);		
		$members[] = $e;
		$members[] = $e2;
		$members[] = $e3;
		$g->addMembers($members);	
	*
	* @param array $members Array of IEmployee
	*/
	public function addMembers($members) {
		//G_Group_Manager::addMembers($this, $members);
	}
	
	public function addEmployee($e, $date_start, $date_end) {
		return G_Group_Manager::addEmployee($this, $e, $date_start, $date_end);
	}	
	
	/*
	* Remove members to this group
	*
	* Usage:
		$g = Group_Factory::get(1);			
		$member_ids[] = 3;
		$member_ids[] = 4;
		$member_ids[] = 7;
		$g->removeMembers($member_ids);	
	*
	* @param array $members Array of IEmployee
	*/	
	public function removeMembers($member_ids) {
		//Group_Manager::removeMembers($this, $member_ids);
	}
	
	public function getMembers() {
		return $this->getEmployees();
	}

    public function getEmployees() {
        return G_Employee_Finder::findAllByGroup($this);
    }

    /*
     * Adds leave credit or days to this group
     *
     * @param object $leave Instance of G_Leave
     * @param float $number_of_days Number of days to add in leave credit
     */
    public function addLeaveCredit($leave, $number_of_days, $year = '') {
        return G_Leave_Helper::addLeaveCreditsToGroup($this, $leave, $number_of_days, $year);
    }
}
?>