<?php
class G_Employee_Status_History extends Employee_Status_History {
			
	public function __construct() {}
	
	public function save() {		
		return G_Employee_Status_History_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Status_History_Manager::delete($this);
	}
}
?>