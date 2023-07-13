<?php
class G_Settings_Employee_Status extends Settings_Employee_Status {
	
	public $is_archive;
	public $date_created;
	
	const YES = "Yes";
	const NO  = "No";

    const NULL     = 0;
    const ACTIVE     = 1;
	const TERMINATED = 3;
	const RESIGNED   = 2;
	const ENDO		 = 4;
	const INACTIVE	 = 5;
	const AWOL	 = 6;
	
	public function __construct() {
	
	}

	public function getDefaultIds() {
		$default_ids = array(1,2,3,4,5);
		return $default_ids;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;	
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;	
	}

	public function getObjectDataByCompanyStructureId(){
		return G_Settings_Employee_Status_Finder::findAllIsNotArchiveByCompanyStructureId($this->company_structure_id);
	}
	
	public function save() {		
		return G_Settings_Employee_Status_Manager::save($this);	
	}
	
	public function archive() {		
		return G_Settings_Employee_Status_Manager::archive($this);	
	}
	
	public function restore() {		
		return G_Settings_Employee_Status_Manager::restore($this);	
	}
	
	public function delete() {
		return G_Settings_Employee_Status_Manager::delete($this);	
	}
}
?>