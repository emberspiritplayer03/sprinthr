<?php
class G_Payroll_Variables extends Payroll_Variables {
	const DEFAULT_ID = 1;
	public function __construct() {
		
	}

	public function getDefaultSettings() {
		$data = array();
		$data = G_Payroll_Variables_Helper::sqlGetDefaultSettings();
		return $data;
	}

	/*
		Usage :
		$p = new G_Payroll_Variables();
        $payroll_variables = $p->getPayrollSettingsVarialbes();//Returns array
	*/

	public function getPayrollSettingsVarialbes(){
		$data = G_Payroll_Variables_Helper::sqlGetDefaultSettings();
		if( $data ){
			if( $data['number_of_days'] <= 0 ){
				$data['number_of_days'] = 26.17; //Set default value
			}
		}else{
			$data['number_of_days'] = 26.17; //Must return default value if no records found
		}

		return $data;
	}

	/*
		Usage : Update selected payroll setting field
		$id            = 1;
		$field         = "number_of_days";
		$value         = 200;
		$settings_data = array($field => $value);
		$p = new G_Payroll_Variables();
		$p->setId($id);			
		$json = $p->updatePayrollSetting($settings_data);//Returns boolean
	*/

	public function updatePayrollSetting( $data = array() ) {
		$return = array();

		if( !empty($this->id) && !empty($data) ){
			foreach( $data as $key => $value ){
				if( property_exists($this, $key) ){
					$is_success = self::updateSelectedFieldValue($key, $value);
					if( $is_success ){
						$return['is_success'] = true;
						$return['message']    = "Record Updated";
					}else{
						$return['is_success'] = false;
						$return['message']    = "Cannot update record";	
					}
				}else{
					$return['is_success'] = false;
					$return['message']    = "Cannot update record";
				}
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = "Cannot update record";
		}

		return $return;
	}

	public function updateSelectedFieldValue( $field = '', $value = '' ) {
		return G_Payroll_Variables_Manager::updateSelectedFieldValue($this->id, $field, $value);
	}	
							
	public function save() {
		return G_Payroll_Variables_Manager::save($this);
	}
}
?>