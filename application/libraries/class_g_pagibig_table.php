<?php
class G_Pagibig_Table extends Pagibig_Table {
	
	public $company_structure_id;
		
	public function __construct() {
		
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function save() {		
		return G_Pagibig_Table_Manager::save($this);
	}

	public function update() {
		return G_Pagibig_Table_Manager::update($this);
	}
	
	public function delete() {
		return G_Pagibig_Table_Manager::delete($this);
	}

	public function importPagibigTable($file) {
		return G_Pagibig_Table_Manager::importPagibigTable($file);
	}
}
?>