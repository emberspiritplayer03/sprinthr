<?php
class G_Settings_Leave_General extends Settings_Leave_General {

	const DEFAULT_ID = 1;

	const CRITERIA_01 = 1;
	const CRITERIA_02 = 2;
	const CRITERIA_03 = 3;

	const CONVERTIBLE_TITLE = "Converted Leave Credit";

	const CRITERIA_DESCRIPTION_01 = "Convert last year's unused leave credit into cash";
	const CRITERIA_DESCRIPTION_02 = "Unused leave credit will be added to next year";
	const CRITERIA_DESCRIPTION_03 = "Unused leave credits will be removed";
	const CEILING_TOTAL_NONTAXABLE_LEAVE_CONVERSION = 10;

	//protected $conversion_allowed_leave_ids = CONVERSION_ALLOWED_LEAVE_IDS; // 4 = incentive leave
	protected $conversion_allowed_leave_ids = CONVERSION_ALLOWED_LEAVE_IDS; // 4 = incentive leave

	protected $is_proceed = false;
	protected $earnings_find_date = '';
	protected $conversion_allowed_leave_type;
	public $total_leave_converted_to_cash = 0;
	public $total_leave_reset = 0;
	
	public function __construct() {}

	protected function getAllowedLeaveIdsForConversion() {
		return $this->conversion_allowed_leave_ids;
	}

	public function getAllUnusedLeaveCreditLastYear() {
		$unused_leave_credit_last_year = G_Employee_Leave_Available_Helper::getAllUnusedLeaveCreditLessThanYear(date("Y"));

		if ($unused_leave_credit_last_year) {
			$this->unused_leave_credit_last_year = $unused_leave_credit_last_year;
			$this->is_proceed = true;
		}

		return $this;
	}

	public function isCutoffPeriodLock( $cutoff_period = '' ) {		
		$a_cutoffs = explode("/", $cutoff_period);		
		if( !empty($a_cutoffs) ){
			$is_lock = G_Cutoff_Period_Helper::isPeriodLockByDate($a_cutoffs[0], $a_cutoffs[1]);
			if( $is_lock == G_Cutoff_Period::YES ){
				$this->is_proceed = false;	
			}else{				
				$this->is_proceed = true;
				$this->earnings_find_date = $a_cutoffs[0];
				$this->cutoff_period = $a_cutoffs;
			}	
		}		
		return $this;
	}

	public function applyGeneralRule() {
		$summary      = array('is_success' => false, 'message' => 'No record(s) processed');
		$default_rule = $this->getDefaultLeaveGeneralRule();

		$conversion_allowed_leave_ids = json_decode($this->conversion_allowed_leave_ids);
		//var_dump($conversion_allowed_leave_ids);exit();
		//Utilities::displayArray($this->unused_leave_credit_last_year);exit();

		if ($default_rule && $this->is_proceed) { 			
			switch ($default_rule->getConvertLeaveCriteria()) {
				case self::CRITERIA_01:								
					foreach ($this->unused_leave_credit_last_year as $key => $value) {
						//$conversion_allowed_leave_ids = json_decode( $this->conversion_allowed_leave_ids );
						if ( $value['is_paid'] == G_Leave::YES && $value['no_of_days_available'] > 0 && in_array($value['leave_id'], $conversion_allowed_leave_ids)) {
							$arr_data[$value['employee_id']][$value['leave_id']]['unused_leave_credits'] += $value['no_of_days_available'];							
						}
					}	
								

					$this->convertUnusedLeaveCreditToCash($arr_data);
					$this->resetEmployeeLeaveAvailableCredits();
					$this->addLeaveCreditsToAllEmployees(8, 1); //Add bday leave

					break;
				case self::CRITERIA_02:
					// Do nothing
					break;

				case self::CRITERIA_03:
					$this->resetEmployeeLeaveAvailableCredits();
					break;	

				default:
					break;
			}

			$summary = array('is_success' => true, 'message' => 'Total converted leaves : ' . $this->total_leave_converted_to_cash);
		}

		return $summary;
	}

