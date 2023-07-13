<?php
class Group_Restday {
	protected $id;
	protected $group_id;	
	protected $date;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setGroupId($value) {    	
		$this->group_id = (int) $value;
		return $this;
	}
	
	public function getGroupId() {
		return $this->group_id;
	}

	public function setDate($value) {
		$formatted_value = date("Y-m-d",strtotime($value));
		$this->date = $formatted_value;
	}

	public function getDate() {
		return $this->date;
	}
}
?>