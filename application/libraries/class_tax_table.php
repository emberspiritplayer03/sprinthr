<?php
/*
	$tax_table = Tax_Factory::getTaxTable(Tax_Table::MONTHLY);
		// or $tax_table = new Tax_Table_Monthly;
	$taxex = new Tax_Table_Item;
	$taxex->setName('S/ME');
	$taxex->setNumberOfDependent(0);
				
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(4167);
	$range->setExemptionAmount(0.00);
	$range->setPercentage(5);			
	$taxex->addSalaryRange($range);
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(5000);
	$range->setExemptionAmount(41.67);
	$range->setPercentage(10);			
	$taxex->addSalaryRange($range);
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(6667);
	$range->setExemptionAmount(208.33);
	$range->setPercentage(15);			
	$taxex->addSalaryRange($range);
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(10000);
	$range->setExemptionAmount(708.33);
	$range->setPercentage(20);			
	$taxex->addSalaryRange($range);	
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(15833);
	$range->setExemptionAmount(1875);
	$range->setPercentage(25);			
	$taxex->addSalaryRange($range);
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(25000);
	$range->setExemptionAmount(4166.67);
	$range->setPercentage(30);			
	$taxex->addSalaryRange($range);	
	
	$range = new Tax_Salary_Range;
	$range->setSalaryRange(45833);
	$range->setExemptionAmount(10416.67);
	$range->setPercentage(32);			
	$taxex->addSalaryRange($range);	
	$tax_table->addTableItem($taxex);	
*/
class Tax_Table {
	const ANNUAL = 'annual';
	const MONTHLY = 'monthly';
	const SEMI_MONTHLY = 'semi_monthly';
	const WEEKLY = 'weekly';
	const DAILY = 'daily';
	
	protected $table_items = array();
	
	public function __construct() {
		
	}
	
	/*
		$table_item - Instance of class Tax_Table_Item
	*/
	public function addTableItem($table_item) {
		$this->table_items[] = $table_item;	
	}
	
	public function getTableItems() {
		return $this->table_items;	
	}
}
?>