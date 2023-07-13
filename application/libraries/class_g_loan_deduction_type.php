<?php
class G_Loan_Deduction_Type extends Loan_Deduction_Type {
	public $is_archive;
	public $date_created;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	//defaults
	const BIMONTHLY = 1;
	const WEEKLY    = 8;
	const DAILY     = 9;
	const MONTHLY   = 2;
	public $government_loan_type_ids = array(3,4,8,10,11,12,13,16);
	
	public function __construct() {
		
	}

	public function getGovernmentLoanType(){
		$s_government_loan_types = implode(",", $this->government_loan_type_ids);
		$data = G_Loan_Deduction_Type_Finder::findAllByIds($s_government_loan_types);
		return $data;

	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}	
	
	public function save() {		
		return G_Loan_Deduction_Type_Manager::save($this);
	}
	
	public function delete() {
		return G_Loan_Deduction_Type_Manager::delete($this);
	}
}
?>