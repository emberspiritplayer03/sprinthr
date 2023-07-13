<?php
class G_Employee_Schedule_Type extends Employee_Schedule_Type {
	
	public function __construct() {
	
	}
	
	public function save() {
		return G_Settings_Requirement_Manager::save($this);	
	}
	
	public function delete() {
		return G_Settings_Requirement_Manager::delete($this);	
	}
}
?>