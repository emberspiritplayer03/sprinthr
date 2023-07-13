<?php
class G_Employee_Loan_Payment_Schedule extends Employee_Loan_Payment_Schedule {
			
	public $is_archive;
	public $date_created;
	protected $a_bulk_insert   = array();		
	
	const IN_PROGRESS 	= 'In Progress';
	const CANCELLED		= 'Cancelled';	
	const DONE			= 'Done';
	const PENDING       = 'Pending';
	
	const BI_MONTHLY    = "Bi-monthly";
	const MONTHLY		= "Monthly";	
	
	const DEFAULT_INTEREST = 0;
	
	const YES = 'Yes';
	const NO  = 'No';
			
	public function __construct() {
		
	}

	public function isLock() {
		if( $this->is_lock == self::YES ){
			return true;
		}else{
			return false;
		}
	}

	public function bulkSave( $bulk_data = array(), $fields = array() ) {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data';

		$this->a_bulk_insert = $bulk_data;
		if( !empty($this->a_bulk_insert) ){
			$is_success = G_Employee_Loan_Payment_Schedule_Manager::bulkInsertData($this->a_bulk_insert, $fields);
			if( $is_success ){
				$return['is_success'] = true;
				$return['message']    = 'Record(s) was successfully saved';
			}
		}

		return $return;
	}
	
	public function save() {		
		return G_Employee_Loan_Payment_Schedule_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Loan_Payment_Schedule_Manager::delete($this);
	}
}
?>