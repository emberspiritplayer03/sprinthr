<?php
class G_Employee_Leave_Available {
	
	public $id;
	public $employee_id;
	public $leave_id;
	public $no_of_days_alloted;
	public $no_of_days_available;
    public $no_of_days_used;
    public $covered_year;
    public $employee_leave_with_increase = array();
    public $employee_id_for_update_year_covered = array();
    public $employee_list_with_leave_increase = array();

    CONST DEFAULT_START_LEAVE_CREDIT = 5;
    CONST MAX_LEAVE_CREDIT = 14;
	
	
	function __construct() {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}

    public function setCoveredYear($value) {
        $this->covered_year = $value;
    }

    public function getCoveredYear() {
        return $this->covered_year;
    }
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setLeaveId($value) {
		$this->leave_id = $value;	
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}
	
	public function setNoOfDaysAlloted($value) {
		$this->no_of_days_alloted = $value;	
	}
	
	public function getNoOfDaysAlloted() {
		return $this->no_of_days_alloted;
	}
	
	public function setNoOfDaysAvailable($value) {
		$this->no_of_days_available = $value;	
	}
	
	public function getNoOfDaysAvailable() {
		return $this->no_of_days_available;
	}

    public function setNoOfDaysUsed($value) {
        $this->no_of_days_used = $value;
    }

    public function getNoOfDaysUsed() {
        return $this->no_of_days_used;
    }
	
	public function lessLeaveAvailable($days) {
		return G_Employee_Leave_Available_Manager::minusAvailableLeave($this,$days);
	}

	public function employeeLeaveTypeAvailableByEmployeeId() {
		$data = array();

		if( $this->employee_id > 0 ){
			$data = G_Employee_Leave_Available_Helper::sqlEmployeeLeaveTypeAvailable($this->employee_id);
		}

		return $data;
	}

	/*
		Usage : 
		$el = new G_Employee_Leave_Available();
		$el->setEmployeeId($employee_id);
		$el->setLeaveId($leave_id);
		$is_deducted = $el->deductLeaveCredit($days); $days -> number of days to deduct
	*/

	public function deductLeaveCredits($num_to_deduct = 0) {
		$return = array();
		if( !empty($this->employee_id) && !empty($this->leave_id) && $num_to_deduct >= 0 ){

			$available_leave_data = G_Employee_Leave_Available_Helper::sqlGetEmployeeNonZeroAvailableCreditByLeaveIdAndEmployeeId($this->leave_id , $this->employee_id);			
			if($available_leave_data['no_of_days_available'] >= $num_to_deduct){
				$this->id = $available_leave_data['id'];
				$is_deducted = G_Employee_Leave_Available_Manager::deductLeaveCredits($this->id, $num_to_deduct); //Deduct leave credit
				
				if( $is_deducted ){
					$return['is_success'] = true;
					$return['message']    = "Record updated";
				}else{
					$return['is_success'] = false;
					$return['message']    = "Insufficient leave credits : Available leave credit is <b>" . $available_leave_data['no_of_days_available'] . "</b>";
				}

			}else{
				$return['is_success'] = false;
				$return['message']    = "Insufficient leave credits : Available leave credit is <b>" . $available_leave_data['no_of_days_available'] . "</b>";
			}

		}else{
			$return['is_success'] = false;
			$return['message']    = "No leave credit available";
		}

		return $return;
	}

	/*
		Usage :
		$employee_id = 1;
		$leave_id    = 2;
		$el = new G_Employee_Leave_Available();						
		$el->setEmployeeId($employee_id);
		$el->setLeaveId($leave_id);
		$total_available_leave = $el->employeeTotalAvailableLeaveByEmployeeIdAndLeaveId();			
	*/

	public function employeeTotalAvailableLeaveByEmployeeIdAndLeaveId() {
		$total_leave = 0;		
		if( $this->employee_id > 0 && $this->leave_id > 0 ){			
			$data = G_Employee_Leave_Available_Helper::sqlGetEmployeeLeaveCreditsByEmployeeIdAndLeaveId($this->leave_id, $this->employee_id);
			$total_leave = $data['no_of_days_available'] > 0 ? $data['no_of_days_available'] : 0;
		}

		return $total_leave;
	}

