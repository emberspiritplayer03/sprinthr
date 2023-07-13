<?php
class Tax_Table_Weekly extends Tax_Table {
	public function __construct() {	
	
	}
	
	public static function getDefault() {
		$tax_table = new Tax_Table_Weekly;
		$items['Z']['amount'] = array(0, 192, 577, 1346, 2692, 4808, 9615);
		$items['Z']['dependent'] = 0;
		$items['Z']['is_zero'] = true;
				
		$items['S/ME']['amount'] = array(962, 1154, 1538, 2308, 3654, 5769, 10577);
		$items['S/ME']['dependent'] = 0;
		
		$items['ME1/S1']['amount'] = array(1442, 1635, 2019, 2788, 4135, 6250, 11058);
		$items['ME1/S1']['dependent'] = 1;
		
		$items['ME2/S2']['amount'] = array(1923, 2115, 2500, 3269, 4615, 6731, 11538);
		$items['ME2/S2']['dependent'] = 2;
		
		$items['ME3/S3']['amount'] = array(2404, 2596, 2981, 3750, 5096, 7212, 12019);
		$items['ME3/S3']['dependent'] = 3;
		
		$items['ME4/S4']['amount'] = array(2885, 3077, 3462, 4231, 5577, 7692, 12500);
		$items['ME4/S4']['dependent'] = 4;
					
		$exempts = array(0, 9.62, 48.08, 163.46, 432.69, 961.54, 2403.85);
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