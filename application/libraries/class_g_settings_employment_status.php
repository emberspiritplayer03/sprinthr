<?php

// USAGE:
	/*$emp_status = new G_Settings_Employment_Status(4);
	$emp_status->setCompanyStructureId(1);
	$emp_status->setCode('FT');
	$emp_status->setStatus('Full Time');
	$emp_status->save();*/
	
	/*$emp_status = G_Settings_Employment_Status_Finder::findAll();
	echo '<pre>';
	print_r($emp_status);
	echo '</pre>';*/
	
	//$emp_status = G_Settings_Employment_Status_Finder::findById(3);				
	//$emp_status->delete();

class G_Settings_Employment_Status {
	public $id;
	public $company_structure_id;
	public $code;
	public $status;
		
	public function __construct($id = '') {
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
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function getAllEmploymentStatus($fields = array(), $order_by = ''){
		$data = G_Settings_Employment_Status_Helper::sqlAllEmploymentStatus($fields, $order_by);

		return $data;
	}
	
	public function save (G_Company_Structure $gcs) {
		return G_Settings_Employment_Status_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Settings_Employment_Status_Manager::delete($this);
	}
	
	public function setToEmployee(G_Employee $e) {
		G_Settings_Employment_Status_Manager::setToEmployee($this, $e);
	}
}
?>