	public function addLeaveCredits($num_to_add = 0) {
		$return = array();

		if( !empty($this->employee_id) && !empty($this->leave_id) && $num_to_add >= 0 ){
			$available_leave_data = G_Employee_Leave_Available_Helper::sqlGetEmployeeLeaveCreditsByEmployeeIdAndLeaveId($this->leave_id , $this->employee_id);
			if( !empty($available_leave_data) ){
				$is_added = G_Employee_Leave_Available_Manager::addLeaveCredits($available_leave_data['id'], $num_to_add);

				if(!empty($is_added) || $is_added > 0) {
					$h = new G_Employee_Leave_Credit_History();
					$h->setEmployeeId($this->employee_id);
					$h->setLeaveId($this->leave_id);
					$h->setCreditsAdded($num_to_add);
					$h->addToHistory();
				} else {
					$return['is_success'] = false;
					$return['message']    = "Error on adding leave available";					
				}

			}else{
				$this->covered_year = date("Y");
				$this->no_of_days_alloted = $num_to_add;
				$this->no_of_days_available = $num_to_add;
				self::save();				
			}			

			$return['is_success'] = true;
			$return['message']    = "Record updated";

		}else{
			$return['is_success'] = false;
			$return['message']    = "Record not found";
		}

		return $return;
	}

	public function saveEmployeeLeaveCredits() {
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'Cannot save record';

		$numDaysAlloted   = $this->no_of_days_alloted;
		$numDaysAvailable = $this->no_of_days_available;
		if( !empty( $this->leave_id ) && $this->employee_id > 0 ){
			if( $numDaysAlloted < $numDaysAvailable ){
				$return['message'] = "Invalid form entry. Number of days allotted should be greater than or equal to number of days available";
			}else{
				self::save();
				$return['is_success'] = true;
				$return['message']    = 'Record was successfully saved.';
			}
		}

		return $return;
	}

	public function getAllEmployeesEntitledForLeaveIncrease() {
		$employeeLeaveAvailableList = G_Employee_Leave_Available_Helper::getEmployeeLeaveAvailableForIncrease(); //get  total year service
		$this->employee_leave_with_increase = $employeeLeaveAvailableList;
		return $this;
	}

	public function saveAllEmployeeWithLeaveIncrease(){
		if( !empty($this->employee_leave_with_increase)){
			$employee_for_update_year_covered = array();
			foreach($this->employee_leave_with_increase as $key => $employee_leave_avail) {
				$emp = G_Employee_Helper::getEmployeeYearsOfService($employee_leave_avail['employee_id']);
				$lcdata['employment_status'] 		= $emp['employment_status_id'];
				$lcdata['employee_year_of_service'] = $emp['year_of_service']; //total_months
				$lcdata['leave_id'] 				= $employee_leave_avail['leave_id'];
				$leave_credit_addition = G_Settings_Leave_Credit_Helper::getLeaveDefaultCredit($lcdata);

				echo "Add leave credit {$leave_credit_addition} / " . $emp['year_of_service'];
				exit;

				$leave_credit_addition = self::DEFAULT_START_LEAVE_CREDIT + ( ($emp['year_of_service'] - 1) * $leave_credit_addition );

				$lc = G_Employee_Leave_Available_Finder::findById($employee_leave_avail['id']);
				$lc->setNoOfDaysAlloted($employee_leave_avail['no_of_days_alloted'] + $leave_credit_addition);				
				$lc->setNoOfDaysAvailable($employee_leave_avail['no_of_days_available'] + $leave_credit_addition);
				$employee_for_update_year_covered[] 		= $employee_leave_avail['id'];
				$emp_id_array[]								= $employee_leave_avail['employee_id'];
				$this->employee_id_for_update_year_covered 	= $employee_for_update_year_covered;
				$update_leave_credit 						= $lc->save();
			}
		}

		return $this;
	}

