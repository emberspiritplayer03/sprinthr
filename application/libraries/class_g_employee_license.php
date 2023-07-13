<?php
class G_Employee_License {
	
	public $id;
	public $employee_id;
	public $license_type;
	public $license_number;
	public $issued_date;
	public $expiry_date;
	public $notes;
	
	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setLicenseType($value) {
		$this->license_type = $value;	
	}
	
	public function getLicenseType() {
		return $this->license_type;
	}
	
	public function setLicenseNumber($value) {
		$this->license_number = $value;	
	}
	
	public function getLicenseNumber() {
		return $this->license_number;
	}
	
	public function setIssuedDate($value) {
		$this->issued_date = $value;	
	}
	
	public function getIssuedDate() {
		return $this->issued_date;
	}
	
	public function setExpiryDate($value) {
		$this->expiry_date = $value;	
	}
	
	public function getExpiryDate() {
		return $this->expiry_date;
	}
	
	public function setNotes($value) {
		$this->notes = $value;	
	}
	
	public function getNotes() {
		return $this->notes;
	}
	
		
	public function save() {
		return G_Employee_License_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_License_Manager::delete($this);
	}
}

?>