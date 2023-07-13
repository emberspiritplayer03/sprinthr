<?php
class G_Settings_Subdivision_Type {
	public $id;
	public $company_structure_id;
	public $type;	
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
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function save (G_Company_Structure $gcs) {
		return G_Settings_Subdivision_Type_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Settings_Subdivision_Type_Manager::delete($this);
	}
}
?>