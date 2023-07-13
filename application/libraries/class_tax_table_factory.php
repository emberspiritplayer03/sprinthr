<?php
class Tax_Table_Factory {
	/*
		$payroll_cycle - Tax_Table::MONTHLY, Tax_Table::SEMI_MONTHLY, Tax_Table::WEEKLY, Tax_Table::DAILY
	*/
	public static function get($payroll_cycle) {
		switch ($payroll_cycle) {
			case Tax_Table::MONTHLY:
				$tax_table = Tax_Table_Monthly::getDefault();			
				return $tax_table;
			break;
			case Tax_Table::SEMI_MONTHLY:
				$tax_table = Tax_Table_Semi_Monthly::getDefault();			
				return $tax_table;				
			break;
			case Tax_Table::WEEKLY:
				$tax_table = Tax_Table_Weekly::getDefault();			
				return $tax_table;					
			break;
			case Tax_Table::DAILY:
				$tax_table = Tax_Table_Daily::getDefault();			
				return $tax_table;					
			break;						
		}
	}

	/*
	 * Revised Tax Table for 2018
	*/
	public static function getRevisedTax($payroll_cycle) {
		switch ($payroll_cycle) {
			case Tax_Table::MONTHLY:
				$tax_table = Revised_Tax_Table::getMonthly();			
				return $tax_table;
			break;
			case Tax_Table::SEMI_MONTHLY:
				$tax_table = Revised_Tax_Table::getSemiMonthly();			
				return $tax_table;				
			break;
			case Tax_Table::ANNUAL:
				$tax_table = Revised_Tax_Table::getAnnual();			
				return $tax_table;				
			break;
		}
	}
}
?>