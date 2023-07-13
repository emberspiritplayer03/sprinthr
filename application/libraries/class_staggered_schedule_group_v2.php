<?php
/*
	This class is used to group schedules
*/
class Staggered_Schedule_Group_V2 {	
	protected $id;
	protected $schedule_name;
	
	function __construct() {

	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	
	
	public function setName($value) {
		$this->schedule_name = $value;	
	}
	
	public function getName() {
		return $this->schedule_name;	
	}
}
?>