	/**
	* Add leave converted to earnings
	*
	* @param array data
	* @param boolean taxable
	* @return void
	*/
	private function addLeaveConversionToEarnings( $data = array(), $taxable = false ) {
		if( !empty($data) ){
			$title =  "Leave credit to cash : Non-taxable";
			if( $taxable ){
				$title = "Leave credit to cash : Taxable";
			}
			
			$gee = new G_Employee_Earnings();			
			$gee->setCompanyStructureId($data['employee_id']);
			$gee->setObjectId($data['employee_id']);
			$gee->setObjectDescription(self::CONVERTIBLE_TITLE . " : ".$data['unused_leave_credits']);
			$gee->applyToEmployee();
			$gee->setTitle($title);			
			$gee->setPercentage(0);				
			$gee->setPercentageMultiplier(0);				
			$gee->setAmount($data['amount']);				
			$gee->setPayrollPeriodId(Utilities::decrypt($data['cutoff_id']));				
			$gee->setDescription($title);
			$gee->approved();
			
			if( $taxable ){				
				$gee->taxable();									
			}else{
				$gee->notTaxable();									
			}

			$gee->setRemarks('');		
			$gee->isNotArchive();					
			$gee->setDateCreated($data['date_created']);
			G_Employee_Earnings_Manager::save($gee);				
		}		
	}

