<?php

class Overtime_Settings {
	
	protected $employee;
	protected $attendance;
	protected $cutoff_period;

	public function __construct() {
		
	}

	public function setEmployee($value) {
		$this->employee = $value;
	}
	
	public function getEmployee() {
		return $this->employee;
	}

	public function setAttendance($value) {
		$this->attendance = $value;
	}
	
	public function getAttendance() {
		return $this->attendance;
	}

	public function setCutoffPeriod($value) {
		$this->cutoff_period = $value;
	}
	
	public function getCutoffPeriod() {
		return $this->cutoff_period;
	}

	function getEmployeeOvertimeAllowance() {
		$e = $this->employee;
		$attendance = $this->attendance;
		$cutoff_period = $this->cutoff_period;

		$is_taxable = Earning::NON_TAXABLE;
	    $title 	    = "OT Allowance";
	    $total_ot_allowance = 0;

		if($e && $cutoff_period) {
			//get overtime allowance by Individual
			$data = G_Overtime_Allowance_Helper::getAllOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart($e->getId(), G_Overtime_Allowance::EMPLOYEE_TYPE, $cutoff_period->getEndDate());			

			if(!empty($data)) {
				$oa = new G_Overtime_Allowance();	

				foreach( $data as $d ){
					$ot_allowance = $oa->computeOvertimeAllowance($attendance, $d);
					//echo $ot_allowance . " OT ALLOWANCE Employee";
					
					if($ot_allowance > 0) {
		                //$earn = new Earning($title, $ot_allowance, $is_taxable);
		                //$benefits[] = $earn;
		                $total_ot_allowance += $ot_allowance;

						//return $benefits;
					}
				}							
			}

			//get overtime allowance by Department/Group
			$sql_fields = array("id,department_company_structure_id");
			$emp = G_Employee_Helper::sqlGetEmployeeDetailsById($e->getId(), $sql_fields);
			$data = G_Overtime_Allowance_Helper::getAllOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart($emp['department_company_structure_id'], G_Overtime_Allowance::DEPARTMENT_TYPE, $cutoff_period->getEndDate());
			if(!empty($data)) {
				$oa = new G_Overtime_Allowance();
				foreach( $data as $d ){
					$ot_allowance = $oa->computeOvertimeAllowance($attendance, $d);
					//echo $ot_allowance . " OT ALLOWANCE Department";
					
					if($ot_allowance > 0) {
		                //$earn = new Earning($title, $ot_allowance, $is_taxable);
		                //$benefits[] = $earn;
		                $total_ot_allowance += $ot_allowance;

						//return $benefits;
					}
				}				
			}

			//get overtime allowance by employment status
			$data = G_Overtime_Allowance_Helper::getAllOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart($e->getEmploymentStatusId(), G_Overtime_Allowance::EMPLOYMENT_STATUS_TYPE, $cutoff_period->getEndDate());			
			if(!empty($data)) {				
				$oa = new G_Overtime_Allowance();
				foreach( $data as $d ){
					$ot_allowance = $oa->computeOvertimeAllowance($attendance, $d);
					//echo $ot_allowance . " OT ALLOWANCE Employee";
					
					if($ot_allowance > 0) {
		                //$earn = new Earning($title, $ot_allowance, $is_taxable);
		                //$benefits[] = $earn;
		                $total_ot_allowance += $ot_allowance;

						//return $benefits;
					}
				}
			}			

			//get overtime allowance by ALL Employee
			$data = G_Overtime_Allowance_Helper::getAllOvertimeAllowanceByObjectIdAndObjectTypeAndDateStart(0, G_Overtime_Allowance::ALL_TYPE, $cutoff_period->getEndDate());
			if(!empty($data)) {
				$oa = new G_Overtime_Allowance();

				foreach( $data as $d ){
					$ot_allowance = $oa->computeOvertimeAllowance($attendance, $d);
					//echo $ot_allowance . " OT ALLOWANCE All";

					if($ot_allowance > 0) {
		                //$earn = new Earning($title, $ot_allowance, $is_taxable);
		                //$benefits[] = $earn;
		                $total_ot_allowance += $ot_allowance;

						//return $benefits;
					}      
				} 
			}

			$earn = new Earning($title, $total_ot_allowance, $is_taxable);
		    $benefits[] = $earn;		
			return $benefits;
		}
		return false;
	}

}
?>