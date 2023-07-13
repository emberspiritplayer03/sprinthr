<?php

class G_Excluded_Employee_Deduction extends Excluded_Employee_Deduction {

	const MOVE = "Move";
	const HOLD = "Hold";
	
	public function __construct() {
		
	}

	public function excludeEmployeeDeduction($data = array()) {
		if(!empty($data)) {
			$period = G_Cutoff_Period_Finder::findByPeriod($data['from'],$data['to']);

			foreach($data['selected_deduction'] as $key => $value) {
				$v = explode("/",$value);
				//$e = G_Employee_Finder::findByEmployeeCode($v[0]);
				$fields = array("id","company_structure_id");
				$e = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($v[0],$fields);
				if($e && $period) {
					$employee_id 			= $e['id'];
					$company_structure_id	= $e['company_structure_id'];
					$payroll_period_id 		= $period->getId();
					$variable_name 			= $v[1];
					$amount 				= $v[2];
					$action 				= $data['action'];
					$new_payroll_period_id 	= Utilities::decrypt($data['new_payroll_period_id']);

					$eed_values[]		= "(".
						Model::safeSql($employee_id) . ",".
						Model::safeSql($payroll_period_id) . ",".
						Model::safeSql($new_payroll_period_id) . ",".
						Model::safeSql($variable_name) . ",".
						Model::safeSql($amount) . ",".
						Model::safeSql($action) . ",".
						Model::safeSql(date("Y-m-d")) 
					.")";

					$emp_deduction_values[] = "(".
						Model::safeSql($company_structure_id) . ",".
						Model::safeSql(serialize($employee_id)) . ",".
						Model::safeSql("Moved Deduction : ".ucfirst(str_replace("_"," ",$variable_name))) . ",".
						Model::safeSql("Moved Deduction from ".Tools::convertDateFormat($data['from']) . ' to ' . Tools::convertDateFormat($data['to'])) . ",".
						Model::safeSql($amount) . ",".
						Model::safeSql($new_payroll_period_id) . ",".
						Model::safeSql(G_Employee_Deductions::NO) . ",".
						Model::safeSql(G_Employee_Deductions::APPROVED) . ",".
						Model::safeSql(G_Employee_Deductions::NO) . ",".
						Model::safeSql(G_Employee_Deductions::NO) . ",".
						Model::safeSql(date("Y-m-d H:i:s")) . ",".
						Model::safeSql(1) 
					.")";

					$ids[] 	 = $employee_id;
					if(isset($excluded_employee_deduction[$employee_id])) {
						array_push($excluded_employee_deduction[$employee_id],$variable_name); 
					}else{
						$excluded_employee_deduction[$employee_id] = array($variable_name);
					}
				}
			}

			if(!empty($period) && !empty($ids)) {
				$selected_employee 	= implode(",",$ids);
				$year 				= $period->getYearTag();
	            $month 				= date('m', strtotime($period->getStartDate()));
	            $cutoff_number 		= $period->getCutoffNumber();	            

				$c = new G_Company;
		        $c->setFilteredEmployeeId($selected_employee);
		        $payslips = $c->generatePayslip($month, $cutoff_number, $year, $excluded_employee_deduction);

		        if($payslips) {
		        	G_Excluded_Employee_Deduction_Manager::saveBulk(implode(",",$eed_values));
		        	if($action == G_Excluded_Employee_Deduction::MOVE) {
		        		G_Employee_Deductions_Manager::saveBulk(implode(",",$emp_deduction_values));
		        	}
		        	$return['is_success'] = true;
					$return['message'] = "<div style='margin-left:0px; padding-top:9px' class='alert alert-success'>Payroll has been successfully generated.</div>";
				}else{
		        	$return['is_success'] = false;
		        	$return['message'] = "<div style='margin-left:-10px;' class='alert alert-error'>Unable to generate payroll. </div>";
		        }
			}else{
				$return['is_success'] = false;
				$return['message'] = "<div style='margin-left:0px; padding-top:9px' class='alert alert-error'>Invalid Payroll period.</div>";
			}
		}else{
			$return['is_success'] = false;
			$return['message'] = "<div style='margin-left:0px; padding-top:9px' class='alert alert-error'>Invalid Form Entry.</div>";
		}

		return $return;
	}
							
	public function save() {
		return G_Excluded_Employee_Deduction_Manager::save($this);
	}
		
	public function delete() {
		G_Excluded_Employee_Deduction_Manager::delete($this);
	}
}
?>