<?php
class G_Settings_Pay_Period {
	const TYPE_BI_MONTHLY = 'BMO';
	const TYPE_MONTHLY = 'MO';
	const TYPE_WEEKLY = 'WEEKLY';
	const TYPE_MONTHLY2 = "MONTHLY";
	
	const IS_DEFAULT = 1;

	const NAME_BI_MONTHLY = 'Bi-Monthly';
	const NAME_WEEKLY = 'Weekly';

			
	public $id;
	public $company_structure_id;
	public $pay_period_code;	
	public $pay_period_name;
	public $cut_off;
	public $payout_day;
	public $is_default;
	//objects
	public $gcs;
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setPayPeriodCode($value) {
		$this->pay_period_code = $value;
	}
	
	public function getPayPeriodCode() {
		return $this->pay_period_code;
	}
	
	
	public function setPayPeriodName($value) {
		$this->pay_period_name = $value;
	}
	
	public function getPayPeriodName() {
		return $this->pay_period_name;
	}



	public function setCutOff($value) {
		$this->cut_off = $value;
	}
	
	public function getCutOff() {
		return $this->cut_off;
	}
	
	public function setPayOutDay($value) {
		$this->payout_day = $value;
	}
	
	public function getPayOutDay() {
		return $this->payout_day;
	}

	public function setIsDefault($value) {
		$this->is_default = $value;
	}
	
	public function getIsDefault() {
		return $this->is_default;
	}


	public function getDefaultPayPeriod($fields = array()) {
		$data = array();
		$data = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
		return $data;
	}

	public function getValidCutoffDays() {
		$cutoff_days 		   = array();		
		$fields 			   = array("payout_day");
		$default_cutoff_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);		
		if( !empty($default_cutoff_period) ){
			$cutoff_days = explode(",", $default_cutoff_period['payout_day']);
		}

		return $cutoff_days;
	}
	
	public function save(G_Company_Structure $gcs) {
		return G_Settings_Pay_Period_Manager::save($this, $gcs);
	}

	public function updateDefault(){
		return G_Settings_Pay_Period_Manager::updateDefault($this);
	}
	
	public function setAsDefault(G_Company_Structure $gcs) {
		return G_Settings_Pay_Period_Manager::setAsDefault($this, $gcs);
	}
	
	public function setAllNotDefault(G_Company_Structure $gcs) {
		return G_Settings_Pay_Period_Manager::setAllNotDefault($gcs);
	}
	
	public function delete() {
		return G_Settings_Pay_Period_Manager::delete($this);
	}
}
?>