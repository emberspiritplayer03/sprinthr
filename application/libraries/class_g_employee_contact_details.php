<?php
class G_Employee_Contact_Details {
	
	public $id;
	public $employee_id;
	public $address;
	public $city;
	public $province;
	public $zip_code;
	public $country;
	public $home_telephone;
	public $mobile;
	public $work_telephone;
	public $work_email;
	public $other_email;

	
	function __construct($id = '') {
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
	
	public function setAddress($value) {
		$this->address = $value;	
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setCity($value) {
		$this->city = $value;	
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setProvince($value) {
		$this->province = $value;	
	}
	
	public function getProvince() {
		return $this->province;
	}
	
	public function setZipCode($value) {
		$this->zip_code = $value;	
	}
	
	public function getZipCode() {
		return $this->zip_code;
	}
	
	public function setCountry($value) {
		$this->country = $value;	
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setHomeTelephone($value) {
		$this->home_telephone = $value;	
	}
	
	public function getHomeTelephone() {
		return $this->home_telephone;
	}
	
	public function setMobile($value) {
		$this->mobile = $value;	
	}
	
	public function getMobile() {
		return $this->mobile;
	}
	
	public function setWorkTelephone($value) {
		$this->work_telephone = $value;	
	}
	
	public function getWorkTelephone() {
		return $this->work_telephone;
	}
	
	public function setWorkEmail($value) {
		$this->work_email = $value;	
	}
	
	public function getWorkEmail() {
		return $this->work_email;
	}
	
	public function setOtherEmail($value) {
		$this->other_email = $value;	
	}
	
	public function getOtherEmail() {
		return $this->other_email;
	}
	
	public function save() {
		return G_Employee_Contact_Details_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Contact_Details_Manager::delete($this);
	}
}

?>