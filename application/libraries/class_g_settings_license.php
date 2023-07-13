<?php
class G_Settings_License {
	public $id;
	public $company_structure_id;
	public $license_type;	
	public $description;
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
	
	public function setLicenseType($value) {
		$this->license_type = $value;
	}
	
	public function getLicenseType() {
		return $this->license_type;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function save (G_Company_Structure $gcs) {
		return G_Settings_License_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Settings_License_Manager::delete($this);
	}
}
?>