<?php
class G_Settings_Grace_Period {
	
	public $id;
	public $company_structure_id;
	public $title;
	public $description;
	public $is_archive;
	public $is_default;
	public $quantity;
	public $number_minute_default;
	
	const NO_ACCESS 	= 0;
	const HAS_ACCESS	= 1;
	const CAN_MANAGE	= 2;
	const CUSTOM		= 3;
	
	//const YES   = 'Yes';
	//const NO  	= 'No';
	
	const USER	= 'User';
	const GROUP	= 'Group';
	
	const YES = 1;
	const NO  = 0;
	 
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;	
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($value) {
		$this->description = $value;	
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setIsDefault($value) {
		$this->is_default = $value;	
	}
	
	public function getIsDefault() {
		return $this->is_default;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setNumberMinuteDefault($value) {
		$this->number_minute_default = $value;	
	}
	
	public function getNumberMinuteDefault() {
		return $this->number_minute_default;
	}
	
	
	public function save() {
		return G_Settings_Grace_Period_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Grace_Period_Manager::delete($this);
	}
	
	public function save_not_default() {
		return G_Settings_Grace_Period_Manager::save_not_default($this);
	}
	
	public function set_all_not_default() {
		return G_Settings_Grace_Period_Manager::set_all_not_default($this);
	}
	
	public function save_default() {
		return G_Settings_Grace_Period_Manager::save_default($this);
	}
	
}

?>