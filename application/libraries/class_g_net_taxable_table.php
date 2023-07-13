<?php
class G_Net_Taxable_Table {
	
	protected $net_taxable_compensation;
	protected $withholding_tax;

	public function __construct() {
		
	}

	public function setNetTaxableCompensation($value = 0) {		
		$this->net_taxable_compensation = $value;
		
	}

	public function setWithholdingTax($value = 0) {
		$this->withholding_tax = $value;
	}
							
	public function getTaxDue() {
		$data = array();

		$data['tax_due'] 	 = 0;
		$data['tax_payable'] = 0;
		$data['tax_refund']  = 0;

		if( $this->net_taxable_compensation > 0 ){			
				$net_compensation = $this->net_taxable_compensation;
				$taxable_data     = self::getTaxableCompensationDataByNetCompensation();
				
				$taxable_over       = $taxable_data['over'];
				$taxable_not_over   = $taxable_data['not_over'];
				$taxable_amount     = $taxable_data['amount'];
				$taxable_percentage = $taxable_data['rate_percentage'];
				$tax_due            = ($net_compensation - $taxable_over) * $taxable_percentage + $taxable_amount;
				$tax_withheld       = $this->withholding_tax;

				if( $tax_due < $tax_withheld ){					
					$tax_refund  = $tax_withheld - $tax_due;
					$tax_payable = 0;
				}else{
					$tax_payable = $tax_due - $tax_withheld;
					$tax_refund  = 0;
				}
				
				$data['tax_due']     = $tax_due;
				$data['tax_payable'] = $tax_payable;
				$data['tax_refund']  = $tax_refund;
		}

		return $data;
	}

	public function getTaxableCompensationDataByNetCompensation() {
		$data = array();

		if( $this->net_taxable_compensation >= 0 ){			
			$data = G_Net_Taxable_Table_Helper::sqlTaxableCompensationDataByNetCompensation($this->net_taxable_compensation);
		}

		return $data;
	}
}
?>