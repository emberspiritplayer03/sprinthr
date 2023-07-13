<?php
class G_Settings_Skills {
	public $id;
	public $company_structure_id;
	public $skill;	
	//objects
	public $gcs;
		
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
	
	public function setSkill($value) {
		$this->skill = $value;
	}
	
	public function getSkill() {
		return $this->skill;
	}

	public function getAllUniqueSkills($fields = array(), $order_by = ''){
		$data = G_Settings_Skills_Helper::sqlDistinctSkills($fields, $order_by);

		return $data;
	}
	
	public function save (G_Company_Structure $gcs) {
		return G_Settings_Skills_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Settings_Skills_Manager::delete($this);
	}
}
?>