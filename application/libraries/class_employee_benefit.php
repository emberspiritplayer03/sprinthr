<?php
class Employee_Benefit {
	public $id;
	public $obj_id;
	public $obj_type;
	public $benefit_id;
	public $date_created;	
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setObjId($value) {
		$this->obj_id = $value;
	}
	
	public function getObjId() {
		return $this->obj_id;
	}
	
	public function setObjType($value) {
		$this->obj_type = $value;
	}
	
	public function getObjType() {
		return $this->obj_type;
	}
	
	public function setApplyToAll($value) {
		$this->apply_to_all = $value;
	}
	
	public function getApplyToAll() {
		return $this->apply_to_all;
	}
        
    public function setBenefitId($value) {
		$this->benefit_id = $value;
	}
	
	public function getBenefitId() {
		return $this->benefit_id;
	}
			
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>