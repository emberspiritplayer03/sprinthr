<?php
class G_Employee_Loan_Payment_History extends Employee_Loan_Payment_History {	
	
	public $fields;
	const NO = 'No';
	const YES = 'Yes';	

	public function __construct() {
		
	}

	public function setDefaultRemarks( $value = '' ) {
		if( $value != '' ){
			$this->remarks = "Deducted to cutoff period : {$value}";
		}else{
			$this->remarks = "Deducted to payroll";
		}
	}

	public function setAsLock() {
		$this->is_lock = self::YES;
	}

	public function setAsUnlock() {
		$this->is_lock = self::NO;
	}

	public function setFields($value = array()){
		$this->fields = $value;
	}

	public function getLoanPaymentHistoryByLoanId() {
		$data = array();

		if( !empty($this->employee_loan_id) ){
			$data = G_Employee_Loan_Payment_History_Helper::sqlLoanPaymentHistoryDetailsByEmployeeLoanId($this->employee_loan_id,$this->fields);
		}
		
		return $data;
	}

	public function objectRules() {
		$is_debug = false;

		$return['is_valid'] = false;
		$return['message']  = 'Invalid entries';
		
		if( $this->loan_id == '' ){
			$return['message']  = 'Cannot find loan id';
		}elseif( $this->loan_payment_scheduled_date == '' ){
			$return['message'] = 'Expected loan payment date is required!';
		}elseif( $this->amount_to_pay <= 0 ){			
			$return['message'] = 'Amount to pay cannot be null or 0!';
		}elseif( $this->amount_to_pay < $this->amount_paid ){
			$return['message'] = 'Amount to pay must not be greater than the expected amount to pay!';			
		/*}elseif( $this->date_paid <> '' && (strtotime($this->date_paid) < strtotime($this->loan_payment_scheduled_date)) ){					
			$return['message'] = 'Date paid must be greater than or equal to expected date of payment';*/
		/*}elseif($this->amount_paid > 0 && $this->date_paid == ''){
			$return['message'] = 'Date paid must not be empty!';*/
		}else{
			$return['is_valid'] = true;
			$return['message']  = 'Invalid entries';
		}	

		if( $is_debug ){
			Utilities::displayArray($return);
		}		

		return $return;
	}

	public function setData( $data = array() ){
		foreach( $data as $key => $d ){
			if( property_exists($this, $key) ){
				if( ($key == 'loan_payment_scheduled_date' || $key == 'date_paid') && trim($d) != '' ){
					$value = date("Y-m-d",strtotime($d));
				}else{
					$value = $d;
				}
				$this->{$key} = $value;
			}
		}
	}  

	public function setToPaid() {
		$return['is_success'] = false;
		$return['message']    = "Cannot update record";
		if( $this->id > 0 ){
			/*if( $this->amount_paid < $this->amount_to_pay ){
				$return['message'] = "Invalid form entry <p><b>Amount paid</b> must be greater than or equal to <b>expected amount to pay</b></p>";
				return $return;
			}*/
			$this->is_lock = self::YES;
			$return = self::update();
		}

		return $return;
	}
	
	public function save() {		
		$return['is_success'] = false;
		$return['message']    = "Cannot save record";
		$return['last_inserted_id'] = 0;

		$validate = $this->objectRules();		
		if( $validate['is_valid'] ){
			$id = G_Employee_Loan_Payment_History_Manager::save($this);
			if( $id > 0 ){
				$return['is_success'] = true;
				$return['message']    = "Record saved";
				$return['last_inserted_id'] = $id;
			}
		}else{
			$return['message'] = $validate['message'];
		}

		return $return;
		
	}

	public function updateLoanHeaderAmountPaid() {
		$new_total_amount = 0;
		if( $this->loan_id > 0 ){
			$l = new G_Employee_Loan();
			$l->setId($this->loan_id);
			$new_total_amount = $l->updateAmountPaid();
		}
		return $new_total_amount;
	}

	public function update() {
		$return['is_success'] = false;
		$return['message']    = "Cannot update record";

		if( $this->id > 0 ){
			$required = self::requiredFields();			
			
			/*foreach( $required as $key => $r ){
				if( $this->{$key} == '' ){
						$return['message'] = "Required field <b>{$r}</b> must not be empty";
						return $return;
				}
			}*/

			$validate = $this->objectRules();	
			if( $validate['is_valid'] ){
				$total_records_updated = G_Employee_Loan_Payment_History_Manager::update($this);
				$return['is_success']  = true;
				$return['message']     = "<b>{$total_records_updated}</b> record(s) has been updated";	
			}else{
				$return['message'] = $validate['message'];
			}
		}

		return $return;
	}

	public function delete() {
		$return['is_success'] = false;
		$return['message']    = "No record(s) to delete";

		$total_deleted = G_Employee_Loan_Payment_History_Manager::delete($this);
		if( $total_deleted > 0 ){
			$return['is_success'] = true;
			$return['message']    = "<b>{$total_deleted}</b> record(s) deleted";			
		}
		return $return;
	}
	
}
?>