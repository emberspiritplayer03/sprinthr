<?php
class G_Applicant_License {
	
	public $id;
	public $applicant_id;
	public $license_type;
	public $license_number;
	public $issued_date;
	public $expiry_date;
	
	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setApplicantId($value) {
		$this->applicant_id = $value;	
	}
	
	public function getApplicantId() {
		return $this->applicant_id;
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
	
		
	public function save() {
		return G_Applicant_License_Manager::save($this);
	}
	
	public function delete() {
		return G_Applicant_License_Manager::delete($this);
	}
}

?>