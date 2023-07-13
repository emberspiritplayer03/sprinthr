<?php
class G_Employee_Earnings extends Employee_Earnings {
	protected $is_archive;
	protected $date_created;
	protected $remarks;	
	
	const YES = 'Yes';
	const NO  = 'No';
	

    const IS_TAXABLE_YES = 'Yes';
    const IS_TAXABLE_NO = 'No';
	
	const PENDING  = 'Pending';
	const APPROVED = 'Approved';

	const EARNING_TYPE_PERCENTAGE = 1;
	const EARNING_TYPE_AMOUNT     = 2;

	const APPLIED_TO_EMPLOYEE     = 1;
	const APPLIED_TO_DEPARTMENT   = 2;
	const APPLIED_TO_EMPLOYMENT_STATUS = 3;
	const APPLIED_TO_ALL          = 4;

	const APPLY_TO_ALL_ID = 0;

	const PERCENTAGE_SELECTION_MONTHLY = 1; //Monthly 
	const PERCENTAGE_SELECTION_DAILY   = 2; //Daily

	protected $apply_to_ids = array();
	private $continue  = false;
	private $bulk_data = array();

	public function __construct() {
		
	}

	public function isNotArchive() {
		$this->is_archive = self::NO;
	}

	public function isArchive() {
		$this->is_archive = self::YES;
	}

	public function approved() {
		$this->status = self::APPROVED;
	}

	public function earningTypeToAmount() {
		$this->earning_type = self::EARNING_TYPE_AMOUNT;
	}

	public function earningTypeToPercentage() {
		$this->earning_type = self::EARNING_TYPE_PERCENTAGE;
	}

	public function applyToEmployee() {
		$this->applied_to = self::APPLIED_TO_EMPLOYEE;
	}

	public function applyToDepartment() {
		$this->applied_to = self::APPLIED_TO_DEPARTMENT;
	}

	public function applyToEmploymentStatus() {
		$this->applied_to = self::APPLIED_TO_EMPLOYMENT_STATUS;
	}

	public function applyToAll() {
		$this->applied_to = self::APPLIED_TO_ALL;
	}

	public function setApplyToIds( $ids = array() ) {
		$this->apply_to_ids = $ids;
		return $this;
	}

	public function getValidEarningTypeSelections(){
		$selections = array(self::EARNING_TYPE_AMOUNT => "Amount",self::EARNING_TYPE_PERCENTAGE => "Percentage");
		return $selections;
	}

	public function setAsNotArchive() {
		$this->is_archive = self::NO;
	}

	public function setAsIsArchive() {
		$this->is_archive = self::YES;
	}

	public function setAsTaxable() {
		$this->is_taxable = self::IS_TAXABLE_YES;
	}

	public function setAsIsNotTaxable() {
		$this->is_taxable = self::IS_TAXABLE_NO;	
	}	

	public function taxable() {
		$this->is_taxable = self::IS_TAXABLE_YES;
	}

	public function notTaxable() {
		$this->is_taxable = self::IS_TAXABLE_NO;	
	}

	public function setAsPending() {
		$this->status = self::PENDING;
	}

	public function setAsApproved() {
		$this->status = self::APPROVED;
	}

	public function getValidPercentageSelections(){
		$selections = array(self::PERCENTAGE_SELECTION_MONTHLY => "Monthly Salary", self::PERCENTAGE_SELECTION_DAILY => "Daily Salary");
		return $selections;
	}

	public function getPercentageSelectionDescription( $percentage_multiplier_selected = 0 ){
		$selections = array(self::PERCENTAGE_SELECTION_MONTHLY => "Monthly Salary", self::PERCENTAGE_SELECTION_DAILY => "Daily Salary");
		return $selections[$percentage_multiplier_selected];
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}

    public function isApproved() {
        if ($this->status == self::APPROVED) {
            return true;
        } else {
            return false;
        }
    }

    public function isArchived() {
        if ($this->is_archive == self::YES) {
            return true;
        } else {
            return false;
        }
    }

    public function isApplyToAllEmployees() {
        if ($this->apply_to_all_employee == self::YES) {
            return true;
        } else {
            return false;
        }
    }

