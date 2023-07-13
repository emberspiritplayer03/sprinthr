<?php
class Tax_Table_Semi_Monthly extends Tax_Table {
	public function __construct() {	
	
	}
	
	public static function getDefault() {
		$tax_table = new Tax_Table_Semi_Monthly;
		$items['Z']['amount'] = array(0, 417, 1250, 2917, 5833, 10417, 20833);
		$items['Z']['dependent'] = 0;
		$items['Z']['is_zero'] = true;
				
		$items['S/ME']['amount'] = array(2083, 2500, 3333, 5000, 7917, 12500, 22917);
		$items['S/ME']['dependent'] = 0;
		
		$items['ME1/S1']['amount'] = array(3125, 3542, 4375, 6042, 8958, 13542, 23958);
		$items['ME1/S1']['dependent'] = 1;
		
		$items['ME2/S2']['amount'] = array(4167, 4583, 5417, 7083, 10000, 14583, 25000);
		$items['ME2/S2']['dependent'] = 2;
		
		$items['ME3/S3']['amount'] = array(5208, 5625, 6458, 8125, 11042, 15625, 26042);
		$items['ME3/S3']['dependent'] = 3;
		
		$items['ME4/S4']['amount'] = array(6250, 6667, 7500, 9167, 12083, 16667, 27083);
		$items['ME4/S4']['dependent'] = 4;
					
		$exempts = array(0, 20.83, 104.17, 354.17, 937.50, 2083.33, 5208.33);
		$percents = array(5, 10, 15, 20, 25, 30, 32);			
		
		foreach ($items as $name => $amounts) {
			$tax = new Tax_Table_Item;
			$tax->setName($name);
			$tax->setNumberOfDependent($items[$name]['dependent']);
			$tax->setIsZeroExemption($items[$name]['is_zero']);

			foreach ($amounts['amount'] as $key => $amount) {
				$range = new Tax_Salary_Range;
				$range->setSalaryRange($amount);
				$range->setExemptionAmount($exempts[$key]);
				$range->setPercentage($percents[$key]);			
				$tax->addSalaryRange($range);
			}
			$tax_table->addTableItem($tax);	
		}				
		
		return $tax_table;
	}
}
?>