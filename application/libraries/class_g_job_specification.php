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
class G_Job_Specification {
	public $id;
	public $company_structure_id;
	public $name;
	public $description;
	public $duties;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function setDuties($value) {
		$this->duties = $value;
	}
	
	
	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getDuties() {
		return $this->duties;
	}
	
	public function save() {
		return G_Job_Specification_Manager::save($this);
	}
	
	public function delete() {
		G_Job_Specification_Manager::delete($this);
	}
	
}
?>