    public function isTaxable() {
        if ($this->taxable == self::IS_TAXABLE_YES) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setDateCreated($value) {
		$date_format = date("Y-m-d H:i:s",strtotime($value));
		$this->date_created = $date_format;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}

	public function createApplyToAllEarningData() {
		if( $this->earning_type != "" ){
			$valid_earning_type_selections = self::getValidEarningTypeSelections();		
			if( array_key_exists($this->earning_type, $valid_earning_type_selections) ){				
				$this->object_id  = self::APPLY_TO_ALL_ID;
				$this->applied_to = self::APPLIED_TO_ALL;
				$this->object_description = "Applied to all employees";

				if( $this->earning_type == self::EARNING_TYPE_PERCENTAGE ){
					$this->amount = 0;	
					$percentage_description = self::getPercentageSelectionDescription($this->percentage_multiplier);											
					$this->description = "{$this->percentage}% of {$percentage_description}";		
					$this->continue = true;
				}elseif( $this->earning_type == self::EARNING_TYPE_AMOUNT ){
					$this->description = "{$this->amount}";	
					$this->percentage 			 = 0;
					$this->percentage_multiplier = 0;
					$this->continue = true;
				}else{
					$this->continue = false;
					$this->description = "";						
				}
			}
		}

		return $this;
	}

	public function createBulkEarningData() {
		
		$bulk_insert_data = array();
		if( !empty($this->apply_to_ids) && $this->earning_type != "" ){			
			$valid_earning_type_selections = self::getValidEarningTypeSelections();			
			if( array_key_exists($this->earning_type, $valid_earning_type_selections) ){							
				$a_apply_to = self::createValidApplyToIds($this->apply_to_ids);

				if( $this->earning_type == self::EARNING_TYPE_PERCENTAGE ){
					$this->amount = 0;	
					$percentage_description = self::getPercentageSelectionDescription($this->percentage_multiplier);											
					$this->description = "{$this->percentage}% of {$percentage_description}";		
				}elseif( $this->earning_type == self::EARNING_TYPE_AMOUNT ){
					$this->description = number_format($this->amount, 2);	
					$this->percentage 			 = 0;
					$this->percentage_multiplier = 0;
				}else{
					$this->continue = false;
					$this->description = "";	
					return $this;
				}
				
				foreach( $a_apply_to as $key => $values ){						
					foreach( $values as $subKey => $subValue ){											
						switch (trim($key)) {
							case 'employee':							
								$this->applied_to = self::APPLIED_TO_EMPLOYEE;
								break;
							case 'department':
								$this->applied_to = self::APPLIED_TO_DEPARTMENT;
								break;
							case 'employment_status':
								$this->applied_to = self::APPLIED_TO_EMPLOYMENT_STATUS;
								break;
							default:
								continue;							
								break;
						}						

						$e = G_EMPLOYEE_FINDER::findById($subKey);
						$frequency_id = $e->getFrequencyId();
					
						$this->object_id          = $subKey;
						$this->object_description = $subValue;
						$bulk_insert_data[] = "(" . Model::safeSql($this->company_structure_id) . "," . Model::safeSql($this->object_id) . "," . Model::safeSql($this->object_description) . "," . Model::safeSql($frequency_id) . "," . Model::safeSql($this->applied_to) . "," . Model::safeSql($this->title) . "," . Model::safeSql($this->remarks) . "," . Model::safeSql($this->earning_type) . "," . Model::safeSql($this->percentage) . "," . Model::safeSql($this->percentage_multiplier) . "," . Model::safeSql($this->amount) . "," . Model::safeSql($this->payroll_period_id) . "," . Model::safeSql($this->description) . "," . Model::safeSql($this->status) . "," . Model::safeSql($this->is_taxable) . "," . Model::safeSql($this->is_archive) . "," . Model::safeSql($this->date_created) . ")";
					}					
					
				}

				if( count($bulk_insert_data) > 0 ){
					$this->bulk_data = $bulk_insert_data;
					$this->continue  = true;
				}
				
			}
		}

		return $this;
	}

	public function bulkInsertData() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';

		if( !empty($this->bulk_data) && $this->continue ){
			$is_success = G_Employee_Earnings_Manager::bulkInsertData( $this->bulk_data );
			if( $is_success ){
				$return['is_success'] = true;
				$return['message']    = 'Record(s) saved';
			}
		}

		return $return;
	}

	private function createValidApplyToIds( $ids = array() ) {		
		$new_ids  = array();
		$is_valid = false;
		if( !empty($ids) ){
			foreach( $ids as $key => $value ){				
				$a_values = array();
				$a_values = explode(",", $value);
				foreach( $a_values as $eid ){	
					$description = "";	
					$id     	 = Utilities::decrypt(trim($eid));			
					switch ($key) {
						case 'employee':							
							$fields = array("CONCAT(firstname, ' ', lastname)AS object_description"); 
							$data   = G_Employee_Helper::sqlGetEmployeeDetailsById($id, $fields);		
							$is_valid = true;					
							break;
						case 'department':
							$fields = array("CONCAT(type, ' : ', title)AS object_description"); 
							$data   = G_Company_Structure_Helper::sqlDataById($id, $fields);
							$is_valid = true;					
							break;
						case 'employment_status':
							$fields = array("CONCAT('Employment status : ', status)AS object_description"); 
							$data   = G_Settings_Employment_Status_Helper::sqlDataById($id, $fields);
							$is_valid = true;					
							break;
						default:
							$is_valid = false;
							break;
					}

					if( !empty($data) && $is_valid ){
						$description = $data['object_description'];
						$new_ids[$key][$id] = $description;
					}
				}
			}
		}		
		return $new_ids;
	}
		
	public function save() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';
		
		if( $this->continue && $this->earning_type != "" ){
			$is_success = G_Employee_Earnings_Manager::save($this);
			if( $is_success ){
				$return['is_success'] = true;
				$return['message']    = 'Record saved';
			}
		}

		return $return;
	}	
	
	public function approve() {
		G_Employee_Earnings_Helper::approve($this);
	}
	
	public function disapprove() {
		G_Employee_Earnings_Helper::disapprove($this);
	}
		
	public function pending() {		
		G_Employee_Earnings_Manager::pending($this);
	}
	
	public function archive() {		
		G_Employee_Earnings_Helper::archive($this);
	}
	
	public function restore_archived() {		
		G_Employee_Earnings_Manager::restore_archived($this);
	}
	
	public function delete() {
		G_Employee_Earnings_Manager::delete($this);
	}
}
?>