	public function convertUnusedLeaveCreditToCash($data = array()) {				
		$bulk_insert_earnings 	        = array();
		$bulk_insert_conversion_history = array();
		$total_amount_converted         = 0;
		$total_converted_leaves			= 0;
		
		$cp = new G_Cutoff_Period();
		//$cutoff_period = $cp->getCurrentCutoffPeriod($this->earnings_find_date);		
		$cutoff_period = $cp->getCurrentCutoffPeriodByDates($this->cutoff_period);		

		if (!empty($data)) {		
			foreach ($data as $employee_id => $value) {
				$total_unused_leave_credits = 0;

				$fields = array('company_structure_id','lastname','firstname');				
				$e      = G_Employee_Finder::findById($employee_id);
				$e_obj  = G_Employee_Finder::findById($employee_id);

				if (!empty($e) && !empty($cutoff_period) && $e_obj) {					
					$ceta_sea_rate = $e->getEmployeeCetaSeaRate();
					$cutoff_id  = Utilities::decrypt($cutoff_period['id']);
					$daily_rate = $e_obj->getEmployeeDailyRate();
					$daily_rate = str_replace(',', '', $daily_rate);
					foreach( $value as $leave_id => $subValue ){
						$amount = $subValue['unused_leave_credits'] * ($daily_rate + $ceta_sea_rate);
						$total_unused_leave_credits += $subValue['unused_leave_credits'];
						$bulk_insert_conversion_history[] = "(" . Model::safeSql($employee_id) . "," . Model::safeSql($leave_id) . "," . Model::safeSql(date("Y")) . "," . Model::safeSql($subValue['unused_leave_credits']) . "," . Model::safeSql($amount) . "," . Model::safeSql(date("Y-m-d")) . "," . Model::safeSql(date("Y-m-d H:i:s")) . ")";
					}

					if( $total_unused_leave_credits > self::CEILING_TOTAL_NONTAXABLE_LEAVE_CONVERSION ){
						$excess = $total_unused_leave_credits - self::CEILING_TOTAL_NONTAXABLE_LEAVE_CONVERSION;

						//Taxable	
						$title   = "Taxable Converted Leave";		
						$remarks = self::CONVERTIBLE_TITLE . " : ". $excess;		
						$amount  = $excess * ($daily_rate + $ceta_sea_rate);
						$object_description = $e->getFirstname() . " " . $e->getLastname();
						$bulk_insert_earnings[] = "(" . Model::safeSql($e->getCompanyStructureId()) . ", " . Model::safeSql($title) . "," . Model::safeSql($remarks) . "," . Model::safeSql($amount) . "," . $cutoff_id . "," . Model::safeSql(G_Employee_Earnings::APPROVED) . "," . Model::safeSql(G_Employee_Earnings::YES) . "," . Model::safeSql(G_Employee_Earnings::NO) . "," . Model::safeSql(date("Y-m-d H:i:s")) . "," . Model::safeSql($employee_id) . "," . Model::safeSql(G_Employee_Earnings::APPLIED_TO_EMPLOYEE)  . "," . Model::safeSql(G_Employee_Earnings::EARNING_TYPE_AMOUNT) . ",0,0," . Model::safeSql($amount) . "," . Model::safeSql($object_description) .  ")";							

						//Non taxable	
						$title   = "Non taxable Converted Leave";		
						$remarks = self::CONVERTIBLE_TITLE . " : ". self::CEILING_TOTAL_NONTAXABLE_LEAVE_CONVERSION;		
						$amount  = self::CEILING_TOTAL_NONTAXABLE_LEAVE_CONVERSION * ($daily_rate + $ceta_sea_rate);
						$object_description = $e->getFirstname() . " " . $e->getLastname();			
						$bulk_insert_earnings[] = "(" . Model::safeSql($e->getCompanyStructureId()) . ", " . Model::safeSql($title) . "," . Model::safeSql($remarks) . "," . Model::safeSql($amount) . "," . $cutoff_id . "," . Model::safeSql(G_Employee_Earnings::APPROVED) . "," . Model::safeSql(G_Employee_Earnings::NO) . "," . Model::safeSql(G_Employee_Earnings::NO) . "," . Model::safeSql(date("Y-m-d H:i:s")) . "," . Model::safeSql($employee_id) . "," . Model::safeSql(G_Employee_Earnings::APPLIED_TO_EMPLOYEE)  . "," . Model::safeSql(G_Employee_Earnings::EARNING_TYPE_AMOUNT) . ",0,0," . Model::safeSql($amount) . "," . Model::safeSql($object_description) .  ")";	
					}else{
						//Non taxable	
						$title   = "Non taxable Converted Leave";		
						$remarks = self::CONVERTIBLE_TITLE . " : ". $total_unused_leave_credits;		
						$amount  = $total_unused_leave_credits * ($daily_rate + $ceta_sea_rate);
						$object_description = $e->getFirstname() . " " . $e->getLastname();			
						$bulk_insert_earnings[] = "(" . Model::safeSql($e->getCompanyStructureId()) . ", " . Model::safeSql($title) . "," . Model::safeSql($remarks) . "," . Model::safeSql($amount) . "," . $cutoff_id . "," . Model::safeSql(G_Employee_Earnings::APPROVED) . "," . Model::safeSql(G_Employee_Earnings::NO) . "," . Model::safeSql(G_Employee_Earnings::NO) . "," . Model::safeSql(date("Y-m-d H:i:s")) . "," . Model::safeSql($employee_id) . "," . Model::safeSql(G_Employee_Earnings::APPLIED_TO_EMPLOYEE)  . "," . Model::safeSql(G_Employee_Earnings::EARNING_TYPE_AMOUNT) . ",0,0," . Model::safeSql($amount) . "," . Model::safeSql($object_description) .  ")";	
					}
				}

				$total_converted_leaves += $total_unused_leave_credits;				
			}

			//Save bulk data
			//Earnings
			G_Employee_Earnings_Manager::deleteAllByCutoffIdAndRemarks($cutoff_id, self::CONVERTIBLE_TITLE);
			$fields = array('company_structure_id','title','remarks','amount','payroll_period_id','status','is_taxable','is_archive','date_created','object_id','applied_to','earning_type','percentage','percentage_multiplier','description','object_description');
			G_Employee_Earnings_Manager::bulkInsertData($bulk_insert_earnings,$fields);
			
			//Leave conversion history
			G_Converted_Leave_Manager::deleteAllByYear(date("Y"));
			G_Converted_Leave_Manager::bulkInsertData($bulk_insert_conversion_history);

			$this->total_leave_converted_to_cash += $total_converted_leaves;
		}
	}

