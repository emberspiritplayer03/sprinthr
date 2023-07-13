<?php
class Overtime_Allowance {
	protected $id;
	protected $object_id;
	protected $object_type;
	protected $ot_allowance;
	protected $multiplier;
	protected $max_ot_allowance;
	protected $date_start;	
	protected $description;	
	protected $date_created;	
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}

	public function setObjectId($value) {
		$this->object_id = $value;
	}
	
	public function getObjectId() {
		return $this->object_id;
	}

	public function setObjectType($value) {
		$this->object_type = $value;
	}
	
	public function getObjectType() {
		return $this->object_type;
	}
	
    public function setOtAllowance($value) {    	
		$this->ot_allowance = $value;
	}
	
	public function getOtAllowance() {
		return $this->ot_allowance;
	}
        
    public function setMultiplier($value) {
		$this->multiplier = $value;
	}
	
	public function getMultiplier() {
		return $this->multiplier;
	}
			
	public function setMaxOtAllowance($value) {		
		$this->max_ot_allowance = $value;
	}
	
	public function getMaxOtAllowance() {
		return $this->max_ot_allowance;
	}

	public function setDateStart($value) {		
		$this->date_start = $value;
	}
	
	public function getDateStart() {
		return $this->date_start;
	}

	public function setDescription($value) {		
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setDateCreated($value) {		
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>