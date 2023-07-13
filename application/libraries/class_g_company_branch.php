<?php
class G_Company_Branch {
	public $id;
	public $company_structure_id;
	public $name;	
	public $province;
	public $city;
	public $address;
	public $zip_code;
	public $phone;
	public $fax;
	public $location_id;
	public $is_archive;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	//objects
	protected $gcs;
		
	public function __construct($id) {
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
	
	public function setProvince($value) {
		$this->province = $value;
	}
	
	public function getProvince() {
		return $this->province;
	}
	
	public function setCity($value) {
		$this->city = $value;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setAddress($value) {
		$this->address = $value;
	}
	
	public function getAddress($value) {
		return $this->address;
	}
	
	public function getZipCode() {
		return $this->zip_code;
	}
	
	public function setZipCode($value) {
		$this->zip_code = $value;
	}
	public function getPhone() {
		return $this->phone;
	}
	
	public function setPhone($value) {
		$this->phone = $value;
	}
	public function getFax() {
		return $this->fax;
	}
	
	public function setFax($value) {
		$this->fax = $value;
	}
	public function getLocationId() {
		return $this->location_id;
	}
	
	public function setLocationId($value) {
		$this->location_id = $value;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	public function getIsArchive() {
		return $this->is_archive;
	}
			
	public function save (G_Company_Structure $gcs) {
		return G_Company_Branch_Manager::save($this, $gcs);
	}
	
	public function archive() {
		G_Company_Branch_Manager::archive($this);
	}
	
	public function restore() {
		G_Company_Branch_Manager::restore($this);
	}
	
	public function delete() {
		G_Company_Branch_Manager::delete($this);
	}
	
	public function addEmployee(G_Employee $e,$start_date,$end_date = '') {
		G_Company_Branch_Manager::addEmployee($this, $e,$start_date,$end_date);	
	}
}
?>