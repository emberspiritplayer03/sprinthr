<?php
class Tax_Table_Daily extends Tax_Table {
	public function __construct() {	
	
	}
	
	public static function getDefault() {
		$tax_table = new Tax_Table_Daily;
		$items['Z']['amount'] = array(0, 33, 99, 231, 462, 825, 1650);
		$items['Z']['dependent'] = 0;
		$items['Z']['is_zero'] = true;
				
		$items['S/ME']['amount'] = array(165, 198, 264, 396, 627, 990, 1815);
		$items['S/ME']['dependent'] = 0;
		
		$items['ME1/S1']['amount'] = array(248, 281, 347, 479, 710, 1073, 1898);
		$items['ME1/S1']['dependent'] = 1;
		
		$items['ME2/S2']['amount'] = array(330, 363, 429, 561, 792, 1155, 1980);
		$items['ME2/S2']['dependent'] = 2;
		
		$items['ME3/S3']['amount'] = array(413, 446, 512, 644, 875, 1238, 2063);
		$items['ME3/S3']['dependent'] = 3;
		
		$items['ME4/S4']['amount'] = array(495, 528, 594, 726, 957, 1320, 2145);
		$items['ME4/S4']['dependent'] = 4;
					
		$exempts = array(0, 1.65, 8.25, 28.05, 74.26, 165.02, 412.54);
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