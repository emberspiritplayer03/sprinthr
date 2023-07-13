<?php
/*
 * Revised Tax Table for 2018	
*/

/*
	alex note: updated tax table 2023
*/

class Revised_Tax_Table {
	const ANNUAL 		= 'annual';
	const MONTHLY 		= 'monthly';
	const SEMI_MONTHLY 	= 'semi_monthly';
	const WEEKLY 		= 'weekly';
	const DAILY 		= 'daily';
	
	protected $table_items = array();
	
	public function __construct() {
		
	}
	
	/*
		$table_item - Instance of class Tax_Table_Item
	*/
	public function getMonthly() {
		$tax_table = array();

		$tax_table[0]['from']  = 0;
		$tax_table[0]['to']    = 20833;
		$tax_table[0]['fixed'] = 0;
		$tax_table[0]['rate']  = 0;

		$tax_table[1]['from']  = 20833;
		$tax_table[1]['to']    = 33333;
		$tax_table[1]['fixed'] = 0;
		$tax_table[1]['rate']  = 15;

		$tax_table[2]['from']  = 33333;
		$tax_table[2]['to']    = 66667;
		$tax_table[2]['fixed'] = 1875;
		$tax_table[2]['rate']  = 20;

		$tax_table[3]['from']  = 66667;
		$tax_table[3]['to']    = 166667;
		$tax_table[3]['fixed'] = 8541.80;
		$tax_table[3]['rate']  = 25;

		$tax_table[4]['from']  = 166667;
		$tax_table[4]['to']    = 666667;
		$tax_table[4]['fixed'] = 33541.80;
		$tax_table[4]['rate']  = 30;

		$tax_table[5]['from']  = 666667;
		$tax_table[5]['to']    = 9999999;
		$tax_table[5]['fixed'] = 183541.80;
		$tax_table[5]['rate']  = 35;		
		
		return $tax_table;		
	}
	
	public function getSemiMonthly() {
		$tax_table = array();

		$tax_table[0]['from']  = 0;
		$tax_table[0]['to']    = 10417;
		$tax_table[0]['fixed'] = 0;
		$tax_table[0]['rate']  = 0;

		$tax_table[1]['from']  = 10417;
		$tax_table[1]['to']    = 16666;
		$tax_table[1]['fixed'] = 0;
		$tax_table[1]['rate']  = 15;

		$tax_table[2]['from']  = 16667;
		$tax_table[2]['to']    = 33332;
		$tax_table[2]['fixed'] = 937.50;
		$tax_table[2]['rate']  = 20;

		$tax_table[3]['from']  = 33333;
		$tax_table[3]['to']    = 83332;
		$tax_table[3]['fixed'] = 4270.70;
		$tax_table[3]['rate']  = 25;

		$tax_table[4]['from']  = 83333;
		$tax_table[4]['to']    = 333332;
		$tax_table[4]['fixed'] = 16770.70;
		$tax_table[4]['rate']  = 30;

		$tax_table[5]['from']  = 333333;
		$tax_table[5]['to']    = 9999999;
		$tax_table[5]['fixed'] = 91770.70;
		$tax_table[5]['rate']  = 35;		
		
		return $tax_table;
	}
	
	public function getAnnual() {
		$tax_table = array();

		$tax_table[0]['from']  = 0;
		$tax_table[0]['to']    = 250000;
		$tax_table[0]['fixed'] = 0;
		$tax_table[0]['rate']  = 0;

		$tax_table[1]['from']  = 250000;
		$tax_table[1]['to']    = 400000;
		$tax_table[1]['fixed'] = 0;
		$tax_table[1]['rate']  = 15;

		$tax_table[2]['from']  = 400000;
		$tax_table[2]['to']    = 800000;
		$tax_table[2]['fixed'] = 22500;
		$tax_table[2]['rate']  = 20;

		$tax_table[3]['from']  = 800000;
		$tax_table[3]['to']    = 2000000;
		$tax_table[3]['fixed'] = 102500;
		$tax_table[3]['rate']  = 25;

		$tax_table[4]['from']  = 2000000;
		$tax_table[4]['to']    = 8000000;
		$tax_table[4]['fixed'] = 402500;
		$tax_table[4]['rate']  = 30;

		$tax_table[5]['from']  = 8000000;
		$tax_table[5]['to']    = 9999999;
		$tax_table[5]['fixed'] = 2202500;
		$tax_table[5]['rate']  = 35;

		return $tax_table;
	}
}
?>