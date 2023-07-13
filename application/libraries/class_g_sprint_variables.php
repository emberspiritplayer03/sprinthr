<?php
class G_Sprint_Variables extends Sprint_Variables {	

	const OPTION_WRKNG_DAYS_5DW = 261; // 5 days a week / 261 days
	const OPTION_WRKNG_DAYS_6DW = 313; // 6 days a week / 314 days
	const OPTION_WRKNG_DAYS_7DW = 365; // 7 days a week / 365 days
	const OPTION_WRKNG_DAYS_7DW_360 = 360; // 7 days a week / 365 days
	const OPTION_WRKNG_DAYS_7DWS = 392.5; // 7 days a week (required to work on special days) / 392.5 days

	const FIELD_DEFAULT_TOTAL_WRKNG_DAYS = 'default_total_working_days';
	const FIELD_DEFAULT_FISCAL_YEAR      = 'default_fiscal_year';
	const FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE = 'default_earnings_ceiling_non_taxable';
	const FIELD_CETA = 'ceta';
	const FIELD_SEA  = 'sea';
	const FIELD_MIN_RATE = 'minimum_rate';
	const FIELD_NIGHTSHIFT_HOUR = 'night_shift_hour';
	const FIELD_LOANS_GROSS_LIMIT = 'loans_gross_limit';
	const FIELD_DEFAULT_WEEKLY_PAYROLL_RATES      = 'default_default_weekly_payroll_rates';
	const FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES      = 'default_default_bimonthly_payroll_rates';
	const FIELD_DEFAULT_MONTHLY_PAYROLL_RATES      = 'default_default_monthly_payroll_rates';

	protected $default_form_input;
	protected $variable_name;
	protected $default_variables   = array(self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS, self::FIELD_NIGHTSHIFT_HOUR, self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE, self::FIELD_CETA, self::FIELD_SEA, self::FIELD_LOANS_GROSS_LIMIT, self::FIELD_MIN_RATE, self::FIELD_DEFAULT_FISCAL_YEAR, self::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES, self::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES,  self::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES);

	const YES = 1;
	const NO  = 0;

	public function __construct( $value = '' ) {
		$this->variable_name = trim(strtolower($value));
	}	

	public function customValueVariableMapping() {
		$custom_value = array(
			'custom_value_a' => array(
				self::FIELD_CETA => 1,
				self::FIELD_SEA => 1
			)
		);

		return $custom_value;
	}

	public function getCustomValueDefaultFormInputValue( $custom_value_key = '' ) {
		$default_form_input_value = '';

		if( $this->variable_name != '' ){			
			$custom_value = self::customValueVariableMapping();			
			$default_form_input_value = $custom_value[$custom_value_key][$this->variable_name];
		}
		
		$this->default_form_input = $default_form_input_value;	
		return $this;
	}

	public function variableCustomValueFormInput() {
		$html_input = '';
		if( $this->default_form_input != "" && $this->variable_name != '' ){						
			switch ($this->variable_name) {
				case self::FIELD_CETA:
					if( $this->custom_value_a != '' ){
						$input_value = $this->custom_value_a;
						$is_checked  = "checked='checked'";
					}else{						
						$input_value = $this->default_form_input;
						$is_checked  = '';
					}					
					$html_input = "<label class=\"checkbox\"><input name=\"custom_value_a\" type=\"checkbox\" {$is_checked} value='{$input_value}' />Apply only to employees which daily rate is less than or equal to minimum rate</label>";
					break;
				case self::FIELD_SEA:
					if( $this->custom_value_a != '' ){
						$input_value = $this->custom_value_a;
						$is_checked  = "checked='checked'";
					}else{						
						$input_value = $this->default_form_input;
						$is_checked  = '';
					}					
					$html_input = "<label class=\"checkbox\"><input name=\"custom_value_a\" type=\"checkbox\" {$is_checked} value='{$input_value}' />Apply only to employees which daily rate is less than or equal to minimum rate</label>";
					break;				
				default:					
					break;
			}
		}
		return $html_input;
	}

