<?php
class G_Employee_Fixed_Contribution extends Employee_Fixed_Contribution {

	const TYPE_SSS  = 'SSS';
	const TYPE_PHIC = 'PHIC';
	const TYPE_HDMF = 'HDMF';
	 
	public function __construct() {
		
	}

	public function getFixedContributionTypes(){
		$types = array(self::TYPE_SSS, self::TYPE_HDMF, self::TYPE_PHIC);
		return $types;
	}

	public function getEmployeeFixedContributions( $employee_id = null ){
		$fixed_contributions = G_Employee_Fixed_Contribution_Helper::getEmployeeFixedContributions($employee_id);
		return $fixed_contributions;
	}

	public function getEmployeeFixedSSSContri( $employee_id = null ){
		$fixed_contributions = self::getEmployeeContributionsByType( $employee_id, self::TYPE_SSS );
		return $fixed_contributions;
	}

	public function getEmployeeFixedPhilHealthContri( $employee_id = null ){
		$fixed_contributions = self::getEmployeeContributionsByType( $employee_id, self::TYPE_PHIC );
		return $fixed_contributions;
	}

	public function getEmployeeFixedPagibigContri( $employee_id = null ){
		$fixed_contributions = self::getEmployeeContributionsByType( $employee_id, self::TYPE_HDMF );
		return $fixed_contributions;
	}

	public function getEmployeeContributionsByType( $employee_id = null, $type = '' ){
		$fixed_contri = G_Employee_Fixed_Contribution_Helper::getEmployeeContributionsByType($employee_id, $type);
		return $fixed_contri;
	} 
							
	public function save() {
		return G_Employee_Fixed_Contribution_Manager::save($this);
	}

	public function deleteAllByEmployeeId() {
		return G_Employee_Fixed_Contribution_Manager::deleteAllByEmployeeId($this->employee_id);
	}
}
?>