<?php
class G_User_Group {
	
	public $id;
	public $company_structure_id;
	public $group_name;
	public $description;
	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setGroupName($value) {
		$this->group_name= $value;	
	}
	
	public function getGroupName() {
		return $this->group_name;
	}
	
	public function setDescription($value) {
		$this->description= $value;	
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function save() {
		return G_User_Group_Manager::save($this);
	}
	
	public function delete() {
		return G_User_Group_Manager::delete($this);
	}
}

?>