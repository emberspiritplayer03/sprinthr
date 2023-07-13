<?php
class G_Employee_Deductions extends Employee_Deductions {
	public $is_archive;
	public $date_created;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	const PENDING  = 'Pending';
	const APPROVED = 'Approved';
	
	public function __construct() {
		
	}
	
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}

    public function isApproved() {
        if ($this->status == self::APPROVED) {
            return true;
        } else {
            return false;
        }
    }

    public function isArchived() {
        if ($this->is_archive == self::YES) {
            return true;
        } else {
            return false;
        }
    }

    public function isApplyToAllEmployees() {
        if ($this->apply_to_all_employee == self::YES) {
            return true;
        } else {
            return false;
        }
    }
	
	public function getRemarks() {
		return $this->remarks;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
		
	public function save() {		
	
		return G_Employee_Deductions_Manager::save($this);
	}	
	
	public function approve() {
		G_Employee_Deductions_Helper::approve($this);
	}
	
	public function disapprove() {		
		G_Employee_Deductions_Helper::disapprove($this);
	}
		
	public function pending() {		
		return G_Employee_Deductions_Manager::pending($this);
	}
	
	public function archive() {		
		G_Employee_Deductions_Helper::archive($this);
	}
	
	public function restore_archived() {		
		G_Employee_Deductions_Manager::restore_archived($this);
	}
	
	public function delete() {
		G_Employee_Deductions_Manager::delete($this);
	}
}
?>