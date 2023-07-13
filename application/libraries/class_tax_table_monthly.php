<?php
class Tax_Table_Monthly extends Tax_Table {
	public function __construct() {	
	
	}
	
	public static function getDefault() {
		$tax_table = new Tax_Table_Monthly;
		$items['Z']['amount'] = array(0, 833, 2500, 5833, 11667, 20833, 41667);
		$items['Z']['dependent'] = 0;
		$items['Z']['is_zero'] = true;
				
		$items['S/ME']['amount'] = array(4167, 5000, 6667, 10000, 15833, 25000, 45833);
		$items['S/ME']['dependent'] = 0;
		
		$items['ME1/S1']['amount'] = array(6250, 7083, 8750, 12083, 17917, 27083, 47917);
		$items['ME1/S1']['dependent'] = 1;
		
		$items['ME2/S2']['amount'] = array(8333, 9167, 10833, 14167, 20000, 29167, 50000);
		$items['ME2/S2']['dependent'] = 2;
		
		$items['ME3/S3']['amount'] = array(10417, 11250, 12917, 16250, 22083, 31250, 52083);
		$items['ME3/S3']['dependent'] = 3;
		
		$items['ME4/S4']['amount'] = array(12500, 13333, 15000, 18333, 24167, 33333, 54167);
		$items['ME4/S4']['dependent'] = 4;
					
		$exempts = array(0, 41.67, 208.33, 708.33, 1875, 4166.67, 10416.67);
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