	public function resetEmployeeLeaveAvailableCredits() {
		$la = new G_Employee_Leave_Available();
		$la->deleteAllLeaveCredit();
		/*if (!empty($this->unused_leave_credit_last_year)) {			
			foreach ($this->unused_leave_credit_last_year as $key => $value) {
				$employee_leave_available = G_Employee_Leave_Available_Finder::findById($value['id']);
				if ($employee_leave_available) {
					$this->total_leave_reset += 1;
					$employee_leave_available->setNoOfDaysAlloted(0);
					$employee_leave_available->setNoOfDaysAvailable(0);
					$employee_leave_available->save();
				}				
			}
		}*/
	}

	/**
	 * Add leave credits
	 *
	 * @param boolean override - to bypass 
	 * @return array return
	*/
	public function applyCredits( $override = false ) {
		$return = array();
		if( $override ){
			$this->is_proceed = true;
		}

		if ($this->is_proceed) { 
			$gela = new G_Employee_Leave_Available();
        	$return = $gela->addLeaveCreditsToEmployees();
    	}

    	return $return;
	}

	/**
	 * Add leave credits
	 *
	 * @param int leave_id
	 * @param int credits
	 * @return array
	*/
	public function addLeaveCreditsToAllEmployees( $leave_id = 0, $credits = 0 ){
		$return = array(
			'is_success' => false,
			'message' => 'Invalid leave type'
		);

		if( $leave_id > 0 ){
			$is_leave_id_exists = G_Leave_Helper::isLeaveIdExists($leave_id);
			if( $is_leave_id_exists ){
				$employees = G_Employee_Finder::findAllActiveRegularEmployees();
				$counter = 0;
				foreach( $employees as $e ){
					$la = new G_Employee_Leave_Available();
					$la->setLeaveId($leave_id);
					$la->setEmployeeId($e->getId());
					$la->addLeaveCredits($credits);
					$counter++;
				}

				if( $counter > 0 ){
					$return = array(
						'is_success' => true,
						'message' => "Leave was successfully added to {$counter} Employees"
					);
				}else{
					$return = array(
						'is_success' => false,
						'message' => "Employee record not found"
					);
				}				
			}
		}

		return $return;
	}

	public function yearlyLeaveAutoReset(){
		$getAllUnusedLeaveCreditLessThanYear = G_Employee_Leave_Available_Helper::getAllUnusedLeaveCreditLessThanYear(date("Y"));	
		$default_rule = G_Settings_Leave_General_Finder::findDefaultLeaveGeneralRule();
		
		switch ($default_rule->getConvertLeaveCriteria()) {

			case self::CRITERIA_03:
				$prevYear = date("Y", strtotime('-1 year'));
				$service_incentive_leave = G_Leave_Finder::findByName('Service Incentive Leave');
				G_Settings_Leave_General_Helper::getAllResetLeave();
				$la = new G_Employee_Leave_Available();
				$la->deleteAllLeaveCredit();
				return true;

				break;	

			default:
				return false;
				break;
		}
	}

	public function resetLeaveCreditsByLeaveId( $leave_id = 0 ) {
		G_Employee_Leave_Available_Manager::resetLeaveCreditsByLeaveId($leave_id, 0);
	}

	public function getDefaultLeaveGeneralRule() {
		return G_Settings_Leave_General_Finder::findDefaultLeaveGeneralRule();
	}

	public function save() {		
		return G_Settings_Leave_General_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Leave_General_Manager::delete($this);
	}	
	
}
?>