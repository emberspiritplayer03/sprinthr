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
class G_Eeo_Job_Category {
	public $id;
	public $company_structure_id;
	public $category_name;
	public $description;

	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setCategoryName($value) {
		$this->category_name = $value;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}

	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getCategoryName() {
		return $this->category_name;
	}
	
	public function getDescription() {
		return $this->description;
	}
				
	public function save() {
		return G_Eeo_Job_Category_Manager::save($this);
	}
	
	public function delete() {
		G_Eeo_Job_Category_Manager::delete($this);
	}
	
}
?>