<?php

class G_Employee_Benefit extends Employee_Benefit {
	
	const YES = "Yes";
	const NO  = "No";
	
	const EARNING   = "Earning";
	const DEDUCTION = "Deduction";	
		
	const EMPLOYEE   = "Employee";
	const DEPARTMENT = "Department";
	const POSITION   = "Position";
	
	
	//obj
	protected $e;
	
	public function __construct() {
		
	}
	
	public function addToPayslip(G_Employee $e, $period){
		return G_Employee_Benefit_Helper::addToPayslip($e, $period);
	}
	
	public function assignBenefit($employee_array, $criteria) {
		return G_Employee_Benefit_Helper::assignBenefit($this,$employee_array,$criteria);
	}
							
	public function save() {
		return G_Employee_Benefit_Manager::save($this);
	}
	
	public function deleteAllByBenefitId($benefit_id) {
		G_Employee_Benefit_Manager::deleteAllByBenefitId($benefit_id);
	}
		
	public function delete() {
		G_Employee_Benefit_Manager::delete($this);
	}
}
?>