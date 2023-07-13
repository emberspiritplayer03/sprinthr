<?php
class G_Company_Info {
	protected $id;
	protected $company_structure_id;
	protected $address;	
	protected $phone;
	protected $fax;
	protected $address1;
	protected $city;
	protected $address2;
	protected $state;
	protected $zip_code;
	protected $remarks;
	protected $sss_number;
	protected $philhealth_number;
	protected $pagibig_number;
	protected $tin_number;
	protected $company_logo;

	//objects
	protected $gcs;
	
	public function __construct($csid) {
		$this->company_structure_id = $csid;
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
	
	public function setAddress($value) {
		$this->address = $value;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setPhone($value) {
		$this->phone = $value;
	}
	
	public function getPhone() {
		return $this->phone;
	}
	
	public function setFax($value) {
		$this->fax = $value;
	}
	
	public function getFax() {
		return $this->fax;
	}
	
	public function setState($value) {
		$this->state = $value;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function getAddress1() {
		return $this->address1;
	}
	
	public function setAddress1($value) {
		$this->address1 = $value;
	}	
	
	public function setCity($value) {
		$this->city = $value;
	}
	
	public function getCity() {
		return $this->city;
	}	
	
	public function setAddress2($value) {
		$this->address2 = $value;
	}
	
	public function setZipCode($value) {
		$this->zip_code = $value;
	}
	
	public function getZipCode() {
		return $this->zip_code;
	}	
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}	
	
	public function setSssNumber($value) {
		$this->sss_number = $value;
	}
	
	public function getSssNumber() {
		return $this->sss_number;
	}	
	
	public function setPhilHealthNumber($value) {
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
	
	public function setTinNumber($value) {
		$this->tin_number = $value;
	}
	
	public function getTinNumber() {
		return $this->tin_number;
	}	
	
	
	public function setCompanyLogo($value) {
		$this->company_logo = $value;
	}
	
	public function getCompanyLogo() {
		return $this->company_logo;
	}	
			
	public function save(G_Company_Structure $gcs) {		
		return G_Company_Info_Manager::save($this,$gcs);
	}
	
	public function delete() {
		return G_Company_Info_Manager::delete($this);
	}
}
?>