<?php
class G_Employee_Group_Schedule extends Employee_Group_Schedule {
	
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