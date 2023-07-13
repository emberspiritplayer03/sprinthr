<?php
class G_Employee_Undertime_Request extends Employee_Undertime_Request {
	
	public $company_structure_id;
	public $is_approved;
	public $created_by;
	public $is_archive;
	
	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES = 'Yes';
	const NO  = 'No';
			
	public function __construct() {
		
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setIsApproved($value) {
		$this->is_approved = $value;
	}
	
	public function getIsApproved() {
		return $this->is_approved;
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
	
	public function save() {		
		return G_Employee_Undertime_Request_Manager::save($this);
	}
	
	public function approve() {		
		return G_Employee_Undertime_Request_Manager::approve($this);
	}
	
	public function disapprove() {		
		return G_Employee_Undertime_Request_Manager::disapprove($this);
	}
	
	public function delete() {
		return G_Employee_Undertime_Request_Manager::delete($this);
	}
}
?>