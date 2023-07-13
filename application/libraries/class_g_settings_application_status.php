<?php
/*
	Usage:
		$c = new Company('Gleent');
		$c->setBusinessDescription('Web Development Company');
		$c->setBusinessAddress('Laguna');
		$c->setBusinessEmail('info@gleent.com');
		$c->setBusinessPhoneNo('5024826');
		$c->save();	
*/
class G_Settings_Application_Status {
	public $id;
	public $company_structure_id;
	public $status;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}

	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function save() {
		return G_Settings_Application_Status_Manager::save($this);
	}
	
	public function delete() {
		G_Settings_Application_Status_Manager::delete($this);
	}
	
}
?>