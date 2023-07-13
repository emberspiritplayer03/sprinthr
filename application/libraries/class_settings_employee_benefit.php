<?php
class Settings_Employee_Benefit {
	public $id;
	public $code;
	public $name;
	public $description;	
	public $amount;	
	public $is_taxable;
	public $is_archive;
	public $date_created;
	public $date_last_modified;

	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setCode($value) {
    	$value = strtoupper($value);
		$this->code = trim($value);
	}
	
	public function getCode() {
		return $this->code;
	}
        
    public function setName($value) {
    	$value = ucwords($value);
		$this->name = trim($value);
	}
	
	public function getName() {
		return $this->name;
	}
			
	public function setDescription($value) {
		$value = ucfirst($value);
		$this->description = trim($value);
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setAmount($value) {
		$this->amount = trim($value);
	}

	public function getAmount() {
		return $this->amount;
	}
	
	public function setIsTaxable($value) {
		$value = ucfirst($value);
		$this->is_taxable = trim($value);
	}

	public function getIsTaxable() {
		return $this->is_taxable;
	}

	public function setIsArchive($value) {
		$value = ucfirst($value);
		$this->is_archive = trim($value);
	}

	public function getIsArchive() {
		return $this->is_archive;
	}

	public function setDateCreated($value) {
		$formatted_value = date("Y-m-d H:i:s",strtotime($value));
		$this->date_created = trim($formatted_value);
	}

	public function getDateCreated() {
		return $this->date_created;
	}

	public function setDateLastModified($value) {
		$formatted_value = date("Y-m-d H:i:s",strtotime($value));
		$this->date_last_modified = trim($formatted_value);
	}

	public function getDateLastModified() {
		return $this->date_last_modified;
	}
}
?>