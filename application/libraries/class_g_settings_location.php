<?php
class G_Settings_Location {
	public $id;
	public $company_structure_id;
	public $code;	
	public $location;
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
	
	public function setCode($value) {
		$this->code = $value;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function setLocation($value) {
		$this->location = $value;
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function save (G_Company_Structure $gcs) {
		return G_Settings_Location_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Settings_Location_Manager::delete($this);
	}
}
?>