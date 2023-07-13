<?php
class Tax_Table_Annual {

	public function __construct() {	
	
	}

	public function getTaxTable() {
		$tax_table[0] = array(
			'from' => 0,
			'to' => 10000,
			'first' => 0,
			'percent' => 5,
			'excess' => 0
		);
		$tax_table[1] = array(
			'from' => 10001,
			'to' => 30000,
			'first' => 500,
			'percent' => 10,
			'excess' => 10000
		);
		$tax_table[2] = array(
			'from' => 30001,
			'to' => 70000,
			'first' => 2500,
			'percent' => 15,
			'excess' => 30000
		);
		$tax_table[3] = array(
			'from' => 70001,
			'to' => 140000,
			'first' => 8500,
			'percent' => 20,
			'excess' => 70000
		);
		$tax_table[4] = array(
			'from' => 140001,
			'to' => 250000,
			'first' => 22500,
			'percent' => 25,
			'excess' => 140000
		);
		$tax_table[5] = array(
			'from' => 250001,
			'to' => 500000,
			'first' => 50000,
			'percent' => 30,
			'excess' => 250000
		);
		$tax_table[6] = array(
			'from' => 500001,
			'to' => 9999999,
			'first' => 125000,
			'percent' => 32,
			'excess' => 500000
		);

		return $tax_table;
	}

	public function getTaxTableHB563() {
		$tax_table = array();

		$tax_table[0]['from']  = 0;
		$tax_table[0]['to']    = 250000;
		$tax_table[0]['fixed'] = 0;
		$tax_table[0]['rate']  = 0;

		$tax_table[1]['from']  = 250000;
		$tax_table[1]['to']    = 400000;
		$tax_table[1]['fixed'] = 0;
		$tax_table[1]['rate']  = 20;

		$tax_table[2]['from']  = 400000;
		$tax_table[2]['to']    = 800000;
		$tax_table[2]['fixed'] = 30000;
		$tax_table[2]['rate']  = 25;

		$tax_table[3]['from']  = 800000;
		$tax_table[3]['to']    = 2000000;
		$tax_table[3]['fixed'] = 130000;
		$tax_table[3]['rate']  = 30;

		$tax_table[4]['from']  = 2000000;
		$tax_table[4]['to']    = 8000000;
		$tax_table[4]['fixed'] = 490000;
		$tax_table[4]['rate']  = 32;

		$tax_table[5]['from']  = 8000000;
		$tax_table[5]['to']    = 9999999;
		$tax_table[5]['fixed'] = 2410000;
		$tax_table[5]['rate']  = 35;

		return $tax_table;
	}
	
	public function getAnnualTaxBracket( $taxable_income = 0 ) {
		$return    = array();
		$tax_table = $this->getTaxTable();
		foreach( $tax_table as $tax ){
			$from = $tax['from'];
			$to   = $tax['to'];
			if( $from <= $taxable_income && $to >= $taxable_income ){
				$return = $tax;
				return $return;				
			}
		}

		return $return;
	}

	public function getAnnualTaxBracketHB563( $taxable_income = 0 ) {
		$return    = array();
		$tax_table = $this->getTaxTableHB563();
		foreach( $tax_table as $tax ){
			$from = $tax['from'];
			$to   = $tax['to'];
			if( $from <= $taxable_income && $to >= $taxable_income ){
				$return = $tax;
				return $return;				
			}
		}

		return $return;
	}	
}
?>