	public function getVariableDescription( $sprint_variable = '' ){		
		$description = '';
		$variables = array(
			self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS => 'Default total working days',
			self::FIELD_NIGHTSHIFT_HOUR => 'Night Shift hour',
			self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE => 'Non-taxable earnings ceiling amount',
			self::FIELD_CETA => 'CTPA',
			self::FIELD_SEA => 'SEA',
			self::FIELD_LOANS_GROSS_LIMIT => 'Grosspay percentage limit for deducting loans',
			self::FIELD_DEFAULT_FISCAL_YEAR => 'Fiscal Year',
			self::FIELD_MIN_RATE => 'Minimum Rate',
			'5DW' => '5 days a week',
			'6DW' => '6 days a week',
			'7DW' => '7 days a week',
			'7DWS' => '7 days a week (required to work on special days)',
			self::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES => 'Mandated Payroll Rates for Weekly employees',
			self::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES => 'Mandated Payroll Rates for Bi-Monthly employees',
			self::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES => 'Mandated Payroll Rates for Monthly employees',
		);

		if( !empty($sprint_variable) ){
			$description = $variables[$sprint_variable];
		}else{
			$description = $variables[$this->variable_name];
		}

		return $description;
	}

	public function optionsWorkingDays() {		
		$options = array(
				0 => array('description' => '5 days a week', 'num_days' => self::OPTION_WRKNG_DAYS_5DW), 
				1 => array('description' => '6 days a week', 'num_days' => self::OPTION_WRKNG_DAYS_6DW), 
				2 => array('description' => '6 days a week (312 days)', 'num_days' => 312), 
				3 => array('description' => '7 days a week', 'num_days' => self::OPTION_WRKNG_DAYS_7DW),
				4 => array('description' => '7 days a week (required to work on special days)', 'num_days' => self::OPTION_WRKNG_DAYS_7DWS)
		);
		return $options;
	}

	public function validWorkingDaysOptions() {
		$options = array('5DW' => self::OPTION_WRKNG_DAYS_5DW,'6DW' => self::OPTION_WRKNG_DAYS_6DW, '7DW' => self::OPTION_WRKNG_DAYS_7DW, '7DWS' => self::OPTION_WRKNG_DAYS_7DWS);
		return $options;
	}

	public function getWorkingDaysDescriptionNumberOfDays( $description ) {
		$number_of_days = 0;
		$options = self::optionsWorkingDays();
		foreach( $options as $option ){
			$option_description = $option['description'];
			$option_num_days    = $option['num_days'];
			if( $option_description == $description ){
				$number_of_days = $option_num_days;
				return $number_of_days;
			} 
		}

		return $number_of_days;
	}

