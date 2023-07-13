<?php
class G_Employee_Leave_Credit_Tracking extends Employee_Leave_Credit_Tracking {

	public function __construct() {
		
	}
	
	public function save() {		
		return G_Employee_Leave_Credit_Tracking_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Leave_Credit_Tracking_Manager::delete($this);
	}
}
?>