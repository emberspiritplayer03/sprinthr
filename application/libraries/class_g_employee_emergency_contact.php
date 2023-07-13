<?php
class G_Employee_Emergency_Contact {
	
	public $id;
	public $employee_id;
	public $person;
	public $relationship;
	public $home_telephone;
	public $mobile;
	public $work_telephone;
	public $address;


	
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
	
	public function setPerson($value) {
		$this->person = $value;	
	}
	
	public function getPerson() {
		return $this->person;
	}
	
	public function setRelationship($value) {
		$this->relationship = $value;	
	}
	
	public function getRelationship() {
		return $this->relationship;
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
	
	public function setAddress($value) {
		$this->address = $value;	
	}
	
	public function getAddress() {
		return $this->address;
	}
	
		
	public function save() {
		return G_Employee_Emergency_Contact_Manager::save($this);
	}

	/*
		Note:
		Array Structure :
		Array
		(
		    [0] => (57,'Sisa','Mother','Blk1 lot 11, Golden City',0234323,3333,'')
		    [1] => (57,'Basilio','Father','Blk1 lot 11, Golden City',231121,56435,3342)
		)
	*/

	public function bulkInsert( $data = array() ) {
		return G_Employee_Emergency_Contact_Manager::bulkInsert($data);
	}
	
	public function delete() {
		return G_Employee_Emergency_Contact_Manager::delete($this);
	}
}

?>