	/**
	 * Add leave credits to all employees base on years of service and leave settings credits 
	 *
	 * @return array
	*/
	public function addLeaveCreditsToEmployees(){
		$data['is_success'] = false;
		$data['message']    = "No records updated";

		$fields    = array("id","hired_date","employment_status_id","DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),hired_date)), '%Y')+0 AS year_of_service");
		$employees = G_Employee_Helper::sqlGetAllEmployeesMoreThanAYearOfService($fields);				
		$leave_ids = G_Settings_Leave_Credit_Helper::getAllUniqueLeaveId();		
		$year      = date("Y");
		$counter   = 0;	
		if( !empty($leave_ids) ){
			foreach($employees as $e){
				foreach( $leave_ids as $leave ){
					$leave_id 			  = $leave['leave_id'];
					$years_of_service     = $e['year_of_service'];
					$employment_status_id = $e['employment_status_id'];
					$lcdata = array('leave_id' => $leave_id, 'employee_year_of_service' => $years_of_service, 'employment_status' => $employment_status_id);
					$leave_credit_addition = G_Settings_Leave_Credit_Helper::getLeaveDefaultCredit($lcdata, array('employment_years' => 'DESC'));	
					$is_employee_leave_id_year_exists = G_Employee_Leave_Available_Helper::sqlIsEmployeeLeaveTypeExists($e['id'],$leave['leave_id'],$year);			
					if( $leave_credit_addition > 0 && !$is_employee_leave_id_year_exists ){					
						$lc = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($e['id'], $leave_id);					
						if( empty($lc) ){
							$lc = new G_Employee_Leave_Available();
							$lc->setEmployeeId($e['id']);
						}

						$lc->setLeaveId($leave['leave_id']);
						$lc->setNoOfDaysAlloted($leave_credit_addition);
						$lc->setNoOfDaysAvailable($leave_credit_addition);
				        //$lc->setNoOfDaysUsed(0);
				        $lc->setCoveredYear($year);
				        $lc->save();

				        //Add to history
				        $h = new G_Employee_Leave_Credit_History();
						$h->setEmployeeId($e['id']);
						$h->setLeaveId($leave['leave_id']);
						$h->setCreditsAdded($leave_credit_addition);
						$h->addToHistory();

				        $counter++;
					}
				}			
			}
								
			if( $counter > 0 ){
				$data['is_success'] = true;
				$data['message']    = "{$counter} employees leave credits updated";
				$data['total']      = $counter;
			}
		}		

		return $data;
	}

	public function updateCoveredYear() {
		if(!empty($this->employee_id_for_update_year_covered)) {
			foreach($this->employee_id_for_update_year_covered as $key => $id) {
				$lc = G_Employee_Leave_Available_Finder::findById($id);
				if($lc) {
					$lc->setCoveredYear(date("Y"));
					$lc->save();
					$emp_id_array[]	= $lc->getEmployeeId();
				}
				
			}

			$employee_id_array = array_unique($emp_id_array);
			foreach ($employee_id_array as $key => $value) {
				$fields = array("employee_code", "firstname","lastname");
				$e = G_Employee_Helper::sqlGetEmployeeDetailsById($value);
				if ($e) {
					$employee_list_with_leave_increase[] = "{$e['employee_code']} : {$e['firstname']} {$e['lastname']}";
				}
			}
			$this->employee_list_with_leave_increase = $employee_list_with_leave_increase;

		}
		
		return $this;
	}

	public function updateEmployeeLeaveCredits() {
		if( $this->employee_id > 0 && $this->leave_id > 0 && $this->covered_year > 0 ){			
			$is_employee_with_leave_type = G_Employee_Leave_Available_Helper::sqlIsEmployeeLeaveTypeExists( $this->employee_id, $this->leave_id, $this->covered_year );
			if( $is_employee_with_leave_type ){
				G_Employee_Leave_Available_Manager::updateEmployeeLeaveCredits($this);
			}else{
				$this->save();
			}

		}
	}
	
	public function save() {
		return G_Employee_Leave_Available_Manager::save($this);
	}

	public function deleteAllLeaveCredit() {
		return G_Employee_Leave_Available_Manager::deleteAllLeaveCredit($this);
	}
	
	public function delete() {
		return G_Employee_Leave_Available_Manager::delete($this);
	}
}

?>