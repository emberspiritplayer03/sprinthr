<?php
class G_Leave {
    const NAME_SICK = 'Sick Leave';
    const NAME_VACATION = 'Vacation Leave';
    const NAME_BEREAVEMENT = 'Bereavement Leave';
    const NAME_MATERNITY = 'Maternity Leave';
    const NAME_EMERGENCY = 'Emergency Leave';
    const NAME_PATERNITY = 'Paternity Leave';

    const ID_SICK = 1;
    const ID_VACATION = 2;
    const ID_BEREAVEMENT = 3;
    const ID_MATERNITY = 4;
    const ID_EMERGENCY = 5;
    const ID_PATERNITY = 6;

    const TYPE_SICK = 'sick';
    const TYPE_VACATION = 'vacation';
    const TYPE_BEREAVEMENT = 'bereavement';
    const TYPE_MATERNITY = 'maternity';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_PATERNITY = 'paternity';

	public $id;
	public $company_structure_id;
	public $name;
	public $default_credit;
	public $is_paid;
	public $is_archive;
    public $type;
    public $is_default = 'No';
    public $leave_array; 
    protected $new_leave_type;
	
	const YES = 'Yes';
	const NO  = 'No';

    const IS_DEFAULT_YES = 'Yes';
    const IS_DEFAULT_NO = 'No';

	function __construct($id = '') {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;
	}

    public function setType($value) {
        $this->type = $value;
    }

    public function getType() {
        return $this->type;
    }
	
	public function setDefaultCredit($value) {
		$this->default_credit = $value;	
	}
	
	public function getDefaultCredit() {
		return $this->default_credit;
	}
	
	public function setIsPaid($value) {
		$this->is_paid = $value;	
	}
	
	public function getIsPaid() {
		return $this->is_paid;
	}

    public function setIsDefault($value) {
        $this->is_default = $value;
    }

    public function getIsDefault() {
        return $this->is_default;
    }

    public function isDefault() {
        if ($this->is_default == self::IS_DEFAULT_YES) {
            return true;
        } else if ($this->is_default == self::IS_DEFAULT_NO) {
            return false;
        }
    }
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}	

	public function setLeaveArray($value) {
		$this->leave_array = $value;
		return $this;
	}

	public function getLeaveArray() {
		return $this->leave_array;
	}	

	public function removeDuplicates() {
		$leave_type_array = $this->leave_array;
		$leave_type_set   = $leave_type_array[0];

		foreach($leave_type_set as $l_data) {
			$leave_type = ucwords($l_data);
			$is_exist = G_Leave_Helper::isNameExist($leave_type);
			if($is_exist <= 0) {
				$a_new_leave_type[] = $leave_type;
			}
			
		}

		$this->new_leave_type = $a_new_leave_type;
		return $this;		
	}

	public function bulkSave() {
		G_Leave_Manager::bulkInsertData($this, $this->new_leave_type);
	}
	
	public function save() {
		return G_Leave_Manager::save($this);
	}
	
	public function delete() {
		return G_Leave_Manager::delete($this);
	}
}

?>