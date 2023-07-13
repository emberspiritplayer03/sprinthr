<?php
class G_Employee_Membership {
	
	public $id;
	public $employee_id;
	public $membership_type_id;
	public $membership_id;
	public $subscription_ownership;
	public $subscription_amount;
	public $commence_date;
	public $renewal_date;

	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setMembershipTypeId($value) {
		$this->membership_type_id = $value;	
	}
	
	public function getMembershipTypeId() {
		return $this->membership_type_id;
	}
	
	public function setMembershipId($value) {
		$this->membership_id = $value;	
	}
	
	public function getMembershipId() {
		return $this->membership_id;
	}
	
	public function setSubscriptionOwnership($value) {
		$this->subscription_ownership = $value;	
	}
	
	public function getSubscriptionOwnership() {
		return $this->subscription_ownership;
	}
	
	public function setSubscriptionAmount($value) {
		$this->subscription_amount = $value;	
	}
	
	public function getSubscriptionAmount() {
		return $this->subscription_amount;
	}
	
	public function setCommenceDate($value) {
		$this->commence_date = $value;	
	}
	
	public function getCommenceDate() {
		return $this->commence_date;
	}
	
	public function setRenewalDate($value) {
		$this->renewal_date = $value;	
	}
	
	public function getRenewalDate() {
		return $this->renewal_date;
	}
	
		
	public function save() {
		return G_Employee_Membership_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Membership_Manager::delete($this);
	}
}

?>