	public function loadDefaultEarningsCeilingNonTaxable() {
		$return = array();

		$variable_details = array(self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE => 30000);		
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultCetaAndSea(){
		$return = array();

		$variable_details = array(self::FIELD_CETA => 12.50, self::FIELD_SEA => 13.00);
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultMinimumRate(){
		$return = array();

		$variable_details = array(self::FIELD_MIN_RATE => 310.00);
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultNightShiftHours(){
		$return = array();

		$variable_details = array(self::FIELD_NIGHTSHIFT_HOUR => '22:00:00 to 06:00:00');
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultLoansGrossLimit(){
		$return = array();

		$variable_details = array(self::FIELD_LOANS_GROSS_LIMIT => '20');
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultWorkingDays() {
		$return = array();

		$variable_details = array(self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS => self::OPTION_WRKNG_DAYS_5DW);		
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadDefaultFiscalYear() {
		$return = array();

		$variable_details = array(self::FIELD_DEFAULT_FISCAL_YEAR => FISCAL_YEAR);	// config_client	
		$return = self::addVariable($variable_details);

		return $return;
	}

	public function loadCetaSea()
	{

	}

	/*
		Usage : 
		$variable = array('field_name' => 'value');
		$return = self::addVariable($variable);
	*/
	protected function addVariable( $variable = array() ) {		
		$return = array();

		if( !empty($variable) ){			
			foreach( $variable as $key => $value ){				
				$is_exists = self::checkIfVariableExists($key);
				if( !$is_exists ){				
					$this->variable_name  = $key;
					$this->variable_value = $value;
					self::save();
					$return['variable_name'][$this->variable_name] = $this->variable_value;				
				}
			}
		}

		return $return;
	}

	protected function checkIfVariableExists( $variable_name = '' ) {
		$is_variable_exists = G_Sprint_Variables_Helper::sqlIsVariableExists($variable_name);
		return $is_variable_exists;
	}

	protected function isVariableNameValid() {
		$is_valid 		 = false;
		$valid_variables = $this->default_variables;

		if( !empty($this->variable_name) ){			
			$array_key = $this->variable_name;			
			if( in_array($array_key, $valid_variables) ){				
				$is_valid = true;
			}
		}  

		return $is_valid;
	}
	
	public function getVariableValue() {
		$var_value = '';

		if( $this->variable_name != '' && self::isVariableNameValid() ){			
			$var_value = G_Sprint_Variables_Helper::sqlVariableValue( $this->variable_name );
		}

		return $var_value;
	}

	public function getVariableCustomValueA() {
		$var_custom_value_a = '';

		if( $this->variable_name != '' && self::isVariableNameValid() ){			
			$var_custom_value_a = G_Sprint_Variables_Helper::sqlVariableCustomValueA( $this->variable_name );
		}

		return $var_custom_value_a;
	}

	public function getPayrollDefaultVariables(){
		$variables = array();

		/*$this->variable_name = self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE;
		$value = self::getVariableValue();		
		$variables[self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_EARNINGS_CEILING_NON_TAXABLE]['value'] = number_format($value,2);*/

		$this->variable_name = self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS;
		$value = self::getVariableValue();
		$custom_value_a = self::getVariableCustomValueA();	
		$variables[self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_TOTAL_WRKNG_DAYS]['value'] = $value . " days";

		$this->variable_name = self::FIELD_MIN_RATE;
		$value = self::getVariableValue();	
		$custom_value_a = self::getVariableCustomValueA();	
		$variables[self::FIELD_MIN_RATE]['description'] = self::getVariableDescription();
		$variables[self::FIELD_MIN_RATE]['value'] = number_format($value,2);

		$this->variable_name = self::FIELD_CETA;
		$value = self::getVariableValue();	
		$custom_value_a = self::getVariableCustomValueA();	

		if( $custom_value_a != '' ){
			$value = number_format($value,2) . " - Applied only to employees with daily rate less than or equal to minimum rate";
		}

		$variables[self::FIELD_CETA]['description'] = self::getVariableDescription();
		$variables[self::FIELD_CETA]['value'] = $value;

		$this->variable_name = self::FIELD_SEA;
		$value = self::getVariableValue();		
		$custom_value_a = self::getVariableCustomValueA();

		if( $custom_value_a != '' ){
			$value = number_format($value,2) . " - Applied only to employees with daily rate less than or equal to minimum rate";
		}

		$variables[self::FIELD_SEA]['description'] = self::getVariableDescription();
		$variables[self::FIELD_SEA]['value'] = $value;

		$this->variable_name = self::FIELD_NIGHTSHIFT_HOUR;
		$value = self::getVariableValue();		
		$variables[self::FIELD_NIGHTSHIFT_HOUR]['description'] = self::getVariableDescription();
		$variables[self::FIELD_NIGHTSHIFT_HOUR]['value'] = $value;

		$this->variable_name = self::FIELD_LOANS_GROSS_LIMIT;
		$value = self::getVariableValue();		
		$variables[self::FIELD_LOANS_GROSS_LIMIT]['description'] = self::getVariableDescription();
		$variables[self::FIELD_LOANS_GROSS_LIMIT]['value'] = $value;

		$this->variable_name = self::FIELD_DEFAULT_FISCAL_YEAR;
		$value = self::getVariableValue();		
		$variables[self::FIELD_DEFAULT_FISCAL_YEAR]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_FISCAL_YEAR]['value'] = $value;

		$this->variable_name = self::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES;
		$value = self::getVariableValue();		
		$variables[self::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_WEEKLY_PAYROLL_RATES]['value'] = $value;

		$this->variable_name = self::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES;
		$value = self::getVariableValue();		
		$variables[self::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES]['value'] = $value;


		$this->variable_name = self::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES;
		$value = self::getVariableValue();		
		$variables[self::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES]['description'] = self::getVariableDescription();
		$variables[self::FIELD_DEFAULT_MONTHLY_PAYROLL_RATES]['value'] = $value;

		return $variables;
	}

	public function updateVariableValue( $variable = array() ) {		
		$return['is_success'] = false;
		$return['message']    = 'Record not found';

		if( !empty($variable) ){
			foreach( $variable as $key => $value ){
				$is_variable_exists = self::checkIfVariableExists($key);
				if( $is_variable_exists ){
					$this->variable_name  = $key;
					$this->variable_value = $value;
					self::updateValue();
				}
			}

			$return['is_success'] = true;
			$return['message']    = 'Data was successfully updated';
		}

		return $return;
	}

	public function save() {
		$return['is_success'] = false;
		$return['message']    = 'Record not found';

		$id = G_Sprint_Variables_Manager::save($this);
		if( $id ){
			$return['is_success'] = true;
			$return['message']    = 'Record was successfully updated';
		}

		return $return;
	}
							
	public function updateValue() {
		return G_Sprint_Variables_Manager::updateVariableValue($this);
	}
}
?>