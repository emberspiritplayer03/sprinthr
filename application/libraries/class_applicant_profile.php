<?php
class Applicant_Profile {
	public $id;
	public $lastname;
	public $firstname;
	public $middlename;
	public $extension_name;	
	public $birthdate;	
	public $gender;
	public $marital_status;	
	public $home_telephone;
	public $mobile;
	public $birth_place;	
	public $address;
	public $city;
	public $province;
	public $zip_code;
	public $sss_number;
	public $tin_number;
	public $philhealth_number;
	public $pagibig_number;
	public $resume_name;
	public $resume_path;
	public $photo;
		
	public function __construct() {

	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setLastname($value) {
		$this->lastname = $value;
	}
	
	public function getLastname() {
		return $this->lastname;
	}
	
	public function setFirstname($value) {
		$this->firstname= $value;
	}
	
	public function getFirstname() {
		return $this->firstname;
	}
	
	public function setMiddlename($value) {
		$this->middlename = $value;
	}
	
	public function getMiddlename() {
		return $this->middlename;
	}
	
	public function setExtensionName($value) {
		$this->extension_name= $value;
	}
	
	public function getExtensionName() {
		return $this->extension_name;
	}
	
	public function setBirthdate($value) {
		$this->birthdate = $value;
	}
	
	public function getBirthdate() {
		return $this->birthdate;
	}
	
	public function setGender($value) {
		$this->gender = $value;
	}
	
	public function getGender() {
		return $this->gender;
	}
	
	public function setMaritalStatus($value) {
		$this->marital_status = $value;
	}
	
	public function getMaritalStatus() {
		return $this->marital_status;
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
	
	public function setBirthPlace($value) {
		$this->birth_place = $value;
	}
	
	public function getBirthPlace() {
		return $this->birth_place;
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

	public function setSssNumber($value) {
		$this->sss_number = $value;	
	}
	
	public function getSssNumber() {
		return $this->sss_number;	
	}

	public function setTinNumber($value) {
		$this->tin_number = $value;	
	}
	
	public function getTinNumber() {
		return $this->tin_number;	
	}
	
	public function setPhilhealthNumber($value) {
		$this->philhealth_number = $value;	
	}
	
	public function getPhilhealthNumber() {
		return $this->philhealth_number;	
	}

	public function setPagibigNumber($value) {
		$this->pagibig_number = $value;	
	}
	
	public function getPagibigNumber() {
		return $this->pagibig_number;	
	}

	public function setResumeName($value) {
		$this->resume_name = $value;
	}
	
	public function getResumeName() {
		return $this->resume_name;
	}
	
	public function setResumePath($value) {
		$this->resume_path = $value;
	}
	
	public function getResumePath() {
		return $this->resume_path;
	}
	
	public function setPhoto($value) {
		$this->photo = $value;
	}
	
	public function getPhoto() {
		return $this->photo;
	}
	
}
?>