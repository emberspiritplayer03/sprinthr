<?php
class G_Loan_Type extends Loan_Type {
	public $is_archive;
	public $date_created;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	public function __construct() {
		
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

	public function getAllLoanTypes(){
		$data   = array();
		$fields = array("id","loan_type");
		$data   = G_Loan_Type_Helper::sqlAllIsNotArchiveLoanTypes($fields);
		return $data;
	}
	
	public function save() {		
		return G_Loan_Type_Manager::save($this);
	}
	
	public function delete() {
		return G_Loan_Type_Manager::delete($this);
	}
}
?>