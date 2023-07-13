<?php
class G_Settings_Deduction_Breakdown {
	
	public $id;
	public $name;
	public $breakdown;
	public $salary_credit;
	public $is_active;
	public $is_taxable;
	
	const YES 	= 'Yes';
	const NO 	= 'No';

	const OPTION_SALARY_CREDIT_BASIC_PAY = 0;
	const OPTION_SALARY_CREDIT_GROSS_PAY = 1;
	const OPTION_SALARY_CREDIT_NA        = 2;
	const OPTION_SALARY_CREDIT_MONTHLY_PAY = 3;
	
	const SSS   	  = 1;
	const HDMF 	      = 2;
	const PHIL_HEALTH = 3;
	const TAX   	  = 4;

	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setBreakdown($value) {
		$this->breakdown = $value;	
	}
	
	public function getBreakdown() {
		return $this->breakdown;
	}

	public function setIsActive($value) {
		$this->is_active = $value;	
	}
	
	public function getIsActive() {
		return $this->is_active;
	}

	public function setIsTaxable($value) {
		$this->is_taxable = $value;
	}

	public function getIsTaxable() {
		return $this->is_taxable;
	}	

	public function setSalaryCredit( $value ) {
		$this->salary_credit = $value;
	}

	public function getSalaryCredit() {
		return $this->salary_credit;
	}

	public function getOptionsSalaryCredit() {
		$salary_credit_options = array(self::OPTION_SALARY_CREDIT_BASIC_PAY => 'Basic Pay', self::OPTION_SALARY_CREDIT_MONTHLY_PAY => 'Monthly Pay', self::OPTION_SALARY_CREDIT_GROSS_PAY => 'Gross Pay', self::OPTION_SALARY_CREDIT_NA => 'NA');
		return $salary_credit_options;
	}

	public function getOptionsIsTaxable() {
		$is_taxable_options = array(G_Settings_Deduction_Breakdown::YES, G_Settings_Deduction_Breakdown::NO);
		return $is_taxable_options;
	}

	public function getAllContributions( $ids = array() ) {
		$data   = array();

		if( empty( $ids ) ){
			$ids    = array(self::SSS, self::HDMF, self::PHIL_HEALTH);		
		}

		$data   = G_Settings_Deduction_Breakdown_Helper::sqlDeductionDataByIds($ids);
		$data   = Tools::encryptMulitDimeArrayIndexValue("id",$data);
		return $data;
	}

	public function getActiveContributionsBreakDown() {
		$data     = array();
		$new_data = array();

		$data = G_Settings_Deduction_Breakdown_Helper::sqlGetAllActiveDeductionBreakDown();
		
		foreach( $data as $d){
			$new_data[$d['name']]['breakdown']     = $d['breakdown']; 
			$new_data[$d['name']]['is_active'] 	   = $d['is_active'];
			$new_data[$d['name']]['is_taxable']    = $d['is_taxable'];
			$new_data[$d['name']]['salary_credit'] = $d['salary_credit'];
		}

		return $new_data;
	}
	
	public function save() {
		return G_Settings_Deduction_Breakdown_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Deduction_Breakdown_Manager::delete($this);
	}
}

?>