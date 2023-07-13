<?php
		
class Net_Taxable_Calculator {
	
	const LIMIT_TAXABLE_ALLOWANCE = 30000;
	const LIMIT_DEPENDENTS        = 4;
	const FIXED_EXEMPTIONS        = 50000;
	const EXEMPTION_AMOUNT        = 25000;
	
	public function __construct() {
		
	}
	
	public function compute($data = array()) {
		$net_taxable_compensation = 0;		

		if( !empty( $data ) ){
			//Exemptions       
			$dependents           = $data['qualified_dependents'];
	        $additional_exemption = self::computeAdditionalExemptions($dependents);
	        $fixed_exemptions     = self::FIXED_EXEMPTIONS;

	        $total_taxable_compensation = self::computeTaxableCompensation($data);
	        $net_taxable_compensation   = $total_taxable_compensation - ($fixed_exemptions + $additional_exemption);
	    }

        return $net_taxable_compensation;
	}

	public function computeTaxableCompensation($data = array()) {
		$taxable_compensation = 0;		

		if( !empty( $data ) ){
			$monthly_salary = $data['monthly_salary'];
	        $months_stayed  = $data['months_stayed'];	        
	        $overtime       = $data['overtime_amount'];
	        $_13thmonth     = $data['total_13th_month'];
	        $sss            = $data['sss_amount'];
	        $philhealth     = $data['philhealth_amount'];
	        $pagibig        = $data['pagibig_amount'];
	        $non_taxable_allowance = $data['non_taxable_amount'];
	        $taxable_allowance     = $data['taxable_allowance_amount'];
	        
	        //Limit non taxable allowance
	        if( $non_taxable_allowance > self::LIMIT_TAXABLE_ALLOWANCE ){
	            $non_taxable_allowance = self::LIMIT_TAXABLE_ALLOWANCE;
	        }

	        $total_gross 		  = $monthly_salary * $months_stayed;
	        $total_net   		  = $total_gross + $overtime + $_13thmonth + $taxable_allowance;
	        $total_deductions	  = $sss + $pagibig + $philhealth + $non_taxable_allowance;

	        $taxable_compensation = $total_net - $total_deductions;	       
	    }

        return $taxable_compensation;
	}

	public function computeAdditionalExemptions($dependents = 0) {
		//Limit dependents
        if( $dependents > self::LIMIT_DEPENDENTS ){
        	$dependents = self::LIMIT_DEPENDENTS;
        }
        $additional_exemption = self::FIXED_EXEMPTIONS + ($dependents * self::EXEMPTION_AMOUNT);
        return $additional_exemption;
	}
}	
?>